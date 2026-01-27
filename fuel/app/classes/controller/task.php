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
         * View::forge() は「見た目（HTMLファイル）を準備しろ」という命令です。
         * ここでは views/task/hello.php を使うように指示しています。
         */
        return View::forge('task/hello');
    }
}
