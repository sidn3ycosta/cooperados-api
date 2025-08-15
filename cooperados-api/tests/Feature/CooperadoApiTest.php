<?php

namespace Tests\Feature;

use App\Domain\Cooperado\Enums\TipoPessoa;
use App\Domain\Cooperado\Entities\Cooperado;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CooperadoApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_cooperados()
    {
        $response = $this->getJson('/api/v1/cooperados');

        $response->assertStatus(200);
    }

    public function test_can_create_pessoa_fisica()
    {
        $data = [
            'nome' => 'João Silva',
            'documento' => '12345678909', // CPF válido
            'tipo_pessoa' => 'PF',
            'data_nascimento' => '1990-05-15',
            'renda_faturamento' => 5000.00,
            'telefone' => '11999999999',
            'email' => 'joao@email.com'
        ];

        $response = $this->postJson('/api/v1/cooperados', $data);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'nome',
                'documento',
                'tipo_pessoa',
                'data_nascimento',
                'data_constituicao',
                'renda_faturamento',
                'telefone',
                'email',
                'created_at',
                'updated_at'
            ]
        ]);

        $this->assertDatabaseHas('cooperados', [
            'nome' => 'João Silva',
            'documento' => '12345678909',
            'tipo_pessoa' => 'PF',
            'data_nascimento' => '1990-05-15',
            'data_constituicao' => null,
        ]);
    }

    public function test_can_create_pessoa_juridica()
    {
        $data = [
            'nome' => 'Empresa LTDA',
            'documento' => '12345678000195', // CNPJ válido
            'tipo_pessoa' => 'PJ',
            'data_constituicao' => '2010-01-15',
            'renda_faturamento' => 100000.00,
            'telefone' => '1133333333',
            'email' => 'contato@empresa.com'
        ];

        $response = $this->postJson('/api/v1/cooperados', $data);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'nome',
                'documento',
                'tipo_pessoa',
                'data_nascimento',
                'data_constituicao',
                'renda_faturamento',
                'telefone',
                'email',
                'created_at',
                'updated_at'
            ]
        ]);

        $this->assertDatabaseHas('cooperados', [
            'nome' => 'Empresa LTDA',
            'documento' => '12345678000195',
            'tipo_pessoa' => 'PJ',
            'data_nascimento' => null,
            'data_constituicao' => '2010-01-15',
        ]);
    }

    public function test_can_search_cooperados()
    {
        $response = $this->getJson('/api/v1/cooperados/search?q=João');

        $response->assertStatus(200);
    }

    public function test_can_get_cooperado_by_id()
    {
        // Criar cooperado diretamente sem usar Factory
        $cooperadoData = [
            'nome' => 'João Silva',
            'documento' => '12345678909',
            'tipo_pessoa' => 'PF',
            'data_nascimento' => '1990-05-15',
            'renda_faturamento' => 5000.00,
            'telefone' => '11999999999',
            'email' => 'joao@email.com'
        ];

        $response = $this->postJson('/api/v1/cooperados', $cooperadoData);
        $response->assertStatus(201);

        $cooperadoId = $response->json('data.id');
        $this->assertNotNull($cooperadoId, 'ID do cooperado não foi retornado');

        $response = $this->getJson("/api/v1/cooperados/{$cooperadoId}");

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'nome',
                'documento',
                'tipo_pessoa',
                'data_nascimento',
                'data_constituicao',
                'renda_faturamento',
                'telefone',
                'email',
                'created_at',
                'updated_at'
            ]
        ]);
    }

    public function test_can_update_cooperado()
    {
        // Criar cooperado diretamente sem usar Factory
        $cooperadoData = [
            'nome' => 'João Silva',
            'documento' => '12345678909',
            'tipo_pessoa' => 'PF',
            'data_nascimento' => '1990-05-15',
            'renda_faturamento' => 5000.00,
            'telefone' => '11999999999',
            'email' => 'joao@email.com'
        ];

        $response = $this->postJson('/api/v1/cooperados', $cooperadoData);
        $cooperadoId = $response->json('data.id');

        $updateData = [
            'nome' => 'João Silva Atualizado',
            'renda_faturamento' => 6000.00
        ];

        $response = $this->putJson("/api/v1/cooperados/{$cooperadoId}", $updateData);

        $response->assertStatus(200);
        $this->assertDatabaseHas('cooperados', [
            'id' => $cooperadoId,
            'nome' => 'João Silva Atualizado',
            'renda_faturamento' => 6000.00
        ]);
    }

    public function test_can_delete_cooperado()
    {
        // Criar cooperado diretamente sem usar Factory
        $cooperadoData = [
            'nome' => 'João Silva',
            'documento' => '12345678909',
            'tipo_pessoa' => 'PF',
            'data_nascimento' => '1990-05-15',
            'renda_faturamento' => 5000.00,
            'telefone' => '11999999999',
            'email' => 'joao@email.com'
        ];

        $response = $this->postJson('/api/v1/cooperados', $cooperadoData);
        $cooperadoId = $response->json('data.id');

        $response = $this->deleteJson("/api/v1/cooperados/{$cooperadoId}");

        $response->assertStatus(204);
        $this->assertSoftDeleted('cooperados', ['id' => $cooperadoId]);
    }

    public function test_validation_requires_data_nascimento_for_pf()
    {
        $data = [
            'nome' => 'João Silva',
            'documento' => '12345678909', // CPF válido
            'tipo_pessoa' => 'PF',
            'renda_faturamento' => 5000.00,
            'telefone' => '11999999999'
        ];

        $response = $this->postJson('/api/v1/cooperados', $data);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['data_nascimento']);
    }

    public function test_validation_requires_data_constituicao_for_pj()
    {
        $data = [
            'nome' => 'Empresa LTDA',
            'documento' => '12345678000195', // CNPJ válido
            'tipo_pessoa' => 'PJ',
            'renda_faturamento' => 100000.00,
            'telefone' => '1133333333'
        ];

        $response = $this->postJson('/api/v1/cooperados', $data);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['data_constituicao']);
    }

    public function test_validation_minimum_age_for_pf()
    {
        $data = [
            'nome' => 'João Silva',
            'documento' => '12345678909', // CPF válido
            'tipo_pessoa' => 'PF',
            'data_nascimento' => '2010-05-15', // Menor de 18 anos
            'renda_faturamento' => 5000.00,
            'telefone' => '11999999999'
        ];

        $response = $this->postJson('/api/v1/cooperados', $data);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['data_nascimento']);
    }

    public function test_health_check()
    {
        $response = $this->getJson('/api/v1/health');

        $response->assertStatus(200);
        $response->assertJson(['status' => 'ok']);
    }
}
