<?php

return array(
    'account_not_activated'     => 'Your account has not been activated yet. Please check for your activation email, making sure to check the <strong>spam</strong> folder as well.',
    'account_suspended'         => "Your account has been temporarily locked for exceeding the login attempt limit.<br>Please try again in 15 minutes.",
    'account_banned'            => "Your account has been locked. Please contact an administrator for more information.",

    'invalid_credentials'       => 'Incorrect email/password entered. Be aware that you only get <strong>five</strong> login attempts before being locked.',

    'logged_out'                => '<i class="icon-info-sign"></i> Your session has been ended.',

    'register' => array(
            'success' => '<i class="icon-ok-sign"></i> Your account has been created. Please check your email for the activation code. <strong>Remember to check the <em>SPAM</em> folder.</strong>',
            'exception' => '<i class="icon-exclamation-sign"></i> An unexpected issue occured while attempting to create your account.',
            'used_invite' => 'This invitation code has already been used.',
            'used_email' => 'This email address has already been used.',
        ),

    'activate' => array(
            'success' => '<i class="icon-ok-sign"></i> Your account has been successfully activated. Please login now to begin.',
            'error' => '<i class="icon-exclamation-sign"></i> Unable to activate your account due to an error. Please contact and administrator.',
            'invalid_code' => 'Your activation code is invalid. Please check your input and try again.',
        ),

    'reset' => array(
            'success' => '<i class="icon-ok-sign"></i> Please check your inbox for a confirmation email and link in order to reset your password.',
        ),

    'reset_confirm' => array(
            'success' => '<i class="icon-ok-sign"></i> Your password has been successfully updated.',
            'error' => '<i class="icon-exclamation-sign"></i> Unable to reset your password for some reason.',
        ),

    'email' => array(
            'activate_subject' => 'Activate your account.',
            'reset_subject' => 'Reset your password.',
        ),
);

