<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Projects | TaskBoard</title>
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
            display: flex;
            align-items: center;
            gap: 8px;
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
        .toolbar {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 24px;
        }
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
        .create-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(31, 111, 235, 0.3);
        }
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
        .projects-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 20px;
        }
        .project-card {
            background: #161b22;
            border: 1px solid #30363d;
            border-radius: 8px;
            padding: 24px;
            transition: all 0.2s;
            cursor: pointer;
            position: relative;
        }
        .project-card:hover {
            border-color: #58a6ff;
            box-shadow: 0 0 0 1px #58a6ff;
            transform: translateY(-2px);
        }
        .project-name {
            font-size: 20px;
            font-weight: 600;
            color: #f0f6fc;
            margin-bottom: 12px;
        }
        .project-description {
            font-size: 14px;
            color: #8b949e;
            margin-bottom: 16px;
            line-height: 1.5;
        }
        .project-meta {
            display: flex;
            gap: 16px;
            align-items: center;
            font-size: 13px;
            color: #6e7681;
            margin-bottom: 16px;
        }
        .project-due-date {
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .project-actions {
            display: flex;
            gap: 8px;
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
        }
        .btn-edit {
            background: rgba(87, 96, 106, 0.1);
            color: #8b949e;
            border-color: #30363d;
        }
        .btn-edit:hover { background: rgba(87, 96, 106, 0.2); color: #c9d1d9; }
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
        .empty-subtext { font-size: 14px; color: #8b949e; }
    </style>
</head>
<body>
    <nav class="topnav">
        <div class="topnav-left">
            <div class="logo">📋 TaskBoard</div>
        </div>
        <a href="<?php echo Uri::create('auth/logout'); ?>" class="logout-btn">Sign out</a>
    </nav>

    <div class="container">
        <div class="page-header">
            <h1 class="page-title">Projects</h1>
            <p class="page-subtitle">Manage your projects and tasks</p>
        </div>

        <?php if (Session::get_flash('error')): ?>
            <div class="flash error">⚠️ <?php echo htmlspecialchars(Session::get_flash('error')); ?></div>
        <?php endif; ?>
        <?php if (Session::get_flash('success')): ?>
            <div class="flash success">✓ <?php echo htmlspecialchars(Session::get_flash('success')); ?></div>
        <?php endif; ?>

        <div class="toolbar">
            <a href="<?php echo Uri::create('projects/create'); ?>" class="create-btn">
                <span>+</span> New Project
            </a>
        </div>

        <?php if (!empty($own_projects)): ?>
            <h2 style="color: #f0f6fc; margin-bottom: 16px; font-size: 18px; font-weight: 600;">My Projects</h2>
        <?php endif; ?>

        <div class="projects-grid">
            <?php if (empty($own_projects) && empty($member_projects)): ?>
                <div class="empty">
                    <div class="empty-icon">📁</div>
                    <div class="empty-text">No projects yet</div>
                    <div class="empty-subtext">Create your first project to get started</div>
                </div>
            <?php endif; ?>

            <?php if (!empty($own_projects)): ?>
                <?php foreach ($own_projects as $project): ?>
                    <div class="project-card" onclick="location.href='<?php echo Uri::create('projects/view/' . $project->id); ?>'">
                        <div class="project-name"><?php echo htmlspecialchars($project->name); ?></div>
                        <?php if ($project->description): ?>
                            <div class="project-description"><?php echo htmlspecialchars($project->description); ?></div>
                        <?php endif; ?>
                        <div class="project-meta">
                            <?php if ($project->due_date): ?>
                                <div class="project-due-date">
                                    📅 Due: <?php echo date('M j, Y', $project->due_date); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="project-actions">
                            <a href="<?php echo Uri::create('projects/edit/' . $project->id); ?>" class="btn btn-edit" onclick="event.stopPropagation()">Edit</a>
                            <a href="<?php echo Uri::create('projects/delete/' . $project->id); ?>" class="btn btn-delete" onclick="event.stopPropagation(); return confirm('本当に削除しますか？プロジェクト内の全てのタスクも削除されます。')">Delete</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <?php if (!empty($member_projects)): ?>
            <h2 style="color: #f0f6fc; margin: 32px 0 16px 0; font-size: 18px; font-weight: 600;">Shared with Me</h2>
            <div class="projects-grid">
                <?php foreach ($member_projects as $project): ?>
                    <div class="project-card" onclick="location.href='<?php echo Uri::create('projects/view/' . $project->id); ?>'">
                        <div class="project-name"><?php echo htmlspecialchars($project->name); ?></div>
                        <?php if ($project->description): ?>
                            <div class="project-description"><?php echo htmlspecialchars($project->description); ?></div>
                        <?php endif; ?>
                        <div class="project-meta">
                            <?php if ($project->due_date): ?>
                                <div class="project-due-date">
                                    📅 Due: <?php echo date('M j, Y', $project->due_date); ?>
                                </div>
                            <?php endif; ?>
                            <span style="color: #8b949e; font-size: 12px;">👥 Shared</span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
