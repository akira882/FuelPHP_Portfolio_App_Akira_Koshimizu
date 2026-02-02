<?php

class Controller_Auth extends Controller
{
    public function action_register()
    {
        if (Auth::check()) {
            Response::redirect('tasks');
        }

        if (Input::method() === 'POST') {
            $val = Validation::forge();
            $val->add('username', 'ユーザー名')
                ->add_rule('required')
                ->add_rule('min_length', 3)
                ->add_rule('max_length', 50);
            $val->add('email', 'メールアドレス')
                ->add_rule('required')
                ->add_rule('valid_email');
            $val->add('password', 'パスワード')
                ->add_rule('required')
                ->add_rule('min_length', 6);

            if ($val->run()) {
                try {
                    $user = Auth::create_user(
                        Input::post('username'),
                        Input::post('password'),
                        Input::post('email'),
                        1
                    );

                    if ($user) {
                        Session::set_flash('success', '登録が完了しました。ログインしてください。');
                        Response::redirect('auth/login');
                    } else {
                        Session::set_flash('error', '登録に失敗しました');
                    }
                } catch (Exception $e) {
                    Session::set_flash('error', '登録エラー: ' . $e->getMessage());
                }
            } else {
                Session::set_flash('error', $val->error());
            }
        }

        return View::forge('auth/register');
    }

    public function action_login()
    {
        if (Auth::check()) {
            Response::redirect('tasks');
        }

        if (Input::method() === 'POST') {
            $username = Input::post('username');
            $password = Input::post('password');

            if (Auth::login($username, $password)) {
                Session::set_flash('success', 'ログインしました');
                Response::redirect('tasks');
            } else {
                Session::set_flash('error', 'ユーザー名またはパスワードが間違っています');
            }
        }

        return View::forge('auth/login');
    }

    public function action_logout()
    {
        Auth::logout();
        Session::set_flash('success', 'ログアウトしました');
        Response::redirect('auth/login');
    }
}
