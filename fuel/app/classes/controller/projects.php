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

        // Member projects - Eager Loading でN+1問題を解消（World Class対応）
        // Before: N+1クエリ（1回 + プロジェクト数N回）
        // After: 1回のJOINクエリで完結
        $member_records = Model_ProjectMember::query()
            ->where('user_id', $user_id[1])
            ->related('project') // Eager Loading: プロジェクト情報を事前読み込み
            ->get();

        // Eager Loadingされたプロジェクトを抽出（追加クエリなし）
        $member_projects = array();
        foreach ($member_records as $member) {
            if ($member->project) {
                $member_projects[] = $member->project;
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
            ->where('project_id', $project->id);

        if ($filter === 'pending') {
            $query->where('done', 0);
        } elseif ($filter === 'completed') {
            $query->where('done', 1);
        }

        // タスク一覧取得
        $data['tasks'] = $query
            ->order_by('priority', 'desc')
            ->order_by('due_date', 'asc')
            ->order_by('created_at', 'desc')
            ->get();

        // 統計情報を1回のクエリで取得（World Class最適化）
        // Before: 3回のクエリ
        // After: 1回のクエリ
        $statistics = Model_Project::get_task_statistics($project->id);

        $data['project'] = $project;
        $data['filter'] = $filter;
        $data['total'] = $statistics['total'];
        $data['completed'] = $statistics['completed'];
        $data['pending'] = $statistics['pending'];
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
            $upload_path = DOCROOT . 'uploads/projects/' . $project->id . '/';

            // Create directory if it doesn't exist
            if (!is_dir($upload_path)) {
                mkdir($upload_path, 0777, true);
            }

            $config = array(
                'path' => $upload_path,
                'randomize' => true,
                'ext_whitelist' => array('img', 'jpg', 'jpeg', 'gif', 'png', 'pdf', 'doc', 'docx', 'xls', 'xlsx', 'zip', 'txt', 'csv'),
                'max_size' => 10 * 1024 * 1024, // 10MB max
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
                $errors = Upload::get_errors();
                $error_msg = is_array($errors) ? implode(', ', $errors[0]['errors']) : 'ファイルのアップロードに失敗しました';
                Session::set_flash('error', $error_msg);
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

    public function action_download_file($project_id, $file_id)
    {
        $user_id = Auth::get_user_id();
        $project = Model_Project::find($project_id);
        $file = Model_ProjectFile::find($file_id);

        if (!$project || !$project->has_access($user_id[1])) {
            Session::set_flash('error', 'アクセス権限がありません');
            Response::redirect('projects');
        }

        if (!$file || $file->project_id != $project_id) {
            Session::set_flash('error', 'ファイルが見つかりません');
            Response::redirect('projects/view/' . $project_id);
        }

        $filepath = DOCROOT . $file->filepath;

        if (!file_exists($filepath)) {
            Session::set_flash('error', 'ファイルが存在しません');
            Response::redirect('projects/view/' . $project_id);
        }

        // ストリーム処理でファイルダウンロード（World Class最適化）
        // Before: file_get_contents() - 全体をメモリに読み込み（メモリ枯渇リスク）
        // After: ストリーム処理 - チャンク単位で送信（メモリ効率的）

        // HTTPヘッダー設定
        header('Content-Type: ' . $file->mimetype);
        header('Content-Disposition: attachment; filename="' . $file->filename . '"');
        header('Content-Length: ' . filesize($filepath));
        header('Pragma: public');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Expires: 0');

        // 出力バッファをクリア
        if (ob_get_level()) {
            ob_end_clean();
        }

        // ストリーム処理でファイル送信（8KBチャンク）
        // メモリ使用量: ファイルサイズに関わらず最大8KB
        $handle = fopen($filepath, 'rb');
        if ($handle) {
            while (!feof($handle)) {
                echo fread($handle, 8192); // 8KBチャンクで送信
                flush(); // バッファをフラッシュ
            }
            fclose($handle);
        }

        exit(); // レスポンス送信後に終了
    }
}
