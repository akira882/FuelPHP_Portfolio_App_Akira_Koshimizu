<?php

class Model_TaskChecklist extends \Orm\Model
{
    protected static $_properties = array(
        'id',
        'task_id',
        'title',
        'is_completed',
        'sort_order',
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

    protected static $_table_name = 'task_checklists';

    protected static $_belongs_to = array(
        'task' => array(
            'key_from' => 'task_id',
            'model_to' => 'Model_Task',
            'key_to' => 'id',
        )
    );
}
