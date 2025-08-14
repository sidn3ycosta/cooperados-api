<?php

namespace Database\Factories;

use App\Domain\Cooperado\Entities\Cooperado;
use App\Domain\Cooperado\Enums\TipoPessoa;
use Illuminate\Database\Eloquent\Factories\Factory;

class CooperadoFactory extends Factory
{
    protected $model = Cooperado::class;

    public function definition(): array
    {
        $tipoPessoa = $this->faker->randomElement([TipoPessoa::PESSOA_FISICA, TipoPessoa::PESSOA_JURIDICA]);

        if ($tipoPessoa === TipoPessoa::PESSOA_FISICA) {
            return [
                'nome' => $this->faker->name(),
                'documento' => $this->faker->numerify('###.###.###-##'),
                'tipo_pessoa' => $tipoPessoa,
                'data_referencia' => $this->faker->dateTimeBetween('-70 years', '-18 years'),
                'renda_faturamento' => $this->faker->randomFloat(2, 1000, 50000),
                'telefone' => $this->faker->numerify('(##) #####-####'),
                'email' => $this->faker->optional()->safeEmail(),
            ];
        } else {
            return [
                'nome' => $this->faker->company(),
                'documento' => $this->faker->numerify('##.###.###/####-##'),
                'tipo_pessoa' => $tipoPessoa,
                'data_referencia' => $this->faker->dateTimeBetween('-30 years', '-1 year'),
                'renda_faturamento' => $this->faker->randomFloat(2, 10000, 1000000),
                'telefone' => $this->faker->numerify('(##) ####-####'),
                'email' => $this->faker->optional()->companyEmail(),
            ];
        }
    }

    public function pessoaFisica(): static
    {
        return $this->state(fn (array $attributes) => [
            'tipo_pessoa' => TipoPessoa::PESSOA_FISICA,
            'documento' => $this->faker->numerify('###.###.###-##'),
            'data_referencia' => $this->faker->dateTimeBetween('-70 years', '-18 years'),
            'telefone' => $this->faker->numerify('(##) #####-####'),
        ]);
    }

    public function pessoaJuridica(): static
    {
        return $this->state(fn (array $attributes) => [
            'tipo_pessoa' => TipoPessoa::PESSOA_JURIDICA,
            'documento' => $this->faker->numerify('##.###.###/####-##'),
            'data_referencia' => $this->faker->dateTimeBetween('-30 years', '-1 year'),
            'telefone' => $this->faker->numerify('(##) ####-####'),
        ]);
    }

    public function comEmail(): static
    {
        return $this->state(fn (array $attributes) => [
            'email' => $this->faker->safeEmail(),
        ]);
    }

    public function semEmail(): static
    {
        return $this->state(fn (array $attributes) => [
            'email' => null,
        ]);
    }
}
