# ⚡ クイックスタートガイド - 5分で始める

このガイドに従えば、**5分でTaskBoardアプリを起動**できます。

---

## 📋 チェックリスト

開始前に以下がインストールされていることを確認:

- [ ] Docker Desktop がインストールされている
- [ ] Docker Desktop が起動している

---

## 🚀 5ステップで起動

### ステップ1: リポジトリに移動

```bash
cd /Users/882akira/Desktop/FuelPHP_Portfolio_App_Akira_Koshimizu
```

### ステップ2: Dockerコンテナを起動

```bash
docker-compose up -d
```

初回は5-10分かかる場合があります（イメージのダウンロード）。

### ステップ3: 起動確認

```bash
docker-compose ps
```

以下のように3つのコンテナが`Up`になっていればOK:
```
NAME     STATE    PORTS
web      Up       0.0.0.0:8080->80/tcp
app      Up       9000/tcp
db       Up       0.0.0.0:3306->3306/tcp
```

### ステップ4: 依存パッケージをインストール

```bash
docker-compose exec app bash
composer install
exit
```

### ステップ5: データベースをセットアップ

```bash
docker-compose exec app bash
php oil refine migrate --packages=auth
php oil refine migrate
exit
```

---

## ✅ 動作確認

ブラウザで以下のURLにアクセス:

```
http://localhost:8080
```

FuelPHPのウェルカムページが表示されれば成功！

---

## 👤 初期ユーザーの作成

アプリを使うには、まずユーザーを作成します。

### 方法1: ブラウザから登録（推奨）

1. `http://localhost:8080/auth/register` にアクセス
2. ユーザー名、メールアドレス、パスワードを入力
3. 「登録する」をクリック
4. ログイン画面にリダイレクトされるので、そのままログイン

### 方法2: コマンドラインから作成

```bash
docker-compose exec app bash
php oil console
```

PHPコンソールで以下を実行:
```php
Auth::create_user('admin', 'password123', 'admin@example.com', 100);
```

`Ctrl+D`で終了。

---

## 🎯 次のステップ

### アプリを使ってみる

1. ログイン: `http://localhost:8080/auth/login`
2. タスクを作成
3. タスクを編集・削除
4. フィルター機能を試す

### 開発を学ぶ

詳しい学習は以下を参照:

- **[README.md](./README.md)** - プロジェクト全体のドキュメント
- **[TUTORIAL.md](./TUTORIAL.md)** - 2日間の完全ハンズオン講義

---

## 🛑 アプリの停止

```bash
# コンテナを停止
docker-compose down

# コンテナを停止してデータも削除（注意）
docker-compose down -v
```

---

## 🔧 トラブルシューティング

### 問題: ポート8080が既に使用されている

**解決方法:**

`docker-compose.yml` の8080を別のポート（例: 8081）に変更:
```yaml
services:
  web:
    ports:
      - "8081:80"
```

### 問題: "Permission Denied" エラー

**解決方法:**

```bash
docker-compose exec app bash
chmod -R 777 fuel/app/logs fuel/app/cache
exit
```

### 問題: データベースに接続できない

**解決方法:**

```bash
# コンテナを完全に削除して再起動
docker-compose down -v
docker-compose up -d
```

### その他の問題

詳細は [README.md](./README.md) の「トラブルシューティング」セクションを参照してください。

---

## 📚 さらに学ぶ

- [FuelPHP公式ドキュメント](https://fuelphp.com/docs/)
- [PHP公式マニュアル](https://www.php.net/manual/ja/)
- [Docker公式ドキュメント](https://docs.docker.com/)

---

<div align="center">

**それでは、TaskBoardアプリを楽しんでください！ 🎉**

質問があれば [README.md](./README.md) を参照するか、Issueを作成してください。

</div>
