<?php

namespace App\Infrastructure\Persistence\Eloquent\Models;

use App\Domain\Cooperado\Entities\Cooperado;
use App\Domain\Cooperado\Enums\TipoPessoa;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class CooperadoModel extends Cooperado
{
    use HasUuids, SoftDeletes;

    protected $table = 'cooperados';

    protected $fillable = [
        'nome',
        'documento',
        'tipo_pessoa',
        'data_nascimento',
        'data_constituicao',
        'renda_faturamento',
        'telefone',
        'email',
    ];

    protected $casts = [
        'tipo_pessoa' => TipoPessoa::class,
        'data_nascimento' => 'date',
        'data_constituicao' => 'date',
        'renda_faturamento' => 'decimal:2',
        'email' => 'string',
    ];

    protected $hidden = [
        'deleted_at',
    ];

    // Configuração de UUID
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    // Configuração de timestamps
    public $timestamps = true;

    // Configuração de soft deletes
    protected $dates = ['deleted_at'];
}
