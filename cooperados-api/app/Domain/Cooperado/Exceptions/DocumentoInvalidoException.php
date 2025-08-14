<?php

namespace App\Domain\Cooperado\Exceptions;

use Exception;

class DocumentoInvalidoException extends Exception
{
    public function __construct(string $documento, string $tipoPessoa)
    {
        $label = match($tipoPessoa) {
            'PF' => 'CPF',
            'PJ' => 'CNPJ',
            default => 'documento'
        };
        
        parent::__construct("O {$label} '{$documento}' é inválido.");
    }
}
