<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * ユーザー登録リクエストを処理します。
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered(($user = User::create($validated))));

        Auth::login($user);

        $this->redirectIntended(route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="flex flex-col gap-6">
    <x-auth-header title="アカウントを作成" description="アカウント作成のために以下の情報を入力してください" />

    <!-- セッション状況 -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <form method="POST" wire:submit="register" class="flex flex-col gap-6">
        <!-- 名前 -->
        <flux:input
            wire:model="name"
            label="名前"
            type="text"
            required
            autofocus
            autocomplete="name"
            placeholder="フルネーム"
        />

        <!-- メールアドレス -->
        <flux:input
            wire:model="email"
            label="メールアドレス"
            type="email"
            required
            autocomplete="email"
            placeholder="email@example.com"
        />

        <!-- パスワード -->
        <flux:input
            wire:model="password"
            label="パスワード"
            type="password"
            required
            autocomplete="new-password"
            placeholder="パスワード"
            viewable
        />

        <!-- パスワード確認 -->
        <flux:input
            wire:model="password_confirmation"
            label="パスワード確認"
            type="password"
            required
            autocomplete="new-password"
            placeholder="パスワード確認"
            viewable
        />

        <div class="flex items-center justify-end">
            <flux:button type="submit" variant="primary" class="w-full" data-test="register-user-button">
                アカウント作成
            </flux:button>
        </div>
    </form>

    <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-zinc-600 dark:text-zinc-400">
        <span>既にアカウントをお持ちですか？</span>
        <flux:link :href="route('login')" wire:navigate>ログイン</flux:link>
    </div>
</div>
