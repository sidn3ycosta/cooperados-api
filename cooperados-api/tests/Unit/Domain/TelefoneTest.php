<?php

namespace Tests\Unit\Domain;

use App\Domain\Cooperado\ValueObjects\Telefone;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class TelefoneTest extends TestCase
{
    public function test_telefone_fixo_valido_com_mascara()
    {
        $telefone = new Telefone('(11) 3333-3333');
        $this->assertEquals('1133333333', $telefone->value());
        $this->assertEquals('(11) 3333-3333', $telefone->formatted());
        $this->assertTrue($telefone->isFixo());
        $this->assertFalse($telefone->isCelular());
    }

    public function test_telefone_celular_valido_com_mascara()
    {
        $telefone = new Telefone('(11) 99999-9999');
        $this->assertEquals('11999999999', $telefone->value());
        $this->assertEquals('(11) 99999-9999', $telefone->formatted());
        $this->assertTrue($telefone->isCelular());
        $this->assertFalse($telefone->isFixo());
    }

    public function test_telefone_valido_sem_mascara()
    {
        $telefone = new Telefone('11999999999');
        $this->assertEquals('11999999999', $telefone->value());
        $this->assertEquals('(11) 99999-9999', $telefone->formatted());
    }

    public function test_telefone_valido_com_espacos()
    {
        $telefone = new Telefone(' (11) 99999-9999 ');
        $this->assertEquals('11999999999', $telefone->value());
    }

    public function test_telefone_invalido_tamanho_menor()
    {
        $this->expectException(InvalidArgumentException::class);
        new Telefone('119999999'); // 9 dígitos
    }

    public function test_telefone_invalido_tamanho_maior()
    {
        $this->expectException(InvalidArgumentException::class);
        new Telefone('119999999999'); // 12 dígitos
    }

    public function test_telefone_invalido_ddd_menor()
    {
        $this->expectException(InvalidArgumentException::class);
        new Telefone('(10) 99999-9999'); // DDD 10 não existe
    }

    public function test_telefone_invalido_ddd_maior()
    {
        $this->expectException(InvalidArgumentException::class);
        new Telefone('(100) 99999-9999'); // DDD 100 não existe
    }

    public function test_telefone_celular_invalido_nono_digito()
    {
        $this->expectException(InvalidArgumentException::class);
        new Telefone('(11) 59999-9999'); // Nono dígito 5 não é válido para celular
    }

    public function test_telefone_celular_valido_nono_digito_6()
    {
        $telefone = new Telefone('(11) 69999-9999');
        $this->assertTrue($telefone->isCelular());
    }

    public function test_telefone_celular_valido_nono_digito_7()
    {
        $telefone = new Telefone('(11) 79999-9999');
        $this->assertTrue($telefone->isCelular());
    }

    public function test_telefone_celular_valido_nono_digito_8()
    {
        $telefone = new Telefone('(11) 89999-9999');
        $this->assertTrue($telefone->isCelular());
    }

    public function test_telefone_celular_valido_nono_digito_9()
    {
        $telefone = new Telefone('(11) 99999-9999');
        $this->assertTrue($telefone->isCelular());
    }

    public function test_to_string()
    {
        $telefone = new Telefone('(11) 99999-9999');
        $this->assertEquals('11999999999', (string) $telefone);
    }
}
