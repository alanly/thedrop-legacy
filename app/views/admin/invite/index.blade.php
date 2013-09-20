@section('subContent')

    <section>
        <div class="row-fluid">
            <h3>Invite Statistics</h3>
            <hr>
            <table class="table">
                <thead>
                    <tr>
                        <th>Open Invites</th>
                        <th>Closed Invites</th>
                        <th>Total Invites</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{{ $openInviteCount }}}</td><td>{{{ $closedInviteCount }}}</td><td>{{{ $totalInviteCount }}}</td>
                </tbody>
            </table>
        </div>
    </section>

    <section>
        <div class="row-fluid">
            {{ Form::open(array('method' => 'get', 'class' => 'form-inline')) }}
            <div class="span2">
                <label for="status">Status:</label>
                <select name="status" id="status" class="input-small">
                    <option value="all"{{ Input::old('status') == 'all' ? ' selected' : '' }}>All</option>
                    <option value="open"{{ Input::old('status') == 'open' ? ' selected' : '' }}>Open</option>
                    <option value="closed"{{ Input::old('status') == 'closed' ? ' selected' : '' }}>Closed</option>
                </select>
            </div>
            <div class="span4">
                <label for="inviter">Inviter:</label>
                <select name="inviter" id="inviter">
                    <option value="0"{{ Input::old('inviter') == 0 ? ' selected' : '' }}>All</option>
                    @foreach ($users as $user)
                    <option value="{{ $user->id }}"{{ Input::old('inviter') == $user->id ? ' selected' : '' }}>{{{ $user->name }}} ({{{ $user->email }}})</option>
                    @endforeach
                </select>
            </div>
            <div class="span3">
                <label for="results">Results per Page:</label>
                <select name="results" id="results" class="input-small">
                    <option value="10"{{ Input::old('results') == 10 ? ' selected' : '' }}>10</option>
                    <option value="20"{{ Input::old('results') == 20 ? ' selected' : '' }}>20</option>
                    <option value="50"{{ Input::old('results') == 50 ? ' selected' : '' }}>50</option>
                    <option value="100"{{ Input::old('results') == 100 ? ' selected' : '' }}>100</option>
                </select>
            </div>
            <div class="span3">
                <button type="submit" class="btn"><i class="icon-filter"></i> Filter</button>
            </div>
            {{ Form::close() }}
        </div>
        <hr>
        <div class="row-fluid">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th><th>Inviter</th><th>Invited</th><th>Invited On</th><th>Created On</th><th>Code</th><th>Delete</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($totalInviteCount == 0)
                    <tr>
                        <th colspan="7"><p class="muted text-center">There are currently no invites in the system.</p></th>
                    </tr>
                    @elseif (count($invites) == 0)
                    <tr>
                        <th colspan="7"><p class="muted text-center">There are no invites that match your filter criteria.</p></th>
                    </tr>
                    @endif
                    @foreach ($invites as $invite)
                    <tr{{ $invite->isUsed() ? ' class="error"' : '' }}>
                        <td>{{{ $invite->id }}}</td>
                        <td>{{ link_to_action('AdminUserController@show', $invite->inviter()->first()->name, array('user' => $invite->inviter()->first()->id)) }}</td>
                        <td>
                            @if ($invite->isUsed())
                            {{ link_to_action('AdminUserController@show', $invite->invited()->first()->name, array('user' => $invite->invited()->first()->id)) }}
                            @else
                            &mdash;
                            @endif
                        </td>
                        <td>
                            @if ($invite->isUsed())
                            {{{ date('j-M-o H:i', strtotime($invite->updated_at)) }}}
                            @else
                            &mdash;
                            @endif
                        </td>
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
        <div class="row-fluid">
            {{ $invites->appends(array('status' => Input::old('status'), 'inviter' => Input::old('inviter'), 'results' => Input::old('results')))->links() }}
        </div>
    </section>

@stop
