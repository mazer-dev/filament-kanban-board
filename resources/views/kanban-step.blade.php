@props(['step'])

<div class="md:w-[24rem] flex-shrink-0 mb-5 md:min-h-full flex flex-col">
    @include(static::$stepHeaderView)

    <div
        data-status-id="{{ $step['id'] }}"
        class="flex flex-col flex-1 gap-2 p-3 bg-gray-200 dark:bg-gray-800 rounded-xl"
    >
        @foreach($step['records'] as $card)
            @include(static::$cardView)
        @endforeach
    </div>
</div>
