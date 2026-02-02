<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title><?php echo htmlspecialchars($project->name); ?> | TaskBoard</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Inter', sans-serif;
            background: #0d1117;
            color: #c9d1d9;
            min-height: 100vh;
        }
        .topnav {
            background: #161b22;
            border-bottom: 1px solid #30363d;
            padding: 12px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .topnav-left {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        .logo {
            font-size: 20px;
            font-weight: 600;
            color: #f0f6fc;
        }
        .nav-item {
            color: #8b949e;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            padding: 6px 12px;
            border-radius: 6px;
            transition: all 0.2s;
        }
        .nav-item:hover {
            color: #c9d1d9;
            background: rgba(177,186,196,0.1);
        }
        .logout-btn {
            background: rgba(248, 81, 73, 0.1);
            color: #ff7b72;
            padding: 8px 16px;
            border: 1px solid rgba(248, 81, 73, 0.3);
            border-radius: 6px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s;
        }
        .logout-btn:hover { background: rgba(248, 81, 73, 0.2); }
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 32px 24px;
        }
        .page-header {
            margin-bottom: 32px;
        }
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: #58a6ff;
            text-decoration: none;
            font-size: 14px;
            margin-bottom: 16px;
        }
        .back-link:hover { text-decoration: underline; }
        .page-title {
            font-size: 32px;
            font-weight: 700;
            color: #f0f6fc;
            margin-bottom: 8px;
        }
        .page-subtitle {
            font-size: 16px;
            color: #8b949e;
        }
        .project-info {
            background: #161b22;
            border: 1px solid #30363d;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 32px;
        }
        .project-due-date {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            color: #8b949e;
        }
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            margin-bottom: 32px;
        }
        .stat-card {
            background: #161b22;
            border: 1px solid #30363d;
            padding: 20px;
            border-radius: 8px;
            transition: all 0.2s;
        }
        .stat-card:hover { border-color: #58a6ff; box-shadow: 0 0 0 1px #58a6ff; }
        .stat-card h3 {
            color: #8b949e;
            font-size: 13px;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 500;
        }
        .stat-card .number { font-size: 36px; font-weight: 700; color: #58a6ff; }
        .stat-card:nth-child(2) .number { color: #f85149; }
        .stat-card:nth-child(3) .number { color: #56d364; }
        .toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            flex-wrap: wrap;
            gap: 16px;
        }
        .filters {
            display: flex;
            gap: 8px;
            background: #161b22;
            border: 1px solid #30363d;
            border-radius: 6px;
            padding: 4px;
        }
        .filter-btn {
            padding: 8px 16px;
            border: none;
            background: transparent;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            color: #c9d1d9;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s;
        }
        .filter-btn:hover { background: rgba(177,186,196,0.1); }
        .filter-btn.active { background: #0969da; color: white; }
        .create-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            background: linear-gradient(135deg, #0969da 0%, #1f6feb 100%);
            color: white;
            border-radius: 6px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.2s;
        }
        .create-btn:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(31, 111, 235, 0.3); }
        .flash {
            padding: 14px 18px;
            margin-bottom: 24px;
            border-radius: 6px;
            font-size: 14px;
            border: 1px solid;
        }
        .flash.error {
            background: rgba(248, 81, 73, 0.1);
            color: #ff7b72;
            border-color: rgba(248, 81, 73, 0.3);
        }
        .flash.success {
            background: rgba(46, 160, 67, 0.1);
            color: #56d364;
            border-color: rgba(46, 160, 67, 0.3);
        }
        .task-list { display: grid; gap: 12px; }
        .task-item {
            background: #161b22;
            border: 1px solid #30363d;
            border-radius: 8px;
            padding: 20px;
            display: flex;
            align-items: flex-start;
            gap: 16px;
            transition: all 0.2s;
            position: relative;
        }
        .task-item:hover { border-color: #58a6ff; box-shadow: 0 0 0 1px #58a6ff; cursor: pointer; }
        .task-item.done { opacity: 0.6; }
        .task-item.done .task-content { text-decoration: line-through; }
        .task-checkbox {
            width: 20px;
            height: 20px;
            cursor: pointer;
            margin-top: 2px;
            accent-color: #0969da;
            position: relative;
            z-index: 2;
        }
        .task-checkbox-form {
            position: relative;
            z-index: 2;
        }
        .task-content { flex: 1; min-width: 0; }
        .task-title {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 8px;
            color: #f0f6fc;
            line-height: 1.4;
        }
        .task-meta { font-size: 13px; color: #8b949e; margin-top: 8px; }
        .task-actions {
            display: flex;
            gap: 8px;
            margin-left: auto;
            position: relative;
            z-index: 2;
        }
        .btn {
            padding: 6px 14px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
            border: 1px solid;
            transition: all 0.2s;
            white-space: nowrap;
        }
        .btn-delete {
            background: rgba(248, 81, 73, 0.1);
            color: #ff7b72;
            border-color: rgba(248, 81, 73, 0.3);
        }
        .btn-delete:hover { background: rgba(248, 81, 73, 0.2); }
        .empty {
            text-align: center;
            padding: 80px 20px;
            color: #6e7681;
        }
        .empty-icon { font-size: 64px; margin-bottom: 16px; opacity: 0.3; }
        .empty-text { font-size: 18px; font-weight: 500; margin-bottom: 8px; }
        .section-title {
            font-size: 20px;
            font-weight: 600;
            color: #f0f6fc;
            margin: 48px 0 20px 0;
        }
        .members-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 16px;
            margin-bottom: 20px;
        }
        .member-card {
            background: #161b22;
            border: 1px solid #30363d;
            border-radius: 8px;
            padding: 16px;
            transition: all 0.2s;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        .member-card:hover { border-color: #58a6ff; }
        .member-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 4px;
        }
        .member-name {
            font-size: 15px;
            font-weight: 600;
            color: #f0f6fc;
        }
        .member-email {
            font-size: 13px;
            color: #8b949e;
            word-break: break-all;
        }
        .member-meta {
            font-size: 12px;
            color: #6e7681;
            margin-top: 8px;
        }
        .role-badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .role-badge.owner {
            background: rgba(31, 111, 235, 0.15);
            color: #58a6ff;
            border: 1px solid rgba(31, 111, 235, 0.3);
        }
        .role-badge.member {
            background: rgba(139, 148, 158, 0.15);
            color: #8b949e;
            border: 1px solid rgba(139, 148, 158, 0.3);
        }
        .btn-remove {
            background: rgba(248, 81, 73, 0.1);
            color: #ff7b72;
            border-color: rgba(248, 81, 73, 0.3);
            padding: 4px 10px;
            font-size: 12px;
        }
        .btn-remove:hover { background: rgba(248, 81, 73, 0.2); }
        .btn-invite {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 10px 18px;
            background: linear-gradient(135deg, #0969da 0%, #1f6feb 100%);
            color: white;
            border-radius: 6px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.2s;
            margin-bottom: 20px;
        }
        .btn-invite:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(31, 111, 235, 0.3); }
        .upload-form {
            background: #161b22;
            border: 1px solid #30363d;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .upload-form h3 {
            font-size: 15px;
            font-weight: 600;
            color: #f0f6fc;
            margin-bottom: 12px;
        }
        .upload-form form {
            display: flex;
            gap: 12px;
            align-items: center;
            flex-wrap: wrap;
        }
        .file-input {
            flex: 1;
            min-width: 250px;
            padding: 8px 12px;
            background: #0d1117;
            border: 1px solid #30363d;
            border-radius: 6px;
            color: #c9d1d9;
            font-size: 14px;
        }
        .file-input:focus { outline: none; border-color: #1f6feb; }
        .btn-upload {
            padding: 8px 16px;
            background: linear-gradient(135deg, #0969da 0%, #1f6feb 100%);
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn-upload:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(31, 111, 235, 0.3); }
        .files-list {
            display: grid;
            gap: 12px;
        }
        .file-item {
            background: #161b22;
            border: 1px solid #30363d;
            border-radius: 8px;
            padding: 16px;
            display: flex;
            align-items: center;
            gap: 16px;
            transition: all 0.2s;
        }
        .file-item:hover { border-color: #58a6ff; }
        .file-icon {
            font-size: 32px;
            flex-shrink: 0;
        }
        .file-info {
            flex: 1;
            min-width: 0;
        }
        .file-name {
            font-size: 15px;
            font-weight: 600;
            color: #f0f6fc;
            margin-bottom: 4px;
            word-break: break-word;
        }
        .file-meta {
            font-size: 13px;
            color: #8b949e;
            display: flex;
            gap: 16px;
            flex-wrap: wrap;
        }
        .file-actions {
            display: flex;
            gap: 8px;
            flex-shrink: 0;
        }
        .btn-download {
            padding: 6px 14px;
            background: rgba(31, 111, 235, 0.1);
            color: #58a6ff;
            border: 1px solid rgba(31, 111, 235, 0.3);
            border-radius: 6px;
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
            transition: all 0.2s;
        }
        .btn-download:hover { background: rgba(31, 111, 235, 0.2); }
    </style>
</head>
<body>
    <nav class="topnav">
        <div class="topnav-left">
            <div class="logo">📋 TaskBoard</div>
            <a href="<?php echo Uri::create('projects'); ?>" class="nav-item">Projects</a>
        </div>
        <a href="<?php echo Uri::create('auth/logout'); ?>" class="logout-btn">Sign out</a>
    </nav>

    <div class="container">
        <a href="<?php echo Uri::create('projects'); ?>" class="back-link">← Back to Projects</a>

        <div class="page-header">
            <h1 class="page-title"><?php echo htmlspecialchars($project->name); ?></h1>
            <?php if ($project->description): ?>
                <p class="page-subtitle"><?php echo htmlspecialchars($project->description); ?></p>
            <?php endif; ?>
        </div>

        <?php if ($project->due_date): ?>
            <div class="project-info">
                <div class="project-due-date">
                    📅 Due Date: <?php echo date('F j, Y', $project->due_date); ?>
                </div>
            </div>
        <?php endif; ?>

        <?php if (Session::get_flash('error')): ?>
            <div class="flash error">⚠️ <?php echo htmlspecialchars(Session::get_flash('error')); ?></div>
        <?php endif; ?>
        <?php if (Session::get_flash('success')): ?>
            <div class="flash success">✓ <?php echo htmlspecialchars(Session::get_flash('success')); ?></div>
        <?php endif; ?>

        <div class="stats">
            <div class="stat-card">
                <h3>Total Tasks</h3>
                <div class="number"><?php echo $total; ?></div>
            </div>
            <div class="stat-card">
                <h3>In Progress</h3>
                <div class="number"><?php echo $pending; ?></div>
            </div>
            <div class="stat-card">
                <h3>Completed</h3>
                <div class="number"><?php echo $completed; ?></div>
            </div>
        </div>

        <div class="toolbar">
            <div class="filters">
                <a href="<?php echo Uri::create('projects/view/' . $project->id . '?filter=all'); ?>" class="filter-btn <?php echo $filter === 'all' ? 'active' : ''; ?>">All</a>
                <a href="<?php echo Uri::create('projects/view/' . $project->id . '?filter=pending'); ?>" class="filter-btn <?php echo $filter === 'pending' ? 'active' : ''; ?>">In Progress</a>
                <a href="<?php echo Uri::create('projects/view/' . $project->id . '?filter=completed'); ?>" class="filter-btn <?php echo $filter === 'completed' ? 'active' : ''; ?>">Completed</a>
            </div>
            <a href="<?php echo Uri::create('tasks/create/' . $project->id); ?>" class="create-btn">
                <span>+</span> New Task
            </a>
        </div>

        <div class="task-list">
            <?php if (empty($tasks)): ?>
                <div class="empty">
                    <div class="empty-icon">📝</div>
                    <div class="empty-text">No tasks yet</div>
                </div>
            <?php else: ?>
                <?php foreach ($tasks as $task): ?>
                    <div class="task-item <?php echo $task->done ? 'done' : ''; ?>" onclick="location.href='<?php echo Uri::create('tasks/edit/' . $task->id); ?>'">
                        <form method="POST" action="<?php echo Uri::create('tasks/toggle/' . $task->id); ?>" style="margin: 0;" class="task-checkbox-form" onclick="event.stopPropagation();">
                            <?php echo Form::csrf(); ?>
                            <input type="checkbox" class="task-checkbox" <?php echo $task->done ? 'checked' : ''; ?> onchange="this.form.submit()">
                        </form>
                        <div class="task-content">
                            <div class="task-title"><?php echo htmlspecialchars($task->title); ?></div>
                            <?php if ($task->content): ?>
                                <div class="task-meta"><?php echo htmlspecialchars($task->content); ?></div>
                            <?php endif; ?>
                            <div class="task-meta"><?php echo date('M j, Y', $task->created_at); ?></div>
                        </div>
                        <div class="task-actions">
                            <a href="<?php echo Uri::create('tasks/delete/' . $task->id); ?>" class="btn btn-delete" onclick="event.stopPropagation(); return confirm('本当に削除しますか？')">Delete</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
