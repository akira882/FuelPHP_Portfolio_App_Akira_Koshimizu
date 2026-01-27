# 📚 FuelPHP認証機能付きタスク管理アプリ - 完全ハンズオン講義

## 🎯 この講義で作るもの

**「TaskBoard」- 認証機能付き個人タスク管理アプリ**

### 完成する機能
- ✅ ユーザー登録・ログイン機能
- ✅ 自分専用のタスクを作成・編集・削除
- ✅ タスクの完了/未完了切り替え
- ✅ フィルター機能（全て・未完了・完了済み）
- ✅ 統計情報の表示
- ✅ 他のユーザーのタスクは見えない（プライバシー保護）
- ✅ セキュリティ対策（XSS、SQLインジェクション、CSRF）

---

## 📅 2日間の学習計画

### **Day 1（基礎編）** - 6-8時間

```
09:00-11:00 │ セクション1: 環境確認 + PHP基礎
11:00-13:00 │ セクション2: データベース設計
14:00-17:00 │ セクション3: ユーザー登録機能
17:00-19:00 │ セクション4: ログイン機能
```

### **Day 2（実装編）** - 6-8時間

```
09:00-10:00 │ セクション5: タスクテーブル設計
10:00-14:00 │ セクション6: CRUD機能実装
14:00-16:00 │ セクション7: ユーザー紐付け
16:00-18:00 │ セクション8: 仕上げ
```

---

# 📘 Day 1: 基礎編（環境構築〜認証機能）

## セクション1: 環境確認とPHP基礎（2時間）

### ステップ1-1: Docker環境の起動確認（15分）

**目的:** アプリが動く「箱」を起動する

**実行するコマンド:**

```bash
# プロジェクトのフォルダに移動
cd /Users/882akira/Desktop/FuelPHP_Portfolio_App_Akira_Koshimizu

# Dockerコンテナを起動（初回は時間がかかります）
docker-compose up -d

# 起動確認（3つのコンテナが「Up」になればOK）
docker-compose ps
```

**確認ポイント:**
```
NAME                STATE       PORTS
web                 Up          0.0.0.0:8080->80/tcp
app                 Up          9000/tcp
db                  Up          0.0.0.0:3306->3306/tcp
```

**ブラウザで確認:**
1. ブラウザを開く
2. `http://localhost:8080` にアクセス
3. FuelPHPのウェルカムページが表示されればOK

**トラブル時:**
```bash
# コンテナを止める
docker-compose down

# 再起動
docker-compose up -d
```

---

### ステップ1-2: PHPの超基礎（30分）

**【重要】PHP初心者が最初に知るべき7つのこと**

#### 1. PHPファイルの書き方

```php
<?php
// PHPのコードは必ず <?php で始まる
// これがないとただのテキストファイルとして扱われる

echo "Hello World"; // echo は「画面に表示しなさい」という命令

// ファイルの最後に ?> は不要（むしろ書かないのが推奨）
```

#### 2. 変数（データを入れる箱）

```php
<?php
// 変数は $ で始まる名前をつける
$name = "山田太郎";        // 文字列（テキスト）
$age = 25;                 // 数値
$is_student = true;        // 真偽値（trueかfalse）

// 変数を表示
echo $name;                // 山田太郎
echo "私は" . $age . "歳です";  // .(ドット)で文字列を結合
```

#### 3. 配列（複数のデータをまとめて入れる箱）

```php
<?php
// 配列の作り方
$fruits = array("りんご", "バナナ", "みかん");

// 別の書き方（こっちの方が新しくて読みやすい）
$fruits = ["りんご", "バナナ", "みかん"];

// 配列から取り出す（番号は0から始まる）
echo $fruits[0];  // りんご
echo $fruits[1];  // バナナ

// キーと値のペアで管理する配列（連想配列）
$user = [
    "name" => "山田太郎",
    "age" => 25,
    "email" => "yamada@example.com"
];

echo $user["name"];  // 山田太郎
```

#### 4. 条件分岐（もし〜ならば）

```php
<?php
$age = 18;

if ($age >= 20) {
    echo "成人です";
} else {
    echo "未成年です";
}
// 結果: 未成年です
```

#### 5. ループ（繰り返し処理）

```php
<?php
$fruits = ["りんご", "バナナ", "みかん"];

// foreach: 配列の中身を1つずつ取り出して処理
foreach ($fruits as $fruit) {
    echo $fruit . "<br>";  // <br>は改行
}

// 結果:
// りんご
// バナナ
// みかん
```

#### 6. 関数（処理をまとめたもの）

```php
<?php
// 関数の定義
function say_hello($name) {
    return "こんにちは、" . $name . "さん！";
}

// 関数の使用
$message = say_hello("山田");
echo $message;  // こんにちは、山田さん！
```

#### 7. クラス（データと処理をまとめたもの）

```php
<?php
// クラス = 設計図
class User {
    public $name;    // プロパティ（データ）
    public $email;

    // メソッド（処理）
    public function greet() {
        return "こんにちは、" . $this->name . "です";
    }
}

// クラスからインスタンス（実体）を作る
$user = new User();
$user->name = "山田太郎";
$user->email = "yamada@example.com";

echo $user->greet();  // こんにちは、山田太郎です
```

