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
        const cardId = e.item.dataset.cardId
        const step = e.to.dataset.stepId
        const newIndex = e.newIndex

        @this.moveCard(cardId, step, newIndex);
    }

    function onUpdate(e) {
        const cardId = e.item.dataset.cardId
        const step = e.from.dataset.stepId
        const orderedIds = [].slice.call(e.from.children).map(child => child.id)

        Livewire.dispatch('sort-changed', {cardId, step, orderedIds})
    }

    // Funções para controlar o colapso vertical de colunas
    function toggleColumnCollapse(column) {
        const statusId = column.dataset.stepId;
        const isCollapsed = column.classList.toggle('collapsed');

        // Salvar estado no localStorage
        localStorage.setItem(`kanban-column-${statusId}-collapsed`, isCollapsed ? 'true' : 'false');
    }

    function initColumnCollapseState() {
        document.querySelectorAll('.kanban-column').forEach(column => {
            const statusId = column.dataset.stepId;

            const header = column.querySelector('.kanban-column-header');

            if (header) {
                const collapseBtn = header.querySelector('.kanban-toggle-collapse');
                if (collapseBtn) {
                    collapseBtn.addEventListener('click', (e) => {
                        e.preventDefault();
                        e.stopPropagation();
                        toggleColumnCollapse(column);
                    });
                }
            }

            // Restaurar estado do localStorage
            const isCollapsed = localStorage.getItem(`kanban-column-${statusId}-collapsed`) === 'true';
            if (isCollapsed) {
                column.classList.add('collapsed');
            }
        });
    }

    function initSortable() {
        const steps = @js($steps->map(fn ($step) => $step['id']));

        steps.forEach(step => {
            const el = document.querySelector(`.kanban-column[data-step-id='${step}'] .kanban-column-content`);

            if (el) {
                Sortable.create(el, {
                    group: 'filament-kanban-board',
                    ghostClass: 'opacity-50',
                    animation: 150,
                    onStart,
                    onEnd,
                    onUpdate,
                    setData,
                    onAdd,
                });
            }
        });
    }

    document.addEventListener('livewire:navigated', () => {
        // Aguardar o DOM estar pronto
        setTimeout(() => {
            initSortable();
            initColumnCollapseState();
        }, 100);
    });
</script>
