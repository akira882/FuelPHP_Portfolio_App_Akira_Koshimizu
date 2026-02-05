<?php

/**
 * バリデーション失敗例外（World Class設計）
 *
 * 入力データのバリデーションが失敗した場合にスローされる
 *
 * @package    Exception
 * @category   Error Handling
 * @author     World Class FuelPHP Engineer
 */
class Exception_ValidationFailed extends \FuelException
{
    /**
     * HTTPステータスコード
     * @var int
     */
    protected $http_status = 400;

    /**
     * バリデーションエラーメッセージ配列
     * @var array
     */
    protected $validation_errors = array();

    /**
     * コンストラクタ
     *
     * @param string $message エラーメッセージ
     * @param array $validation_errors バリデーションエラー配列
     * @param int $code エラーコード
     * @param Exception|null $previous 前の例外
     */
    public function __construct($message = 'バリデーションに失敗しました', $validation_errors = array(), $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->validation_errors = $validation_errors;

        \Log::info('Validation failed', array(
            'message' => $message,
            'errors' => $validation_errors,
            'user' => \Auth::check() ? \Auth::get_user_id()[1] : 'guest',
        ));
    }

    /**
     * バリデーションエラー配列を取得
     *
     * @return array バリデーションエラー配列
     */
    public function getValidationErrors()
    {
        return $this->validation_errors;
    }

    /**
     * HTTPステータスコードを取得
     *
     * @return int HTTPステータスコード
     */
    public function getHttpStatus()
    {
        return $this->http_status;
    }
}
