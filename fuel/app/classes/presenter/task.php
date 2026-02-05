<?php

/**
 * TaskPresenter（World Class設計）
 *
 * タスクの表示ロジックをViewから分離
 * Viewは単純な表示のみに専念
 *
 * @package    Presenter
 * @category   Presentation Layer
 * @author     World Class FuelPHP Engineer
 */
class Presenter_Task extends Presenter_Base
{
    /**
     * @var Model_Task タスクモデル
     */
    protected $task;

    /**
     * コンストラクタ
     *
     * @param Model_Task $task タスクモデル
     */
    public function __construct($task)
    {
        parent::__construct($task);
        $this->task = $task;
    }

    /**
     * View用データ配列を返す
     *
     * @return array View用データ
     */
    public function to_array()
    {
        return array(
            'id' => $this->task->id,
            'title' => $this->escape($this->task->title),
            'content' => $this->escape($this->task->content),
            'done' => $this->task->done,
            'priority' => $this->task->priority,
            'user_id' => $this->task->user_id,
            'project_id' => $this->task->project_id,

            // 表示用フォーマット（Viewロジックを排除）
            'due_date_formatted' => $this->get_due_date_formatted(),
            'created_at_formatted' => $this->format_datetime($this->task->created_at),
            'updated_at_formatted' => $this->format_datetime($this->task->updated_at),
            'created_at_relative' => $this->relative_time($this->task->created_at),

            // ステータス表示
            'status_label' => $this->get_status_label(),
            'status_color' => $this->get_status_color(),

            // 優先度表示
            'priority_label' => $this->task->get_priority_label(),
            'priority_color' => $this->task->get_priority_color(),

            // 状態判定
            'is_overdue' => $this->task->is_overdue(),
            'is_completed' => $this->task->done == Model_Task::STATUS_COMPLETE,
            'is_pending' => $this->task->done == Model_Task::STATUS_INCOMPLETE,
        );
    }

    /**
     * フォーマットされた期限日を取得
     *
     * @return string フォーマットされた期限日
     */
    protected function get_due_date_formatted()
    {
        if (!$this->task->due_date) {
            return '期限なし';
        }

        $formatted = $this->format_date($this->task->due_date);

        // 期限切れの場合は警告を追加
        if ($this->task->is_overdue()) {
            return $formatted . ' (期限切れ)';
        }

        return $formatted;
    }

    /**
     * ステータスラベルを取得
     *
     * @return string ステータスラベル
     */
    protected function get_status_label()
    {
        return $this->task->done == Model_Task::STATUS_COMPLETE ? '完了' : '未完了';
    }

    /**
     * ステータスカラーを取得
     *
     * @return string ステータスカラー（CSSクラス名）
     */
    protected function get_status_color()
    {
        if ($this->task->done == Model_Task::STATUS_COMPLETE) {
            return '#56d364'; // 緑
        }

        if ($this->task->is_overdue()) {
            return '#ff7b72'; // 赤（期限切れ）
        }

        return '#58a6ff'; // 青（進行中）
    }

    /**
     * 複数のタスクをPresenter配列に変換
     *
     * @param array $tasks タスクモデルの配列
     * @return array Presenter配列
     */
    public static function collection($tasks)
    {
        $result = array();
        foreach ($tasks as $task) {
            $presenter = new self($task);
            $result[] = $presenter->to_array();
        }
        return $result;
    }
}
