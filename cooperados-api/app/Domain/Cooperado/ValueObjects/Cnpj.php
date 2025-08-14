<?php

namespace App\Domain\Cooperado\ValueObjects;

use App\Domain\Cooperado\Exceptions\DocumentoInvalidoException;

class Cnpj
{
    private string $value;

    public function __construct(string $cnpj)
    {
        $this->value = $this->normalize($cnpj);
        $this->validate();
    }

    public function value(): string
    {
        return $this->value;
    }

    public function formatted(): string
    {
        return substr($this->value, 0, 2) . '.' . 
               substr($this->value, 2, 3) . '.' . 
               substr($this->value, 5, 3) . '/' . 
               substr($this->value, 8, 4) . '-' . 
               substr($this->value, 12, 2);
    }

    private function normalize(string $cnpj): string
    {
        return preg_replace('/[^0-9]/', '', $cnpj);
    }

    private function validate(): void
    {
        if (strlen($this->value) !== 14) {
            throw new DocumentoInvalidoException($this->value, 'PJ');
        }

        if ($this->hasInvalidSequence()) {
            throw new DocumentoInvalidoException($this->value, 'PJ');
        }

        if (!$this->validateDigits()) {
            throw new DocumentoInvalidoException($this->value, 'PJ');
        }
    }

    private function hasInvalidSequence(): bool
    {
        $sequences = [
            '00000000000000',
            '11111111111111',
            '22222222222222',
            '33333333333333',
            '44444444444444',
            '55555555555555',
            '66666666666666',
            '77777777777777',
            '88888888888888',
            '99999999999999'
        ];

        return in_array($this->value, $sequences);
    }

    private function validateDigits(): bool
    {
        // Validação do primeiro dígito verificador
        $weights = [5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
        $sum = 0;
        for ($i = 0; $i < 12; $i++) {
            $sum += (int) $this->value[$i] * $weights[$i];
        }
        $remainder = $sum % 11;
        $digit1 = $remainder < 2 ? 0 : 11 - $remainder;

        if ((int) $this->value[12] !== $digit1) {
            return false;
        }

        // Validação do segundo dígito verificador
        $weights = [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
        $sum = 0;
        for ($i = 0; $i < 13; $i++) {
            $sum += (int) $this->value[$i] * $weights[$i];
        }
        $remainder = $sum % 11;
        $digit2 = $remainder < 2 ? 0 : 11 - $remainder;

        return (int) $this->value[13] === $digit2;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
