@section('subContent')

    <div class="container">
        <ul class="breadcrumb">
            <li class="active">List Users</li>
        </ul>
    </div>

    <div class="container">
        <div class="row-fluid">
            <section>
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th><th>Name</th><th>Email</th><th>Last Seen</th><th>Open Invites</th><th>Activated</th><th>Privileged</th><th>Locked</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th colspan="8">Total Users: <code>{{{ count($users) }}}</code></th>
                        </tr>
                    </tfoot>
                    <tbody>
                        @foreach ( $users as $user )
                        <tr>
                            <td>{{ link_to_action('AdminUserController@show', $user->id, array('user' => $user->id)) }}</td>
                            <td>{{ link_to_action('AdminUserController@show', $user->name, array('user' => $user->id)) }}</td>
                            <td>{{{ $user->email }}}</td>
                            <td>{{{ date('j-M-o H:i', strtotime($user->last_login)) }}}</td>
                            <td>{{{ $user->invites()->whereNull('invited_id')->count() }}}</td>
                            <td><i class="icon-check{{ $user->isActivated() ? '' : '-empty' }}"></i></td>
                            <td><i class="icon-star{{ $user->isPrivileged() ? '' : '-empty' }}"></i></td>
                            <td><i class="icon-{{ $user->isBanned() ? 'lock' : 'unlock' }}"></i></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </section>
        </div>
    </div>

@stop