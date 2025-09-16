<?php

use App\Models\Product;
use App\Models\Category;
use Livewire\Volt\Component;
use Livewire\Attributes\Layout;

new #[Layout('components.layouts.app')] class extends Component {
    public Product $product;
    public $name = '';
    public $description = '';
    public $price = '';
    public $stock_quantity = '';
    public $category = '';
    public $is_active = true;

    public function mount(Product $product): void
    {
        $this->product = $product;
        $this->name = $product->name;
        $this->description = $product->description;
        $this->price = $product->price;
        $this->stock_quantity = $product->stock_quantity;
        $this->category = $product->category?->name ?? '';
        $this->is_active = $product->is_active;
    }

    public function with(): array
    {
        return [];
    }

    public function save(): void
    {
        try {
            $validated = $this->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'price' => 'required|integer|min:0',
                'stock_quantity' => 'required|integer|min:0',
                'category' => 'nullable|string|max:255',
                'is_active' => 'boolean',
            ]);

            $category = null;
            if (!empty($validated['category'])) {
                $category = Category::firstOrCreate([
                    'name' => $validated['category']
                ], [
                    'description' => null,
                    'slug' => \Illuminate\Support\Str::slug($validated['category']),
                    'is_active' => true
                ]);
            }

            $this->product->update([
                'name' => $validated['name'],
                'description' => $validated['description'],
                'price' => $validated['price'],
                'stock_quantity' => $validated['stock_quantity'],
                'category_id' => $category?->id,
                'is_active' => $validated['is_active'],
            ]);

            session()->flash('message', '製品が正常に更新されました。');
            $this->redirect('/products', navigate: true);
        } catch (\Exception $e) {
            session()->flash('error', '製品の更新に失敗しました: ' . $e->getMessage());
        }
    }
}; ?>

<div class="flex flex-col gap-6">
    <!-- ヘッダー -->
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">製品を編集</h1>
        <a href="{{ route('products.index') }}"
           class="inline-flex items-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-md hover:bg-gray-700 transition-colors"
           wire:navigate>
            戻る
        </a>
    </div>

    <!-- フォーム -->
    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
        <form wire:submit="save" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- 製品名 -->
                <div>
                    <flux:input
                        wire:model="name"
                        label="製品名"
                        type="text"
                        required
                        placeholder="製品名を入力してください"
                    />
                </div>

                <!-- 価格 -->
                <div>
                    <flux:input
                        wire:model="price"
                        label="価格"
                        type="number"
                        min="0"
                        required
                        placeholder="0"
                    />
                </div>

                <!-- 在庫数量 -->
                <div>
                    <flux:input
                        wire:model="stock_quantity"
                        label="在庫数量"
                        type="number"
                        min="0"
                        required
                        placeholder="0"
                    />
                </div>

                <!-- カテゴリ -->
                <div>
                    <flux:input
                        wire:model="category"
                        label="カテゴリ"
                        type="text"
                        placeholder="カテゴリ名を入力してください（任意）"
                    />
                </div>

                <!-- ステータス -->
                <div class="flex items-center">
                    <flux:checkbox
                        wire:model="is_active"
                        label="アクティブ"
                    />
                </div>
            </div>

            <!-- 説明 -->
            <div>
                <flux:textarea
                    wire:model="description"
                    label="製品説明"
                    placeholder="製品の詳細説明を入力してください（任意）"
                    rows="4"
                />
            </div>

            <!-- ボタン -->
            <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('products.index') }}"
                   class="inline-flex items-center px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-white text-sm font-medium rounded-md hover:bg-gray-400 dark:hover:bg-gray-500 transition-colors"
                   wire:navigate>
                    キャンセル
                </a>
                <flux:button variant="primary" type="submit">
                    製品を更新
                </flux:button>
            </div>
        </form>
    </div>
</div>