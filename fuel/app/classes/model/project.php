<?php

class Model_Project extends \Orm\Model
{
    protected static $_properties = array(
        'id',
        'name',
        'description',
        'due_date',
        'user_id',
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

    protected static $_table_name = 'projects';

    protected static $_has_many = array(
        'tasks' => array(
            'key_from' => 'id',
            'model_to' => 'Model_Task',
            'key_to' => 'project_id',
            'cascade_save' => false,
            'cascade_delete' => true,
        )
    );
}
