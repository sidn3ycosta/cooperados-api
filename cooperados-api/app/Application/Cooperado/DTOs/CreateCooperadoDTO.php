<?php

namespace App\Application\Cooperado\DTOs;

use App\Domain\Cooperado\Enums\TipoPessoa;
use DateTime;

class CreateCooperadoDTO
{
    public function __construct(
        public readonly string $nome,
        public readonly string $documento,
        public readonly TipoPessoa $tipoPessoa,
        public readonly DateTime $dataReferencia,
        public readonly float $rendaFaturamento,
        public readonly string $telefone,
        public readonly ?string $email = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            nome: $data['nome'],
            documento: $data['documento'],
            tipoPessoa: TipoPessoa::from($data['tipo_pessoa']),
            dataReferencia: new DateTime($data['data_referencia']),
            rendaFaturamento: (float) $data['renda_faturamento'],
            telefone: $data['telefone'],
            email: $data['email'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'nome' => $this->nome,
            'documento' => $this->documento,
            'tipo_pessoa' => $this->tipoPessoa->value,
            'data_referencia' => $this->dataReferencia->format('Y-m-d'),
            'renda_faturamento' => $this->rendaFaturamento,
            'telefone' => $this->telefone,
            'email' => $this->email,
        ];
    }
}
