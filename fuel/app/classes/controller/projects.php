<?php

class Controller_Projects extends Controller_Base
{
    public function action_index()
    {
        $user_id = Auth::get_user_id();

        // Own projects
        $own_projects = Model_Project::query()
            ->where('user_id', $user_id[1])
            ->order_by('created_at', 'desc')
            ->get();

        // Member projects
        $member_ids = Model_ProjectMember::query()
            ->where('user_id', $user_id[1])
            ->get();

        $member_projects = array();
        foreach ($member_ids as $member) {
            $project = Model_Project::find($member->project_id);
            if ($project) {
                $member_projects[] = $project;
            }
        }

        $data['own_projects'] = $own_projects;
        $data['member_projects'] = $member_projects;

        return View::forge('projects/index', $data);
    }

    public function action_view($id = null)
    {
        $user_id = Auth::get_user_id();
        $project = Model_Project::find($id);

        if (!$project || !$project->has_access($user_id[1])) {
            Session::set_flash('error', 'アクセス権限がありません');
            Response::redirect('projects');
        }

        $filter = Input::get('filter', 'all');
        $query = Model_Task::query()
            ->where('project_id', $project->id)
            ->where('user_id', $user_id[1]);

        if ($filter === 'pending') {
            $query->where('done', 0);
        } elseif ($filter === 'completed') {
            $query->where('done', 1);
        }

        $data['project'] = $project;
        $data['tasks'] = $query->order_by('created_at', 'desc')->get();
        $data['filter'] = $filter;
        $data['total'] = Model_Task::query()->where('project_id', $project->id)->count();
        $data['completed'] = Model_Task::query()->where('project_id', $project->id)->where('done', 1)->count();
        $data['pending'] = Model_Task::query()->where('project_id', $project->id)->where('done', 0)->count();
        $data['user_role'] = $project->get_role($user_id[1]);
        $data['members'] = $project->members;
        $data['files'] = $project->files;

        return View::forge('projects/view', $data);
    }

    public function action_invite($id = null)
    {
        $user_id = Auth::get_user_id();
        $project = Model_Project::find($id);

        if (!$project || $project->user_id != $user_id[1]) {
            Session::set_flash('error', 'プロジェクトのオーナーのみ招待できます');
            Response::redirect('projects');
        }

        if (Input::method() === 'POST') {
            $email = Input::post('email');
            $role = Input::post('role', 'member');

            // Find user by email
            $invited_user = Model_User::query()
                ->where('email', $email)
                ->get_one();

            if (!$invited_user) {
                Session::set_flash('error', 'ユーザーが見つかりません');
            } else {
                // Check if already a member
                $existing = Model_ProjectMember::query()
                    ->where('project_id', $project->id)
                    ->where('user_id', $invited_user->id)
                    ->get_one();

                if ($existing) {
                    Session::set_flash('error', '既にメンバーです');
                } else {
                    $member = Model_ProjectMember::forge();
                    $member->project_id = $project->id;
                    $member->user_id = $invited_user->id;
                    $member->role = $role;

                    if ($member->save()) {
                        Session::set_flash('success', 'メンバーを追加しました');
                        Response::redirect('projects/view/' . $project->id);
                    } else {
                        Session::set_flash('error', '招待に失敗しました');
                    }
                }
            }
        }

        $data['project'] = $project;
        return View::forge('projects/invite', $data);
    }

    public function action_remove_member($project_id, $member_id)
    {
        $user_id = Auth::get_user_id();
        $project = Model_Project::find($project_id);

        if (!$project || $project->user_id != $user_id[1]) {
            Session::set_flash('error', 'プロジェクトのオーナーのみ削除できます');
            Response::redirect('projects');
        }

        $member = Model_ProjectMember::find($member_id);
        if ($member && $member->project_id == $project_id) {
            $member->delete();
            Session::set_flash('success', 'メンバーを削除しました');
        }

        Response::redirect('projects/view/' . $project_id);
    }