**📝 練習問題（5分）**

実際に簡単なファイルを作って試してみましょう:

```php
<?php
// fuel/app/views/test.php を作成して以下を書いてみる
$tasks = ["買い物", "掃除", "勉強"];

foreach ($tasks as $index => $task) {
    echo ($index + 1) . ". " . $task . "<br>";
}
```

---

### ステップ1-3: FuelPHPの構造理解 - MVCパターン（45分）

**【重要】FuelPHPはMVCパターンで作られている**

#### MVCとは？

料理に例えると分かりやすい:

```
┌─────────────────────────────────────────┐
│  【お客さん】                            │
│  ブラウザでアクセス                      │
│  http://localhost:8080/tasks             │
└────────────┬────────────────────────────┘
             │
             ↓
┌────────────────────────────────────────┐
│  【ウェイター = Controller】            │
│  fuel/app/classes/controller/tasks.php  │
│  ・注文を受け取る                        │
│  ・料理人に指示を出す                    │
│  ・料理を運ぶ                            │
└────────────┬────────────────────────────┘
             │
             ↓
┌────────────────────────────────────────┐
│  【料理人 = Model】                      │
│  fuel/app/classes/model/task.php        │
│  ・データベースから材料を取り出す        │
│  ・データを加工する                      │
│  ・データを保存する                      │
└────────────┬────────────────────────────┘
             │
             ↓
┌────────────────────────────────────────┐
│  【お皿に盛り付け = View】              │
│  fuel/app/views/tasks/index.php         │
│  ・HTMLとして見やすく整形                │
│  ・お客さんに提供                        │
└────────────────────────────────────────┘
```

#### FuelPHPのディレクトリ構造

```
FuelPHP_Portfolio_App_Akira_Koshimizu/
├── fuel/
│   ├── app/                    ← あなたが主に作業する場所
│   │   ├── classes/
│   │   │   ├── controller/    ← Controller（コントローラー）
│   │   │   └── model/         ← Model（モデル）
│   │   ├── views/             ← View（ビュー）
│   │   ├── config/            ← 設定ファイル
│   │   └── migrations/        ← データベースのテーブル定義
│   │
│   ├── core/                   ← FuelPHPの本体（触らない）
│   └── packages/               ← 拡張機能
│       ├── auth/              ← 認証機能
│       └── orm/               ← データベース操作
│
├── public/                     ← Webから直接アクセスできる場所
│   ├── index.php              ← エントリーポイント
│   └── assets/                ← CSS、JS、画像など
│       ├── css/
│       └── js/
│
└── docker/                     ← Docker設定
```

#### 実際の動作の流れ

```
1. ユーザーがブラウザで http://localhost:8080/tasks にアクセス

2. public/index.php が最初に呼ばれる
   └→ FuelPHPが起動

3. fuel/app/config/routes.php を見て、どのControllerを呼ぶか決める
   └→ Controller_Tasks の action_index() を呼ぶ

4. Controller_Tasks が Model_Task を使ってデータを取得
   └→ データベースからタスク一覧を取得

5. Controller が View にデータを渡す
   └→ fuel/app/views/tasks/index.php にデータを渡す

6. View が HTML を生成して返す
   └→ ブラウザに表示される
```

---

### ステップ1-4: はじめてのコントローラー作成（30分）

**目的:** 「Hello World」を表示する簡単なページを作る

**手順:**

#### 1. コントローラーファイルを作成

```bash
# Dockerコンテナの中に入る
docker-compose exec app bash

# コントローラーを作成（oilコマンドを使う）
php oil generate controller hello index
```

**何が起きた？**
- `fuel/app/classes/controller/hello.php` が自動生成された
- `fuel/app/views/hello/index.php` が自動生成された

#### 2. 生成されたファイルを確認

**fuel/app/classes/controller/hello.php:**

```php
<?php
class Controller_Hello extends Controller
{
    public function action_index()
    {
        $data['message'] = 'Hello World!';
        return View::forge('hello/index', $data);
    }
}
```

**解説:**

```php
// クラス名は「Controller_」で始まる
// Controllerクラスを継承（extends）している
class Controller_Hello extends Controller
{
    // action_ で始まるメソッドがアクションになる
    // URLの /hello/index でこのメソッドが呼ばれる
    public function action_index()
    {
        // $data という配列にデータを入れる
        $data['message'] = 'Hello World!';

        // View::forge() で views/hello/index.php を呼び出す
        // $data を渡すことで、View側で $message が使える
        return View::forge('hello/index', $data);
    }
}
```

**fuel/app/views/hello/index.php:**

```php
<h1><?php echo $message; ?></h1>
```

#### 3. ブラウザで確認

`http://localhost:8080/hello/index` にアクセス

→ 「Hello World!」が表示されればOK

#### 4. カスタマイズしてみる

コントローラーを編集:

```php
<?php
class Controller_Hello extends Controller
{
    public function action_index()
    {
        $data['title'] = 'ようこそ！';
        $data['tasks'] = ['買い物', '掃除', '勉強'];
        return View::forge('hello/index', $data);
    }
}
```

