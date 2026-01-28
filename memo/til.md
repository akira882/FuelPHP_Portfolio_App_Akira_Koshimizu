# About this file
This file is a collection of useful information that I have learned while studying web development.

# ０１２８

## Dockerとは何か
Docker：コンテナ技術を用いて、アプリケーションを簡単に展開するためのツール。
コンテナ型の仮想環境を簡単に作成・配布・実行することができる
コンテナ：OSの最小限の機能を備えた仮想環境

## Dockerのメリット
どのコンピューターでも全く同じ環境でアプリを動かせる

## 仮想環境とは
- 複数のOSを同一のPC上で動作させる環境

## 仮想環境とPCの呼び名
- 仮想環境：ゲストOS
- PC：ホストOS  



## Dockerの利用形態
Dockerは主に以下の3つの形態で利用されます。
1. Docker Desktop
2. Docker Engine
3. Docker Compose

## レンタルサーバーで運用しているサイトではDockerが不要な理由
→レンタルサーバーでは、OSの最小限の機能を備えた仮想環境を提供するため、Dockerは不要。
レンタルサーバーの環境は完成品。下記が最初から揃っている
- Apache（Webサーバー）
- MySQL（データベース）
- PHP（プログラミング言語）

Apache：Webサーバーで、ブラウザからアクセスされたリクエストを処理して、レスポンスを返す役割を果たします。
MySQL：データベースで、データの保存や取得を行う役割を果たします。
PHP：プログラミング言語で、Webページの動的な要素を生成する役割を果たします。

多くの会社は、レンタルサーバーで運用しているサイトを、Dockerで展開する形で運用しています。


# コアテック社の技術スタック
【バックエンド言語】PHP
【フロントエンド言語】JavaScript,TypeScript
【FW】FuelPHP, Laravel, CakePHP, React
→バックエンド言語は主にPHPを使用しています。  
FWは主にFuelPHPを使用しています。 Reactはフロントエンド言語として主に使用しています。

【DB】MySQL（AWS Aurora）, Redis, DynamoDB
→DBは主にMySQLを使用しています。 AWS AuroraはDBとして主に使用しています。

【本番／ステージング環境】AWS(EC2, ECS, S3, CloudFront, Lambda, Aurora, Amplify, WorkMail, MediaConvert, ElasticTranscoder, Athena, Simple Notification Service)
→本番環境は主にAWSを使用しています。

【ローカル環境】Docker
→ローカル環境は主にDockerを使用しています。
※ローカル環境とは、開発環境をPC上で実行する環境です。

【CI/CD】jenkins
→CI/CDは主にjenkinsを使用しています。

用語：
- CI/CDとは何か？ (Continuous Integration / Continuous Delivery & Deployment)
ソフトウェアの変更を継続的にテストし、自動的に本番環境へ反映させることで、開発の効率化と品質向上を実現する手法。 

- CI（継続的インテグレーション）: 開発者がコードを頻繁に共有リポジトリ（Gitなど）にマージし、自動ビルドと自動テストを行うこと。バグを早期に発見できる。

- CD（継続的デリバリー）: CIでテストされたコードを、いつでも本番環境にリリースできる状態（または自動でステージング環境へ反映）に保つこと。
- CD（継続的デプロイメント）: テストを通過したコードを、人間の介入なしで自動的に本番環境へデプロイすること。 

- Jenkins:Javaベースの、オープンソースの自動化サーバー。CI/CDを実現するための最も代表的なツール。 
ビルド・自動テスト・デプロイの統合: コードのコミットを検知し、自動的にビルドやテストタスクを実行する。
豊富なプラグイン: 1,800以上のプラグイン（Jenkins Plugins）が利用可能で、GitHub、Docker、Kubernetesなど多様なツールと連携できる。

 分散ビルド: 大規模なプロジェクトでも、複数のサーバーに負荷を分散して高速に処理できる。
- 実績: 世界的に広く導入されている（170万ユーザー以上）。 

■Jenkinsで実現できる自動化フロー
Jenkinsの仕事：Pushした後に、全自動でエラーがないかチェックすること

- コード変更: 開発者がGitHubへコードをプッシュ。
- 自動ビルド: Jenkinsが変更を検知し、自動でビルドを開始。
- 自動テスト: 単体テスト・結合テストなどを自動実行。
- 自動デプロイ: テストに合格したら、Dockerコンテナやサーバーへ自動デプロイ。
- 通知: 失敗時はSlackやメールで通知。 

Jenkinsを利用することで、手動で行っていた作業を自動化し、人的ミスの削減と開発サイクルの短縮を実現する。 


【プロジェクト管理】Git, Backlog, GoogleSpreadsheet , Codecommit
- Git: バージョン管理ツール。コードの変更履歴を管理し、チームで協力開発を可能にする。
- Backlog: プロジェクト管理ツール。タスクの管理、printsの作成、コミュニケーションを可能にする。
- readsheet: プロジェクト管理ツール。タスクの管理、スprintsの作成、コミュニケーションを可能にする。
- Codecommit: コードの変更履歴を管理し、チームで協力開発を可能にする。
