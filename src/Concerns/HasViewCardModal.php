<?php

namespace MazerDev\FilamentKanbanBoard\Concerns;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;

trait HasViewCardModal
{
    public bool $disableEditModal = false;

    public ?array $editModalFormState = [];

    public null | int | string $editModalRecordId = null;

    protected string $editModalTitle = 'Edit Card';

    protected bool $editModalSlideOver = false;

    protected string $editModalWidth = '2xl';

    protected string $editModalSaveButtonLabel = 'Save';

    protected string $editModalCancelButtonLabel = 'Cancel';

    public function cardClicked(int | string $recordId, array $data): void
    {
        $this->editModalRecordId = $recordId;

        $this->form->fill($this->getEditModalCardData($recordId, $data));

        $this->dispatch('open-modal', id: 'kanban--edit-card-modal');
    }

    public function editModalFormSubmitted(): void
    {
        $this->editCard($this->editModalRecordId, $this->form->getState(), $this->editModalFormState);

        $this->editModalRecordId = null;
        $this->form->fill();

        $this->dispatch('close-modal', id: 'kanban--edit-card-modal');
    }

//    public function form(Form $form): Form
//    {
//        return $form
//            ->schema($this->getEditModalFormSchema($this->editModalRecordId))
//            ->statePath('editModalFormState')
//            ->model($this->editModalRecordId ? static::$model::find($this->editModalRecordId) : static::$model);
//    }

    protected function getEditModalCardData(int | string $recordId, array $data): array
    {
        return $this->getEloquentQuery()->find($recordId)->toArray();
    }

    protected function editCard(int | string $recordId, array $data, array $state): void
    {
        $this->getEloquentQuery()->find($recordId)->update($data);
    }

    protected function getEditModalFormSchema(null | int | string $recordId): array
    {
        return [
            TextInput::make(static::$cardTitleAttribute),
        ];
    }

    protected function getEditModalTitle(): string
    {
        return $this->editModalTitle;
    }

    protected function getEditModalSlideOver(): bool
    {
        return $this->editModalSlideOver;
    }

    protected function getEditModalWidth(): string
    {
        return $this->editModalWidth;
    }

    protected function getEditModalSaveButtonLabel(): string
    {
        return $this->editModalSaveButtonLabel;
    }

    protected function getEditModalCancelButtonLabel(): string
    {
        return $this->editModalCancelButtonLabel;
    }
}