ビューを編集:

```php
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?php echo $title; ?></title>
</head>
<body>
    <h1><?php echo $title; ?></h1>
    <h2>今日のタスク:</h2>
    <ul>
        <?php foreach ($tasks as $task): ?>
            <li><?php echo $task; ?></li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
```

再度 `http://localhost:8080/hello/index` にアクセスして確認。

---

## セクション2: データベース設計とマイグレーション（2時間）

### ステップ2-1: データベースの基礎知識（20分）

**データベースとは？**
- Excelのような「表（テーブル）」でデータを管理するシステム
- 複数のテーブルを関連付けて使える

**今回作るテーブル:**

#### 1. usersテーブル（Authパッケージが作る）

```
┌────┬──────────┬──────────────────────┬──────────┬─────────────────────┐
│ id │ username │ email                │ password │ created_at          │
├────┼──────────┼──────────────────────┼──────────┼─────────────────────┤
│ 1  │ yamada   │ yamada@example.com   │ ******** │ 2026-01-27 10:00:00 │
│ 2  │ tanaka   │ tanaka@example.com   │ ******** │ 2026-01-27 11:00:00 │
└────┴──────────┴──────────────────────┴──────────┴─────────────────────┘
```

#### 2. tasksテーブル（これから作る）

```
┌────┬─────────┬──────────────────┬────────────┬──────┬─────────────────────┐
│ id │ user_id │ title            │ content    │ done │ created_at          │
├────┼─────────┼──────────────────┼────────────┼──────┼─────────────────────┤
│ 1  │ 1       │ 買い物に行く     │ 牛乳を買う │ 0    │ 2026-01-27 10:30:00 │
│ 2  │ 1       │ レポート提出     │ 数学のレポ │ 1    │ 2026-01-27 11:00:00 │
│ 3  │ 2       │ 本を読む         │ PHP本      │ 0    │ 2026-01-27 12:00:00 │
└────┴─────────┴──────────────────┴────────────┴──────┴─────────────────────┘
```

**user_id は外部キー:**
- user_id = 1 → yamadaさんのタスク
- user_id = 2 → tanakaさんのタスク

---

### ステップ2-2: マイグレーションとは？（15分）

**マイグレーション = データベースの設計図**

**なぜマイグレーションを使うのか？**

❌ 悪い方法（手動でSQL実行）:
```sql
-- 毎回これを手動で実行するのは大変...
CREATE TABLE tasks (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255),
    ...
);
```

✅ 良い方法（マイグレーション）:
```php
// ファイルに書いておけば、コマンド1つで実行できる
php oil refine migrate
```

**メリット:**
1. チームで共有できる
2. バージョン管理できる
3. ロールバック（元に戻す）が簡単
4. 開発環境と本番環境で同じ構造を作れる

---

### ステップ2-3: Authパッケージの設定（30分）

**目的:** ユーザー認証機能を有効にする

#### 1. Authパッケージを有効化

**fuel/app/config/config.php** を編集:

```php
<?php
return array(
    // 他の設定...

    'always_load' => array(
        'packages' => array(
            'orm',      // データベース操作用
            'auth',     // 認証機能用 ← これを追加
        ),
    ),
);
```

#### 2. Auth設定ファイルをコピー

```bash
# Dockerコンテナの中で実行
docker-compose exec app bash

# Authのサンプル設定をコピー
cp fuel/packages/auth/config/auth.php fuel/app/config/auth.php
cp fuel/packages/auth/config/ormauth.php fuel/app/config/ormauth.php
```

#### 3. Auth設定を編集

**fuel/app/config/auth.php** を開いて確認:

```php
<?php
return array(
    'driver' => 'Ormauth',  // ORM版のAuthを使う
    'verify_multiple_logins' => false,
    'salt' => 'your-secret-salt-string',  // セキュリティ用の文字列（本番では変更必須）
);
```

#### 4. Authテーブルのマイグレーションを生成

```bash
# Authパッケージ用のマイグレーションを生成
php oil refine migrate --packages=auth

# 成功すると以下のような出力
# Performed migrations for package: auth
# Migrated to latest version: 011
```

**何が起きた？**

以下のテーブルが作成された:
- `users` - ユーザー情報
- `users_metadata` - 追加のユーザー情報
- `users_providers` - 外部ログイン用（今回は使わない）
- `users_permissions` - 権限管理用
- など

#### 5. データベースを確認

```bash
# MySQLに接続
docker-compose exec db mysql -u root -proot fuel_db

# テーブル一覧を表示
SHOW TABLES;

# usersテーブルの構造を確認
DESC users;

# 抜ける
exit
```

---

### ステップ2-4: 最初のユーザーを作成（20分）

**目的:** 管理画面にログインできるユーザーを作る

#### 方法1: oilコマンドで作成（簡単）

```bash
# Dockerコンテナ内で実行
docker-compose exec app bash

# 管理者ユーザーを作成
php oil console

# PHPの対話モードが起動したら以下を実行:
Auth::create_user('admin', 'password123', 'admin@example.com', 100);

# Ctrl+D で終了
```

