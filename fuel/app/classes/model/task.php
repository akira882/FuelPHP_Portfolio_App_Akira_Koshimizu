<?php

class Model_Task extends \Orm\Model
{
    /**
     * ステータス定数
     */
    const STATUS_INCOMPLETE = 0;
    const STATUS_COMPLETE = 1;

    /**
     * 優先度定数
     */
    const PRIORITY_LOW = 0;
    const PRIORITY_MEDIUM = 1;
    const PRIORITY_HIGH = 2;

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
            self::PRIORITY_LOW => '低',
            self::PRIORITY_MEDIUM => '中',
            self::PRIORITY_HIGH => '高',
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
            self::PRIORITY_LOW => '#6e7681',  // 低 - グレー
            self::PRIORITY_MEDIUM => '#58a6ff',  // 中 - ブルー
            self::PRIORITY_HIGH => '#ff7b72',  // 高 - レッド
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

    /**
     * ユーザーがこのタスクを編集可能か判定
     *
     * @param int $user_id ユーザーID
     * @return bool 編集可能な場合true
     */
    public function can_edit($user_id)
    {
        // プロジェクトタスクの場合
        if ($this->project_id) {
            $project = Model_Project::find($this->project_id);
            return $project && $project->has_access($user_id);
        }

        // 個人タスクの場合
        return $this->user_id == $user_id;
    }

    /**
     * ユーザーがこのタスクを閲覧可能か判定
     *
     * @param int $user_id ユーザーID
     * @return bool 閲覧可能な場合true
     */
    public function can_view($user_id)
    {
        return $this->can_edit($user_id);
    }

    /**
     * ユーザーがこのタスクを削除可能か判定
     *
     * @param int $user_id ユーザーID
     * @return bool 削除可能な場合true
     */
    public function can_delete($user_id)
    {
        // プロジェクトタスクの場合はオーナーまたは管理者のみ
        if ($this->project_id) {
            $project = Model_Project::find($this->project_id);
            if (!$project) {
                return false;
            }
            $role = $project->get_role($user_id);
            return $role === 'owner' || $role === 'admin';
        }

        // 個人タスクの場合は作成者のみ
        return $this->user_id == $user_id;
    }

    /**
     * ユーザーのタスク統計情報を取得
     *
     * @param int $user_id ユーザーID
     * @return array 統計情報（total, completed, pending）
     */
    public static function get_statistics($user_id)
    {
        $result = \DB::select(
            \DB::expr('COUNT(*) as total'),
            \DB::expr('SUM(CASE WHEN done = ' . self::STATUS_COMPLETE . ' THEN 1 ELSE 0 END) as completed'),
            \DB::expr('SUM(CASE WHEN done = ' . self::STATUS_INCOMPLETE . ' THEN 1 ELSE 0 END) as pending')
        )
        ->from(static::$_table_name)
        ->where('user_id', '=', $user_id)
        ->execute()
        ->current();

        return array(
            'total' => (int) $result['total'],
            'completed' => (int) $result['completed'],
            'pending' => (int) $result['pending'],
        );
    }

    /**
     * トランザクション内でタスクと関連データを安全に削除
     *
     * @return bool 削除成功時true
     * @throws \FuelException 削除失敗時
     */
    public function delete_with_transaction()
    {
        try {
            \DB::start_transaction();

            // チェックリストを明示的に削除（cascade_deleteがtrueだが念のため明示）
            \DB::delete('task_checklists')
                ->where('task_id', '=', $this->id)
                ->execute();

            // タスク本体を削除
            $result = $this->delete();

            if (!$result) {
                throw new \FuelException('タスクの削除に失敗しました');
            }

            \DB::commit_transaction();

            \Log::info('Task deleted successfully', array(
                'task_id' => $this->id,
                'title' => $this->title,
            ));

            return true;

        } catch (\Exception $e) {
            \DB::rollback_transaction();

            \Log::error('Failed to delete task', array(
                'task_id' => $this->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ));

            throw $e;
        }
    }
}
