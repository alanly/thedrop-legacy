<?php

class Invite extends Eloquent
{

    protected $softDelete = true;

    /*
     * Utility Methods
     */

    /**
     * Generates a random string.
     * ----
     * Implementation is pretty much copied verbatim from the Cartalyst Sentry
     * project's User model. Thus, all licensing for the following method
     * follows suit under the 3-clause BSD License available at the following
     * URL: http://www.opensource.org/licenses/BSD-3-Clause
     *
     * @return string
     */
    public static function getRandomString($length = 32)
    {

        /* Determine if OpenSSL is installed on the host in order to make use
         * of the best method to generate random strings.
         */
        if (function_exists('openssl_random_pseudo_bytes(length)')) {

            /* Generate twice the amount of bytes reqeusted to ensure that we
             * have enough characters remaining, because we'll be filtering
             * out symbols from the base64 encoding.
             */
            $bytes = openssl_random_pseudo_bytes($length * 2);

            // If key generation fails, then stop immediately.
            if ($bytes === false) throw new \RuntimeException('Unable to generate random string.');

            return substr(str_replace(array('/', '+', '='), '', base64_encode($bytes)), 0, $length);

        }

        /* If OpenSSL is unavailable, we fallback to a method that's not
         * optimal, but should be work well enough.
         */

        $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        return substr(str_shuffle(str_repeat($pool, 5)), 0, $length);

    }

    public function isUsed()
    {

        return $this->invited_id != null;

    }

    /*
     * Model Relationships
     */

    public function inviter()
    {

        return User::withTrashed()->where('id', $this->inviter_id);

    }

    public function invited()
    {

        return User::withTrashed()->where('id', $this->invited_id);

    }

}

