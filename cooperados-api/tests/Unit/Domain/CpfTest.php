<?php

namespace Tests\Unit\Domain;

use App\Domain\Cooperado\ValueObjects\Cpf;
use App\Domain\Cooperado\Exceptions\DocumentoInvalidoException;
use PHPUnit\Framework\TestCase;

class CpfTest extends TestCase
{
    public function test_cpf_valido_com_mascara()
    {
        $cpf = new Cpf('123.456.789-09');
        $this->assertEquals('12345678909', $cpf->value());
        $this->assertEquals('123.456.789-09', $cpf->formatted());
    }

    public function test_cpf_valido_sem_mascara()
    {
        $cpf = new Cpf('12345678909');
        $this->assertEquals('12345678909', $cpf->value());
        $this->assertEquals('123.456.789-09', $cpf->formatted());
    }

    public function test_cpf_valido_com_espacos()
    {
        $cpf = new Cpf(' 123.456.789-09 ');
        $this->assertEquals('12345678909', $cpf->value());
    }

    public function test_cpf_invalido_tamanho_incorreto()
    {
        $this->expectException(DocumentoInvalidoException::class);
        new Cpf('1234567890'); // 10 dígitos
    }

    public function test_cpf_invalido_todos_zeros()
    {
        $this->expectException(DocumentoInvalidoException::class);
        new Cpf('00000000000');
    }

    public function test_cpf_invalido_todos_uns()
    {
        $this->expectException(DocumentoInvalidoException::class);
        new Cpf('11111111111');
    }

    public function test_cpf_invalido_digito_verificador()
    {
        $this->expectException(DocumentoInvalidoException::class);
        new Cpf('12345678900'); // CPF válido seria 12345678909
    }

    public function test_cpf_valido_gerado_aleatoriamente()
    {
        // CPF válido conhecido
        $cpf = new Cpf('529.982.247-25');
        $this->assertEquals('52998224725', $cpf->value());
        $this->assertEquals('529.982.247-25', $cpf->formatted());
    }

    public function test_to_string()
    {
        $cpf = new Cpf('123.456.789-09');
        $this->assertEquals('12345678909', (string) $cpf);
    }
}
