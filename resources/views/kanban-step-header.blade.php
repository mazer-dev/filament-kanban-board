@php
    use Illuminate\View\ComponentAttributeBag;
@endphp

@php
    $icon = static::$defaultStepIcon;
    if (isset($step[static::$stepIconAttribute])) {
        $icon = $step[static::$stepIconAttribute];
    }
@endphp

<div class="fi-kanban-column-header ">
    <h3 class="font-semibold text-lg text-gray-700 dark:text-gray-300 flex items-center gap-2">
        @if( $icon )
            {{ \Filament\Support\generate_icon_html($icon, ['class' => 'w-5 h-5']) }}
        @endif

        {{ $step[static::$stepTitleAttribute] }}
    </h3>

    <span class="bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-400 text-sm px-2 py-1 rounded-full">
        {{ count($step['records']) }}
    </span>
</div>

@if( $step[static::$stepDescriptionAttribute] )
    <p class="text-sm text-gray-500 dark:text-gray-400 mb-4 px-2">
        {{ $step[static::$stepDescriptionAttribute] }}
    </p>
@endif