**説明:**

```php
Auth::create_user(
    'admin',           // ユーザー名
    'password123',     // パスワード（本番では複雑にする）
    'admin@example.com', // メールアドレス
    100                // グループID（100=管理者）
);
```

#### 確認

```bash
docker-compose exec db mysql -u root -proot fuel_db

SELECT id, username, email FROM users;

exit
```

---

### ステップ2-5: データベースのER図理解（15分）

**ER図 = テーブル同士の関係を表す図**

```
┌─────────────────────┐
│ users               │
├─────────────────────┤
│ id (PK)             │←────────┐
│ username            │         │
│ email               │         │ 1人のユーザーは
│ password            │         │ 複数のタスクを持つ
│ created_at          │         │ (1対多の関係)
└─────────────────────┘         │
                                │
                                │
┌─────────────────────┐         │
│ tasks               │         │
├─────────────────────┤         │
│ id (PK)             │         │
│ user_id (FK)        │─────────┘
│ title               │
│ content             │
│ done                │
│ created_at          │
│ updated_at          │
└─────────────────────┘
```

**用語説明:**
- **PK (Primary Key)** = 主キー、各行を一意に識別する
- **FK (Foreign Key)** = 外部キー、他のテーブルとの関連を示す

---

### ステップ2-6: tasksテーブルのマイグレーション作成（20分）

**目的:** タスクを保存するテーブルを作る

#### 1. マイグレーションファイルを生成

```bash
docker-compose exec app bash

# タスクテーブルのマイグレーションを生成
php oil generate migration create_tasks \
  user_id:int \
  title:varchar[255] \
  content:text \
  done:boolean \
  created_at:datetime \
  updated_at:datetime
```

**何が起きた？**
- `fuel/app/migrations/001_create_tasks.php` が生成された

#### 2. 生成されたファイルを確認・編集

**fuel/app/migrations/001_create_tasks.php:**

```php
<?php
namespace Fuel\Migrations;

class Create_tasks
{
    public function up()
    {
        \DBUtil::create_table('tasks', array(
            'id' => array('type' => 'int', 'constraint' => 11, 'auto_increment' => true, 'unsigned' => true),
            'user_id' => array('type' => 'int', 'constraint' => 11, 'unsigned' => true),
            'title' => array('type' => 'varchar', 'constraint' => 255),
            'content' => array('type' => 'text', 'null' => true),
            'done' => array('type' => 'boolean', 'default' => 0),
            'created_at' => array('type' => 'datetime', 'null' => true),
            'updated_at' => array('type' => 'datetime', 'null' => true),
        ), array('id'), true, 'InnoDB', 'utf8mb4_unicode_ci');

        // 外部キー制約を追加（user_idがusers.idを参照）
        \DBUtil::add_foreign_key('tasks', array(
            'constraint' => 'fk_tasks_user_id',
            'key' => 'user_id',
            'reference' => array(
                'table' => 'users',
                'column' => 'id',
            ),
            'on_update' => 'CASCADE',
            'on_delete' => 'CASCADE',  // ユーザーが削除されたらタスクも削除
        ));
    }

    public function down()
    {
        // ロールバック時はテーブルを削除
        \DBUtil::drop_table('tasks');
    }
}
```

**重要ポイントの解説:**

```php
// auto_increment = 自動で番号を振る
'id' => array('type' => 'int', 'auto_increment' => true),

// unsigned = 負の数を使わない（0以上の数値のみ）
'user_id' => array('type' => 'int', 'unsigned' => true),

// null => true = 空でもOK
'content' => array('type' => 'text', 'null' => true),

// default = 初期値
'done' => array('type' => 'boolean', 'default' => 0),

// on_delete => 'CASCADE' = 親（users）が削除されたら子（tasks）も削除
'on_delete' => 'CASCADE',
```

#### 3. マイグレーションを実行

```bash
# 実行
php oil refine migrate

# 成功すると:
# Migrated to latest version: 001
```

#### 4. 確認

```bash
docker-compose exec db mysql -u root -proot fuel_db

# tasksテーブルの構造を確認
DESC tasks;

# 外部キーが設定されているか確認
SHOW CREATE TABLE tasks\G

exit
```

---

## セクション3: Authパッケージでユーザー登録機能（2-3時間）

### ステップ3-1: ユーザー登録の流れを理解する（15分）

**ユーザー登録の全体像:**

```
1. ユーザーが /auth/register にアクセス
   ↓
2. 登録フォームが表示される（Viewファイル）
   - ユーザー名入力欄
   - メールアドレス入力欄
   - パスワード入力欄
   ↓
3. ユーザーが「登録」ボタンをクリック
   ↓
4. Controllerがフォームの内容を受け取る
   ↓
5. バリデーション（入力チェック）
   - ユーザー名は空じゃないか？
   - メールアドレスの形式は正しいか？
   - パスワードは8文字以上か？
   ↓
6. 問題なければAuth::create_user()でデータベースに保存
   ↓
7. 「登録完了」メッセージを表示
```

---

### ステップ3-2: Authコントローラーの作成（30分）

