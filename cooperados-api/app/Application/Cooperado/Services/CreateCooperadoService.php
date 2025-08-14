<?php

namespace App\Application\Cooperado\Services;

use App\Application\Cooperado\DTOs\{CreateCooperadoDTO, CooperadoResponseDTO};
use App\Domain\Cooperado\Entities\Cooperado;
use App\Domain\Cooperado\Exceptions\DocumentoDuplicadoException;
use App\Domain\Cooperado\Repositories\CooperadoRepositoryInterface;
use App\Domain\Cooperado\ValueObjects\{Cpf, Cnpj, Telefone, Email};
use App\Domain\Cooperado\Enums\TipoPessoa;
use Illuminate\Support\Facades\DB;
use Exception;
use InvalidArgumentException;

class CreateCooperadoService
{
    public function __construct(
        private CooperadoRepositoryInterface $repository
    ) {}

    public function execute(CreateCooperadoDTO $dto): CooperadoResponseDTO
    {
        try {
            DB::beginTransaction();

            // Validar se documento já existe
            if ($this->repository->existsByDocumento($dto->documento)) {
                throw new DocumentoDuplicadoException($dto->documento);
            }

            // Validar Value Objects
            $this->validateValueObjects($dto);

            // Criar cooperado
            $cooperado = $this->repository->create($dto->toArray());

            DB::commit();

            return CooperadoResponseDTO::fromEntity($cooperado);

        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function validateValueObjects(CreateCooperadoDTO $dto): void
    {
        // Validar documento baseado no tipo de pessoa
        match($dto->tipoPessoa) {
            TipoPessoa::PESSOA_FISICA => new Cpf($dto->documento),
            TipoPessoa::PESSOA_JURIDICA => new Cnpj($dto->documento),
        };

        // Validar telefone
        new Telefone($dto->telefone);

        // Validar email (se fornecido)
        if ($dto->email) {
            new Email($dto->email);
        }

        // Validações adicionais de negócio
        $this->validateBusinessRules($dto);
    }

    private function validateBusinessRules(CreateCooperadoDTO $dto): void
    {
        // Validar renda/faturamento
        if ($dto->rendaFaturamento <= 0) {
            throw new InvalidArgumentException('Renda/Faturamento deve ser maior que zero.');
        }

        // Validar data de referência
        $hoje = new \DateTime();
        $dataReferencia = $dto->dataReferencia;
        
        if ($dto->tipoPessoa === TipoPessoa::PESSOA_FISICA) {
            // Para PF, data de nascimento não pode ser no futuro
            if ($dataReferencia > $hoje) {
                throw new InvalidArgumentException('Data de nascimento não pode ser no futuro.');
            }
            
            // Idade mínima de 18 anos
            $idadeMinima = $hoje->diff($dataReferencia)->y;
            if ($idadeMinima < 18) {
                throw new InvalidArgumentException('Cooperado deve ter pelo menos 18 anos.');
            }
        } else {
            // Para PJ, data de constituição não pode ser no futuro
            if ($dataReferencia > $hoje) {
                throw new InvalidArgumentException('Data de constituição não pode ser no futuro.');
            }
        }
    }
}
