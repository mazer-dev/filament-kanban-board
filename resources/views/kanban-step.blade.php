<div class="kanban-column" data-step-id="{{ $step->id }}">
    <header class="kanban-column-header">
        <h3 class="kanban-column-title">
            {{ $step->{static::$stepTitleAttribute} }}
            <span class="kanban-column-count">
                {{ $step->{static::$cardsRelationship}?->count() ?? 0 }}
            </span>
        </h3>
    </header>

    <div class="kanban-column-content"
         data-sortable-group="{{ $step->id }}"
         data-sortable-handle=".kanban-card">
        @foreach($cards as $card)
            @include(static::$recordView, ['card' => $card])
        @endforeach
    </div>
</div>