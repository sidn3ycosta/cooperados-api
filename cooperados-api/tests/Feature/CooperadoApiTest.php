<?php

namespace Tests\Feature;

use App\Domain\Cooperado\Entities\Cooperado;
use App\Domain\Cooperado\Enums\TipoPessoa;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CooperadoApiTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_criar_cooperado_pessoa_fisica()
    {
        $data = [
            'nome' => 'João Silva',
            'documento' => '123.456.789-09',
            'tipo_pessoa' => 'PF',
            'data_referencia' => '1990-05-15',
            'renda_faturamento' => 5000.00,
            'telefone' => '(11) 99999-9999',
            'email' => 'joao@email.com'
        ];

        $response = $this->postJson('/api/v1/cooperados', $data);

        $response->assertStatus(201)
                ->assertJsonStructure([
                    'message',
                    'data' => [
                        'id',
                        'nome',
                        'documento',
                        'documento_formatado',
                        'tipo_pessoa',
                        'tipo_pessoa_label',
                        'data_referencia',
                        'renda_faturamento',
                        'telefone',
                        'telefone_formatado',
                        'email',
                        'created_at',
                        'updated_at'
                    ]
                ]);

        $this->assertDatabaseHas('cooperados', [
            'nome' => 'João Silva',
            'documento' => '12345678909',
            'tipo_pessoa' => 'PF'
        ]);
    }

    public function test_criar_cooperado_pessoa_juridica()
    {
        $data = [
            'nome' => 'Empresa LTDA',
            'documento' => '11.222.333/0001-81',
            'tipo_pessoa' => 'PJ',
            'data_referencia' => '2010-01-01',
            'renda_faturamento' => 100000.00,
            'telefone' => '(11) 99999-9999',
            'email' => 'contato@empresa.com'
        ];

        $response = $this->postJson('/api/v1/cooperados', $data);

        $response->assertStatus(201)
                ->assertJsonStructure([
                    'message',
                    'data' => [
                        'id',
                        'nome',
                        'documento',
                        'documento_formatado',
                        'tipo_pessoa',
                        'tipo_pessoa_label',
                        'data_referencia',
                        'renda_faturamento',
                        'telefone',
                        'telefone_formatado',
                        'email',
                        'created_at',
                        'updated_at'
                    ]
                ]);

        $this->assertDatabaseHas('cooperados', [
            'nome' => 'Empresa LTDA',
            'documento' => '11222333000181',
            'tipo_pessoa' => 'PJ'
        ]);
    }

    public function test_criar_cooperado_sem_email()
    {
        $data = [
            'nome' => 'Maria Santos',
            'documento' => '987.654.321-00',
            'tipo_pessoa' => 'PF',
            'data_referencia' => '1985-08-20',
            'renda_faturamento' => 3500.00,
            'telefone' => '(21) 88888-8888'
        ];

        $response = $this->postJson('/api/v1/cooperados', $data);

        $response->assertStatus(201);
        $this->assertDatabaseHas('cooperados', [
            'nome' => 'Maria Santos',
            'email' => null
        ]);
    }

    public function test_criar_cooperado_cpf_invalido()
    {
        $data = [
            'nome' => 'João Silva',
            'documento' => '123.456.789-00', // CPF inválido
            'tipo_pessoa' => 'PF',
            'data_referencia' => '1990-05-15',
            'renda_faturamento' => 5000.00,
            'telefone' => '(11) 99999-9999'
        ];

        $response = $this->postJson('/api/v1/cooperados', $data);

        $response->assertStatus(422);
    }

    public function test_criar_cooperado_cnpj_invalido()
    {
        $data = [
            'nome' => 'Empresa LTDA',
            'documento' => '12.345.678/0001-00', // CNPJ inválido
            'tipo_pessoa' => 'PJ',
            'data_referencia' => '2010-01-01',
            'renda_faturamento' => 100000.00,
            'telefone' => '(11) 3333-3333'
        ];

        $response = $this->postJson('/api/v1/cooperados', $data);

        $response->assertStatus(422);
    }

    public function test_criar_cooperado_documento_duplicado()
    {
        // Criar primeiro cooperado
        $data1 = [
            'nome' => 'João Silva',
            'documento' => '123.456.789-09',
            'tipo_pessoa' => 'PF',
            'data_referencia' => '1990-05-15',
            'renda_faturamento' => 5000.00,
            'telefone' => '(11) 99999-9999'
        ];

        $this->postJson('/api/v1/cooperados', $data1);

        // Tentar criar segundo com mesmo documento
        $data2 = [
            'nome' => 'João Silva Santos',
            'documento' => '123.456.789-09', // Mesmo documento
            'tipo_pessoa' => 'PF',
            'data_referencia' => '1990-05-15',
            'renda_faturamento' => 6000.00,
            'telefone' => '(11) 88888-8888'
        ];

        $response = $this->postJson('/api/v1/cooperados', $data2);

        $response->assertStatus(409);
    }

    public function test_criar_cooperado_telefone_invalido()
    {
        $data = [
            'nome' => 'João Silva',
            'documento' => '123.456.789-09',
            'tipo_pessoa' => 'PF',
            'data_referencia' => '1990-05-15',
            'renda_faturamento' => 5000.00,
            'telefone' => '(11) 9999-999' // Telefone inválido (9 dígitos)
        ];

        $response = $this->postJson('/api/v1/cooperados', $data);

        $response->assertStatus(422);
    }

    public function test_criar_cooperado_idade_menor_18()
    {
        $data = [
            'nome' => 'João Silva',
            'documento' => '123.456.789-09',
            'tipo_pessoa' => 'PF',
            'data_referencia' => '2010-05-15', // Data que resulta em idade < 18
            'renda_faturamento' => 5000.00,
            'telefone' => '(11) 99999-9999'
        ];

        $response = $this->postJson('/api/v1/cooperados', $data);

        $response->assertStatus(422);
    }

    public function test_listar_cooperados()
    {
        // Criar alguns cooperados
        Cooperado::factory()->count(5)->create();

        $response = $this->getJson('/api/v1/cooperados');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'data',
                    'meta' => [
                        'total',
                        'per_page',
                        'current_page',
                        'last_page',
                        'from',
                        'to'
                    ],
                    'links'
                ]);
    }

    public function test_listar_cooperados_com_filtros()
    {
        // Criar cooperados
        Cooperado::factory()->create([
            'nome' => 'João Silva',
            'tipo_pessoa' => TipoPessoa::PESSOA_FISICA
        ]);

        Cooperado::factory()->create([
            'nome' => 'Maria Santos',
            'tipo_pessoa' => TipoPessoa::PESSOA_FISICA
        ]);

        $response = $this->getJson('/api/v1/cooperados?nome=João&tipo_pessoa=PF');

        $response->assertStatus(200);
        $this->assertCount(1, $response->json('data'));
    }

    public function test_visualizar_cooperado()
    {
        $cooperado = Cooperado::factory()->create();

        $response = $this->getJson("/api/v1/cooperados/{$cooperado->id}");

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'data' => [
                        'id',
                        'nome',
                        'documento',
                        'documento_formatado',
                        'tipo_pessoa',
                        'tipo_pessoa_label',
                        'data_referencia',
                        'renda_faturamento',
                        'telefone',
                        'telefone_formatado',
                        'email',
                        'created_at',
                        'updated_at'
                    ]
                ]);
    }

    public function test_visualizar_cooperado_nao_encontrado()
    {
        $response = $this->getJson('/api/v1/cooperados/123e4567-e89b-12d3-a456-426614174000');

        $response->assertStatus(404);
    }

    public function test_atualizar_cooperado()
    {
        $cooperado = Cooperado::factory()->create();

        $data = [
            'nome' => 'João Silva Santos',
            'renda_faturamento' => 6000.00
        ];

        $response = $this->putJson("/api/v1/cooperados/{$cooperado->id}", $data);

        $response->assertStatus(200);
        $this->assertDatabaseHas('cooperados', [
            'id' => $cooperado->id,
            'nome' => 'João Silva Santos',
            'renda_faturamento' => 6000.00
        ]);
    }

    public function test_excluir_cooperado()
    {
        $cooperado = Cooperado::factory()->create();

        $response = $this->deleteJson("/api/v1/cooperados/{$cooperado->id}");

        $response->assertStatus(204);
        $this->assertSoftDeleted('cooperados', [
            'id' => $cooperado->id
        ]);
    }
}
