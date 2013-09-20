<?php

class AuthController extends BaseController
{

    public function getLogin()
    {

        return View::make('auth.login');

    }

    public function postLogin()
    {

        // Login form validation rules.
        $rules = array(
            'email' => 'required|email',
            'password' => 'required',
        );

        // Validator instance with all inputs and rules.
        $validator = Validator::make(Input::all(), $rules);

        // Validate form fields.
        if ($validator->fails()) {
            // ... if there's an error with the form
            // then return to the form with inputs and errors.
            return Redirect::back()->withInput()->withErrors($validator);
        }

        // Attempt to authenticate the user with the given fields.
        try {

            Sentry::authenticate(Input::only('email', 'password'), Input::get('remember', false));

            // Retrieve redirect location from the session
            $urlIntended = Session::get('url.intended', '/');

            // Redirect to appropriate location
            return Redirect::to($urlIntended);

        } catch (Cartalyst\Sentry\Users\UserNotActivatedException $e) {

            $this->messageBag->add('email', Lang::get('auth/messages.account_not_activated'));

        } catch (Cartalyst\Sentry\Throttling\UserSuspendedException $e) {

            $this->messageBag->add('email', Lang::get('auth/messages.account_suspended'));

        } catch (Cartalyst\Sentry\Throttling\UserBannedException $e) {

            $this->messageBag->add('email', Lang::get('auth/messages.account_banned'));

        } catch (Cartalyst\Sentry\Users\UserNotFoundException $e) {

            $this->messageBag->add('email', Lang::get('auth/messages.invalid_credentials'));
            $this->messageBag->add('password', ' ');

        }

        // When an exception occurs, redirect back to the form with input and error messages.
        return Redirect::back()->withInput()->withErrors($this->messageBag);

    }

    public function getLogout()
    {

        Sentry::logout();

        if(! Sentry::check()) {
            Session::flash('actionFlash.status', 'info');
            Session::flash('actionFlash.message', Lang::get('auth/messages.logged_out'));
        }

        return Redirect::route('login');

    }

    public function getRegister()
    {

        return View::make('auth.register');

    }

    public function postRegister()
    {

        // Validation rules
        $rules = array(
                'inviteCode' => 'required|exists:invites,code',
                'name' => 'required',
                'email' => 'required|email',
                'password' => 'required|min:8|confirmed',
                'password_confirmation' => 'required',
            );

        $messages = array(
                'inviteCode.required' => 'You must provide an invitation code.',
                'inviteCode.exists' => 'You have entered an invalid invitation code.',
                'name.required' => 'You must provide a name.',
                'email.unique' => 'The email address you have provided is already in use.',
            );

        $validator = Validator::make(Input::all(), $rules, $messages);

        if ($validator->fails())
            return Redirect::back()->withErrors($validator)->withInput();

        /* Check whether the email address belongs to an active user. */
        if ( User::where('email', Input::get('email'))->count() > 0 ){
            $this->messageBag->add('email', Lang::get("auth/messages.register.used_email"));
            return Redirect::back()->withInput()->withErrors($this->messageBag);
        }

        /* Check to make sure that the invitation code hasn't been used yet. */

        $invite = Invite::where('code', Input::get('inviteCode'))->first();

        if ($invite->isUsed()) {
            $this->messageBag->add('inviteCode', Lang::get("auth/messages.register.used_invite"));
            return Redirect::back()->withInput()->withErrors($this->messageBag);
        }

        /* If the validation passes, then try to register to user whilst handling
         * any exceptions that may occur.
         */

        try {

            // Get the basic Users group
            $usersGroup = Sentry::getGroupProvider()->findByName('Users');

            // Register the user with the input provided
            $user = Sentry::register(array(
                    'email' => Input::get('email'),
                    'password' => Input::get('password'),
                    'name' => Input::get('name'),
                ));

            // Ensure the user has the necessary API key
            $user->resetApiKey();

            $user->addGroup($usersGroup);

            // Update the invite to mark it used
            $invite->invited_id = $user->id;
            $invite->save();

            /* Retrieve the activation code generated for the user and send it
             * to the user at their email address.
             */

            $activationUrl = URL::route('activate', array('email' => $user->email, 'code' => $user->getActivationCode()));

            Mail::send('emails.auth.activate', array('user' => $user, 'activationUrl' => $activationUrl), function($message) use ($user)
            {
                $message->to($user->email, $user->name)->subject(Lang::get("auth/messages.email.activate_subject"));
            });

            Session::flash('actionFlash.status', 'success');
            Session::flash('actionFlash.message', Lang::get("auth/messages.register.success"));
            return Redirect::route('login');

        } catch (Exception $e) {

            Session::flash('actionFlash.status', 'error');
            Session::flash('actionFlash.message', Lang::get("auth/messages.register.exception"));
            return Redirect::back();

        }

    }

