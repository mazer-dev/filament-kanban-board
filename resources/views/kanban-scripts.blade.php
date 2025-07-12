<script>
    function onStart() {
        setTimeout(() => document.body.classList.add("dragging"))
    }

    function onEnd() {
        document.body.classList.remove("drabbing")
    }

    function setData(dataTransfer, el) {
        dataTransfer.setData('id', el.id)
    }

    function onAdd(e) {
        const cardId = e.item.id
        const step = e.to.dataset.stepId
        const fromOrderedIds = [].slice.call(e.from.children).map(child => child.id)
        const toOrderedIds = [].slice.call(e.to.children).map(child => child.id)

        Livewire.dispatch('step-changed', {cardId, step, fromOrderedIds, toOrderedIds})
    }

    function onUpdate(e) {
        const cardId = e.item.id
        const step = e.from.dataset.stepId
        const orderedIds = [].slice.call(e.from.children).map(child => child.id)

        Livewire.dispatch('sort-changed', {cardId, step, orderedIds})
    }

    document.addEventListener('livewire:navigated', () => {
        const steps = @js($steps->map(fn ($step) => $step['id']))

        steps.forEach(status => Sortable.create(document.querySelector(`[data-status-id='${step}']`), {
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
