<?php

class Controller_Base extends Controller
{
    public function before()
    {
        parent::before();

        if (!Auth::check()) {
            $current_action = Request::active()->action;
            $allowed_actions = array('login', 'register');

            if (!in_array($current_action, $allowed_actions)) {
                Session::set_flash('error', 'ログインが必要です');
                Response::redirect('auth/login');
            }
        }
    }
}
