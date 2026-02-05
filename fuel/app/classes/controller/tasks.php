<?php

class Controller_Tasks extends Controller_Base
{
    public function action_index()
    {
        $user_id = Auth::get_user_id();
        $filter = Input::get('filter', 'all');

        $query = Model_Task::query()->where('user_id', $user_id[1]);

        if ($filter === 'pending') {
            $query->where('done', Model_Task::STATUS_INCOMPLETE);
        } elseif ($filter === 'completed') {
            $query->where('done', Model_Task::STATUS_COMPLETE);
        }

        // 1回のクエリでタスク一覧取得
        $data['tasks'] = $query
            ->order_by('priority', 'desc')
            ->order_by('due_date', 'asc')
            ->order_by('created_at', 'desc')
            ->get();

        // 1回のクエリで統計情報取得（4回→2回に最適化）
        $statistics = Model_Task::get_statistics($user_id[1]);

        $data['filter'] = $filter;
        $data['total'] = $statistics['total'];
        $data['completed'] = $statistics['completed'];
        $data['pending'] = $statistics['pending'];

        return View::forge('tasks/index', $data);
    }

    public function action_create($project_id = null)
    {
        $user_id = Auth::get_user_id();

        if ($project_id) {
            $project = Model_Project::find($project_id);
            if (!$project || $project->user_id != $user_id[1]) {
                Session::set_flash('error', 'プロジェクトが見つかりません');
                Response::redirect('projects');
            }
        }

        if (Input::method() === 'POST') {
            $val = Validation::forge();
            $val->add('title', 'タイトル')
                ->add_rule('required')
                ->add_rule('max_length', 255);
            $val->add('priority', '優先度')
                ->add_rule('numeric_min', 0)
                ->add_rule('numeric_max', 2);

            if ($val->run()) {
                $task = Model_Task::forge();
                $task->title = Input::post('title');
                $task->content = Input::post('content');
                $task->user_id = $user_id[1];
                $task->project_id = $project_id;
                $task->done = Model_Task::STATUS_INCOMPLETE;
                $task->priority = Input::post('priority', Model_Task::PRIORITY_MEDIUM);

                // Convert due_date string to Unix timestamp
                $due_date_str = Input::post('due_date');
                if ($due_date_str) {
                    $task->due_date = strtotime($due_date_str . ' 23:59:59');
                } else {
                    $task->due_date = null;
                }

                if ($task->save()) {
                    Session::set_flash('success', 'タスクを作成しました');
                    if ($project_id) {
                        Response::redirect('projects/view/' . $project_id);
                    } else {
                        Response::redirect('tasks');
                    }
                } else {
                    Session::set_flash('error', '作成に失敗しました');
                }
            } else {
                Session::set_flash('error', $val->error());
            }
        }

        $data['project_id'] = $project_id;
        return View::forge('tasks/create', $data);
    }

    public function action_edit($id = null)
    {
        $user_id = Auth::get_user_id();
        $task = Model_Task::find($id);

        if (!$task || !$task->can_edit($user_id[1])) {
            Session::set_flash('error', 'アクセス権限がありません');
            Response::redirect('tasks');
        }

        if (Input::method() === 'POST') {
            $val = Validation::forge();
            $val->add('title', 'タイトル')
                ->add_rule('required')
                ->add_rule('max_length', 255);
            $val->add('priority', '優先度')
                ->add_rule('numeric_min', 0)
                ->add_rule('numeric_max', 2);

            if ($val->run()) {
                $task->title = Input::post('title');
                $task->content = Input::post('content');
                $task->priority = Input::post('priority', Model_Task::PRIORITY_MEDIUM);

                // Convert due_date string to Unix timestamp
                $due_date_str = Input::post('due_date');
                if ($due_date_str) {
                    $task->due_date = strtotime($due_date_str . ' 23:59:59');
                } else {
                    $task->due_date = null;
                }

                if ($task->save()) {
                    Session::set_flash('success', 'タスクを更新しました');
                    if ($task->project_id) {
                        Response::redirect('projects/view/' . $task->project_id);
                    } else {
                        Response::redirect('tasks');
                    }
                } else {
                    Session::set_flash('error', '更新に失敗しました');
                }
            } else {
                Session::set_flash('error', $val->error());
            }
        }

        $data['task'] = $task;
        $data['checklists'] = $task->checklists;
        return View::forge('tasks/edit', $data);
    }

