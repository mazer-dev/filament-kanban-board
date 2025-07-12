<?php

namespace MazerDev\FilamentKanbanBoard;

use Filament\Contracts\Plugin;
use Filament\Panel;

class FilamentKanbanBoardPlugin implements Plugin
{
    const ID = 'filament-kanban-board';

    public function getId(): string
    {
        return self::ID;
    }

    public function register(Panel $panel): void
    {
        //
    }

    public function boot(Panel $panel): void
    {
        //
    }

    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): static
    {
        return app(static::class)->getId();
    }
}
