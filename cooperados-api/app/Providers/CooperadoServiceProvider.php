<?php

namespace App\Infrastructure\Providers;

use App\Domain\Cooperado\Repositories\CooperadoRepositoryInterface;
use App\Infrastructure\Persistence\Eloquent\Repositories\EloquentCooperadoRepository;
use Illuminate\Support\ServiceProvider;

class CooperadoServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Registrar repositório
        $this->app->bind(
            CooperadoRepositoryInterface::class,
            EloquentCooperadoRepository::class
        );
    }

    public function boot(): void
    {
        //
    }
}
