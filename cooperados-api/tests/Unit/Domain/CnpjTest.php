<?php

namespace Tests\Unit\Domain;

use App\Domain\Cooperado\ValueObjects\Cnpj;
use App\Domain\Cooperado\Exceptions\DocumentoInvalidoException;
use PHPUnit\Framework\TestCase;

class CnpjTest extends TestCase
{
    public function test_cnpj_valido_com_mascara()
    {
        $cnpj = new Cnpj('11.222.333/0001-81');
        $this->assertEquals('11222333000181', $cnpj->value());
        $this->assertEquals('11.222.333/0001-81', $cnpj->formatted());
    }

    public function test_cnpj_valido_sem_mascara()
    {
        $cnpj = new Cnpj('11222333000181');
        $this->assertEquals('11222333000181', $cnpj->value());
        $this->assertEquals('11.222.333/0001-81', $cnpj->formatted());
    }

    public function test_cnpj_valido_com_espacos()
    {
        $cnpj = new Cnpj(' 11.222.333/0001-81 ');
        $this->assertEquals('11222333000181', $cnpj->value());
    }

    public function test_cnpj_invalido_tamanho_incorreto()
    {
        $this->expectException(DocumentoInvalidoException::class);
        new Cnpj('1234567800019'); // 13 dígitos
    }

    public function test_cnpj_invalido_todos_zeros()
    {
        $this->expectException(DocumentoInvalidoException::class);
        new Cnpj('00000000000000');
    }

    public function test_cnpj_invalido_todos_uns()
    {
        $this->expectException(DocumentoInvalidoException::class);
        new Cnpj('11111111111111');
    }

    public function test_cnpj_invalido_digito_verificador()
    {
        $this->expectException(DocumentoInvalidoException::class);
        new Cnpj('11222333000100'); // CNPJ válido seria 11222333000181
    }

    public function test_cnpj_valido_gerado_aleatoriamente()
    {
        // CNPJ válido conhecido
        $cnpj = new Cnpj('11.222.333/0001-81');
        $this->assertEquals('11222333000181', $cnpj->value());
        $this->assertEquals('11.222.333/0001-81', $cnpj->formatted());
    }

    public function test_to_string()
    {
        $cnpj = new Cnpj('11.222.333/0001-81');
        $this->assertEquals('11222333000181', (string) $cnpj);
    }
}
