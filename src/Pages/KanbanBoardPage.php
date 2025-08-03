<?php

namespace MazerDev\FilamentKanbanBoard\Pages;

use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\CreateAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Text;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use MazerDev\FilamentKanbanBoard\Concerns\HasEditCardModal;
use MazerDev\FilamentKanbanBoard\Concerns\HasStepChange;
use MazerDev\FilamentKanbanBoard\FilamentKanbanBoardPlugin;
use UnitEnum;

class KanbanBoardPage extends Page implements HasForms, HasActions
{
    use InteractsWithForms;
    use InteractsWithActions;
    use HasEditCardModal;
    use HasStepChange;

    protected static string|null|\BackedEnum $navigationIcon = 'heroicon-o-view-columns';
    protected static string $recordView = FilamentKanbanBoardPlugin::ID . '::kanban-card';
    protected static string $stepView = FilamentKanbanBoardPlugin::ID . '::kanban-step';
    protected static string $stepHeaderView = FilamentKanbanBoardPlugin::ID . '::kanban-step-header';
    protected static string $scriptsView = FilamentKanbanBoardPlugin::ID . '::kanban-scripts';
    protected static string $cardDetailsView = FilamentKanbanBoardPlugin::ID . '::components.view-card-modal';

    protected static string $stepModel;
    protected static string $stepsRelationship = 'cards';

    protected static string $cardsModel;
    protected static string $cardsRelationship = 'cards';

    protected static string $cardTitleAttribute = 'title';
    protected static string $cardStepAttribute = 'step';
    protected static string $cardStepFKAttribute = 'step_id';
    protected static string $cardOrderAttribute = 'order';
    protected static string $cardStatusAttribute = 'status';
    protected static string $cardLeadRelationship = 'lead';
    protected static string $cardLeadNameAttribute = 'name';
    protected static string $cardAssigneeRelationship = 'user';
    protected static string $cardAssigneeNameAttribute = 'name';

    protected static string $stepTitleAttribute = 'title';
    protected static string $stepIconAttribute = 'icon';
    protected static string $stepDescriptionAttribute = 'description';

    protected static string $defaultStepIcon = 'heroicon-o-rectangle-stack';

    protected string $view = FilamentKanbanBoardPlugin::ID . '::kanban-board';

    public ?array $cardData = [];

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Criar novo card')
                ->translateLabel()
                ->icon('heroicon-o-plus')
                ->modalHeading('Criar novo card')
                ->model(static::$cardsModel)
                ->schema(fn() => $this->cardForm(new Schema($this))
                    ->model(static::$cardsModel)
                    ->statePath('cardData')
                    ->fill()
                ),
        ];
    }
    
    public function getCardFooterAction(): Action
    {
        return
            Action::make('teste')
                ->label('View Card')
                ->icon('heroicon-o-eye')
                ->modalHeading('View Card Details')
                ->record(fn($record) => $record)
        ;
    }

    /**
     * Return the list of the steps to create the kanban board columns.
     *
     * @return Collection
     */
    protected function getSteps(): Collection
    {
        $steps = static::$stepModel::steps();

        return $steps;
    }

    protected function getCards(): Collection
    {
        return $this->getEloquentQuery()
            ->when(method_exists(static::$cardsModel, 'scopeOrdered'), fn($query) => $query->ordered())
            ->orderBy(static::$cardOrderAttribute)
            ->get();
    }

    protected function renderCardInfoList($card, Schema $schema): Schema
    {
        return $this->cardInfoList($schema)
            ->record($card);
    }

    public function cardInfoList(Schema $schema): array|Schema
    {
        return $schema
            ->columns(1)
            ->schema([
                Text::make('Override cardInfoList method to render your cards')
            ]);
    }

    public function cardForm(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->schema($this->getEditModalFormSchema($this->editModalRecordId))
            ->statePath('editModalFormState')
            ->model(
                $this->editModalRecordId ? static::$cardsModel::find($this->editModalRecordId) : static::$cardsModel
            );
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
        return static::$cardsModel::query();
    }

    protected function isCardStepAttributeEnum($card): bool
    {
        return $card?->getAttribute(static::$cardStepAttribute) instanceof UnitEnum;
    }

    public function moveCard(int $cardId, int $stepId, int $orderIndex): void
    {
        ds($cardId, $stepId, $orderIndex);
        $card = static::$cardsModel::find($cardId);

        if ($card) {
            if ($card->{static::$cardStepFKAttribute} != $stepId) {
                $card->{static::$cardStepFKAttribute} = $stepId;
            }
            
            if ($card->{static::$cardOrderAttribute} != $orderIndex) {
                $card->{static::$cardOrderAttribute} = $orderIndex;
            }
            
            if ($card->isDirty()) {
                $card->save();                
            }
        }
    }
    
    public function viewCardDetails(int $recordId): void
    {
        $this->editModalRecordId = $recordId;

        $this->form->fill($this->getEditModalCardData($recordId, $this->cardData));

        $this->dispatch('open-modal', id: 'kanban--view-card-modal');
    }

}
