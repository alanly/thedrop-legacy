<?php

use Cartalyst\Sentry\Users\Eloquent\User as SentryUserModel;

class User extends SentryUserModel
{

    protected $softDelete = true;

    /*
     * Utility Methods
     */

    public function getGravatarUrl($size = 100, $default = 'identicon')
    {
        $hash = md5(strtolower(trim($this->email)));

        return "//www.gravatar.com/avatar/{$hash}?s={$size}&d={$default}";
    }

    public function resetApiKey()
    {
        $key = $this->getRandomString(24);

        // Make sure we don't have a duplicate key (unlikely, but we never know)
        while ( User::where('api_key', $key)->first() )
            $key = $this->getRandomString();

        $this->api_key = $key;

        return $this->save();
    }

    public function inGroupByName($groupName = null)
    {
        if ($groupName === null) return false;

        $group = Sentry::getGroupProvider()->findByName($groupName);

        return $this->inGroup($group);
    }

    public function isAdmin()
    {
        return $this->inGroupByName('Administrators');
    }

    public function isPrivileged()
    {
        return $this->isAdmin() || $this->inGroupByName('Privileged');
    }

    public function isBanned()
    {
        return Sentry::getThrottleProvider()->findByUserId($this->id)->isBanned();
    }

    public function canTest()
    {
        return $this->inGroupByName('Testers');
    }

    public function hasDownloaded($file)
    {
        return $this->downloads()->where('file_id', $file->id)->count() > 0 ? true : false;
    }

    public function hasSetting($key)
    {
        $count = $this->settings()->where('key', $key)->count('id');

        return ($count > 0) ? true : false;
    }

    public function getSetting($key, $default = null)
    {
        $setting = $this->settings()->where('key', $key)->first();

        return (! $setting) ? $default : $setting->value;
    }



    public function setSetting($key, $value)
    {
        if ($this->hasSetting($key)) {
            $setting = $this->settings()->where('key', $key)->first();
        } else {
            $setting = new UserSetting;
            $setting->user_id = $this->id;
            $setting->key = $key;
        }

        $setting->value = $value;

        return $setting->save();
    }

    /*
     * Invitation Related Implementation
     */

    public function getInviter()
    {
        $invite = Invite::where('invited_id', $this->id)->first();

        if(! $invite)
            return null;

        return Sentry::getUserProvider()->findById($invite->inviter_id);
    }

    /*
     * Model Relationships
     */

    public function invites()
    {
        return $this->hasMany('Invite', 'inviter_id');
    }

    public function downloads()
    {
        return $this->hasMany('Download');
    }

    public function requests()
    {
        return $this->hasMany('FileRequest');
    }

    public function settings()
    {
        return $this->hasMany('UserSetting');
    }

}

