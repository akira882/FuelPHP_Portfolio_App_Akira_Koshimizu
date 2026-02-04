<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>タスク一覧 | TaskBoard</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Inter', sans-serif;
            background: #0d1117;
            min-height: 100vh;
            padding: 0;
            color: #c9d1d9;
        }
        .header {
            background: #161b22;
            border-bottom: 1px solid #30363d;
            color: #f0f6fc;
            padding: 16px 32px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .header h1 { font-size: 20px; font-weight: 600; }
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
        .main-content { padding: 32px; max-width: 1400px; margin: 0 auto; }
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
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
        .task-actions { display: flex; gap: 8px; margin-left: auto; }
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
        .task-actions {
            position: relative;
            z-index: 2;
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
        .empty {
            text-align: center;
            padding: 80px 20px;
            color: #6e7681;
        }
        .empty-icon { font-size: 64px; margin-bottom: 16px; opacity: 0.3; }
        .empty-text { font-size: 18px; font-weight: 500; margin-bottom: 8px; }
        .priority-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-right: 8px;
        }
        .due-date {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 13px;
            color: #8b949e;
        }
        .due-date.overdue {
            color: #ff7b72;
            font-weight: 600;
        }
        .task-badges {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
            margin-top: 8px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>📋 TaskBoard</h1>
        <a href="<?php echo Uri::create('auth/logout'); ?>" class="logout-btn">ログアウト</a>
    </div>

    <div class="main-content">
        <?php if (Session::get_flash('error')): ?>
            <div class="flash error"><?php echo htmlspecialchars(Session::get_flash('error')); ?></div>
        <?php endif; ?>
        <?php if (Session::get_flash('success')): ?>
            <div class="flash success"><?php echo htmlspecialchars(Session::get_flash('success')); ?></div>
        <?php endif; ?>

        <div class="stats">
        <div class="stat-card">
            <h3>全タスク</h3>
            <div class="number"><?php echo $total; ?></div>
        </div>
        <div class="stat-card">
            <h3>未完了</h3>
            <div class="number"><?php echo $pending; ?></div>
        </div>
        <div class="stat-card">
            <h3>完了済み</h3>
            <div class="number"><?php echo $completed; ?></div>
            </div>
        </div>

        <div class="toolbar">
            <div class="filters">
                <a href="<?php echo Uri::create('tasks?filter=all'); ?>" class="filter-btn <?php echo $filter === 'all' ? 'active' : ''; ?>">全て</a>
                <a href="<?php echo Uri::create('tasks?filter=pending'); ?>" class="filter-btn <?php echo $filter === 'pending' ? 'active' : ''; ?>">未完了</a>
                <a href="<?php echo Uri::create('tasks?filter=completed'); ?>" class="filter-btn <?php echo $filter === 'completed' ? 'active' : ''; ?>">完了済み</a>
            </div>
            <a href="<?php echo Uri::create('tasks/create'); ?>" class="create-btn">+ 新しいタスクを作成</a>
        </div>

        <div class="task-list">
            <?php if (empty($tasks)): ?>
                <div class="empty">
                    <div class="empty-icon">📝</div>
                    <div class="empty-text">タスクがありません</div>
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
                        <div class="task-badges">
                            <span class="priority-badge" style="background: <?php echo $task->get_priority_color(); ?>20; color: <?php echo $task->get_priority_color(); ?>; border: 1px solid <?php echo $task->get_priority_color(); ?>;">
                                <?php echo $task->get_priority_label(); ?>
                            </span>
                            <?php if ($task->due_date): ?>
                                <span class="due-date <?php echo $task->is_overdue() ? 'overdue' : ''; ?>">
                                    📅 <?php echo date('Y-m-d', $task->due_date); ?>
                                    <?php if ($task->is_overdue()): ?>
                                        (期限切れ)
                                    <?php endif; ?>
                                </span>
                            <?php endif; ?>
                        </div>
                        <div class="task-meta"><?php echo date('Y-m-d H:i', $task->created_at); ?></div>
                    </div>
                    <div class="task-actions">
                        <a href="<?php echo Uri::create('tasks/delete/' . $task->id); ?>" class="btn btn-delete" onclick="event.stopPropagation(); return confirm('本当に削除しますか？')">削除</a>
                    </div>
                </div>
            <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