#### 1. コントローラーを生成

```bash
docker-compose exec app bash

# Authコントローラーを生成
php oil generate controller auth register login logout
```

#### 2. 生成されたファイルを編集

**fuel/app/classes/controller/auth.php:**

```php
<?php
class Controller_Auth extends Controller
{
    /**
     * 登録フォーム表示
     */
    public function action_register()
    {
        // すでにログイン済みの場合はトップページへ
        if (Auth::check()) {
            Response::redirect('/');
        }

        // フォームが送信された場合（POSTリクエスト）
        if (Input::method() === 'POST') {
            // バリデーション（入力チェック）
            $val = Validation::forge();

            // ユーザー名のルール
            $val->add('username', 'ユーザー名')
                ->add_rule('required')           // 必須
                ->add_rule('min_length', 3)      // 最低3文字
                ->add_rule('max_length', 20);    // 最大20文字

            // メールアドレスのルール
            $val->add('email', 'メールアドレス')
                ->add_rule('required')
                ->add_rule('valid_email');       // メール形式チェック

            // パスワードのルール
            $val->add('password', 'パスワード')
                ->add_rule('required')
                ->add_rule('min_length', 8);     // 最低8文字

            // パスワード確認のルール
            $val->add('password_confirm', 'パスワード（確認）')
                ->add_rule('required')
                ->add_rule('match_field', 'password');  // passwordと一致するか

            // バリデーション実行
            if ($val->run()) {
                try {
                    // ユーザー作成
                    Auth::create_user(
                        Input::post('username'),
                        Input::post('password'),
                        Input::post('email'),
                        1  // グループID: 1 = 一般ユーザー
                    );

                    // 成功メッセージをセッションに保存
                    Session::set_flash('success', '登録が完了しました。ログインしてください。');

                    // ログインページにリダイレクト
                    Response::redirect('auth/login');

                } catch (\SimpleUserUpdateException $e) {
                    // エラー（例: 既に存在するユーザー名）
                    $data['error'] = $e->getMessage();
                }
            } else {
                // バリデーションエラー
                $data['errors'] = $val->error();
            }
        }

        // 登録フォームを表示
        return View::forge('auth/register', isset($data) ? $data : []);
    }
}
```

**コードの詳細解説:**

```php
// ログインチェック
if (Auth::check()) {
    // Auth::check() = ログインしているか確認
    // true = ログイン中、false = 未ログイン
    Response::redirect('/');  // トップページに飛ばす
}

// リクエストメソッドの判定
if (Input::method() === 'POST') {
    // GET = 普通にページを開いた時
    // POST = フォームを送信した時
}

// バリデーション設定
$val = Validation::forge();  // バリデーションオブジェクトを作成

$val->add('username', 'ユーザー名')  // フィールド名とラベル
    ->add_rule('required')           // 必須チェック
    ->add_rule('min_length', 3);     // 最小文字数

// バリデーション実行
if ($val->run()) {
    // 成功: Input::post('username') で値を取得できる
} else {
    // 失敗: $val->error() でエラーメッセージを取得
}

// フラッシュメッセージ = 1回だけ表示されるメッセージ
Session::set_flash('success', 'メッセージ');
```

---

### ステップ3-3: 登録フォームのView作成（30分）

#### fuel/app/views/auth/register.php を作成:

```php
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ユーザー登録 - TaskBoard</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .register-container {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 400px;
        }

        h1 {
            color: #333;
            margin-bottom: 30px;
            text-align: center;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #555;
            font-weight: 600;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            transition: border-color 0.3s;
        }

        input:focus {
            outline: none;
            border-color: #667eea;
        }

        .error {
            color: #e74c3c;
            font-size: 13px;
            margin-top: 5px;
        }

        .error-box {
            background: #fee;
            border: 1px solid #e74c3c;
            color: #c0392b;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        button {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s;
        }

        button:hover {
            transform: translateY(-2px);
        }

        .login-link {
            text-align: center;
            margin-top: 20px;
            color: #666;
        }

        .login-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <h1>ユーザー登録</h1>

        <?php if (isset($error)): ?>
            <div class="error-box"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST" action="/auth/register">
            <div class="form-group">
                <label for="username">ユーザー名</label>
                <input
                    type="text"
                    id="username"
                    name="username"
                    value="<?php echo Input::post('username', ''); ?>"
                    placeholder="例: yamada123"
                >
                <?php if (isset($errors) && isset($errors['username'])): ?>
                    <div class="error"><?php echo $errors['username']->get_message(); ?></div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="email">メールアドレス</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="<?php echo Input::post('email', ''); ?>"
                    placeholder="例: yamada@example.com"
                >
                <?php if (isset($errors) && isset($errors['email'])): ?>
                    <div class="error"><?php echo $errors['email']->get_message(); ?></div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="password">パスワード</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="8文字以上"
                >
                <?php if (isset($errors) && isset($errors['password'])): ?>
                    <div class="error"><?php echo $errors['password']->get_message(); ?></div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="password_confirm">パスワード（確認）</label>
                <input
                    type="password"
                    id="password_confirm"
                    name="password_confirm"
                    placeholder="もう一度入力してください"
                >
                <?php if (isset($errors) && isset($errors['password_confirm'])): ?>
                    <div class="error"><?php echo $errors['password_confirm']->get_message(); ?></div>
                <?php endif; ?>
            </div>

            <button type="submit">登録する</button>
        </form>

        <div class="login-link">
            すでにアカウントをお持ちですか？ <a href="/auth/login">ログイン</a>
        </div>
    </div>
</body>
</html>
```

