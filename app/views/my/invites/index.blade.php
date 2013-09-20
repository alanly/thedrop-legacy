@extends('layouts.application')

@section('pageName')
My Invites
@stop

@section('content')

    <div class="container">
        <div class="row-fluid">
            <div class="span3">
                <section>
                    <h4>Notes on invites.</h4>
                    <p>You may hand out the invitation codes that you've been assigned to people that you trust.
                        But be aware that you will be responsible for their actions if issues arise.</p>
                    <p>Users are provided invitation codes on an individual basis with consideration or when they
                        have <em>privileged</em> status (via donations).</p>
                </section>
            </div>
            <div class="span9">
                <section>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Available Invites</th><th>Used Invites</th><th>Total Invites</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><span class="text-success">{{{ $tally['available'] }}}</span></td>
                                <td><span class="text-error">{{{ $tally['used'] }}}</span></td>
                                <td>{{{ $tally['total'] }}}</td>
                            </tr>
                        </tbody>
                    </table>
                </section>

                <section>
                    <table class="table">
                        <thead>
                            <th>Invited</th><th>Invited On</th><th>Created On</th><th>Invitation Code</th>
                        </thead>
                        <tbody>
                            @if (count($invites) == 0)
                            <tr>
                                <th colspan="4"><p class="text-center muted">You have not been assigned any invitation codes yet.</p></th>
                            </tr>
                            @endif
                            @foreach ($invites as $invite)
                            <tr{{ $invite->isUsed() ? ' class="error"' : '' }}>
                                <td>
                                    @if ($invite->isUsed())
                                        @if (is_null(User::withTrashed()->find($invite->invited_id)->deleted_at))
                                            <strong>{{{ User::withTrashed()->find($invite->invited_id)->name }}}</strong> <small class="muted">({{{ User::withTrashed()->find($invite->invited_id)->email }}})</small>
                                        @else
                                            <strong title="User has been deleted."><del>{{{ User::withTrashed()->find($invite->invited_id)->name }}}</del></strong> <small class="muted">({{{ User::withTrashed()->find($invite->invited_id)->email }}})</small>
                                        @endif
                                    @else
                                    &mdash;
                                    @endif
                                </td>
                                <td>{{{ $invite->isUsed() ? date('j-M-o H:i', strtotime($invite->updated_at)) : '&mdash;' }}}</td>
                                <td>{{{ date('j-M-o H:i', strtotime($invite->created_at)) }}}</td>
                                <td><code>{{{ $invite->code }}}</code></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </section>
            </div>
        </div>
    </div>

@stop