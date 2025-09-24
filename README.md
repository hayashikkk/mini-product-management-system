# Mini Product Management System

## 概要

Laravel + Livewire で構築したシンプルな商品管理システムです。商品の登録・編集・削除などの基本的なCRUD操作を実装しています。

## 技術スタック

- **Backend**: Laravel 12
- **Frontend**: Livewire 3 + Volt
- **Styling**: Tailwind CSS v4 + Flux UI
- **Database**: MySQL (XAMPP)
- **Build Tool**: Vite

## 主な機能

- ✅ 商品管理（登録・編集・削除）
- ✅ 商品一覧表示・検索
- ✅ 在庫管理
- ✅ ユーザー認証
- ✅ ダッシュボード
- ✅ レスポンシブデザイン

## セットアップ

### 必要要件
- PHP 8.2+
- Composer
- Node.js & npm

### インストール手順

```bash
# 依存関係のインストール
composer install
npm install

# 環境設定
cp .env.example .env
php artisan key:generate

# データベース設定（MySQL）
# XAMPPでMySQLを起動してからデータベースを作成
php artisan migrate

# フロントエンドビルド
npm run build

# 開発サーバー起動
php artisan serve
```

## 開発

```bash
# 開発環境の起動（並行実行）
composer dev

# テスト実行
composer test

# コード整形
vendor/bin/pint
```

## スクリーンショット

ダッシュボードで商品の在庫状況を一覧表示し、商品管理画面から簡単に商品情報の登録・編集が可能です。
