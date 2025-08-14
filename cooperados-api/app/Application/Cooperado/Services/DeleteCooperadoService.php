<?php

namespace App\Application\Cooperado\Services;

use App\Domain\Cooperado\Repositories\CooperadoRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Exception;
use InvalidArgumentException;

class DeleteCooperadoService
{
    public function __construct(
        private CooperadoRepositoryInterface $repository
    ) {}

    public function execute(string $id): bool
    {
        try {
            DB::beginTransaction();

            // Verificar se cooperado existe
            $cooperado = $this->repository->findById($id);
            if (!$cooperado) {
                throw new InvalidArgumentException('Cooperado não encontrado.');
            }

            // Realizar exclusão lógica
            $deleted = $this->repository->delete($id);
            if (!$deleted) {
                throw new InvalidArgumentException('Erro ao excluir cooperado.');
            }

            DB::commit();

            return true;

        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
