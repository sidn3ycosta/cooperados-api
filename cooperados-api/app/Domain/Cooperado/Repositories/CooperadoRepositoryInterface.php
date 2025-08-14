<?php

namespace App\Domain\Cooperado\Repositories;

use App\Domain\Cooperado\Entities\Cooperado;
use App\Domain\Cooperado\Enums\TipoPessoa;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface CooperadoRepositoryInterface
{
    public function create(array $data): Cooperado;
    
    public function findById(string $id): ?Cooperado;
    
    public function update(string $id, array $data): ?Cooperado;
    
    public function delete(string $id): bool;
    
    public function existsByDocumento(string $documento): bool;
    
    public function findByDocumento(string $documento): ?Cooperado;
    
    public function paginateWithFilters(
        int $perPage = 15,
        ?string $nome = null,
        ?string $documento = null,
        ?TipoPessoa $tipoPessoa = null
    ): LengthAwarePaginator;
    
    public function getAll(): Collection;
    
    public function count(): int;
}
