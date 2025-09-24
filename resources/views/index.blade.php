<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>製品管理システム</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 dark:bg-gray-900 min-h-screen">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="max-w-md w-full">
            <!-- ロゴ・タイトル -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
                    製品管理システム
                </h1>
                <p class="text-gray-600 dark:text-gray-400">
                    シンプルで使いやすい商品管理
                </p>
            </div>

            <!-- カード -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 p-8">
                <div class="text-center space-y-6">
                    <div class="space-y-4">
                        <!-- ログインボタン -->
                        <a href="{{ route('login') }}"
                           class="w-full inline-flex justify-center items-center px-6 py-3 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition-colors">
                            ログイン
                        </a>

                        <!-- 登録ボタン -->
                        <a href="{{ route('register') }}"
                           class="w-full inline-flex justify-center items-center px-6 py-3 bg-gray-600 text-white text-sm font-medium rounded-md hover:bg-gray-700 transition-colors">
                            新規登録
                        </a>
                    </div>

                    <!-- 説明 -->
                    <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            商品の登録・管理・在庫確認ができます
                        </p>
                    </div>
                </div>
            </div>