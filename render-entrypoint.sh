#!/bin/sh

# データベースのマイグレーション（テーブル作成）を実行
echo "Running database migrations..."
php oil refine migrate --all
php oil refine migrate --packages=auth

# Apacheをフォアグラウンドで起動
echo "Starting Apache..."
apache2-foreground
