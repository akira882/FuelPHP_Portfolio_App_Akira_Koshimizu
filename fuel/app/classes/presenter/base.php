<?php

/**
 * Presenter基底クラス（World Class設計）
 *
 * Viewとビジネスロジックを分離するためのPresenterパターン実装
 * Viewは表示のみに専念し、全てのデータ加工はPresenterで完結させる
 *
 * @package    Presenter
 * @category   Presentation Layer
 * @author     World Class FuelPHP Engineer
 */
abstract class Presenter_Base
{
    /**
     * Modelオブジェクトまたはデータ配列
     * @var mixed
     */
    protected $data;

    /**
     * コンストラクタ
     *
     * @param mixed $data Modelオブジェクトまたはデータ配列
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * 日付を人間が読みやすい形式でフォーマット
     *
     * @param int|null $timestamp Unixタイムスタンプ
     * @param string $format 日付フォーマット（デフォルト: Y-m-d）
     * @return string フォーマットされた日付、またはnullの場合は「未設定」
     */
    protected function format_date($timestamp, $format = 'Y-m-d')
    {
        if (!$timestamp) {
            return '未設定';
        }

        return date($format, $timestamp);
    }

    /**
     * 日付時刻を人間が読みやすい形式でフォーマット
     *
     * @param int|null $timestamp Unixタイムスタンプ
     * @return string フォーマットされた日付時刻
     */
    protected function format_datetime($timestamp)
    {
        return $this->format_date($timestamp, 'Y-m-d H:i');
    }

    /**
     * 相対的な時間表現を取得（例: 3日前、2時間後）
     *
     * @param int $timestamp Unixタイムスタンプ
     * @return string 相対的な時間表現
     */
    protected function relative_time($timestamp)
    {
        $diff = time() - $timestamp;

        if ($diff < 60) {
            return '1分以内';
        } elseif ($diff < 3600) {
            return floor($diff / 60) . '分前';
        } elseif ($diff < 86400) {
            return floor($diff / 3600) . '時間前';
        } elseif ($diff < 2592000) {
            return floor($diff / 86400) . '日前';
        } else {
            return $this->format_date($timestamp);
        }
    }

    /**
     * ファイルサイズを人間が読みやすい形式でフォーマット
     *
     * @param int $bytes バイト数
     * @return string フォーマットされたファイルサイズ
     */
    protected function format_filesize($bytes)
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);

        return round($bytes, 2) . ' ' . $units[$pow];
    }

    /**
     * XSS対策のためのHTMLエスケープ
     *
     * @param string $string エスケープする文字列
     * @return string エスケープされた文字列
     */
    protected function escape($string)
    {
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }

    /**
     * データをViewに渡す形式で返す抽象メソッド
     *
     * @return array View用データ配列
     */
    abstract public function to_array();
}
