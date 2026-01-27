# 📋 TaskBoard - FuelPHP認証機能付きタスク管理アプリ

<div align="center">

![FuelPHP](https://img.shields.io/badge/FuelPHP-1.9-orange.svg)
![PHP](https://img.shields.io/badge/PHP-8.2-blue.svg)
![MySQL](https://img.shields.io/badge/MySQL-8.0-blue.svg)
![Docker](https://img.shields.io/badge/Docker-ready-green.svg)
![License](https://img.shields.io/badge/license-MIT-green.svg)

**ポートフォリオ向け - 実用的な掲示板/タスク管理アプリケーション**

</div>

---

## 📖 目次

- [概要](#概要)
- [デモ・スクリーンショット](#デモスクリーンショット)
- [主な機能](#主な機能)
- [技術スタック](#技術スタック)
- [アーキテクチャ](#アーキテクチャ)
- [環境構築](#環境構築)
- [使い方](#使い方)
- [ハンズオン講義](#ハンズオン講義)
- [ディレクトリ構造](#ディレクトリ構造)
- [開発の進め方](#開発の進め方)
- [セキュリティ](#セキュリティ)
- [トラブルシューティング](#トラブルシューティング)
- [今後の拡張アイデア](#今後の拡張アイデア)
- [ライセンス](#ライセンス)

---

## 🎯 概要

**TaskBoard**は、FuelPHP 1.9フレームワークを使用して開発した、認証機能付きの実用的なタスク管理アプリケーションです。

### このプロジェクトの目的

- ✅ FuelPHPフレームワークの基本的な使い方を習得
- ✅ MVCパターンの理解と実践
- ✅ ユーザー認証機能の実装（Authパッケージ使用）
- ✅ CRUD操作の完全な実装
- ✅ データベース設計とリレーションシップの理解
- ✅ セキュリティ対策の実装（XSS、SQLインジェクション、CSRF）
- ✅ ポートフォリオとして企業にアピールできる成果物の作成

### 対象者

- PHPの基礎知識がある方（変数、配列、関数、クラスが理解できる）
- HTML/CSSの実務経験がある方
- Webアプリケーション開発に興味がある方
- フレームワークを使った開発を学びたい方

---

## 🖼️ デモ・スクリーンショット

### ログイン画面
美しいグラデーションデザインのログイン画面。ユーザー認証を安全に実施します。

### タスク一覧画面
- 統計情報の表示（全タスク数、完了済み、未完了）
- フィルター機能（全て・未完了・完了済み）
- チェックボックスで簡単に完了/未完了切り替え
- 直感的な編集・削除ボタン

### タスク作成・編集画面
シンプルで使いやすいフォームデザイン。バリデーション機能付き。

---

## ✨ 主な機能

### 🔐 認証機能
- **ユーザー登録**
  - ユーザー名、メールアドレス、パスワードで登録
  - 入力バリデーション（必須チェック、文字数制限、メール形式チェック等）
  - パスワードの安全な暗号化保存

- **ログイン/ログアウト**
  - セッション管理
  - ログイン状態の自動チェック
  - 未ログイン時の自動リダイレクト

### 📝 タスク管理機能（CRUD）
- **作成（Create）**
  - タスクのタイトルと詳細を入力
  - バリデーション機能
  - 自動的にログインユーザーと紐付け

- **表示（Read）**
  - 自分が作成したタスクのみ表示
  - 作成日時の表示
  - 完了/未完了の状態表示
  - 統計情報の自動計算

- **更新（Update）**
  - タスクの編集機能
  - 完了/未完了のトグル機能
  - リアルタイムでの状態更新

- **削除（Delete）**
  - 確認ダイアログ付き削除機能
  - 論理削除ではなく物理削除

### 🎨 UI/UX機能
- **フィルター機能**
  - 全てのタスク
  - 未完了のタスクのみ
  - 完了済みのタスクのみ

- **統計ダッシュボード**
  - 全タスク数
  - 完了済みタスク数
  - 未完了タスク数

- **レスポンシブデザイン**
  - PC、タブレット、スマートフォン対応
  - モダンなグラデーションデザイン

### 🔒 セキュリティ機能
- **XSS対策**: HTMLエスケープ処理
- **SQLインジェクション対策**: ORMの使用
- **CSRF対策**: トークン検証
- **権限チェック**: 他人のタスクへのアクセス制限
- **パスワード暗号化**: bcryptによるハッシュ化

---

## 🛠️ 技術スタック

### バックエンド
| 技術 | バージョン | 用途 |
|------|-----------|------|
| PHP | 8.2 | プログラミング言語 |
| FuelPHP | 1.9 | PHPフレームワーク |
| FuelPHP Auth | 1.9 | 認証パッケージ |
| FuelPHP ORM | 1.9 | データベースORM |
| MySQL | 8.0 | データベース |

### フロントエンド
| 技術 | 用途 |
|------|------|
| HTML5 | マークアップ |
| CSS3 | スタイリング（グラデーション、フレックスボックス、グリッド） |
| JavaScript | インタラクション（削除確認等） |

### インフラ・開発環境
| 技術 | バージョン | 用途 |
|------|-----------|------|
| Docker | latest | コンテナ化 |
| Docker Compose | latest | 複数コンテナの管理 |
| Nginx | latest | Webサーバー |
| PHP-FPM | 8.2 | PHPプロセスマネージャー |
| Composer | latest | PHPパッケージ管理 |

---

## 🏗️ アーキテクチャ

### MVCパターン

```
┌─────────────────────────────────────────────────┐
│                   ブラウザ                       │
│          http://localhost:8080/tasks            │
└───────────────────┬─────────────────────────────┘
                    │
                    ↓
┌─────────────────────────────────────────────────┐
│              Webサーバー (Nginx)                 │
│              ポート: 80                          │
└───────────────────┬─────────────────────────────┘
                    │
                    ↓
┌─────────────────────────────────────────────────┐
│         アプリケーションサーバー (PHP-FPM)        │
│              ポート: 9000                        │
│                                                  │
│  ┌──────────────────────────────────────────┐   │
│  │  Controller (fuel/app/classes/controller)│   │
│  │  - ルーティング処理                       │   │
│  │  - リクエスト受付                         │   │
│  │  - レスポンス生成                         │   │
│  └──────────────┬───────────────────────────┘   │
│                 │                                │
│                 ↓                                │
│  ┌──────────────────────────────────────────┐   │
│  │  Model (fuel/app/classes/model)          │   │
│  │  - データベース操作                       │   │
│  │  - ビジネスロジック                       │   │
│  │  - バリデーション                         │   │
│  └──────────────┬───────────────────────────┘   │
│                 │                                │
│                 ↓                                │
│  ┌──────────────────────────────────────────┐   │
│  │  View (fuel/app/views)                   │   │
│  │  - HTML生成                               │   │
│  │  - データ表示                             │   │
│  └──────────────────────────────────────────┘   │
└───────────────────┬─────────────────────────────┘
                    │
                    ↓
┌─────────────────────────────────────────────────┐
│           データベース (MySQL)                   │
│              ポート: 3306                        │
│                                                  │
│  ┌──────────────┐      ┌──────────────┐         │
│  │ usersテーブル │←────→│ tasksテーブル │         │
│  │  - id        │ 1:N  │  - id        │         │
│  │  - username  │      │  - user_id   │         │
│  │  - email     │      │  - title     │         │
│  │  - password  │      │  - content   │         │
│  └──────────────┘      │  - done      │         │
│                        └──────────────┘         │
└─────────────────────────────────────────────────┘
```

### データフロー

1. **リクエスト受信**: ユーザーがブラウザで `/tasks` にアクセス
2. **ルーティング**: `routes.php` が `Controller_Tasks::action_index()` を呼び出し
3. **認証チェック**: `Controller_Base::before()` でログイン状態を確認
4. **データ取得**: `Model_Task` を使ってデータベースからタスクを取得
5. **View生成**: `views/tasks/index.php` でHTMLを生成
6. **レスポンス返却**: HTMLをブラウザに返す

---

## 🚀 環境構築

### 前提条件

以下のソフトウェアがインストールされていることを確認してください:

- [Docker Desktop](https://www.docker.com/products/docker-desktop) (Windows/Mac)
- [Git](https://git-scm.com/)

### インストール手順

#### 1. リポジトリのクローン

```bash
git clone <your-repository-url>
cd FuelPHP_Portfolio_App_Akira_Koshimizu
```

#### 2. Dockerコンテナの起動

```bash
# コンテナをビルド＆起動
docker-compose up -d

# 起動確認（3つのコンテナが Up になっていればOK）
docker-compose ps
```

**期待される出力:**
```
NAME                STATE       PORTS
web                 Up          0.0.0.0:8080->80/tcp
app                 Up          9000/tcp
db                  Up          0.0.0.0:3306->3306/tcp
```

#### 3. 依存パッケージのインストール

```bash
# appコンテナに入る
docker-compose exec app bash

# Composerで依存パッケージをインストール
composer install

# コンテナから抜ける
exit
```

#### 4. データベースのセットアップ

```bash
# appコンテナに入る
docker-compose exec app bash

# Authパッケージのマイグレーション実行
php oil refine migrate --packages=auth

# アプリケーションのマイグレーション実行
php oil refine migrate

# コンテナから抜ける
exit
```

#### 5. 動作確認

ブラウザで以下のURLにアクセス:

```
http://localhost:8080
```

FuelPHPのウェルカムページが表示されれば成功です！

#### 6. 初期ユーザーの作成（オプション）

```bash
docker-compose exec app bash

# PHPコンソールを起動
php oil console

# 以下のコマンドを実行してユーザーを作成
Auth::create_user('admin', 'password123', 'admin@example.com', 100);

# Ctrl+D で終了
```

---

## 📱 使い方

### 1. ユーザー登録

1. `http://localhost:8080/auth/register` にアクセス
2. ユーザー名、メールアドレス、パスワードを入力
3. 「登録する」ボタンをクリック
4. 登録が完了したらログイン画面にリダイレクト

### 2. ログイン

1. `http://localhost:8080/auth/login` にアクセス
2. 登録したユーザー名とパスワードを入力
3. 「ログイン」ボタンをクリック
4. タスク一覧画面にリダイレクト

### 3. タスクの作成

1. タスク一覧画面で「+ 新しいタスクを作成」ボタンをクリック
2. タイトルと詳細（オプション）を入力
3. 「作成する」ボタンをクリック

### 4. タスクの完了/未完了切り替え

1. タスク一覧画面のチェックボックスをクリック
2. 自動的に完了/未完了が切り替わります

### 5. タスクの編集

1. 編集したいタスクの「編集」ボタンをクリック
2. タイトルや詳細を変更
3. 「更新する」ボタンをクリック

### 6. タスクの削除

1. 削除したいタスクの「削除」ボタンをクリック
2. 確認ダイアログで「OK」をクリック

### 7. フィルター機能

- 「全て」「未完了」「完了済み」のタブをクリックして表示を切り替え

### 8. ログアウト

- 画面右上の「ログアウト」ボタンをクリック

---

## 📚 ハンズオン講義

**このプロジェクトを1から作成する詳細なハンズオン講義資料を用意しています！**

### 📖 [TUTORIAL.md - 完全ハンズオン講義（2日間コース）](./TUTORIAL.md)

#### 内容:
- **Day 1（6-8時間）**: 環境構築、PHP基礎、データベース設計、認証機能
- **Day 2（6-8時間）**: タスク管理CRUD機能、セキュリティ対策、仕上げ

#### 特徴:
- PHP初心者でも理解できる丁寧な解説
- コピペで動くコード例
- 各ステップごとの動作確認方法
- トラブルシューティングガイド付き
- チェックリストで進捗管理

#### 対象者:
- PHPの知識は皆無だが、HTML/CSSの実務経験がある方
- FuelPHPを学びたい方
- 2日以内に実用的なアプリを作りたい方

**👉 [今すぐハンズオン講義を始める](./TUTORIAL.md)**

---

## 📁 ディレクトリ構造

```
FuelPHP_Portfolio_App_Akira_Koshimizu/
│
├── docker/                          # Docker関連設定
│   ├── nginx/
│   │   └── default.conf            # Nginx設定ファイル
│   └── php/
│       └── Dockerfile              # PHPコンテナのDockerfile
│
├── fuel/                            # FuelPHPメインディレクトリ
│   ├── app/                         # アプリケーションコード
│   │   ├── classes/
│   │   │   ├── controller/         # コントローラー
│   │   │   │   ├── base.php       # ベースコントローラー（認証チェック）
│   │   │   │   ├── auth.php       # 認証コントローラー
│   │   │   │   └── tasks.php      # タスク管理コントローラー
│   │   │   │
│   │   │   └── model/              # モデル
│   │   │       ├── user.php        # ユーザーモデル
│   │   │       └── task.php        # タスクモデル
│   │   │
│   │   ├── views/                   # ビュー（HTML）
│   │   │   ├── auth/
│   │   │   │   ├── register.php   # ユーザー登録画面
│   │   │   │   └── login.php      # ログイン画面
│   │   │   ├── tasks/
│   │   │   │   ├── index.php      # タスク一覧画面
│   │   │   │   ├── create.php     # タスク作成画面
│   │   │   │   └── edit.php       # タスク編集画面
│   │   │   └── errors/
│   │   │       └── 404.php         # 404エラー画面
│   │   │
│   │   ├── config/                  # 設定ファイル
│   │   │   ├── config.php          # メイン設定
│   │   │   ├── db.php              # データベース設定（グローバル）
│   │   │   ├── routes.php          # ルーティング設定
│   │   │   ├── auth.php            # 認証設定
│   │   │   ├── ormauth.php         # ORM認証設定
│   │   │   └── development/
│   │   │       └── db.php          # データベース設定（開発環境）
│   │   │
│   │   └── migrations/              # マイグレーションファイル
│   │       └── 001_create_tasks.php # tasksテーブル作成
│   │
│   ├── core/                        # FuelPHPコア（触らない）
│   ├── packages/                    # パッケージ
│   │   ├── auth/                   # 認証パッケージ
│   │   └── orm/                    # ORMパッケージ
│   └── vendor/                      # Composer依存パッケージ
│
├── public/                          # 公開ディレクトリ
│   ├── index.php                   # エントリーポイント
│   └── assets/                     # 静的ファイル
│       ├── css/
│       ├── js/
│       └── img/
│
├── docker-compose.yml               # Docker Compose設定
├── composer.json                    # Composer設定
├── README.md                        # このファイル
└── TUTORIAL.md                      # ハンズオン講義資料
```

---

## 💻 開発の進め方

### ローカル開発サーバーの起動

```bash
# コンテナを起動
docker-compose up -d

# ログを確認（リアルタイム）
docker-compose logs -f

# コンテナを停止
docker-compose down
```

### データベースへの接続

#### 方法1: MySQLコマンドライン

```bash
docker-compose exec db mysql -u root -proot fuel_db
```

#### 方法2: GUIツール（Sequel Pro、TablePlus等）

- Host: `127.0.0.1`
- Port: `3306`
- User: `root`
- Password: `root`
- Database: `fuel_db`

### マイグレーションの管理

```bash
# appコンテナに入る
docker-compose exec app bash

# マイグレーション実行
php oil refine migrate

# マイグレーションをロールバック
php oil refine migrate:down

# マイグレーション状態確認
php oil refine migrate:current
```

### コードの追加・変更

1. ローカルのファイルを編集
2. Dockerのvolumeマウントで自動的にコンテナに反映
3. ブラウザで確認

---

## 🔒 セキュリティ

このアプリケーションには以下のセキュリティ対策が実装されています:

### 1. XSS（クロスサイトスクリプティング）対策

**対策内容:**
- 全てのユーザー入力を `htmlspecialchars()` でエスケープ
- FuelPHPの `Security::htmlentities()` を使用

**実装例:**
```php
<?php echo htmlspecialchars($task->title); ?>
```

### 2. SQLインジェクション対策

**対策内容:**
- ORMを使用して自動的にプリペアドステートメント化
- 生のSQLクエリは使用しない

**実装例:**
```php
// 安全（ORMが自動でエスケープ）
$tasks = Model_Task::find('all', array(
    'where' => array('user_id' => $user_id)
));
```

### 3. CSRF（クロスサイトリクエストフォージェリ）対策

**対策内容:**
- 全てのフォームにCSRFトークンを埋め込み
- FuelPHPの `Form::csrf()` を使用

**実装例:**
```php
<form method="POST">
    <?php echo Form::csrf(); ?>
    <!-- フォームフィールド -->
</form>
```

### 4. パスワードの暗号化

**対策内容:**
- bcryptアルゴリズムでハッシュ化
- Authパッケージが自動的に処理

### 5. アクセス制御

**対策内容:**
- ログインチェック（`Controller_Base::before()`）
- 所有者チェック（他人のタスクは操作不可）

**実装例:**
```php
// 所有者チェック
if ($task->user_id != $user_id[1]) {
    Session::set_flash('error', '他人のタスクは操作できません');
    Response::redirect('tasks');
}
```

### 6. セッション管理

**対策内容:**
- セッションIDの自動再生成
- セッションハイジャック対策

---

## 🔧 トラブルシューティング

### よくある問題と解決方法

#### 問題1: Dockerコンテナが起動しない

**症状:**
```
Error: Cannot start service xxx: ...
```

**解決方法:**
```bash
# 既存のコンテナを完全に削除して再起動
docker-compose down -v
docker-compose up -d --build
```

#### 問題2: "Class 'Auth' not found"

**症状:**
```
Fatal error: Class 'Auth' not found
```

**原因:** Authパッケージが読み込まれていない

**解決方法:**
`fuel/app/config/config.php` を編集:
```php
'always_load' => array(
    'packages' => array(
        'orm',
        'auth',  // ← これを追加
    ),
),
```

#### 問題3: "Table 'fuel_db.tasks' doesn't exist"

**症状:**
```
SQLSTATE[42S02]: Base table or view not found: Table 'fuel_db.tasks' doesn't exist
```

**原因:** マイグレーションが実行されていない

**解決方法:**
```bash
docker-compose exec app bash
php oil refine migrate --packages=auth
php oil refine migrate
exit
```

#### 問題4: ログインできない

**症状:** ユーザー名とパスワードを入力してもログインできない

**原因:** ユーザーが存在しない、またはパスワードが間違っている

**解決方法:**
```bash
# データベースを確認
docker-compose exec db mysql -u root -proot fuel_db
SELECT * FROM users;
exit

# ユーザーを作り直す
docker-compose exec app bash
php oil console
Auth::create_user('testuser', 'password123', 'test@example.com');
exit
```

#### 問題5: ポート8080が既に使用されている

**症状:**
```
Error: Bind for 0.0.0.0:8080 failed: port is already allocated
```

**解決方法:**
`docker-compose.yml` を編集してポート番号を変更:
```yaml
services:
  web:
    ports:
      - "8081:80"  # 8080を8081に変更
```

#### 問題6: Permission Denied（権限エラー）

**症状:**
```
Permission denied: fuel/app/logs/...
```

**解決方法:**
```bash
# logsディレクトリの権限を変更
docker-compose exec app bash
chmod -R 777 fuel/app/logs fuel/app/cache
exit
```

#### 問題7: Composer install が失敗する

**症状:**
```
Failed to download xxx from dist
```

**解決方法:**
```bash
docker-compose exec app bash
composer clear-cache
composer install
exit
```

### デバッグのヒント

#### ログの確認

```bash
# FuelPHPのログを確認
tail -f fuel/app/logs/2026/01/27.php

# Dockerのログを確認
docker-compose logs app
docker-compose logs web
docker-compose logs db
```

#### データベースの確認

```bash
docker-compose exec db mysql -u root -proot fuel_db

# テーブル一覧
SHOW TABLES;

# usersテーブルの中身
SELECT * FROM users;

# tasksテーブルの中身
SELECT * FROM tasks;

exit
```

---

## 🚀 今後の拡張アイデア

このアプリケーションをさらに発展させるためのアイデア:

### 機能追加

1. **タスクの優先度機能**
   - 優先度カラムを追加（高・中・低）
   - 色分けして表示
   - 優先度順でソート

2. **期限管理機能**
   - `due_date` カラムを追加
   - 期限切れのタスクを赤く表示
   - 期限が近いタスクを通知

3. **カテゴリ機能**
   - `categories` テーブルを追加
   - タスクにカテゴリを紐付け
   - カテゴリごとにフィルター

4. **検索機能**
   - タイトルや内容で全文検索
   - 日付範囲での検索

5. **タグ機能**
   - タスクに複数のタグを付与
   - タグクラウド表示
   - タグで絞り込み

6. **共有機能**
   - 他のユーザーとタスクを共有
   - 権限管理（閲覧のみ/編集可能）

7. **コメント機能**
   - タスクにコメントを追加
   - コメントの編集・削除

8. **添付ファイル機能**
   - タスクにファイルを添付
   - 画像プレビュー

### UI/UX改善


1. **ドラッグ&ドロップ**
   - タスクの並び替え
   - 優先度の変更

2. **Ajax化**
   - ページリロードなしでタスクを操作
   - リアルタイムでの更新

3. **ダークモード**
   - ライト/ダークテーマの切り替え
   - ユーザー設定で保存

4. **モバイル最適化**
   - スワイプジェスチャー
   - プルトゥリフレッシュ

5. **通知機能**
   - ブラウザ通知
   - メール通知

### パフォーマンス改善

1. **ページネーション**
   - タスクが多い場合のページ分割
   - 無限スクロール

2. **キャッシュ機能**
   - Redis導入
   - クエリキャッシュ

3. **画像最適化**
   - 遅延読み込み
   - WebP対応

### セキュリティ強化

1. **2要素認証**
   - TOTPベースの2FA
   - SMS認証

2. **APIトークン**
   - RESTful API追加
   - OAuth2対応

3. **監査ログ**
   - ユーザーの操作履歴を記録
   - 不正アクセス検知

### テスト・品質向上

1. **ユニットテスト**
   - PHPUnitの導入
   - Model・Controllerのテスト

2. **E2Eテスト**
   - Seleniumの導入
   - 自動テストスクリプト

3. **CI/CD**
   - GitHub Actionsの設定
   - 自動デプロイ

---

## 📄 ライセンス

このプロジェクトはMITライセンスの下で公開されています。

```
MIT License

Copyright (c) 2026 Akira Koshimizu

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
```

---

## 👨‍💻 開発者情報

**開発者:** Akira Koshimizu
**開発期間:** 2日間
**目的:** FuelPHP学習＆ポートフォリオ作成

### 使用したリソース
- [FuelPHP公式ドキュメント](https://fuelphp.com/docs/)
- [PHP公式マニュアル](https://www.php.net/manual/ja/)
- [Docker公式ドキュメント](https://docs.docker.com/)

---

## 🙏 謝辞

このプロジェクトは以下のオープンソースプロジェクトを使用しています:

- [FuelPHP](https://fuelphp.com/) - PHPフレームワーク
- [Docker](https://www.docker.com/) - コンテナ化プラットフォーム
- [MySQL](https://www.mysql.com/) - データベース
- [Nginx](https://nginx.org/) - Webサーバー

---

## 📞 お問い合わせ

質問や提案がある場合は、以下の方法でお問い合わせください:

- **GitHub Issues:** [こちらから Issue を作成](https://github.com/your-username/your-repo/issues)
- **Email:** your-email@example.com

---

<div align="center">

**⭐️ このプロジェクトが役に立った場合は、ぜひスターをお願いします！ ⭐️**

Made with ❤️ by Akira Koshimizu

</div>
