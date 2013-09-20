<?php

class Download extends Eloquent
{

    protected $guarded = array('id');

    public function user()
    {

        return $this->belongsTo('User');

    }

    public function file()
    {

        return RepositoryFile::withTrashed()->where('id', $this->file_id);
        
    }

}

