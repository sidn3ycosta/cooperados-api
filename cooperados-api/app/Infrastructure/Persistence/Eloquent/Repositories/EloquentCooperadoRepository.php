<?php

namespace App\Infrastructure\Persistence\Eloquent\Repositories;

use App\Domain\Cooperado\Entities\Cooperado;
use App\Domain\Cooperado\Repositories\CooperadoRepositoryInterface;
use App\Domain\Cooperado\Enums\TipoPessoa;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class EloquentCooperadoRepository implements CooperadoRepositoryInterface
{
    public function __construct(
        private Cooperado $model
    ) {}

    public function create(array $data): Cooperado
    {
        return $this->model->create($data);
    }

    public function findById(string $id): ?Cooperado
    {
        return $this->model->find($id);
    }

    public function update(string $id, array $data): ?Cooperado
    {
        $cooperado = $this->findById($id);
        if (!$cooperado) {
            return null;
        }

        $cooperado->update($data);
        return $cooperado->fresh();
    }

    public function delete(string $id): bool
    {
        $cooperado = $this->findById($id);
        if (!$cooperado) {
            return false;
        }

        return $cooperado->delete();
    }

    public function existsByDocumento(string $documento): bool
    {
        $documentoNormalizado = preg_replace('/[^0-9]/', '', $documento);
        
        return $this->model
            ->where('documento', $documentoNormalizado)
            ->exists();
    }

    public function findByDocumento(string $documento): ?Cooperado
    {
        $documentoNormalizado = preg_replace('/[^0-9]/', '', $documento);
        
        return $this->model
            ->where('documento', $documentoNormalizado)
            ->first();
    }

    public function paginateWithFilters(
        int $perPage = 15,
        ?string $nome = null,
        ?string $documento = null,
        ?TipoPessoa $tipoPessoa = null
    ): LengthAwarePaginator {
        $query = $this->model->newQuery();

        // Aplicar filtros
        if ($nome) {
            $query->porNome($nome);
        }

        if ($documento) {
            $query->porDocumento($documento);
        }

        if ($tipoPessoa) {
            $query->porTipoPessoa($tipoPessoa);
        }

        // Apenas cooperados ativos
        $query->ativos();

        // Ordenar por nome
        $query->orderBy('nome');

        return $query->paginate($perPage);
    }

    public function getAll(): Collection
    {
        return $this->model
            ->ativos()
            ->orderBy('nome')
            ->get();
    }

    public function count(): int
    {
        return $this->model->ativos()->count();
    }
}
