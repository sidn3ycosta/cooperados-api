<?php

namespace App\Domain\Cooperado\ValueObjects;

use InvalidArgumentException;

class Email
{
    private string $value;

    public function __construct(?string $email)
    {
        if ($email === null) {
            $this->value = '';
            return;
        }
        
        $this->value = $this->normalize($email);
        $this->validate();
    }

    public function value(): string
    {
        return $this->value;
    }

    public function isEmpty(): bool
    {
        return empty($this->value);
    }

    private function normalize(string $email): string
    {
        return strtolower(trim($email));
    }

    private function validate(): void
    {
        if (empty($this->value)) {
            return; // Email é opcional
        }

        if (!filter_var($this->value, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException(
                "Email '{$this->value}' é inválido."
            );
        }

        // Validações adicionais
        if (strlen($this->value) > 254) {
            throw new InvalidArgumentException(
                "Email não pode ter mais de 254 caracteres."
            );
        }

        $parts = explode('@', $this->value);
        if (strlen($parts[0]) > 64) {
            throw new InvalidArgumentException(
                "Parte local do email não pode ter mais de 64 caracteres."
            );
        }

        if (strlen($parts[1]) > 190) {
            throw new InvalidArgumentException(
                "Domínio do email não pode ter mais de 190 caracteres."
            );
        }
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
