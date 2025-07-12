<x-filament-panels::page>
        <div x-data wire:ignore.self class="flex overflow-x-auto overflow-y-hidden gap-4 pb-4">
        <div class="flex-2">a</div>
        <div>b</div>
        <div>ca</div>
    </div>

            @foreach($steps as $step)
            @include(static::$stepView)
        @endforeach

    <div wire:ignore>
        @include(static::$scriptsView)
    </div>

    @unless($disableEditModal)
        <x-filament-kanban-board::edit-card-modal/>
    @endunless
</x-filament-panels::page>