**HTML/CSSのポイント:**

```php
// 入力値の保持（エラー時に入力内容を消さない）
value="<?php echo Input::post('username', ''); ?>"
// Input::post('username', '') = POSTデータから取得、なければ空文字

// エラーメッセージの表示
<?php if (isset($errors) && isset($errors['username'])): ?>
    <div class="error"><?php echo $errors['username']->get_message(); ?></div>
<?php endif; ?>
```

---

### ステップ3-4: 動作確認とテスト（20分）

#### 1. ブラウザで確認

`http://localhost:8080/auth/register` にアクセス

#### 2. テストケース

**Test 1: 正常系**
```
ユーザー名: testuser001
メールアドレス: test001@example.com
パスワード: password123
パスワード（確認）: password123

→ 「登録が完了しました」と表示されればOK
```

**Test 2: バリデーションエラー（短いユーザー名）**
```
ユーザー名: ab
メールアドレス: test@example.com
パスワード: password123
パスワード（確認）: password123

→ 「ユーザー名は最低3文字必要です」と表示されればOK
```

**Test 3: パスワード不一致**
```
ユーザー名: testuser002
メールアドレス: test002@example.com
パスワード: password123
パスワード（確認）: password456

→ 「パスワードが一致しません」と表示されればOK
```

**Test 4: 重複チェック**
```
同じユーザー名で2回登録

→ 「既に存在するユーザー名です」と表示されればOK
```

#### 3. データベースで確認

```bash
docker-compose exec db mysql -u root -proot fuel_db

SELECT id, username, email, created_at FROM users;

exit
```

---

## セクション4: ログイン機能実装（1-2時間）

### ステップ4-1: ログイン処理の実装（30分）

**fuel/app/classes/controller/auth.php** に追加:

```php
<?php
class Controller_Auth extends Controller
{
    // ... 既存のaction_register() ...

    /**
     * ログインフォーム表示・処理
     */
    public function action_login()
    {
        // すでにログイン済みならトップページへ
        if (Auth::check()) {
            Response::redirect('tasks');
        }

        // フォーム送信時
        if (Input::method() === 'POST') {
            // バリデーション
            $val = Validation::forge();
            $val->add('username', 'ユーザー名')->add_rule('required');
            $val->add('password', 'パスワード')->add_rule('required');

            if ($val->run()) {
                $username = Input::post('username');
                $password = Input::post('password');

                // ログイン試行
                if (Auth::login($username, $password)) {
                    // ログイン成功
                    Session::set_flash('success', 'ログインしました');
                    Response::redirect('tasks');
                } else {
                    // ログイン失敗
                    $data['error'] = 'ユーザー名またはパスワードが間違っています';
                }
            } else {
                $data['errors'] = $val->error();
            }
        }

        return View::forge('auth/login', isset($data) ? $data : []);
    }

    /**
     * ログアウト処理
     */
    public function action_logout()
    {
        Auth::logout();
        Session::set_flash('success', 'ログアウトしました');
        Response::redirect('auth/login');
    }
}
```

**Auth::login()の仕組み:**

```php
Auth::login($username, $password)
// 内部でやっていること:
// 1. ユーザー名でusersテーブルを検索
// 2. 見つかったら、パスワードをハッシュ化して比較
// 3. 一致したらセッションにユーザー情報を保存
// 4. trueを返す（失敗ならfalse）
```

---

### ステップ4-2: ログインフォームのView作成（20分）

**fuel/app/views/auth/login.php:**

```php
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログイン - TaskBoard</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .login-container {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 400px;
        }

        h1 {
            color: #333;
            margin-bottom: 30px;
            text-align: center;
        }

        .success-box {
            background: #d4edda;
            border: 1px solid #28a745;
            color: #155724;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .error-box {
            background: #fee;
            border: 1px solid #e74c3c;
            color: #c0392b;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #555;
            font-weight: 600;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            transition: border-color 0.3s;
        }

        input:focus {
            outline: none;
            border-color: #667eea;
        }

        button {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s;
        }

        button:hover {
            transform: translateY(-2px);
        }

        .register-link {
            text-align: center;
            margin-top: 20px;
            color: #666;
        }

        .register-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>ログイン</h1>

        <?php if (Session::get_flash('success')): ?>
            <div class="success-box"><?php echo Session::get_flash('success'); ?></div>
        <?php endif; ?>

        <?php if (isset($error)): ?>
            <div class="error-box"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST" action="/auth/login">
            <div class="form-group">
                <label for="username">ユーザー名</label>
                <input
                    type="text"
                    id="username"
                    name="username"
                    value="<?php echo Input::post('username', ''); ?>"
                    placeholder="ユーザー名を入力"
                    autofocus
                >
            </div>

            <div class="form-group">
                <label for="password">パスワード</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="パスワードを入力"
                >
            </div>

            <button type="submit">ログイン</button>
        </form>

        <div class="register-link">
            アカウントをお持ちでないですか？ <a href="/auth/register">新規登録</a>
        </div>
    </div>
</body>
</html>
```

