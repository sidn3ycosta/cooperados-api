<?php

namespace App\Domain\Cooperado\Enums;

enum TipoPessoa: string
{
    case PESSOA_FISICA = 'PF';
    case PESSOA_JURIDICA = 'PJ';

    public function label(): string
    {
        return match($this) {
            self::PESSOA_FISICA => 'Pessoa Física',
            self::PESSOA_JURIDICA => 'Pessoa Jurídica',
        };
    }

    public function documentoLength(): int
    {
        return match($this) {
            self::PESSOA_FISICA => 11, // CPF
            self::PESSOA_JURIDICA => 14, // CNPJ
        };
    }
}
