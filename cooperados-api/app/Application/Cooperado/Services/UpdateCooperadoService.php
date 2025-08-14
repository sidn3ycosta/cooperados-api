<?php

namespace App\Application\Cooperado\Services;

use App\Application\Cooperado\DTOs\{UpdateCooperadoDTO, CooperadoResponseDTO};
use App\Domain\Cooperado\Entities\Cooperado;
use App\Domain\Cooperado\Exceptions\DocumentoDuplicadoException;
use App\Domain\Cooperado\Repositories\CooperadoRepositoryInterface;
use App\Domain\Cooperado\ValueObjects\{Cpf, Cnpj, Telefone, Email};
use App\Domain\Cooperado\Enums\TipoPessoa;
use Illuminate\Support\Facades\DB;
use Exception;
use InvalidArgumentException;

class UpdateCooperadoService
{
    public function __construct(
        private CooperadoRepositoryInterface $repository
    ) {}

    public function execute(string $id, UpdateCooperadoDTO $dto): CooperadoResponseDTO
    {
        try {
            DB::beginTransaction();

            // Buscar cooperado existente
            $cooperado = $this->repository->findById($id);
            if (!$cooperado) {
                throw new InvalidArgumentException('Cooperado não encontrado.');
            }

            // Se documento foi alterado, validar unicidade
            if ($dto->documento && $dto->documento !== $cooperado->documento) {
                if ($this->repository->existsByDocumento($dto->documento)) {
                    throw new DocumentoDuplicadoException($dto->documento);
                }
            }

            // Validar Value Objects se fornecidos
            $this->validateValueObjects($dto, $cooperado);

            // Atualizar cooperado
            $cooperadoAtualizado = $this->repository->update($id, $dto->toArray());
            if (!$cooperadoAtualizado) {
                throw new InvalidArgumentException('Erro ao atualizar cooperado.');
            }

            DB::commit();

            return CooperadoResponseDTO::fromEntity($cooperadoAtualizado);

        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function validateValueObjects(UpdateCooperadoDTO $dto, Cooperado $cooperado): void
    {
        // Validar documento se fornecido
        if ($dto->documento) {
            $tipoPessoa = $dto->tipoPessoa ?? $cooperado->tipo_pessoa;
            match($tipoPessoa) {
                TipoPessoa::PESSOA_FISICA => new Cpf($dto->documento),
                TipoPessoa::PESSOA_JURIDICA => new Cnpj($dto->documento),
            };
        }

        // Validar telefone se fornecido
        if ($dto->telefone) {
            new Telefone($dto->telefone);
        }

        // Validar email se fornecido
        if ($dto->email !== null) {
            new Email($dto->email);
        }

        // Validações adicionais de negócio
        $this->validateBusinessRules($dto, $cooperado);
    }

    private function validateBusinessRules(UpdateCooperadoDTO $dto, Cooperado $cooperado): void
    {
        // Validar renda/faturamento se fornecido
        if ($dto->rendaFaturamento !== null && $dto->rendaFaturamento <= 0) {
            throw new InvalidArgumentException('Renda/Faturamento deve ser maior que zero.');
        }

        // Validar campos de data específicos se fornecidos
        $hoje = new \DateTime();
        $tipoPessoa = $dto->tipoPessoa ?? $cooperado->tipo_pessoa;
        
        if ($dto->dataNascimento) {
            if ($tipoPessoa === TipoPessoa::PESSOA_FISICA) {
                // Para PF, data de nascimento não pode ser no futuro
                if ($dto->dataNascimento > $hoje) {
                    throw new InvalidArgumentException('Data de nascimento não pode ser no futuro.');
                }
                
                // Idade mínima de 18 anos
                $idadeMinima = $hoje->diff($dto->dataNascimento)->y;
                if ($idadeMinima < 18) {
                    throw new InvalidArgumentException('Cooperado deve ter pelo menos 18 anos.');
                }
            }
        }
        
        if ($dto->dataConstituicao) {
            if ($tipoPessoa === TipoPessoa::PESSOA_JURIDICA) {
                // Para PJ, data de constituição não pode ser no futuro
                if ($dto->dataConstituicao > $hoje) {
                    throw new InvalidArgumentException('Data de constituição não pode ser no futuro.');
                }
            }
        }
    }
}
