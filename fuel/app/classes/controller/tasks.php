<?php

class Controller_Tasks extends Controller_Base
{
    public function action_index()
    {
        $user_id = Auth::get_user_id();
        $filter = Input::get('filter', 'all');

        $query = Model_Task::query()->where('user_id', $user_id[1]);

        if ($filter === 'pending') {
            $query->where('done', 0);
        } elseif ($filter === 'completed') {
            $query->where('done', 1);
        }

        $data['tasks'] = $query->order_by('created_at', 'desc')->get();
        $data['filter'] = $filter;
        $data['total'] = Model_Task::query()->where('user_id', $user_id[1])->count();
        $data['completed'] = Model_Task::query()->where('user_id', $user_id[1])->where('done', 1)->count();
        $data['pending'] = Model_Task::query()->where('user_id', $user_id[1])->where('done', 0)->count();

        return View::forge('tasks/index', $data);
    }

    public function action_create()
    {
        if (Input::method() === 'POST') {
            $val = Validation::forge();
            $val->add('title', 'タイトル')
                ->add_rule('required')
                ->add_rule('max_length', 255);

            if ($val->run()) {
                $user_id = Auth::get_user_id();
                $task = Model_Task::forge();
                $task->title = Input::post('title');
                $task->content = Input::post('content');
                $task->user_id = $user_id[1];
                $task->done = 0;

                if ($task->save()) {
                    Session::set_flash('success', 'タスクを作成しました');
                    Response::redirect('tasks');
                } else {
                    Session::set_flash('error', '作成に失敗しました');
                }
            } else {
                Session::set_flash('error', $val->error());
            }
        }

        return View::forge('tasks/create');
    }

    public function action_edit($id = null)
    {
        $user_id = Auth::get_user_id();
        $task = Model_Task::find($id);

        if (!$task || $task->user_id != $user_id[1]) {
            Session::set_flash('error', 'タスクが見つかりません');
            Response::redirect('tasks');
        }

        if (Input::method() === 'POST') {
            $val = Validation::forge();
            $val->add('title', 'タイトル')
                ->add_rule('required')
                ->add_rule('max_length', 255);

            if ($val->run()) {
                $task->title = Input::post('title');
                $task->content = Input::post('content');

                if ($task->save()) {
                    Session::set_flash('success', 'タスクを更新しました');
                    Response::redirect('tasks');
                } else {
                    Session::set_flash('error', '更新に失敗しました');
                }
            } else {
                Session::set_flash('error', $val->error());
            }
        }

        $data['task'] = $task;
        return View::forge('tasks/edit', $data);
    }

    public function action_delete($id = null)
    {
        $user_id = Auth::get_user_id();
        $task = Model_Task::find($id);

        if (!$task || $task->user_id != $user_id[1]) {
            Session::set_flash('error', 'タスクが見つかりません');
            Response::redirect('tasks');
        }

        if ($task->delete()) {
            Session::set_flash('success', 'タスクを削除しました');
        } else {
            Session::set_flash('error', '削除に失敗しました');
        }

        Response::redirect('tasks');
    }

    public function action_toggle($id = null)
    {
        $user_id = Auth::get_user_id();
        $task = Model_Task::find($id);

        if (!$task || $task->user_id != $user_id[1]) {
            Session::set_flash('error', 'タスクが見つかりません');
            Response::redirect('tasks');
        }

        $task->done = !$task->done;

        if ($task->save()) {
            Session::set_flash('success', 'ステータスを更新しました');
        } else {
            Session::set_flash('error', '更新に失敗しました');
        }

        Response::redirect('tasks');
    }
}
