@section('subContent')

    <section>
        {{ Form::open(array(
                'url' => URL::action('AdminInviteController@store')
            )) }}
            <div class="row-fluid">
                <br>
                <p class="lead pull-right">
                    Let's generate
                    <input type="number" name="amount" class="input-mini" min="1" value="1">
                    invites for 
                    <select name="inviter">
                        @foreach ($users as $user)
                        <option value="{{ $user->id }}">{{{ $user->name }}} ({{{ $user->email }}})</option>
                        @endforeach
                    </select>
                    , right now.
                </p>
            </div>

            <div class="row-fluid">
                <button type="submit" class="btn btn-primary btn-large pull-right"><i class="icon-magic"></i> Generate invites!</button>
            </div>

        {{ Form::close() }}
    </section>

@stop
