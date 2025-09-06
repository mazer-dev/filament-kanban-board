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
    </div>

    @include(static::$scriptsView)

    <x-filament-actions::modals />
</x-filament-panels::page>

