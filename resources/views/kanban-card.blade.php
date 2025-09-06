<div class="kanban-card"
     data-card-id="{{ $card->id }}"
{{--     wire:click="viewCardDetails({{ $card->id }})"--}}
     :key="{{ $card->id }}"
>

    <div class="kanban-card-content">
        {{ $this->renderCardInfoList($card, new \Filament\Schemas\Schema($this)) }}
    </div>

    <footer class="kanban-card-footer">
        @foreach($this->getCardFooterActions() as $action)
            {{ $this->$action }}
        @endforeach
    </footer>
</div>