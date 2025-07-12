<?php

namespace MazerDev\FilamentKanbanBoard\Pages;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use MazerDev\FilamentKanbanBoard\Concerns\HasEditCardModal;
use MazerDev\FilamentKanbanBoard\Concerns\HasStepChange;
use MazerDev\FilamentKanbanBoard\FilamentKanbanBoardPlugin;
use UnitEnum;

class KanbanBoardPage extends Page implements HasForms
{
    use InteractsWithForms;
    use HasEditCardModal;
    use HasStepChange;

    protected static string|null|\BackedEnum $navigationIcon = 'heroicon-o-view-columns';
    protected static string $recordView = FilamentKanbanBoardPlugin::ID.'::kanban-card';
    protected static string $stepView = FilamentKanbanBoardPlugin::ID.'::kanban-step';
    protected static string $stepHeaderView = FilamentKanbanBoardPlugin::ID.'::kanban-step-header';
    protected static string $scriptsView = FilamentKanbanBoardPlugin::ID.'::kanban-scripts';

    protected static string $model;
    protected static string $stepsModel;

    protected static string $cardTitleAttribute = 'title';
    protected static string $cardStepAttribute = 'step';
    
    protected static string $stepTitleAttribute = 'title';
    protected static string $stepIconAttribute = 'icon';
    protected static string $stepDescriptionAttribute = 'description';
    
    protected static string $defaultStepIcon = 'heroicon-o-rectangle-stack';

    protected string $view = FilamentKanbanBoardPlugin::ID.'::kanban-board';

    /**
     * Return the list of the steps to create the kanban board columns.
     *
     * @return Collection
     */
    protected function getSteps(): Collection
    {
        $steps = static::$stepsModel::steps();

        return $steps;
    }

    protected function getCards(): Collection
    {
        return $this->getEloquentQuery()
            ->when(method_exists(static::$model, 'scopeOrdered'), fn ($query) => $query->ordered())
            ->get();
    }

    protected function getViewData(): array
    {
        $cards = $this->getCards();
        $steps = $this->getSteps()
            ->map(function ($step) use ($cards) {
                $step['records'] = $this->filterCardsByStep($cards, $step);

                return $step;
            });

        return [
            'steps' => $steps,
        ];
    }

    protected function filterCardsByStep(Collection $cards, array|Model $step): array
    {
        $stepIsCastToEnum = $this->isCardStepAttributeEnum($cards->first());

        $filter = $stepIsCastToEnum
            ? static::$stepsModel::from($step['id'])
            : $step['id'];

        return $cards->where(static::$cardStepAttribute, $filter)->all();
    }

    protected function getEloquentQuery(): Builder
    {
        return static::$model::query();
    }

    protected function isCardStepAttributeEnum($card): bool
    {
        return $card?->getAttribute(static::$cardStepAttribute) instanceof UnitEnum;
    }
}
