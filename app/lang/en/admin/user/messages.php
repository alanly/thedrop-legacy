<?php

return array(

        'store' => array(),
        'update' => array(
                'api_key' => array(
                        'success' => '<h5><i class="icon-ok-sign"></i> The API key has been successfully updated for <em>:name</em>.</h5>',
                        'error' => '<h5><i class="icon-exclamation-sign"></i> Unable to reset the API key for <em>:name</em> for some reason.</h5>',
                    ),
                'group' => array(
                        'add' => array(
                                'success' => '<h5><i class="icon-ok-sign"></i> <em>:userName</em> was successfully added to the <em>:groupName</em> group.</h5>',
                                'error' => '<h5><i class="icon-exclamation-sign"></i> Unable to add <em>:userName</em> to the <em>:groupName</em> group for some reason.',
                                'already_in_group' => '<h5><i class="icon-info-sign"></i> <em>:userName</em> already belongs to the <em>:groupName</em> group.</h5>',
                            ),
                        'remove' => array(
                                'success' => '<h5><i class="icon-ok-sign"></i> <em>:userName</em> was successfully removed from the <em>:groupName</em> group.</h5>',
                                'error' => '<h5><i class="icon-ok-sign"></i> Unable to remove <em>:userName</em> from the <em>:groupName</em> group for some reason.</h5>',
                                'not_in_group' => '<h5><i class="icon-info-sign"></i> <em>:userName</em> is not current a member of the <em>:groupName</em> group.</h5>',
                                'primary_admin_error' => '<h5><i class="icon-exclamation-sign"></i> You cannot remove the primary administrator from the <em>Administrators</em> group.</h5>',
                            ),
                        'unspecified_group' => '<h5><i class="icon-exclamation-sign"></i> Could not add the user to a group because a group was not properly defined.</h5>',
                        'group_not_found' => '<h5><i class="icon-exclamation-sign"></i> The group you have defined does not exist.</h5>',
                    ),
                'ban' => array(
                        'success' => '<h5><i class="icon-ok-sign"></i> The account lock for <em>:userName</em> has been successfully toggled.</h5>',
                        'error' => '<h5><i class="icon-exclamation-sign"></i> Unable to toggle account lock for <em>:userName</em> for some reason.</h5>',
                        'primary_admin_error' => '<h5><i class="icon-exclamation-sign"></i> You cannot ban the primary administrator.</h5>',
                        'current_user_error' => '<h5><i class="icon-exclamation-sign"></i> You cannot ban yourself.</h5>',
                    ),
                'undefined_update_type' => '<h5><i class="icon-exclamation-sign"></i> The update operation was not defined or recognized.</h5>',
            ),
        'destroy' => array(
                'success' => '<h5><i class="icon-ok-sign"></i> <em>:name</em> was successfully removed from the system.</h5>',
                'error' => '<h5><i class="icon-exclamation-sign"></i> Unable to delete <em>:name</em> from the system for some reason.</h5>',
                'current_user_error' => '<h5><i class="icon-exclamation-sign"></i> You cannot delete yourself.</h5>',
                'primary_admin_error' => '<h5><i class="icon-exclamation-sign"></i> You cannot delete the primary administrator.</h5>',
            ),

    );
