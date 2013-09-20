<!--
                                   ________        
                                 ||.     . ||
                                 ||   ‿    ||
 _|_|_|    _|      _|    _|_|    ||        ||
 _|    _|  _|_|  _|_|  _|    _| /||--------||\
 _|_|_|    _|  _|  _|  _|    _|  ||===   . ||
 _|    _|  _|      _|  _|    _|  || +  o  0||
 _|_|_|    _|      _|    _|_|    ||________||
                                    |    |
-->

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>the drop.</title>

        <link href="{{ asset('img/icon-256.png') }}" rel="icon" type="image/png">
        <link href="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/2.3.2/css/bootstrap.min.css" rel="stylesheet">
        <link href="/css/main.min.css" rel="stylesheet">
        <link href="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/2.3.2/css/bootstrap-responsive.min.css" rel="stylesheet">
        @yield('style')
        @if(Sentry::check() && Sentry::getUser()->getSetting('background.enable') == 'true')
            <style>
                #background-container {
                    @if(Sentry::getUser()->hasSetting('background.image'))
                        background-image: url("{{{ str_replace(array('https:', 'http:'), '', Sentry::getUser()->getSetting('background.image', '')) }}}");
                        background-attachment: {{{ Sentry::getUser()->getSetting('background.attachment', 'fixed') }}};
                        background-position: {{{ Sentry::getUser()->getSetting('background.position.x', 'center') }}} {{{ Sentry::getUser()->getSetting('background.position.y', 'center') }}};
                        background-size: {{{ Sentry::getUser()->getSetting('background.size', 'auto') }}};
                    @endif
                    background-color: {{{ Sentry::getUser()->getSetting('background.color', '#fff') }}};
                }
            </style>
        @endif
    </head>

    <body>
        @yield('body')
        <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.0.1/jquery.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/2.3.2/js/bootstrap.min.js"></script>
        @yield('script')
    </body>
</html>

<!--
░░░░░░░░░░░░░░▄▄▄▄▄▄▄▄▄▄▄▄░░░░░░░░░░░░░░
░░░░░░░░░░░░▄████████████████▄░░░░░░░░░░
░░░░░░░░░░▄██▀░░░░░░░▀▀████████▄░░░░░░░░
░░░░░░░░░▄█▀░░░░░░░░░░░░░▀▀██████▄░░░░░░
░░░░░░░░░███▄░░░░░░░░░░░░░░░▀██████░░░░░
░░░░░░░░▄░░▀▀█░░░░░░░░░░░░░░░░██████░░░░
░░░░░░░█▄██▀▄░░░░░▄███▄▄░░░░░░███████░░░
░░░░░░▄▀▀▀██▀░░░░░▄▄▄░░▀█░░░░█████████░░
░░░░░▄▀░░░░▄▀░▄░░█▄██▀▄░░░░░██████████░░
░░░░░█░░░░▀░░░█░░░▀▀▀▀▀░░░░░██████████▄░
░░░░░░░▄█▄░░░░░▄░░░░░░░░░░░░██████████▀░
░░░░░░█▀░░░░▀▀░░░░░░░░░░░░░███▀███████░░
░░░▄▄░▀░▄░░░░░░░░░░░░░░░░░░▀░░░██████░░░
██████░░█▄█▀░▄░░██░░░░░░░░░░░█▄█████▀░░░
██████░░░▀████▀░▀░░░░░░░░░░░▄▀█████████▄
██████░░░░░░░░░░░░░░░░░░░░▀▄████████████
██████░░▄░░░░░░░░░░░░░▄░░░██████████████
██████░░░░░░░░░░░░░▄█▀░░▄███████████████
███████▄▄░░░░░░░░░▀░░░▄▀▄███████████████
-->