    public function action_add_checklist($task_id)
    {
        $user_id = Auth::get_user_id();
        $task = Model_Task::find($task_id);

        if (!$task || !$task->can_edit($user_id[1])) {
            Session::set_flash('error', 'アクセス権限がありません');
            Response::redirect('tasks');
        }

        if (Input::method() === 'POST') {
            $title = Input::post('title');
            if ($title) {
                $checklist = Model_TaskChecklist::forge();
                $checklist->task_id = $task_id;
                $checklist->title = $title;
                $checklist->is_completed = Model_Task::STATUS_INCOMPLETE;
                $checklist->sort_order = Model_TaskChecklist::query()->where('task_id', $task_id)->count();

                if ($checklist->save()) {
                    Session::set_flash('success', 'チェックリストを追加しました');
                }
            }
        }

        Response::redirect('tasks/edit/' . $task_id);
    }

    public function action_toggle_checklist($task_id, $checklist_id)
    {
        $user_id = Auth::get_user_id();
        $task = Model_Task::find($task_id);
        $checklist = Model_TaskChecklist::find($checklist_id);

        if (!$task || !$checklist || $checklist->task_id != $task_id) {
            Session::set_flash('error', 'チェックリストが見つかりません');
            Response::redirect('tasks');
        }

        if (!$task->can_edit($user_id[1])) {
            Session::set_flash('error', 'アクセス権限がありません');
            Response::redirect('tasks');
        }

        $checklist->is_completed = !$checklist->is_completed;
        $checklist->save();

        Response::redirect('tasks/edit/' . $task_id);
    }

    public function action_delete_checklist($task_id, $checklist_id)
    {
        $user_id = Auth::get_user_id();
        $task = Model_Task::find($task_id);
        $checklist = Model_TaskChecklist::find($checklist_id);

        if (!$task || !$checklist || $checklist->task_id != $task_id) {
            Session::set_flash('error', 'チェックリストが見つかりません');
            Response::redirect('tasks');
        }

        if (!$task->can_edit($user_id[1])) {
            Session::set_flash('error', 'アクセス権限がありません');
            Response::redirect('tasks');
        }

        $checklist->delete();
        Session::set_flash('success', 'チェックリストを削除しました');

        Response::redirect('tasks/edit/' . $task_id);
    }

    public function action_delete($id = null)
    {
        $user_id = Auth::get_user_id();
        $task = Model_Task::find($id);

        if (!$task || !$task->can_delete($user_id[1])) {
            Session::set_flash('error', 'アクセス権限がありません');
            Response::redirect('tasks');
        }

        $project_id = $task->project_id;

        try {
            $task->delete_with_transaction();
            Session::set_flash('success', 'タスクを削除しました');
        } catch (\Exception $e) {
            Session::set_flash('error', '削除に失敗しました');
            \Log::error('Task deletion error in controller', array(
                'task_id' => $id,
                'user_id' => $user_id[1],
                'error' => $e->getMessage(),
            ));
        }

        if ($project_id) {
            Response::redirect('projects/view/' . $project_id);
        } else {
            Response::redirect('tasks');
        }
    }

    public function action_toggle($id = null)
    {
        $user_id = Auth::get_user_id();
        $task = Model_Task::find($id);

        if (!$task || !$task->can_edit($user_id[1])) {
            Session::set_flash('error', 'アクセス権限がありません');
            Response::redirect('tasks');
        }

        $task->done = !$task->done;

        if ($task->save()) {
            Session::set_flash('success', 'ステータスを更新しました');
        } else {
            Session::set_flash('error', '更新に失敗しました');
        }

        if ($task->project_id) {
            Response::redirect('projects/view/' . $task->project_id);
        } else {
            Response::redirect('tasks');
        }
    }
}
