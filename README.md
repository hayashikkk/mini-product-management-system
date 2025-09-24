# 商品管理システム

Laravelで作った商品管理アプリです。商品の登録・編集・削除ができます。

## 使った技術

- Laravel 11
- MySQL
- Docker
- Tailwind CSS

## 実装機能

- 商品の登録・編集・削除
- 商品一覧の表示
- ユーザー登録・ログイン
- レスポンシブ対応

## セットアップ

```bash
# 依存関係のインストール
composer install
npm install

# 環境設定
cp .env.example .env
php artisan key:generate

# データベース
php artisan migrate

# 開発サーバー起動
php artisan serve
```

## Docker での起動

```bash
docker-compose up -d
```

ブラウザで http://localhost:8000 にアクセス

## 学んだこと

- Laravel の基本的な使い方
- データベース設計
- Docker の基本
- GitHub Actions での自動デプロイ

初心者向けの学習プロジェクトとして作成しました。