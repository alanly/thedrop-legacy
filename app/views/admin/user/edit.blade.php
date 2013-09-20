@section('subContent')

    <div class="container">
        <ul class="breadcrumb">
            <li>{{ link_to_action('AdminUserController@index', 'List Users') }} <span class="divider">/</span></li>
            <li>{{ link_to_action('AdminUserController@show', 'Show User', array('user' => $user->id)) }} <span class="divider">/</span></li>
            <li class="active">Edit User</li>
        </ul>
    </div>

    <div class="container">
        <section>
            <div class="row-fluid">
                <div class="span3">
                    <img class="img-polaroid float-right" src="{{ $user->getGravatarUrl(250) }}" title="Gravatar Profile Image">
                </div>
                <div class="span9">
                    <h3>{{{ $user->name }}}</h3>
                    <hr>
                    <dl class="dl-horizontal">
                        <dt>API key</dt>
                        <dd>
                            {{ Form::open(array('method' => 'put', 'url' => URL::action('AdminUserController@update', array('user' => $user->id)))) }}
                            <code>{{{ $user->api_key }}}</code>
                            <input type="hidden" name="_updateType" value="reset.key">
                            <button type="submit" class="btn btn-mini btn-primary"><i class="icon-refresh"></i> Reset Key</button>
                            {{ Form::close() }}
                        </dd>

                        <dt>Groups</dt>
                        <dd>
                            @foreach ( $user->getGroups() as $group )
                            {{ Form::open(array('name' => 'groupRemoveForm_' . $group->id, 'class' => 'inline', 'method' => 'put', 'url' => URL::action('AdminUserController@update', array('user' => $user->id)))) }}
                                <input type="hidden" name="_updateType" value="remove.group">
                                <input type="hidden" name="_groupId" value="{{ $group->id }}">
                                <code>{{{ $group->name }}} <a href="#" class="muted" onClick="document.forms['groupRemoveForm_{{{ $group->id }}}'].submit()"><i class="icon-remove"></i></a></code>
                            {{ Form::close() }}
                            @endforeach
                            <a class="btn btn-mini btn-primary" href="#addGroupModal" role="button" data-toggle="modal"><i class="icon-plus"></i> Add Group</a>
                        </dd>
                    </dl>
                    <hr>
                    {{ Form::open(array('class' => 'inline', 'method' => 'delete', 'url' => URL::action('AdminUserController@destroy', array('user' => $user->id)))) }}
                    <button type="submit" class="btn btn-danger"{{ $isDeletable ? '' : ' disabled title="You cannot delete yourself or the primary administrator."' }}><i class="icon-trash"></i> Delete User</button>
                    {{ Form::close() }}

                    {{ Form::open(array('class' => 'inline', 'method' => 'put', 'url' => URL::action('AdminUserController@update', array('user' => $user->id)))) }}
                        <input type="hidden" name="_updateType" value="toggle.ban">
                    @if ( $user->isBanned() )
                        <button type="submit" class="btn btn-success"><i class="icon-unlock"></i> Unlock User</button>
                    @else
                        <button type="submit" class="btn btn-danger"{{ $isDeletable ? '' : ' disabled title="You cannot lock yourself or the primary administrator."' }}><i class="icon-lock"></i> Lock User</button>
                    @endif
                    {{ Form::close() }}
                </div>
            </div>
        </section>
    </div>

    <div id="addGroupModal" class="modal hide fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="icon-remove"></i></button>
            <h3>Add Group</h3>
        </div>

        <div class="modal-body">
            {{ Form::open(array('name' => 'groupAddForm', 'method' => 'put', 'url' => URL::action('AdminUserController@update', array('user' => $user->id)), 'class' => 'form-horizontal')) }}
                <input type="hidden" name="_updateType" value="add.group">
                <div class="control-group">
                    <label class="control-label" for="group">User Group:</label>
                    <div class="controls">
                        <select name="group" id="group">
                            @foreach ( $groups as $group )
                            <option value="{{{ $group->id }}}">{{{ $group->name }}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            {{ Form::close() }}
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-primary" role="button" onClick="document.forms['groupAddForm'].submit()">Add to {{{ $user->name }}}</button>
        </div>
    </div>

@stop
