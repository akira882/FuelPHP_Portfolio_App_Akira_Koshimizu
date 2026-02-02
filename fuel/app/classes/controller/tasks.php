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

            if ($val->run()) {
                $task = Model_Task::forge();
                $task->title = Input::post('title');
                $task->content = Input::post('content');
                $task->user_id = $user_id[1];
                $task->project_id = $project_id;
                $task->done = 0;

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

        if (!$task) {
            Session::set_flash('error', 'タスクが見つかりません');
            Response::redirect('tasks');
        }

        // Access control: check project access if task belongs to a project
        if ($task->project_id) {
            $project = Model_Project::find($task->project_id);
            if (!$project || !$project->has_access($user_id[1])) {
                Session::set_flash('error', 'アクセス権限がありません');
                Response::redirect('projects');
            }
        } else if ($task->user_id != $user_id[1]) {
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

        if (!$task) {
            Session::set_flash('error', 'タスクが見つかりません');
            Response::redirect('tasks');
        }

        if ($task->project_id) {
            $project = Model_Project::find($task->project_id);
            if (!$project || !$project->has_access($user_id[1])) {
                Session::set_flash('error', 'アクセス権限がありません');
                Response::redirect('projects');
            }
        } else if ($task->user_id != $user_id[1]) {
            Session::set_flash('error', 'アクセス権限がありません');
            Response::redirect('tasks');
        }

        if (Input::method() === 'POST') {
            $title = Input::post('title');
            if ($title) {
                $checklist = Model_TaskChecklist::forge();
                $checklist->task_id = $task_id;
                $checklist->title = $title;
                $checklist->is_completed = 0;
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

        if ($task->project_id) {
            $project = Model_Project::find($task->project_id);
            if (!$project || !$project->has_access($user_id[1])) {
                Session::set_flash('error', 'アクセス権限がありません');
                Response::redirect('projects');
            }
        } else if ($task->user_id != $user_id[1]) {
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

        if ($task->project_id) {
            $project = Model_Project::find($task->project_id);
            if (!$project || !$project->has_access($user_id[1])) {
                Session::set_flash('error', 'アクセス権限がありません');
                Response::redirect('projects');
            }
        } else if ($task->user_id != $user_id[1]) {
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

        if (!$task || $task->user_id != $user_id[1]) {
            Session::set_flash('error', 'タスクが見つかりません');
            Response::redirect('tasks');
        }

        $project_id = $task->project_id;

        if ($task->delete()) {
            Session::set_flash('success', 'タスクを削除しました');
        } else {
            Session::set_flash('error', '削除に失敗しました');
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

        if ($task->project_id) {
            Response::redirect('projects/view/' . $task->project_id);
        } else {
            Response::redirect('tasks');
        }
    }
}
