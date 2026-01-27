# 学習内容アウトプット

# Vimとは何か？
→キーボードだけで操作する、Linux/Unix用の軽量に動くテキストエディタ   

# Git
## Gitで追跡したファイルを.gitignoreファイルに追加、Gitの履歴リセット
- .gitignoreファイルにgitから無視させるファイル名を追加
> .プロジェクト直下にある、『.gitignoreファイル』

## Gitコンフリクトの解消方法
大前提：今後は作業を実施する前に、必ずgit pull origin mainを実行する
問題の概要：私が作成したMemo.md

### 問題の解決方法
1. GitHubの変更を取り込む: git pull origin main --no-edit を実行します。これにより、自動的に歴史が合流します。
2. 状態を確認する: git status で正しく合流できたか確認します。
3. GitHubへ送信する: git push origin main を実行し、GitHubを最新の状態にします。


# 用語
- 仮想環境（Docker,Xampp,Mampp）︰ローカルにサーバーを作成し、PC上で動かせる環境

