<?php

return array(
        'store' => array(
                'success' => '<h5><i class="icon-ok-sign"></i> <strong>:amount</strong> invites have been generated for <em>:userName</em>.</h5>',
                'error' => '<h5><i class="icon-exclamation-sign"></i> Unable to generate invites for <em>:userName</em> for some reason.</h5>',
                'validator' => '<h5><i class="icon-exclamation-sign"></i> Unable to generate invites due to issues with your input. Please check your paramters and try again.</h5>',
            ),
        'destroy' => array(
                'success' => '<h5><i class="icon-ok-sign"></i> The invite was successfully deleted.</h5>',
                'error' => '<h5><i class="icon-exclamation-sign"></i> Unable to delete the invite for some reason.</h5>',
                'not_found' => '<h5><i class="icon-exclamation-sign"></i> Unable to delete because the invite was either not specified or does not exist.</h5>',
                'is_used' => '<h5><i class="icon-exclamation-sign"></i> You cannot delete an invite that has already been used.</h5>',
            ),
    );
