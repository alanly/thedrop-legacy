<?php

class FileMeta extends Eloquent
{

    protected $table = 'file_meta';
    protected $softDelete = true;

    protected $guarded = array('id');

    public function file()
    {

        return $this->belongsTo('RepositoryFile', 'file_id');
        
    }

}
