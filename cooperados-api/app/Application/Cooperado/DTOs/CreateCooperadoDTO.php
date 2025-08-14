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
        public readonly ?DateTime $dataNascimento,
        public readonly ?DateTime $dataConstituicao,
        public readonly float $rendaFaturamento,
        public readonly string $telefone,
        public readonly ?string $email = null
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            nome: $data['nome'],
            documento: $data['documento'],
            tipoPessoa: TipoPessoa::from($data['tipo_pessoa']),
            dataNascimento: isset($data['data_nascimento']) ? new DateTime($data['data_nascimento']) : null,
            dataConstituicao: isset($data['data_constituicao']) ? new DateTime($data['data_constituicao']) : null,
            rendaFaturamento: $data['renda_faturamento'],
            telefone: $data['telefone'],
            email: $data['email'] ?? null
        );
    }

    public function toArray(): array
    {
        $data = [
            'nome' => $this->nome,
            'documento' => $this->documento,
            'tipo_pessoa' => $this->tipoPessoa->value,
            'renda_faturamento' => $this->rendaFaturamento,
            'telefone' => $this->telefone,
        ];

        // Adicionar campos específicos por tipo
        if ($this->tipoPessoa === TipoPessoa::PESSOA_FISICA && $this->dataNascimento) {
            $data['data_nascimento'] = $this->dataNascimento->format('Y-m-d');
        }

        if ($this->tipoPessoa === TipoPessoa::PESSOA_JURIDICA && $this->dataConstituicao) {
            $data['data_constituicao'] = $this->dataConstituicao->format('Y-m-d');
        }

        if ($this->email) {
            $data['email'] = $this->email;
        }

        return $data;
    }
}
