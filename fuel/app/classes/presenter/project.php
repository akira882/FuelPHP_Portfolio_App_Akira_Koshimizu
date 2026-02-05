<?php

/**
 * ProjectPresenter（World Class設計）
 *
 * プロジェクトの表示ロジックをViewから分離
 * Viewは単純な表示のみに専念
 *
 * @package    Presenter
 * @category   Presentation Layer
 * @author     World Class FuelPHP Engineer
 */
class Presenter_Project extends Presenter_Base
{
    /**
     * @var Model_Project プロジェクトモデル
     */
    protected $project;

    /**
     * コンストラクタ
     *
     * @param Model_Project $project プロジェクトモデル
     */
    public function __construct($project)
    {
        parent::__construct($project);
        $this->project = $project;
    }

    /**
     * View用データ配列を返す
     *
     * @return array View用データ
     */
    public function to_array()
    {
        return array(
            'id' => $this->project->id,
            'name' => $this->escape($this->project->name),
            'description' => $this->escape($this->project->description),
            'user_id' => $this->project->user_id,
            'due_date' => $this->project->due_date,

            // 表示用フォーマット（Viewロジックを排除）
            'due_date_formatted' => $this->get_due_date_formatted(),
            'created_at_formatted' => $this->format_datetime($this->project->created_at),
            'updated_at_formatted' => $this->format_datetime($this->project->updated_at),
            'created_at_relative' => $this->relative_time($this->project->created_at),

            // 状態判定
            'is_overdue' => $this->is_overdue(),
            'has_due_date' => !empty($this->project->due_date),
        );
    }

    /**
     * フォーマットされた期限日を取得
     *
     * @return string フォーマットされた期限日
     */
    protected function get_due_date_formatted()
    {
        if (!$this->project->due_date) {
            return '期限なし';
        }

        $formatted = $this->format_date($this->project->due_date);

        // 期限切れの場合は警告を追加
        if ($this->is_overdue()) {
            return $formatted . ' (期限切れ)';
        }

        return $formatted;
    }

    /**
     * プロジェクトが期限切れかどうか判定
     *
     * @return bool 期限切れの場合true
     */
    protected function is_overdue()
    {
        return $this->project->due_date && $this->project->due_date < time();
    }

    /**
     * 複数のプロジェクトをPresenter配列に変換
     *
     * @param array $projects プロジェクトモデルの配列
     * @return array Presenter配列
     */
    public static function collection($projects)
    {
        $result = array();
        foreach ($projects as $project) {
            $presenter = new self($project);
            $result[] = $presenter->to_array();
        }
        return $result;
    }
}
