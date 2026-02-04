#!/bin/sh

# データベースのマイグレーション（テーブル作成）を実行
echo "Running forced database setup..."
php force_setup_db.php

# Apacheをフォアグラウンドで起動
echo "Starting Apache..."
apache2-foreground
