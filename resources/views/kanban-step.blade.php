@php
    use Filament\Support\Icons\Heroicon;
    use Filament\Support\Colors\Color;
@endphp

<div class="kanban-column" data-step-id="{{ $step->id }}">
    <header class="kanban-column-header">
        <h3 class="kanban-column-title">
            {{ $step->{static::$stepTitleAttribute} }}
        </h3>

        <x-filament::badge>
            {{ count($cards) ?? 0 }}
        </x-filament::badge>

        @if( $step[static::$stepDescriptionAttribute] )
            <x-filament::icon-button
                    :icon="Heroicon::InformationCircle"
                    color="gray"
                    tooltip="{{ $step[static::$stepDescriptionAttribute] }}"
            />
        @endif

        <x-filament::icon-button
            :icon="Heroicon::ChevronDoubleLeft"
            color="gray"
            :title="__('Collapse') "
        />

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