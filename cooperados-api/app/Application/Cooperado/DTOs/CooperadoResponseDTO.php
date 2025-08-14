<?php

namespace App\Application\Cooperado\DTOs;

use App\Domain\Cooperado\Entities\Cooperado;
use App\Domain\Cooperado\Enums\TipoPessoa;
use DateTime;

class CooperadoResponseDTO
{
    public function __construct(
        public readonly string $id,
        public readonly string $nome,
        public readonly string $documento,
        public readonly string $documentoFormatado,
        public readonly TipoPessoa $tipoPessoa,
        public readonly string $tipoPessoaLabel,
        public readonly DateTime $dataReferencia,
        public readonly float $rendaFaturamento,
        public readonly string $telefone,
        public readonly string $telefoneFormatado,
        public readonly ?string $email,
        public readonly DateTime $createdAt,
        public readonly DateTime $updatedAt,
    ) {}

    public static function fromEntity(Cooperado $cooperado): self
    {
        return new self(
            id: $cooperado->id,
            nome: $cooperado->nome,
            documento: $cooperado->documento,
            documentoFormatado: $cooperado->getDocumentoFormatado(),
            tipoPessoa: $cooperado->tipo_pessoa,
            tipoPessoaLabel: $cooperado->getTipoPessoaLabel(),
            dataReferencia: $cooperado->data_referencia,
            rendaFaturamento: $cooperado->renda_faturamento,
            telefone: $cooperado->telefone,
            telefoneFormatado: $cooperado->getTelefoneFormatado(),
            email: $cooperado->email,
            createdAt: $cooperado->created_at,
            updatedAt: $cooperado->updated_at,
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'nome' => $this->nome,
            'documento' => $this->documento,
            'documento_formatado' => $this->documentoFormatado,
            'tipo_pessoa' => $this->tipoPessoa->value,
            'tipo_pessoa_label' => $this->tipoPessoaLabel,
            'data_referencia' => $this->dataReferencia->format('Y-m-d'),
            'renda_faturamento' => $this->rendaFaturamento,
            'telefone' => $this->telefone,
            'telefone_formatado' => $this->telefoneFormatado,
            'email' => $this->email,
            'created_at' => $this->createdAt->format('Y-m-d H:i:s'),
            'updated_at' => $this->updatedAt->format('Y-m-d H:i:s'),
        ];
    }
}
