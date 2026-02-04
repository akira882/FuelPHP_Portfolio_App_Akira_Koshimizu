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
        'priority',
        'due_date',
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

    /**
     * Get priority label
     * @return string
     */
    public function get_priority_label()
    {
        $labels = array(
            0 => '低',
            1 => '中',
            2 => '高',
        );
        return isset($labels[$this->priority]) ? $labels[$this->priority] : '中';
    }

    /**
     * Get priority color
     * @return string
     */
    public function get_priority_color()
    {
        $colors = array(
            0 => '#6e7681',  // 低 - グレー
            1 => '#58a6ff',  // 中 - ブルー
            2 => '#ff7b72',  // 高 - レッド
        );
        return isset($colors[$this->priority]) ? $colors[$this->priority] : '#58a6ff';
    }

    /**
     * Check if task is overdue
     * @return bool
     */
    public function is_overdue()
    {
        return $this->due_date && $this->due_date < time() && !$this->done;
    }
}