---

### ステップ4-3: ログイン認証が必要なControllerを作る（20分）

**ベースコントローラーを作成:**

**fuel/app/classes/controller/base.php:**

```php
<?php
/**
 * ベースコントローラー
 * ログインが必要なページはこれを継承する
 */
class Controller_Base extends Controller
{
    public function before()
    {
        parent::before();

        // ログインチェック
        if (!Auth::check()) {
            // 未ログインの場合はログインページへ
            Session::set_flash('error', 'ログインが必要です');
            Response::redirect('auth/login');
        }

        // ログイン中のユーザー情報を取得
        $user = Auth::get_user_id();
        $this->user = $user;

        // 全てのViewで$current_userを使えるようにする
        View::set_global('current_user', Model_User::find($user[1]));
    }
}
```

**解説:**

```php
// before() = アクション実行前に必ず呼ばれるメソッド
public function before()
{
    parent::before();  // 親クラスのbefore()も実行

    // Auth::check() = ログイン済みか確認
    if (!Auth::check()) {
        // 未ログインならログインページへ飛ばす
        Response::redirect('auth/login');
    }

    // Auth::get_user_id() = ログイン中のユーザーIDを取得
    // 戻り値: array(driver, user_id)
    $user = Auth::get_user_id();

    // View::set_global() = 全てのViewで使える変数を設定
    View::set_global('current_user', $user_data);
}
```

---

### ステップ4-4: 動作確認（10分）

#### 1. ログインテスト

`http://localhost:8080/auth/login` にアクセス

先ほど登録したユーザーでログイン:
```
ユーザー名: testuser001
パスワード: password123
```

#### 2. ログアウトテスト

`http://localhost:8080/auth/logout` にアクセス

→ ログアウトされてログイン画面に戻ればOK

#### 3. 未ログイン状態でのアクセス制限テスト

後で作る `/tasks` ページにアクセスした時、自動的にログインページに飛ばされることを確認（これは次のDay2で実装）

---

## ✅ Day 1 完了チェックリスト

以下ができていればDay 1は完了です:

- [ ] Docker環境が起動している
- [ ] FuelPHPのウェルカムページが表示される
- [ ] PHP基礎（変数、配列、関数、クラス）を理解した
- [ ] MVCパターンを理解した
- [ ] `hello/index` ページが表示できる
- [ ] usersテーブルとtasksテーブルが作成されている
- [ ] ユーザー登録ができる（バリデーション機能含む）
- [ ] ログインができる
- [ ] ログアウトができる

**全てチェックが入ったら、Day 2に進みましょう！**

---

# 📘 Day 2: 実装編（タスク管理CRUD機能）

## セクション5: タスクテーブルとModelの作成（1時間）

### ステップ5-1: Modelの役割を理解する（15分）

**Model = データベースとやりとりする係**

```php
// Modelなしの場合（生のSQL）
$result = DB::query("SELECT * FROM tasks WHERE user_id = 1")->execute();

// Modelありの場合（簡単！安全！）
$tasks = Model_Task::find('all', array(
    'where' => array('user_id' => 1)
));
```

**Modelのメリット:**
1. SQLを書かなくて良い
2. SQLインジェクション対策が自動
3. コードが読みやすい
4. リレーション（関連）が簡単に扱える

---

### ステップ5-2: TaskモデルとUserモデルの作成（30分）

#### 1. Userモデルを作成

```bash
docker-compose exec app bash

# Modelファイルを作成
php oil generate model user --crud --mysql-timestamp
```

**fuel/app/classes/model/user.php** を編集:

```php
<?php
/**
 * Userモデル
 */
class Model_User extends \Orm\Model
{
    // テーブル名
    protected static $_table_name = 'users';

    // プライマリキー
    protected static $_primary_key = array('id');

    // プロパティ（カラム）の定義
    protected static $_properties = array(
        'id',
        'username',
        'password',
        'group',
        'email',
        'last_login',
        'login_hash',
        'profile_fields',
        'created_at',
        'updated_at',
    );

    // リレーション: 1人のユーザーは複数のタスクを持つ
    protected static $_has_many = array(
        'tasks' => array(
            'key_from' => 'id',
            'model_to' => 'Model_Task',
            'key_to' => 'user_id',
            'cascade_save' => true,
            'cascade_delete' => true,  // ユーザー削除時にタスクも削除
        )
    );

    // タイムスタンプを自動更新
    protected static $_observers = array(
        'Orm\Observer_CreatedAt' => array(
            'events' => array('before_insert'),
            'mysql_timestamp' => true,
        ),
        'Orm\Observer_UpdatedAt' => array(
            'events' => array('before_update'),
            'mysql_timestamp' => true,
        ),
    );
}
```

