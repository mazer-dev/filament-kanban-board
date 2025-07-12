@php
    use Illuminate\View\ComponentAttributeBag;
@endphp

@php
    $icon = static::$defaultStepIcon;
    if (isset($step[static::$stepIconAttribute])) {
        $icon = $step[static::$stepIconAttribute];
    }

@endphp

<h3 class="mb-2 px-4 font-semibold text-lg text-gray-400">
    @if( $icon )
        {{ \Filament\Support\generate_icon_html($icon) }}
    @endif

    {{ $step[static::$stepTitleAttribute] }}
    @if( $step[static::$stepDescriptionAttribute] )
        {{ $step[static::$stepDescriptionAttribute] }}
    @endif
</h3>
