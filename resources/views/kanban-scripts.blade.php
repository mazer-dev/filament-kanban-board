<script>
    function onStart() {
        setTimeout(() => document.body.classList.add("dragging"))
    }

    function onEnd() {
        document.body.classList.remove("dragging")
    }

    function setData(dataTransfer, el) {
        dataTransfer.setData('id', el.id)
    }

    function onAdd(e) {
        const cardId = e.item.id
        const step = e.to.dataset.statusId
        const fromOrderedIds = [].slice.call(e.from.children).map(child => child.id)
        const toOrderedIds = [].slice.call(e.to.children).map(child => child.id)

        Livewire.dispatch('step-changed', {cardId, step, fromOrderedIds, toOrderedIds})
    }

    function onUpdate(e) {
        const cardId = e.item.id
        const step = e.from.dataset.statusId
        const orderedIds = [].slice.call(e.from.children).map(child => child.id)

        Livewire.dispatch('sort-changed', {cardId, step, orderedIds})
    }

    document.addEventListener('livewire:navigated', () => {
        const steps = @js($steps->map(fn ($step) => $step['id']))

        steps.forEach(step => Sortable.create(document.querySelector(`[data-status-id='${step}']`), {
            group: 'filament-kanban-board',
            ghostClass: 'opacity-50',
            animation: 150,

            onStart,
            onEnd,
            onUpdate,
            setData,
            onAdd,
        }))
    })
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar Sortable.js para drag & drop
    const columns = document.querySelectorAll('.kanban-column-content');

    columns.forEach(column => {
        new Sortable(column, {
            group: 'kanban',
            animation: 150,
            ghostClass: 'sortable-ghost',
            chosenClass: 'sortable-chosen',
            dragClass: 'sortable-drag',
            onEnd: function(evt) {
                let cardId = evt.item.dataset.cardId;
                let newStepId = evt.to.dataset.sortableGroup;
                let newIndex = evt.newIndex;

                @this.moveCard(cardId, newStepId, newIndex);
            }
        });
    });
});
</script>
