<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
  </head>
  <body>
    <h3>Password reset.</h3>
    <hr>
    <p>There is an outstanding request to reset your password at <strong>the drop</strong>. If you have made this request then you may continue by following this link,</p>
    <code>{{ link_to_route('reset.confirm', null, array('email' => $user->email, 'code' => $resetCode)) }}</code>
    <p>If you did <em>not</em> request to reset your password or if you do not have an account at <strong>the drop</strong>, then you may ignore and delete this email.</p>
    <br>
    <p><strong>the drop.</strong></p>
  </body>
</html>