<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>タスク編集 | TaskBoard</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Inter', sans-serif;
            background: #0d1117;
            min-height: 100vh;
            padding: 40px 20px;
            color: #c9d1d9;
        }
        .container {
            max-width: 700px;
            margin: 0 auto;
            background: #161b22;
            border: 1px solid #30363d;
            padding: 48px;
            border-radius: 12px;
        }
        h1 {
            color: #f0f6fc;
            margin-bottom: 32px;
            font-size: 28px;
            font-weight: 700;
        }
        .form-group { margin-bottom: 24px; }
        label {
            display: block;
            margin-bottom: 8px;
            color: #c9d1d9;
            font-weight: 500;
            font-size: 14px;
        }
        input, textarea {
            width: 100%;
            padding: 12px 14px;
            background: #0d1117;
            border: 1px solid #30363d;
            border-radius: 6px;
            font-size: 14px;
            color: #c9d1d9;
            font-family: inherit;
            transition: all 0.2s;
        }
        input:focus, textarea:focus {
            outline: none;
            border-color: #1f6feb;
            box-shadow: 0 0 0 3px rgba(31, 111, 235, 0.1);
        }
        input::placeholder, textarea::placeholder { color: #6e7681; }
        textarea { min-height: 180px; resize: vertical; }
        .buttons {
            display: flex;
            gap: 12px;
            margin-top: 32px;
        }
        .btn {
            padding: 12px 24px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            border: 1px solid;
            transition: all 0.2s;
        }
        .btn-primary {
            background: linear-gradient(135deg, #0969da 0%, #1f6feb 100%);
            color: white;
            border-color: transparent;
        }
        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(31, 111, 235, 0.3);
        }
        .btn-secondary {
            background: rgba(87, 96, 106, 0.1);
            color: #c9d1d9;
            border-color: #30363d;
        }
        .btn-secondary:hover {
            background: rgba(87, 96, 106, 0.2);
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
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: #58a6ff;
            text-decoration: none;
            font-size: 14px;
            margin-bottom: 24px;
        }
        .back-link:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="container">
        <a href="<?php echo Uri::create('tasks'); ?>" class="back-link">← タスク一覧に戻る</a>
        <h1>タスクを編集</h1>

        <?php if ($error = Session::get_flash('error')): ?>
            <div class="flash error">
                <?php if (is_array($error)): ?>
                    <ul style="list-style: none; padding: 0;">
                        <?php foreach ($error as $e): ?>
                            <li><?php echo htmlspecialchars((string)$e); ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <?php echo htmlspecialchars($error); ?>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php echo Form::open(); ?>
        <?php echo Form::csrf(); ?>

            <div class="form-group">
                <label>タイトル <span style="color: red;">*</span></label>
                <input type="text" name="title" value="<?php echo htmlspecialchars($task->title); ?>" required>
            </div>

            <div class="form-group">
                <label>詳細</label>
                <textarea name="content"><?php echo htmlspecialchars($task->content); ?></textarea>
            </div>

            <div class="form-group">
                <label>優先度</label>
                <select name="priority" style="width: 100%; padding: 12px 14px; background: #0d1117; border: 1px solid #30363d; border-radius: 6px; font-size: 14px; color: #c9d1d9; font-family: inherit;">
                    <option value="0" <?php echo $task->priority == 0 ? 'selected' : ''; ?>>低</option>
                    <option value="1" <?php echo $task->priority == 1 ? 'selected' : ''; ?>>中</option>
                    <option value="2" <?php echo $task->priority == 2 ? 'selected' : ''; ?>>高</option>
                </select>
            </div>

            <div class="form-group">
                <label>期限</label>
                <input type="date" name="due_date" value="<?php echo $task->due_date ? date('Y-m-d', $task->due_date) : ''; ?>" style="width: 100%; padding: 12px 14px; background: #0d1117; border: 1px solid #30363d; border-radius: 6px; font-size: 14px; color: #c9d1d9; font-family: inherit;">
            </div>

            <div class="buttons">
                <button type="submit" class="btn btn-primary">更新する</button>
                <a href="<?php echo Uri::create('tasks'); ?>" class="btn btn-secondary">キャンセル</a>
            </div>

        <?php echo Form::close(); ?>
    </div>
</body>
</html>
