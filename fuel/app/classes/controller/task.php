<?php
/**
 * Controller_Task クラス
 * 
 * WordPressでの「特定のテンプレートファイル」に相当する役割を持ちますが、
 * より強力な「指揮者（司令塔）」として動きます。
 * 
 * クラス名はファイル名と連動しており、Controller_Task は controller/task.php に対応します。
 */
class Controller_Task extends Controller
{
    /**
     * action_hello メソッド
     * 
     * URLの末尾が /task/hello の時に実行される「アクション」です。
     * WordPressでいう「特定のページが表示された時の処理」をここに書きます。
     */
    public function action_hello()
    {
        /**
         * View::forge() に2つ目の引数を渡すと、
         * 見た目（Views）の方でそのデータを使えるようになります。
         */
        $data = array();
        $data['username'] = 'ゲスト'; // 名前を渡してみる
        $data['time'] = date('H:i:s'); // 現在時刻を渡してみる
        
        return View::forge('task/hello', $data);
    }
}
