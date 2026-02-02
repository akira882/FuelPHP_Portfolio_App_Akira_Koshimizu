<?php

class Model_ProjectMember extends \Orm\Model
{
    protected static $_properties = array(
        'id',
        'project_id',
        'user_id',
        'role',
        'created_at',
        'updated_at',
    );

    protected static $_observers = array(
        'Orm\Observer_CreatedAt' => array(
            'events' => array('before_insert'),
            'mysql_timestamp' => false,
        ),
        'Orm\Observer_UpdatedAt' => array(
            'events' => array('before_update'),
            'mysql_timestamp' => false,
        ),
    );

    protected static $_table_name = 'project_members';

    protected static $_belongs_to = array(
        'project' => array(
            'key_from' => 'project_id',
            'model_to' => 'Model_Project',
            'key_to' => 'id',
        ),
        'user' => array(
            'key_from' => 'user_id',
            'model_to' => 'Model_User',
            'key_to' => 'id',
        )
    );
}
