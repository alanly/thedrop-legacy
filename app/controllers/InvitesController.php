<?php

class InvitesController extends \BaseController
{

    public function getIndex()
    {

        $user = Sentry::getUser();

        $tally = array(
                'available' => $user->invites()->whereNull('invited_id')->count(),
                'used' => $user->invites()->whereNotNull('invited_id')->count(),
                'total' => $user->invites()->count(),
            );

        return View::make('my.invites.index')
                    ->with('invites', $user->invites)
                    ->with('tally', $tally);

    }

}
