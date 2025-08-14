<?php

namespace App\Application\Cooperado\Services;

use App\Application\Cooperado\DTOs\CooperadoResponseDTO;
use App\Domain\Cooperado\Repositories\CooperadoRepositoryInterface;
use App\Domain\Cooperado\Enums\TipoPessoa;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use InvalidArgumentException;

class GetCooperadoService
{
    public function __construct(
        private CooperadoRepositoryInterface $repository
    ) {}

    public function findById(string $id): ?CooperadoResponseDTO
    {
        $cooperado = $this->repository->findById($id);
        
        if (!$cooperado) {
            return null;
        }

        return CooperadoResponseDTO::fromEntity($cooperado);
    }

    public function findByDocumento(string $documento): ?CooperadoResponseDTO
    {
        $cooperado = $this->repository->findByDocumento($documento);
        
        if (!$cooperado) {
            return null;
        }

        return CooperadoResponseDTO::fromEntity($cooperado);
    }

    public function paginateWithFilters(
        int $perPage = 15,
        ?string $nome = null,
        ?string $documento = null,
        ?TipoPessoa $tipoPessoa = null
    ): LengthAwarePaginator {
        $paginator = $this->repository->paginateWithFilters($perPage, $nome, $documento, $tipoPessoa);
        
        // Converter entidades para DTOs
        $paginator->getCollection()->transform(function ($cooperado) {
            return CooperadoResponseDTO::fromEntity($cooperado);
        });

        return $paginator;
    }

    public function getAll(): Collection
    {
        $cooperados = $this->repository->getAll();
        
        return $cooperados->map(function ($cooperado) {
            return CooperadoResponseDTO::fromEntity($cooperado);
        });
    }

    public function count(): int
    {
        return $this->repository->count();
    }
}
