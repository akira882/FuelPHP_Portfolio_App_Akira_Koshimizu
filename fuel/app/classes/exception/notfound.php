<?php

/**
 * リソース未発見例外（World Class設計）
 *
 * 要求されたリソースが見つからない場合にスローされる
 *
 * @package    Exception
 * @category   Error Handling
 * @author     World Class FuelPHP Engineer
 */
class Exception_NotFound extends \FuelException
{
    /**
     * HTTPステータスコード
     * @var int
     */
    protected $http_status = 404;

    /**
     * コンストラクタ
     *
     * @param string $message エラーメッセージ
     * @param int $code エラーコード
     * @param Exception|null $previous 前の例外
     */
    public function __construct($message = 'リソースが見つかりません', $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);

        \Log::info('Resource not found', array(
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
