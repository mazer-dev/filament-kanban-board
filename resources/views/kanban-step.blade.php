<div class="kanban-column" data-step-id="{{ $step->id }}">
    <header class="kanban-column-header">
        <h3 class="kanban-column-title">
            {{ $step->{static::$stepTitleAttribute} }}
            <span class="kanban-column-count">
                {{ count($cards) ?? 0 }}
            </span>
            <button class="kanban-toggle-collapse">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                          d="M12.79 5.23a.75.75 0 01-.02 1.06L8.832 10l3.938 3.71a.75.75 0 11-1.04 1.08l-4.5-4.25a.75.75 0 010-1.08l4.5-4.25a.75.75 0 011.06.02z"
                          clip-rule="evenodd"></path>
                </svg>
            </button>
        </h3>
    </header>

    <div class="kanban-column-content"
         data-step-id="{{ $step->id }}"
         data-sortable-handle=".kanban-card"
    >
        @foreach($cards as $card)
            @include(static::$recordView, ['card' => $card])
        @endforeach
    </div>
</div>