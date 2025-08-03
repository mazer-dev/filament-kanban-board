<x-filament-panels::page>
    <div class="kanban-board">
        <div class="kanban-columns" x-data wire:ignore.self>
            @foreach($steps as $step)
                @include(static::$stepView, [
                    'step' => $step,
                    'cards' => $this->getCards($step->id) ?? []
                ])
            @endforeach
        </div>

        <x-filament-actions::modals />
    </div>

    <div wire:ignore>
        @include(static::$scriptsView)
    </div>

    @unless($disableEditModal)
{{--        @include(static::$cardDetailsView)--}}
    @endunless
</x-filament-panels::page>