<?php

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {
    public string $email = '';

    /**
     * 指定されたメールアドレスにパスワードリセットリンクを送信。
     */
    public function sendPasswordResetLink(): void
    {
        $this->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        Password::sendResetLink($this->only('email'));

        session()->flash('status', 'アカウントが存在する場合、リセットリンクを送信しました。');
    }
}; ?>

<div class="flex flex-col gap-6">
    <x-auth-header title="パスワードをお忘れですか？" description="メールアドレスを入力してパスワードリセットリンクを受け取ってください" />

    <!-- セッション状況 -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <form method="POST" wire:submit="sendPasswordResetLink" class="flex flex-col gap-6">
        <!-- メールアドレス -->
        <flux:input
            wire:model="email"
            label="メールアドレス"
            type="email"
            required
            autofocus
            placeholder="email@example.com"
        />

        <flux:button variant="primary" type="submit" class="w-full" data-test="email-password-reset-link-button">
            パスワードリセットリンクを送信
        </flux:button>
    </form>

    <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-zinc-400">
        <span>または、</span>
        <flux:link :href="route('login')" wire:navigate>ログイン</flux:link>
        <span>に戻る</span>
    </div>
</div>
