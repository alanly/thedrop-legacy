<?php

class UserTableSeeder extends Seeder
{

    public function run()
    {

        // Create the initial user groups.
        $adminGroup = $this->createAdminGroup();

        if ( ! $adminGroup || ! $this->createPrivilegedGroup() || ! $this->createUserGroup() ) {
            echo 'Unable to create user groups.';
            return false;
        }

        // Create the primary admin user.
        try {
            $user = Sentry::register(array(
                'email' => 'admin@thedrop.pw',
                'password' => 'password',
                'name' => 'Administrator',
            ), true);

            $user->addGroup($adminGroup);

            $user->save();

            $user->resetApiKey();

        } catch (Exception $e) {
            echo 'Unable to create the default user: ' . $e->getMessage();
        }

    }

    private function createAdminGroup()
    {
        try {
            $group = Sentry::getGroupProvider()->create(array(
                'name' => 'Administrators',
            ));

            return $group;
        } catch (Cartalyst\Sentry\Groups\GroupExistsException $e) {
            return Sentry::getGroupProvider()->findByName('Administrators');
        } catch (Exception $e) {
            return false;
        }
    }

    private function createPrivilegedGroup()
    {
        try {
            $group = Sentry::getGroupProvider()->create(array(
                'name' => 'Privileged',
            ));

            return $group;
        } catch (Cartalyst\Sentry\Groups\GroupExistsException $e) {
            return Sentry::getGroupProvider()->findByName('Privileged');
        } catch (Exception $e) {
            return false;
        }
    }

    private function createUserGroup()
    {
        try {
            $group = Sentry::getGroupProvider()->create(array(
                'name' => 'Users',
            ));

            return $group;
        } catch (Cartalyst\Sentry\Groups\GroupExistsException $e) {
            return Sentry::getGroupProvider()->findByName('Users');
        } catch (Exception $e) {
            return false;
        }
    }

}

