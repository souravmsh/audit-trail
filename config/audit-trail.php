<?php

return [
    /*
     * Enable or disable activity logging. Set to false to disable saving activities to the database.
     */
    'enabled' => env('AUDITTRAIL_ENABLED', true),

    /*
     * Configuration for the audit trail table.
     */
    'migration' => [
        'table' => 'audit_trails',  // The name of the table that stores the activity logs
        
        'type' => [
            'retrieved' => 'RETRIEVED',
            'created'   => 'CREATED',
            'updated'   => 'UPDATED',
            'deleted'   => 'DELETED',
            'loggedin'  => 'LOGGEDIN',
            'imported'  => 'IMPORTED',
            'exported'  => 'EXPORTED',
            'other'     => 'OTHER',
        ],

        'status' => [
            'seen'   => 'SEEN',    // Status when an activity is seen
            'unseen' => 'UNSEEN',  // Status when an activity is unseen
        ],

        'pagination' => 10,  // Number of activities to display per page

        // Attributes to be ignored during activity logging (e.g., sensitive fields)
        'ignored_attributes' => [
            '_token', 'remember_token', 'password', 'created_at', 'updated_at', 'deleted_at',
        ],
    ],

    /*
     * Configuration for models and events related to activity logging.
     */
    'model' =>  Souravmsh\AuditTrail\Models\AuditTrail::class,  // The audit model for storing logs

    // Define the events that should trigger activity logs
    'events' => [
        'created'   => 'CREATED',
        'updated'   => 'UPDATED',
        'deleted'   => 'DELETED'
    ],
];
