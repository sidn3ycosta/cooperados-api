<?php

namespace App\Domain\Cooperado\Exceptions;

use Exception;

class DocumentoDuplicadoException extends Exception
{
    public function __construct(string $documento)
    {
        parent::__construct("O documento '{$documento}' já está cadastrado no sistema.");
    }
}
