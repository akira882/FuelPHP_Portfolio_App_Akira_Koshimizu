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
        ),
        'members' => array(
            'key_from' => 'id',
            'model_to' => 'Model_ProjectMember',
            'key_to' => 'project_id',
            'cascade_save' => false,
            'cascade_delete' => true,
        ),
        'files' => array(
            'key_from' => 'id',
            'model_to' => 'Model_ProjectFile',
            'key_to' => 'project_id',
            'cascade_save' => false,
            'cascade_delete' => true,
        )
    );

    public function has_access($user_id)
    {
        if ($this->user_id == $user_id) {
            return true;
        }

        $member = Model_ProjectMember::query()
            ->where('project_id', $this->id)
            ->where('user_id', $user_id)
            ->get_one();

        return $member !== null;
    }

    public function get_role($user_id)
    {
        if ($this->user_id == $user_id) {
            return 'owner';
        }

        $member = Model_ProjectMember::query()
            ->where('project_id', $this->id)
            ->where('user_id', $user_id)
            ->get_one();

        return $member ? $member->role : null;
    }

    /**
     * プロジェクトのタスク統計情報を取得（World Class最適化）
     *
     * @param int $project_id プロジェクトID
     * @return array 統計情報（total, completed, pending）
     */
    public static function get_task_statistics($project_id)
    {
        $result = \DB::select(
            \DB::expr('COUNT(*) as total'),
            \DB::expr('SUM(CASE WHEN done = ' . Model_Task::STATUS_COMPLETE . ' THEN 1 ELSE 0 END) as completed'),
            \DB::expr('SUM(CASE WHEN done = ' . Model_Task::STATUS_INCOMPLETE . ' THEN 1 ELSE 0 END) as pending')
        )
        ->from('tasks')
        ->where('project_id', '=', $project_id)
        ->execute()
        ->current();

        return array(
            'total' => (int) $result['total'],
            'completed' => (int) $result['completed'],
            'pending' => (int) $result['pending'],
        );
    }
}
