<form wire:submit.prevent="editCardFormSubmitted">
    <x-filament::modal id="kanban--view-card-modal" :slideOver="$this->getEditModalSlideOver()" :width="$this->getEditModalWidth()">
        <x-slot name="header">
            <x-filament::modal.heading>
                {{ $this->getEditModalTitle() }}
            </x-filament::modal.heading>
        </x-slot>

{{--        {{ $this->cardInfoList }}--}}

        <x-slot name="footer">
            <x-filament::button type="submit">
                {{$this->getEditModalSaveButtonLabel()}}
            </x-filament::button>

            <x-filament::button color="gray" x-on:click="$dispatch('close-modal', { id: 'kanban--edit-card-modal' })">
                {{$this->getEditModalCancelButtonLabel()}}
            </x-filament::button>
        </x-slot>
    </x-filament::modal>
</form>
