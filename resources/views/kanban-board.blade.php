<x-filament-panels::page>
    <div class="kanban-board">
        <div class="kanban-columns" x-data wire:ignore.self>
            @foreach($steps as $step)
                @include(static::$stepView, [
                    'step' => $step,
                    'cards' => $step->{static::$cardsRelationship} ?? []
                ])
            @endforeach
        </div>
    </div>

    <div wire:ignore>
        @include(static::$scriptsView)
    </div>

    @unless($disableEditModal)
{{--        @include('kanban.partials.edit-modal')--}}
    @endunless
</x-filament-panels::page>