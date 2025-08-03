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

    // Funções para controlar o colapso vertical de colunas
    function toggleColumnCollapse(column) {
        const statusId = column.dataset.statusId;
        const isCollapsed = column.classList.toggle('collapsed');

        // Salvar estado no localStorage
        localStorage.setItem(`kanban-column-${statusId}-collapsed`, isCollapsed ? 'true' : 'false');
    }

    function initColumnCollapseState() {
        document.querySelectorAll('.kanban-column').forEach(column => {
            const statusId = column.dataset.statusId;

            // Adicionar botão de collapse se não existir
            if (!column.querySelector('.kanban-toggle-collapse')) {
                const header = column.querySelector('.kanban-column-header');
                if (header) {
                    const collapseBtn = document.createElement('button');
                    collapseBtn.className = 'kanban-toggle-collapse';
                    collapseBtn.innerHTML = `
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M12.79 5.23a.75.75 0 01-.02 1.06L8.832 10l3.938 3.71a.75.75 0 11-1.04 1.08l-4.5-4.25a.75.75 0 010-1.08l4.5-4.25a.75.75 0 011.06.02z" clip-rule="evenodd" />
                        </svg>
                    `;
                    collapseBtn.addEventListener('click', (e) => {
                        e.preventDefault();
                        e.stopPropagation();
                        toggleColumnCollapse(column);
                    });
                    header.appendChild(collapseBtn);
                }
            }

            // Restaurar estado do localStorage
            const isCollapsed = localStorage.getItem(`kanban-column-${statusId}-collapsed`) === 'true';
            if (isCollapsed) {
                column.classList.add('collapsed');
            }
        });
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

        // Inicializar estado das colunas colapsáveis
        setTimeout(initColumnCollapseState, 100);
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

    // Inicializar estado das colunas colapsáveis
    initColumnCollapseState();
});
</script>
