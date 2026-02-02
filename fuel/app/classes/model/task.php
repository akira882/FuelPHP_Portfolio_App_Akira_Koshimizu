<?php

class Model_Task extends \Orm\Model
{
    protected static $_properties = array(
        'id',
        'title',
        'content',
        'user_id',
        'project_id',
        'done',
        'created_at',
        'updated_at',
    );

    protected static $_belongs_to = array(
        'project' => array(
            'key_from' => 'project_id',
            'model_to' => 'Model_Project',
            'key_to' => 'id',
            'cascade_save' => false,
            'cascade_delete' => false,
        )
    );

    protected static $_has_many = array(
        'checklists' => array(
            'key_from' => 'id',
            'model_to' => 'Model_TaskChecklist',
            'key_to' => 'task_id',
            'cascade_save' => false,
            'cascade_delete' => true,
        )
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

    protected static $_table_name = 'tasks';
}
