<?php

namespace App\Application\Cooperado\DTOs;

use App\Domain\Cooperado\Enums\TipoPessoa;
use DateTime;

class UpdateCooperadoDTO
{
    public function __construct(
        public readonly ?string $nome = null,
        public readonly ?string $documento = null,
        public readonly ?TipoPessoa $tipoPessoa = null,
        public readonly ?DateTime $dataReferencia = null,
        public readonly ?float $rendaFaturamento = null,
        public readonly ?string $telefone = null,
        public readonly ?string $email = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            nome: $data['nome'] ?? null,
            documento: $data['documento'] ?? null,
            tipoPessoa: isset($data['tipo_pessoa']) ? TipoPessoa::from($data['tipo_pessoa']) : null,
            dataReferencia: isset($data['data_referencia']) ? new DateTime($data['data_referencia']) : null,
            rendaFaturamento: isset($data['renda_faturamento']) ? (float) $data['renda_faturamento'] : null,
            telefone: $data['telefone'] ?? null,
            email: $data['email'] ?? null,
        );
    }

    public function toArray(): array
    {
        $data = [];
        
        if ($this->nome !== null) $data['nome'] = $this->nome;
        if ($this->documento !== null) $data['documento'] = $this->documento;
        if ($this->tipoPessoa !== null) $data['tipo_pessoa'] = $this->tipoPessoa->value;
        if ($this->dataReferencia !== null) $data['data_referencia'] = $this->dataReferencia->format('Y-m-d');
        if ($this->rendaFaturamento !== null) $data['renda_faturamento'] = $this->rendaFaturamento;
        if ($this->telefone !== null) $data['telefone'] = $this->telefone;
        if ($this->email !== null) $data['email'] = $this->email;
        
        return $data;
    }

    public function hasChanges(): bool
    {
        return !empty($this->toArray());
    }
}
