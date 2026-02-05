<?php

/**
 * 認証エラー例外（World Class設計）
 *
 * アクセス権限がない場合にスローされる
 *
 * @package    Exception
 * @category   Error Handling
 * @author     World Class FuelPHP Engineer
 */
class Exception_Unauthorized extends \FuelException
{
    /**
     * HTTPステータスコード
     * @var int
     */
    protected $http_status = 403;

    /**
     * コンストラクタ
     *
     * @param string $message エラーメッセージ
     * @param int $code エラーコード
     * @param Exception|null $previous 前の例外
     */
    public function __construct($message = 'アクセス権限がありません', $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);

        \Log::warning('Unauthorized access attempt', array(
            'message' => $message,
            'user' => \Auth::check() ? \Auth::get_user_id()[1] : 'guest',
            'url' => \Uri::current(),
        ));
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
