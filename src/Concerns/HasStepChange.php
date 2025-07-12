<?php

namespace MazerDev\FilamentKanbanBoard\Concerns;

use Livewire\Attributes\On;

trait HasStepChange
{
    #[On('step-changed')]
    public function stepChanged(int | string $recordId, string $status, array $fromOrderedIds, array $toOrderedIds): void
    {
        $this->onStepChanged($recordId, $status, $fromOrderedIds, $toOrderedIds);
    }

    public function onStepChanged(int | string $recordId, string $status, array $fromOrderedIds, array $toOrderedIds): void
    {
        $this->getEloquentQuery()->find($recordId)->update([
            static::$cardStepAttribute => $status,
        ]);

        if (method_exists(static::$model, 'setNewOrder')) {
            static::$model::setNewOrder($toOrderedIds);
        }
    }

    #[On('sort-changed')]
    public function sortChanged(int | string $recordId, string $status, array $orderedIds): void
    {
        $this->onSortChanged($recordId, $status, $orderedIds);
    }

    public function onSortChanged(int | string $recordId, string $status, array $orderedIds): void
    {
        if (method_exists(static::$model, 'setNewOrder')) {
            static::$model::setNewOrder($orderedIds);
        }
    }
}
