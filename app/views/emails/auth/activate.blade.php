<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
    </head>
    <body>
        <h3>You're almost there...</h3>
        <hr>
        <p>Thank you for joining <strong>the drop</strong>, {{{ $user->name }}}! Before we can get started though, you first have to activate your account by following this link,</p>
        <code><a href="{{ $activationUrl }}">{{{ $activationUrl }}}</a></code>
        <br>
        <p>We welcome you and we hope you enjoy it!</p>
        <br>
        <p><strong>the drop</strong></p>
    </body>
</html>