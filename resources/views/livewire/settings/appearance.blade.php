<?php

use Livewire\Volt\Component;

new class extends Component {
    //
}; ?>

<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout heading="外観" subheading="アカウントの外観設定を更新">
        <flux:radio.group x-data variant="segmented" x-model="$flux.appearance">
            <flux:radio value="light" icon="sun">ライト</flux:radio>
            <flux:radio value="dark" icon="moon">ダーク</flux:radio>
            <flux:radio value="system" icon="computer-desktop">システム</flux:radio>
        </flux:radio.group>
    </x-settings.layout>
</section>
