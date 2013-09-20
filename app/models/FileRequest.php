<?php

class FileRequest extends Eloquent
{

    protected $table = 'file_requests';
    protected $softDelete = true;
    protected $guarded = array('id');

    /**
     * Model relationship functions.
     */

    public function user()
    {
        return $this->belongsTo('User');
    }

    public function file()
    {
        return $this->belongsTo('RepositoryFile', 'file_id');
    }

}
