@section('subContent')

    <div class="container">
        <ul class="breadcrumb">
            <li>{{ link_to_action('AdminUserController@index', 'List Users') }} <span class="divider">/</span></li>
            <li class="active">Show User <span class="divider">/</span></li>
            <li>{{ link_to_action('AdminUserController@edit', 'Edit User', array('user' => $user->id)) }}</li>
        </ul>
    </div>

    <div class="container">
        <div class="row-fluid">
            <section>
                <div class="row-fluid">
                    <div class="span3">
                        <img class="img-polaroid pull-right" src="{{ $user->getGravatarUrl(250) }}" title="Gravatar Profile Image">
                    </div>

                    <div class="span9">
                        <h3>{{{ $user->name }}} {{ $user->isBanned() ? '<i class="icon-lock"></i>' : '<i class="icon-unlock"></i>' }}</h3>
                        <hr>
                        <dl class="dl-horizontal">
                            <dt>Email address</dt>
                            <dd><a href="mailto:{{{ $user->email }}}">{{{ $user->email }}}</a></dd>

                            <dt>Created at</dt>
                            <dd>{{{ date('j-M-o H:i', strtotime($user->created_at)) }}}</dd>

                            <dt>Activated at</dt>
                            <dd>{{ $user->isActivated() ? date('j-M-o H:i', strtotime($user->activated_at)) : '<em>Account is not yet activated.</em>' }}</dd>

                            @if(!$user->isActivated())
                                <dt>Activation code</dt>
                                <dd><code>{{ $user->activation_code }}</code></dd>
                            @endif

                            <dt>Last logged in at</dt>
                            <dd>{{{ date('j-M-o H:i', strtotime($user->last_login)) }}}</dd>

                            <dt>API key</dt>
                            <dd><code>{{{ $user->api_key }}}</code></dd>

                            <dt>Groups</dt>
                            <dd>
                                @foreach ( $user->getGroups() as $group )
                                <code>{{{ $group->name }}}</code>
                                @endforeach
                                &nbsp;
                            </dd>

                            <dt>Invited by</dt>
                            <dd>
                                @if ( $user->getInviter() )
                                {{ link_to_action('AdminUserController@show', $user->getInviter()->name, array('user' => $user->getInviter()->id)) }}
                                @else
                                <em>User was not invited.</em>
                                @endif
                            </dd>
                        </dl>
                    </div>
                </div>
            </section>
        </div>

        <div class="row-fluid">
            <section>
                <div class="page-header">
                    <h3>Invitations</h3>
                </div>
                <div class="row-fluid">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th><th>Invited</th><th>Invited On</th><th>Created On</th><th>Code</th><th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ( count($invites) == 0 )
                            <tr>
                                <td colspan="6"><p class="muted text-center">User does not have any invitations available yet.</p></td>
                            </tr>
                            @endif
                            @foreach ( $invites as $invite )
                            <tr{{ $invite->isUsed() ? ' class="error"' : '' }}>
                                <td>{{{ $invite->id }}}</td>
                                <td>
                                    @if ($invite->isUsed())
                                        @if( is_null(User::withTrashed()->find($invite->invited_id)->deleted_at) )
                                            {{ link_to_action('AdminUserController@show', User::find($invite->invited_id)->name, array('user' => $invite->invited_id)) }}
                                        @else
                                            <span title="User has been deleted.">{{{ User::withTrashed()->find($invite->invited_id)->name }}}</span>
                                        @endif
                                    @else
                                    &mdash;
                                    @endif
                                </td>
                                <td>{{{ $invite->isUsed() ? date('j-M-o H:i', strtotime($invite->updated_at)) : '&mdash;' }}}</td>
                                <td>{{{ date('j-M-o H:i', strtotime($invite->created_at)) }}}</td>
                                <td><code>{{{ $invite->code }}}</code></td>
                                <td>
                                    @if (! $invite->isUsed())
                                    {{ Form::open(array(
                                            'name' => 'destroyInvite_' . $invite->id,
                                            'class' => 'inline',
                                            'method' => 'delete',
                                            'url' => URL::action('AdminInviteController@destroy', array('invite' => $invite->id)),
                                        )) }}
                                    <a href="#" onClick="document.forms['destroyInvite_{{ $invite->id }}'].submit()"><i class="icon-trash"></i></a>
                                    {{ Form::close() }}
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
        </div>

        <div class="row-fluid">
            <section>
                <div class="page-header">
                    <h3>Downloads</h3>
                </div>
                <div class="row-fluid">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>This Month</th>
                                <th>Last Month</th>
                                <th>This Year</th>
                                <th>Lifetime</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{{ $downloadStats['this.month'] }}}</td>
                                <td>{{{ $downloadStats['last.month'] }}}</td>
                                <td>{{{ $downloadStats['this.year'] }}}</td>
                                <td>{{{ $downloadStats['lifetime'] }}}</td>
                        </tbody>
                    </table>
                </div>
                <hr>
                <div class="row-fluid">
                    {{ Form::open(array('method' => 'get', 'class' => 'form-inline')) }}
                        <div class="span4">
                            <label for="sort">Order Date In:</label>
                            <select name="sort" id="sort" class="input-medium">
                                <option value="desc"{{ Input::old('sort') == 'desc' ? ' selected' : '' }}>Descending</option>
                                <option value="asc"{{ Input::old('sort') == 'asc' ? ' selected' : '' }}>Ascending</option>
                            </select>
                        </div>
                        <div class="span4">
                            <label for="results">Results per Page:</label>
                            <select name="results" id="results" class="input-small">
                                <option value="10"{{ Input::old('results') == 10 ? ' selected' : '' }}>10</option>
                                <option value="20"{{ Input::old('results') == 20 ? ' selected' : '' }}>20</option>
                                <option value="50"{{ Input::old('results') == 50 ? ' selected' : '' }}>50</option>
                                <option value="100"{{ Input::old('results') == 100 ? ' selected' : '' }}>100</option>
                            </select>
                        </div>
                        <div class="span4">
                            <button type="submit" class="btn"><i class="icon-filter"></i> Filter</button>
                        </div>
                    {{ Form::close() }}
                </div>
                <hr>
                <div class="row-fluid">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th><th>File Name</th><th>Date &amp; Time</th><th>Client Address</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ( count($downloads) == 0 )
                            <tr>
                                <td colspan="4"><p class="muted text-center">User does not have any downloads yet.</p></td>
                            </tr>
                            @endif
                            @foreach ( $downloads as $download )
                            <tr>
                                <td>{{{ $download->id }}}</td>
                                <td>{{{ $download->file()->first()->name }}}</td>
                                <td>{{{ date('j-M-o H:i', strtotime($download->created_at)) }}}</td>
                                <td><a href="http://whois.arin.net/ui/query.do?q={{ $download->ip_address }}" target="_blank">{{{ $download->ip_address }}}</a></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="row-fluid">
                    {{ $downloads->appends(array('sort' => Input::old('sort'), 'results' => Input::old('results')))->links() }}
                </div>
            </section>
        </div>
    </div>

@stop