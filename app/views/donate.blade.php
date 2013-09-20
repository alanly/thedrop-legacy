@extends('layouts.application')

@section('pageName')
Donate
@stop

@section('content')

    <div class="container">
        <section>
            <div class="row-fluid">
                <div class="page-header">
                    <h3>"Made possible by viewers like you."</h3>
                </div>
                <p>Thank you for being a member of <span class="brand">the drop</span> and I hope that you're making
                    good use of this service.
                <p>First off, I would like to make a point of noting that this site does not monetize your presence via
                    adverts or force you into any sort of paid membership. It's an absolutely free service that I simply
                    hope you use and enjoy using.</p>
                <p>Now, with that out of the way, I do have to say that this service does require money to operate.</p>
                <p class="lead">This service costs about $160 a year to operate and provide.</p>
                <p>If you can afford to and in fact, choose to donate, then it would mean the world to me regardless of the
                    amount that you contribute. In return, your account gets promoted to <em>privileged</em> status (which
                    means a few additional features) and the site continues to operate and offer you all the things you enjoy.</p>
                <p>&mdash; Alan</p>
            </div>
        </section>
        <section>
            <div class="row-fluid">
                <p>Digital contributions are accepted via <em>PayPal</em>, <em>Bitcoins</em>, <em>Litecoins</em>, and <em>Feathercoins</em>, while
                    the real-world alternatives are cash and beer.</p>
                <p>Note that if you do choose to pay me via one of the cryptocurrencies, please let me know about it so
                    that I can update your account to <em>privileged</em> standing.</p>

                <table class="table table-bordered">
                    <thead>
                        <th><i class="icon-usd"></i> PayPal</th><th><i class="icon-btc"></i> Bitcoins</th><th>Litecoins</th><th>Feathercoins</th>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <form action="https://www.paypal.com/cgi-bin/webscr" method="post" class="inline">
                                    <input type="hidden" name="cmd" value="_s-xclick">
                                    <input type="hidden" name="hosted_button_id" value="2V4FEK4PFRNAJ">
                                    <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/x-click-but04.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
                                </form>
                            </td>
                            <td><code>1F8HN7K5K2vryntHs3QjUE6VgY5jd5R61w</code></td>
                            <td><code>LSzg516AYfLyThdtfaDrxtSeyC2j9sH1in</code></td>
                            <td><code>6hmPDvbxLxe1dWV5oDK1CwcDDiEZfxYcqG</code></td>
                        </tr>
                </table>
            </div>
        </section>
    </div>

@stop