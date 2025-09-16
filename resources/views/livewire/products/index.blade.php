<?php

use App\Models\Product;
use App\Models\Category;
use Livewire\Volt\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

new #[Layout('components.layouts.app')] class extends Component {
    use WithPagination;

    public $sortBy = 'product_id';
    public $sortDirection = 'asc';

    public function with(): array
    {
        $query = Product::with('category')
            ->orderBy($this->sortBy, $this->sortDirection);

        return [
            'products' => $query->paginate(10),
        ];
    }

    public function deleteProduct($productId): void
    {
        Product::findOrFail($productId)->delete();
        $this->dispatch('product-deleted');
    }

    public function toggleStatus($productId): void
    {
        $product = Product::findOrFail($productId);
        $product->update(['is_active' => !$product->is_active]);
    }

    public function sortByField($field): void
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
    }

}; ?>

<div class="flex flex-col gap-6">
        <!-- ヘッダー -->
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">製品管理</h1>
            <flux:button
                variant="primary"
                size="sm"
                wire:navigate
                href="{{ route('products.create') }}"
            >
                新しい製品を追加
            </flux:button>
        </div>


        <!-- 製品テーブル -->
        <div class="overflow-hidden rounded-lg border border-gray-200 dark:border-gray-700">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-800">
                        <tr>
                            <th class="px-6 py-3 text-left">
                                <flux:button
                                    variant="ghost"
                                    size="sm"
                                    wire:click="sortByField('name')"
                                    class="font-medium text-gray-500 dark:text-gray-400"
                                >
                                    製品名
                                    @if($sortBy === 'name')
                                        <flux:icon.chevron-up class="ml-1 size-4 {{ $sortDirection === 'desc' ? 'rotate-180' : '' }}" />
                                    @endif
                                </flux:button>
                            </th>
                            <th class="px-6 py-3 text-left">
                                <flux:button
                                    variant="ghost"
                                    size="sm"
                                    wire:click="sortByField('product_id')"
                                    class="font-medium text-gray-500 dark:text-gray-400"
                                >
                                    商品ID
                                    @if($sortBy === 'product_id')
                                        <flux:icon.chevron-up class="ml-1 size-4 {{ $sortDirection === 'desc' ? 'rotate-180' : '' }}" />
                                    @endif
                                </flux:button>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                カテゴリ
                            </th>
                            <th class="px-6 py-3 text-left">
                                <flux:button
                                    variant="ghost"
                                    size="sm"
                                    wire:click="sortByField('price')"
                                    class="font-medium text-gray-500 dark:text-gray-400"
                                >
                                    価格
                                    @if($sortBy === 'price')
                                        <flux:icon.chevron-up class="ml-1 size-4 {{ $sortDirection === 'desc' ? 'rotate-180' : '' }}" />
                                    @endif
                                </flux:button>
                            </th>
                            <th class="px-6 py-3 text-left">
                                <flux:button
                                    variant="ghost"
                                    size="sm"
                                    wire:click="sortByField('stock_quantity')"
                                    class="font-medium text-gray-500 dark:text-gray-400"
                                >
                                    在庫
                                    @if($sortBy === 'stock_quantity')
                                        <flux:icon.chevron-up class="ml-1 size-4 {{ $sortDirection === 'desc' ? 'rotate-180' : '' }}" />
                                    @endif
                                </flux:button>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                ステータス
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                操作
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-700">
                        @forelse($products as $product)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $product->name }}
                                            </div>
                                            @if($product->description)
                                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                                    {{ Str::limit($product->description, 50) }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                                    {{ $product->product_id }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                    {{ $product->category?->name ?? '未分類' }}
                                </td>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $product->formatted_price }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                                    <div class="flex items-center">
                                        <span class="{{ $product->isLowStock() ? 'text-red-600 dark:text-red-400' : 'text-gray-900 dark:text-white' }}">
                                            {{ $product->stock_quantity }}
                                        </span>
                                        @if($product->isLowStock())
                                            <flux:badge variant="danger" size="sm" class="ml-2">
                                                在庫少
                                            </flux:badge>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <flux:button
                                        variant="{{ $product->is_active ? 'filled' : 'danger' }}"
                                        size="sm"
                                        wire:click="toggleStatus({{ $product->id }})"
                                    >
                                        {{ $product->is_active ? 'アクティブ' : '停止中' }}
                                    </flux:button>
                                </td>
                                <td class="px-6 py-4 text-right text-sm font-medium">
                                    <div class="flex items-center justify-end gap-2">
                                        <flux:button
                                            variant="ghost"
                                            size="sm"
                                            wire:navigate
                                            href="{{ route('products.edit', $product) }}"
                                        >
                                            編集
                                        </flux:button>
                                        <flux:button
                                            variant="danger"
                                            size="sm"
                                            wire:click="deleteProduct({{ $product->id }})"
                                            wire:confirm="この製品を削除してもよろしいですか？"
                                        >
                                            削除
                                        </flux:button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="text-gray-500 dark:text-gray-400">
                                        <flux:icon.cube class="mx-auto size-12 mb-4" />
                                        <h3 class="text-lg font-medium mb-2">製品が見つかりません</h3>
                                        <p class="text-sm">新しい製品を追加してください。</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ペジネーション -->
        <div class="flex items-center justify-between">
            <div class="text-sm text-gray-700 dark:text-gray-300">
                {{ $products->total() }} 件中 {{ $products->firstItem() }}-{{ $products->lastItem() }} 件を表示
            </div>
            <div>
                {{ $products->links() }}
            </div>
        </div>
    </div>