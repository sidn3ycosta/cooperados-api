<?php

namespace App\Domain\Cooperado\ValueObjects;

use InvalidArgumentException;

class Telefone
{
    private string $value;

    public function __construct(string $telefone)
    {
        $this->value = $this->normalize($telefone);
        $this->validate();
    }

    public function value(): string
    {
        return $this->value;
    }

    public function formatted(): string
    {
        $length = strlen($this->value);
        
        if ($length === 10) {
            return '(' . substr($this->value, 0, 2) . ') ' . 
                   substr($this->value, 2, 4) . '-' . 
                   substr($this->value, 6, 4);
        }
        
        if ($length === 11) {
            return '(' . substr($this->value, 0, 2) . ') ' . 
                   substr($this->value, 2, 1) . 
                   substr($this->value, 3, 4) . '-' . 
                   substr($this->value, 7, 4);
        }
        
        return $this->value;
    }

    private function normalize(string $telefone): string
    {
        return preg_replace('/[^0-9]/', '', $telefone);
    }

    private function validate(): void
    {
        $length = strlen($this->value);
        
        if ($length < 10 || $length > 11) {
            throw new InvalidArgumentException(
                "Telefone deve ter 10 ou 11 dígitos. Fornecido: {$length} dígitos."
            );
        }

        // Validação de DDD brasileiro (11-99)
        $ddd = (int) substr($this->value, 0, 2);
        if ($ddd < 11 || $ddd > 99) {
            throw new InvalidArgumentException(
                "DDD inválido: {$ddd}. DDD deve estar entre 11 e 99."
            );
        }

        // Validação de telefone fixo (10 dígitos) ou celular (11 dígitos)
        if ($length === 11) {
            $nonoDigito = (int) $this->value[2];
            if ($nonoDigito < 6 || $nonoDigito > 9) {
                throw new InvalidArgumentException(
                    "Nono dígito inválido para celular: {$nonoDigito}. Deve ser 6, 7, 8 ou 9."
                );
            }
        }
    }

    public function isCelular(): bool
    {
        return strlen($this->value) === 11;
    }

    public function isFixo(): bool
    {
        return strlen($this->value) === 10;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
