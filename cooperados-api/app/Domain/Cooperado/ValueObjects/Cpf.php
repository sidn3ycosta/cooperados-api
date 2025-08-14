<?php

namespace App\Domain\Cooperado\ValueObjects;

use App\Domain\Cooperado\Exceptions\DocumentoInvalidoException;

class Cpf
{
    private string $value;

    public function __construct(string $cpf)
    {
        $this->value = $this->normalize($cpf);
        $this->validate();
    }

    public function value(): string
    {
        return $this->value;
    }

    public function formatted(): string
    {
        return substr($this->value, 0, 3) . '.' . 
               substr($this->value, 3, 3) . '.' . 
               substr($this->value, 6, 3) . '-' . 
               substr($this->value, 9, 2);
    }

    private function normalize(string $cpf): string
    {
        return preg_replace('/[^0-9]/', '', $cpf);
    }

    private function validate(): void
    {
        if (strlen($this->value) !== 11) {
            throw new DocumentoInvalidoException($this->value, 'PF');
        }

        if ($this->hasInvalidSequence()) {
            throw new DocumentoInvalidoException($this->value, 'PF');
        }

        if (!$this->validateDigits()) {
            throw new DocumentoInvalidoException($this->value, 'PF');
        }
    }

    private function hasInvalidSequence(): bool
    {
        $sequences = [
            '00000000000',
            '11111111111',
            '22222222222',
            '33333333333',
            '44444444444',
            '55555555555',
            '66666666666',
            '77777777777',
            '88888888888',
            '99999999999'
        ];

        return in_array($this->value, $sequences);
    }

    private function validateDigits(): bool
    {
        // Validação do primeiro dígito verificador
        $sum = 0;
        for ($i = 0; $i < 9; $i++) {
            $sum += (int) $this->value[$i] * (10 - $i);
        }
        $remainder = $sum % 11;
        $digit1 = $remainder < 2 ? 0 : 11 - $remainder;

        if ((int) $this->value[9] !== $digit1) {
            return false;
        }

        // Validação do segundo dígito verificador
        $sum = 0;
        for ($i = 0; $i < 10; $i++) {
            $sum += (int) $this->value[$i] * (11 - $i);
        }
        $remainder = $sum % 11;
        $digit2 = $remainder < 2 ? 0 : 11 - $remainder;

        return (int) $this->value[10] === $digit2;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
