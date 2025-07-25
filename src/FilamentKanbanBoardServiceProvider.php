<?php

namespace MazerDev\FilamentKanbanBoard;

use Filament\Support\Assets\Asset;
use Filament\Support\Assets\Css;
use Filament\Support\Facades\FilamentAsset;
use Illuminate\Filesystem\Filesystem;
use MazerDev\FilamentKanbanBoard\Commands\MakeKanbanBoardPageCommand;
use Livewire\Features\SupportTesting\Testable;
//use MazerDev\FilamentKanbanBoard\Testing\TestsFilamentKanbanBoard;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentKanbanBoardServiceProvider extends PackageServiceProvider
{
    public static string $name = FilamentKanbanBoardPlugin::ID;

    public static string $viewNamespace = FilamentKanbanBoardPlugin::ID;

    public function configurePackage(Package $package): void
    {
        $package->name(static::$name)
            ->hasAssets()
            ->hasCommands($this->getCommands())
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->publishAssets()
                    ->askToStarRepoOnGitHub('mazer-dev/filament-kanban-board');
            });

        if (file_exists($package->basePath('/../resources/views'))) {
            $package->hasViews(static::$viewNamespace);
        }
    }

    public function packageBooted(): void
    {
        FilamentAsset::register(
            $this->getAssets(),
            $this->getAssetPackageName()
        );

        // Handle Stubs
        if (app()->runningInConsole()) {
            foreach (app(Filesystem::class)->files(__DIR__ . '/../stubs/') as $file) {
                $this->publishes([
                    $file->getRealPath() => base_path(
                        "stubs/filament-kanban-board/{$file->getFilename()}"
                    ),
                ], 'filament-kanban-board-stubs');
            }
        }

//        Testable::mixin(new TestsFilamentKanbanBoard());
    }

    protected function getAssetPackageName(): ?string
    {
        return 'mazer-dev/filament-kanban-board';
    }

    /**
     * @return array<Asset>
     */
    protected function getAssets(): array
    {
        return [
            Css::make('filament-kanban-board-styles', __DIR__ . '/../resources/dist/filament-kanban-board.css'),
//            Css::make('filament-kanban-board-styles', __DIR__ . '/../resources/css/index.css')
//                ->loadedOnRequest(),
        ];
    }

    /**
     * @return array<class-string>
     */
    protected function getCommands(): array
    {
        return [
            MakeKanbanBoardPageCommand::class
        ];
    }
}