    public function getActivate()
    {

        if (! Input::has('code')) return View::make('auth.activate');

        $rules = array(
                'email' => 'required|email|exists:users',
                'code' => 'required',
            );

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            Input::flash();
            return View::make('auth.activate')->with('errors', $validator->getMessageBag());
        }

        try {

            $user = User::where('email', Input::get('email'))->first();

            if (! $user->attemptActivation(Input::get('code'))) {
                $this->messageBag->add('code', Lang::get("auth/messages.activate.invalid_code"));
                Input::flash();
                return View::make('auth.activate')->with('errors', $this->messageBag);
            }

        } catch (Cartalyst\Sentry\Users\UserAlreadyActivatedException $e) {
            // Do nothing special if the user's already activated.
        }

        $status = $user->isActivated() ? 'success' : 'error';

        Session::flash('actionFlash.status', $status);
        Session::flash('actionFlash.message', Lang::get("auth/messages.activate.{$status}"));

        if ($status == 'success') {
            return Redirect::route('login');
        } else {
            Input::flash();
            return View::make('auth.activate');
        }

    }

    public function getReset()
    {

        return View::make('auth.reset');

    }

    public function postReset()
    {

        $rules = array(
                'email' => 'required|email|exists:users',
            );

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails())
            return Redirect::back()->withInput()->withErrors($validator);

        $user = User::where('email', Input::get('email'))->first();
        $resetCode = $user->getResetPasswordCode();

        Mail::send(
                'emails.auth.reset',
                array(
                        'user' => $user,
                        'resetCode' => $resetCode,
                    ),
                function($message) use ($user) {
                    $message->to($user->email, $user->name)->subject(Lang::get("auth/messages.email.reset_subject"));
                });

        Session::flash('actionFlash.status', 'success');
        Session::flash('actionFlash.message', Lang::get('auth/messages.reset.success'));
        return Redirect::back();

    }

    public function getResetConfirm()
    {

        return View::make('auth.reset_confirm');

    }

    public function postResetConfirm()
    {

        $rules = array(
                'email' => 'required|email|exists:users',
                'code' => 'required',
                'password' => 'required|min:8|confirmed',
                'password_confirmation' => 'required',
            );

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails())
            return Redirect::back()->withInput()->withErrors($validator);

        $user = User::where('email', Input::get('email'))->first();

        if (! $user->checkResetPasswordCode(Input::get('code'))) {
            $this->messageBag->add('code', 'The entered reset code is invalid.');
            return Redirect::back()->withInput()->withErrors($this->messageBag);
        }

        $status = $user->attemptResetPassword(Input::get('code'), Input::get('password')) ? 'success' : 'error';

        Session::flash('actionFlash.status', $status);
        Session::flash('actionFlash.message', Lang::get("auth/messages.reset_confirm.{$status}"));

        if ($status == 'success')
            return Redirect::route('login');
        else
            return Redirect::back();

    }

}
