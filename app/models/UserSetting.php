<?php

class UserSetting extends Eloquent
{

    protected $table = 'user_settings';

    protected $guarded = array('id');

    /**
     * Model Relationships
     */

    public function user()
    {
        return $this->belongsTo('User');
    }

}
