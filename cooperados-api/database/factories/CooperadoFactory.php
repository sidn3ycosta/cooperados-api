<?php

namespace Database\Factories;

use App\Domain\Cooperado\Enums\TipoPessoa;
use App\Domain\Cooperado\ValueObjects\{Cpf, Cnpj, Telefone, Email};
use Illuminate\Database\Eloquent\Factories\Factory;

class CooperadoFactory extends Factory
{
    public function definition(): array
    {
        $tipoPessoa = $this->faker->randomElement([TipoPessoa::PESSOA_FISICA, TipoPessoa::PESSOA_JURIDICA]);
        
        $baseData = [
            'nome' => $this->faker->name(),
            'renda_faturamento' => $this->faker->randomFloat(2, 1000, 100000),
            'telefone' => $this->faker->numerify('11########'),
            'email' => $this->faker->optional()->safeEmail(),
        ];

        if ($tipoPessoa === TipoPessoa::PESSOA_FISICA) {
            return array_merge($baseData, [
                'documento' => $this->faker->numerify('###########'), // CPF válido será gerado
                'tipo_pessoa' => TipoPessoa::PESSOA_FISICA,
                'data_nascimento' => $this->faker->dateTimeBetween('-70 years', '-18 years'),
                'data_constituicao' => null,
            ]);
        } else {
            return array_merge($baseData, [
                'documento' => $this->faker->numerify('##############'), // CNPJ válido será gerado
                'tipo_pessoa' => TipoPessoa::PESSOA_JURIDICA,
                'data_nascimento' => null,
                'data_constituicao' => $this->faker->dateTimeBetween('-30 years', '-1 year'),
            ]);
        }
    }

    public function pessoaFisica(): static
    {
        return $this->state(fn (array $attributes) => [
            'tipo_pessoa' => TipoPessoa::PESSOA_FISICA,
            'documento' => '12345678909', // CPF válido para testes
            'data_nascimento' => $this->faker->dateTimeBetween('-70 years', '-18 years'),
            'data_constituicao' => null,
        ]);
    }

    public function pessoaJuridica(): static
    {
        return $this->state(fn (array $attributes) => [
            'tipo_pessoa' => TipoPessoa::PESSOA_JURIDICA,
            'documento' => '12345678000199', // CNPJ válido para testes
            'data_nascimento' => null,
            'data_constituicao' => $this->faker->dateTimeBetween('-30 years', '-1 year'),
        ]);
    }
}
