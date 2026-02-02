<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Create Account | TaskBoard</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Inter', sans-serif;
            background: #0d1117;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #c9d1d9;
        }
        .container {
            background: #161b22;
            padding: 48px;
            border-radius: 12px;
            border: 1px solid #30363d;
            box-shadow: 0 8px 32px rgba(0,0,0,0.5);
            width: 100%;
            max-width: 420px;
        }
        .logo {
            text-align: center;
            margin-bottom: 32px;
        }
        .logo-icon {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, #0969da 0%, #1f6feb 100%);
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-bottom: 16px;
        }
        h1 {
            color: #f0f6fc;
            margin-bottom: 8px;
            text-align: center;
            font-size: 24px;
            font-weight: 600;
        }
        .subtitle {
            text-align: center;
            color: #8b949e;
            font-size: 14px;
            margin-bottom: 32px;
        }
        .form-group { margin-bottom: 20px; }
        label {
            display: block;
            margin-bottom: 8px;
            color: #c9d1d9;
            font-weight: 500;
            font-size: 14px;
        }
        input {
            width: 100%;
            padding: 12px 14px;
            background: #0d1117;
            border: 1px solid #30363d;
            border-radius: 6px;
            font-size: 14px;
            color: #c9d1d9;
            transition: all 0.2s;
        }
        input:focus {
            outline: none;
            border-color: #1f6feb;
            box-shadow: 0 0 0 3px rgba(31, 111, 235, 0.1);
        }
        input::placeholder {
            color: #6e7681;
        }
        button {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #0969da 0%, #1f6feb 100%);
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            margin-top: 8px;
        }
        button:hover {
            background: linear-gradient(135deg, #1f6feb 0%, #388bfd 100%);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(31, 111, 235, 0.3);
        }
        button:active {
            transform: translateY(0);
        }
        .link {
            text-align: center;
            margin-top: 24px;
            padding-top: 24px;
            border-top: 1px solid #30363d;
        }
        .link a {
            color: #58a6ff;
            text-decoration: none;
            font-weight: 500;
        }
        .link a:hover {
            text-decoration: underline;
        }
        .flash {
            padding: 12px 16px;
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
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">
            <div class="logo-icon">📋</div>
            <h1>Create your account</h1>
            <p class="subtitle">Get started with TaskBoard</p>
        </div>

        <?php if (Session::get_flash('error')): ?>
            <div class="flash error"><?php echo htmlspecialchars(Session::get_flash('error')); ?></div>
        <?php endif; ?>
        <?php if (Session::get_flash('success')): ?>
            <div class="flash success"><?php echo htmlspecialchars(Session::get_flash('success')); ?></div>
        <?php endif; ?>

        <?php echo Form::open(); ?>
        <?php echo Form::csrf(); ?>

            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" placeholder="Choose a username" required>
            </div>

            <div class="form-group">
                <label>Email address</label>
                <input type="email" name="email" placeholder="your.email@example.com" required>
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Create a password" required>
            </div>

            <button type="submit">Create account</button>

        <?php echo Form::close(); ?>

        <div class="link">
            <p>Already have an account? <a href="<?php echo Uri::create('auth/login'); ?>">Sign in</a></p>
        </div>
    </div>
</body>
</html>