    public function action_upload($id = null)
    {
        $user_id = Auth::get_user_id();
        $project = Model_Project::find($id);

        if (!$project || !$project->has_access($user_id[1])) {
            Session::set_flash('error', 'アクセス権限がありません');
            Response::redirect('projects');
        }

        if (Input::method() === 'POST') {
            $config = array(
                'path' => DOCROOT . 'uploads/projects/' . $project->id . '/',
                'randomize' => true,
                'ext_whitelist' => array('img', 'jpg', 'jpeg', 'gif', 'png', 'pdf', 'doc', 'docx', 'xls', 'xlsx', 'zip', 'txt', 'csv'),
            );

            Upload::process($config);

            if (Upload::is_valid()) {
                Upload::save();
                $file_info = Upload::get_files(0);

                $file = Model_ProjectFile::forge();
                $file->project_id = $project->id;
                $file->user_id = $user_id[1];
                $file->filename = $file_info['original_name'];
                $file->filepath = 'uploads/projects/' . $project->id . '/' . $file_info['saved_as'];
                $file->filesize = $file_info['size'];
                $file->mimetype = $file_info['mimetype'];

                if ($file->save()) {
                    Session::set_flash('success', 'ファイルをアップロードしました');
                } else {
                    Session::set_flash('error', 'ファイルの保存に失敗しました');
                }
            } else {
                Session::set_flash('error', 'ファイルのアップロードに失敗しました: ' . Upload::get_errors());
            }
        }

        Response::redirect('projects/view/' . $project->id);
    }

    public function action_delete_file($project_id, $file_id)
    {
        $user_id = Auth::get_user_id();
        $project = Model_Project::find($project_id);
        $file = Model_ProjectFile::find($file_id);

        if (!$project || !$project->has_access($user_id[1])) {
            Session::set_flash('error', 'アクセス権限がありません');
            Response::redirect('projects');
        }

        if ($file && $file->project_id == $project_id) {
            // Delete physical file
            if (file_exists(DOCROOT . $file->filepath)) {
                unlink(DOCROOT . $file->filepath);
            }
            $file->delete();
            Session::set_flash('success', 'ファイルを削除しました');
        }

        Response::redirect('projects/view/' . $project_id);
    }

    public function action_create()
    {
        if (Input::method() === 'POST') {
            $val = Validation::forge();
            $val->add('name', 'プロジェクト名')
                ->add_rule('required')
                ->add_rule('max_length', 255);

            if ($val->run()) {
                $user_id = Auth::get_user_id();
                $project = Model_Project::forge();
                $project->name = Input::post('name');
                $project->description = Input::post('description');
                $project->user_id = $user_id[1];

                $due_date = Input::post('due_date');
                if ($due_date) {
                    $project->due_date = strtotime($due_date);
                }

                if ($project->save()) {
                    Session::set_flash('success', 'プロジェクトを作成しました');
                    Response::redirect('projects');
                } else {
                    Session::set_flash('error', '作成に失敗しました');
                }
            } else {
                Session::set_flash('error', $val->error());
            }
        }

        return View::forge('projects/create');
    }

    public function action_edit($id = null)
    {
        $user_id = Auth::get_user_id();
        $project = Model_Project::find($id);

        if (!$project || $project->user_id != $user_id[1]) {
            Session::set_flash('error', 'プロジェクトが見つかりません');
            Response::redirect('projects');
        }

        if (Input::method() === 'POST') {
            $val = Validation::forge();
            $val->add('name', 'プロジェクト名')
                ->add_rule('required')
                ->add_rule('max_length', 255);

            if ($val->run()) {
                $project->name = Input::post('name');
                $project->description = Input::post('description');

                $due_date = Input::post('due_date');
                if ($due_date) {
                    $project->due_date = strtotime($due_date);
                } else {
                    $project->due_date = null;
                }

                if ($project->save()) {
                    Session::set_flash('success', 'プロジェクトを更新しました');
                    Response::redirect('projects');
                } else {
                    Session::set_flash('error', '更新に失敗しました');
                }
            } else {
                Session::set_flash('error', $val->error());
            }
        }

        $data['project'] = $project;
        return View::forge('projects/edit', $data);
    }

    public function action_delete($id = null)
    {
        $user_id = Auth::get_user_id();
        $project = Model_Project::find($id);

        if (!$project || $project->user_id != $user_id[1]) {
            Session::set_flash('error', 'プロジェクトが見つかりません');
            Response::redirect('projects');
        }

        if ($project->delete()) {
            Session::set_flash('success', 'プロジェクトを削除しました');
        } else {
            Session::set_flash('error', '削除に失敗しました');
        }

        Response::redirect('projects');
    }
}
