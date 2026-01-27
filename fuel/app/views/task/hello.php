<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>最初の一歩 | FuelPHP ハンズオン</title>
    <!-- 
        WordPressのテーマ開発（HTML/CSS）のスキルがそのまま活かせます。
        FuelPHPでは、ここに自由にHTMLを記述して、コントローラーから渡されたデータを表示します。
    -->
    <style>
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #2d3436;
        }
        .card {
            background: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            text-align: center;
        }
        h1 { color: #0984e3; margin-bottom: 10px; }
        p { line-height: 1.6; }
    </style>
</head>
<body>
    <div class="card">
        <h1>Hello, FuelPHP!</h1>
        <p>WordPressの経験を活かした、最初の自作ページです。</p>
        <p><strong>URL：</strong> <code>task/hello</code><br>
           <strong>担当指揮者：</strong> <code>Controller_Task</code><br>
           <strong>担当見た目：</strong> <code>task/hello.php</code></p>
    </div>
</body>
</html>
