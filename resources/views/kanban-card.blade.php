<div class="kanban-card"
     data-card-id="{{ $card->id }}"
{{--     wire:click="viewCardDetails({{ $card->id }})"--}}
     :key="{{ $card->id }}"
>

    @if($card->priority)
        <div class="kanban-card-priority kanban-card-priority--{{ $card->priority }}"></div>
    @endif

    <div class="kanban-card-content">
        {{ $this->renderCardInfoList($card, new \Filament\Schemas\Schema($this)) }}

        @if($card->description)
            <p class="kanban-card-description">
                {{ Str::limit($card->description, 100) }}
            </p>
        @endif
    </div>

    @if($card->tags?->count() > 0)
        <div class="kanban-card-tags">
            @foreach($card->tags as $tag)
                <span class="kanban-card-tag" style="background-color: {{ $tag->color }}">
                    {{ $tag->name }}
                </span>
            @endforeach
        </div>
    @endif

    <footer class="kanban-card-footer">
{{--        @foreach($this->getCardFooterActions() as $action)--}}
{{--            @ds($card->id)--}}
{{--            {{ ($action)(['cardId' => $card->id]) }}--}}
{{--        @endforeach--}}

        @if($card->due_date)
            <span class="kanban-card-date {{ $card->is_overdue ? 'kanban-card-date--overdue' : '' }}">
                <x-heroicon-m-calendar class="kanban-card-date-icon"/>
                {{ $card->due_date->format('d/m') }}
            </span>
        @endif

        @if($card->assignee)
            <img src="{{ $card->assignee->avatar_url }}"
                 alt="{{ $card->assignee->name }}"
                 class="kanban-card-avatar">
        @endif
    </footer>
</div>