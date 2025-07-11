<?php

namespace MazerDev\FilamentKanbanBoard\Commands;

use Illuminate\Console\GeneratorCommand;

class MakeKanbanBoardPageCommand extends GeneratorCommand
{
    public $name = 'make:filament-kanban-board';

    public $description = 'Create a new (Mazer Dev) Filament Kanban Board page for your project';

    public $type = 'Kanban Board page';

    protected function getStub()
    {
        return __DIR__ . '/../../stubs/board-page.stub';
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Filament\Pages';
    }
}
