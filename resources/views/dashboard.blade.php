@php
use App\Models\Product;

$totalProducts = Product::count();
$inStockProducts = Product::where('stock_quantity', '>', 10)->count();
$lowStockProducts = Product::where('stock_quantity', '>', 0)->where('stock_quantity', '<=', 10)->count();
$outOfStockProducts = Product::where('stock_quantity', 0)->count();
@endphp

<x-layouts.app title="ダッシュボード">
    <div class="max-w-7xl mx-auto py-6 px-4">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-8">ダッシュボード</h1>

        <!-- 統計カード -->
        <div class="bg-white dark:bg-gray-600 rounded-lg shadow p-6 border border-gray-200 dark:border-gray-700">
            <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-6">商品統計</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <!-- 総商品数 -->
                <div class="text-center">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">総商品数</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ number_format($totalProducts) }}</p>
                </div>

                <!-- 在庫有 -->
                <div class="text-center">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">在庫有</p>
                    <p class="text-2xl font-bold text-green-600 dark:text-green-400 mt-1">{{ number_format($inStockProducts) }}</p>
                </div>

                <!-- 在庫少 -->
                <div class="text-center">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">在庫少</p>
                    <p class="text-2xl font-bold text-yellow-600 dark:text-yellow-400 mt-1">{{ number_format($lowStockProducts) }}</p>
                </div>

                <!-- 在庫なし -->
                <div class="text-center">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">在庫なし</p>
                    <p class="text-2xl font-bold text-red-600 dark:text-red-400 mt-1">{{ number_format($outOfStockProducts) }}</p>
                </div>
            </div>
        </div>

        <!-- クイックアクション -->
        <div class="mt-8">
            <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">クイックアクション</h2>
            <div class="flex gap-4">
                <a href="{{ route('products.create') }}"
                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition-colors">
                    新しい製品を追加
                </a>
                <a href="{{ route('products.index') }}"
                   class="inline-flex items-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-md hover:bg-gray-700 transition-colors">
                    製品一覧を見る
                </a>
            </div>
        </div>

        @if($lowStockProducts > 0 || $outOfStockProducts > 0)
        <!-- アラート -->
        <div class="mt-8">
            <div class="bg-yellow-50 dark:bg-yellow-900/10 border border-yellow-200 dark:border-yellow-700 rounded-lg p-4">
                <div>
                        <h3 class="text-sm font-medium text-yellow-800 dark:text-yellow-200">在庫アラート</h3>
                        <div class="mt-1 text-sm text-yellow-700 dark:text-yellow-300">
                            @if($lowStockProducts > 0)
                                <p>{{ $lowStockProducts }}個の製品の在庫が少なくなっています。</p>
                            @endif
                            @if($outOfStockProducts > 0)
                                <p>{{ $outOfStockProducts }}個の製品が在庫切れです。</p>
                            @endif
                        </div>
                        <div class="mt-3">
                            <a href="{{ route('products.index') }}"
                               class="text-sm text-yellow-800 dark:text-yellow-200 underline hover:text-yellow-900 dark:hover:text-yellow-100">
                                在庫を確認する
                            </a>
                        </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</x-layouts.app>