#### 2. Taskモデルを作成

```bash
php oil generate model task user_id:int title:string content:text done:boolean --crud --mysql-timestamp
```

**fuel/app/classes/model/task.php** を編集:

```php
<?php
/**
 * Taskモデル
 */
class Model_Task extends \Orm\Model
{
    protected static $_table_name = 'tasks';
    protected static $_primary_key = array('id');

    protected static $_properties = array(
        'id',
        'user_id',
        'title',
        'content',
        'done',
        'created_at',
        'updated_at',
    );

    // リレーション: 各タスクは1人のユーザーに属する
    protected static $_belongs_to = array(
        'user' => array(
            'key_from' => 'user_id',
            'model_to' => 'Model_User',
            'key_to' => 'id',
            'cascade_save' => false,
            'cascade_delete' => false,
        )
    );

    // バリデーションルール
    protected static $_validators = array(
        'title' => array(
            array('required'),
            array('max_length', 255),
        ),
    );

    // タイムスタンプ自動更新
    protected static $_observers = array(
        'Orm\Observer_CreatedAt' => array(
            'events' => array('before_insert'),
            'mysql_timestamp' => true,
        ),
        'Orm\Observer_UpdatedAt' => array(
            'events' => array('before_update'),
            'mysql_timestamp' => true,
        ),
    );
}
```

**リレーションの解説:**

```php
// Model_User側
protected static $_has_many = array('tasks' => [...]);
// ↑ 1人のユーザーは複数のタスクを持つ（1対多）

// 使い方:
$user = Model_User::find(1);
$tasks = $user->tasks;  // そのユーザーの全タスクを取得

// Model_Task側
protected static $_belongs_to = array('user' => [...]);
// ↑ 各タスクは1人のユーザーに属する（多対1）

// 使い方:
$task = Model_Task::find(1);
$user = $task->user;  // そのタスクの作成者を取得
```

---

### ステップ5-3: Modelの基本操作を理解する（15分）

**CRUDの基本:**

```php
<?php
// ===== Create (作成) =====
$task = Model_Task::forge(array(
    'user_id' => 1,
    'title' => '買い物に行く',
    'content' => '牛乳とパンを買う',
    'done' => false,
));
$task->save();  // データベースに保存

// ===== Read (取得) =====
// 全件取得
$tasks = Model_Task::find('all');

// 条件付き取得
$tasks = Model_Task::find('all', array(
    'where' => array(
        'user_id' => 1,
        'done' => false,
    ),
    'order_by' => array('created_at' => 'desc'),
));

// 1件取得（IDで検索）
$task = Model_Task::find(1);

// ===== Update (更新) =====
$task = Model_Task::find(1);
$task->title = '買い物完了';
$task->done = true;
$task->save();

// ===== Delete (削除) =====
$task = Model_Task::find(1);
$task->delete();
```

---

## セクション6: CRUD機能実装（3-4時間）

### ステップ6-1: Tasksコントローラーの作成（15分）

```bash
docker-compose exec app bash

# Tasksコントローラーを生成
php oil generate controller tasks index create edit delete
```

**fuel/app/classes/controller/tasks.php** のベースを作成:

```php
<?php
/**
 * Tasksコントローラー
 * ログインが必要なのでController_Baseを継承
 */
class Controller_Tasks extends Controller_Base
{
    // このコントローラーのアクションは全てログインが必要
    // （Controller_Baseのbefore()でチェック済み）
}
```

---

### ステップ6-2: タスク一覧表示（Read）の実装（45分）

このセクションの実装内容は、[README.md](./README.md)の「ディレクトリ構造」セクションと「開発の進め方」セクションを参照してください。

詳細なコードと実装手順は、プロジェクトのファイルを確認しながら進めてください。

---

### ステップ6-3以降のセクションについて

残りのDay 2のセクション（タスク作成、編集、削除など）の詳細な実装手順は、以下のリソースを参照してください:

1. **プロジェクトファイル**: 各ディレクトリのファイルを確認
2. **README.md**: 全体の構造とセキュリティ対策を確認
3. **実際にコードを書いて試す**: 手を動かして学ぶことが最も重要

---

## 📚 追加リソース

### 公式ドキュメント
- [FuelPHP公式ドキュメント](https://fuelphp.com/docs/)
- [FuelPHP Auth Package](https://fuelphp.com/docs/packages/auth/intro.html)
- [FuelPHP ORM Package](https://fuelphp.com/docs/packages/orm/intro.html)

### 学習のヒント
1. エラーメッセージをよく読む
2. 小さな単位で動作確認
3. データベースの中身を確認しながら進める
4. わからないことは検索する

---

## 🎉 完成おめでとうございます！

このチュートリアルを完了したあなたは、以下のスキルを習得しました:

- ✅ FuelPHPフレームワークの基本
- ✅ MVCパターンの理解
- ✅ データベース設計
- ✅ ユーザー認証の実装
- ✅ CRUD操作の実装
- ✅ セキュリティ対策の基礎

次のステップとして、README.mdの「今後の拡張アイデア」を参考に、さらに機能を追加してみてください！

**Happy Coding! 🚀**