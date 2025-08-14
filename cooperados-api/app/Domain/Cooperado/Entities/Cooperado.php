<?php

namespace App\Domain\Cooperado\Entities;

use App\Domain\Cooperado\Enums\TipoPessoa;
use App\Domain\Cooperado\ValueObjects\{Cpf, Cnpj, Telefone, Email};

use DateTime;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cooperado extends Model
{
    use HasUuids, SoftDeletes;

    public $incrementing = false;
    protected $keyType = 'string';

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

    // Value Objects
    private ?Cpf $cpf = null;
    private ?Cnpj $cnpj = null;
    private ?Telefone $telefoneObj = null;
    private ?Email $emailObj = null;

    public function getCpf(): ?Cpf
    {
        if ($this->tipo_pessoa === TipoPessoa::PESSOA_FISICA && $this->cpf === null) {
            $this->cpf = new Cpf($this->documento);
        }
        return $this->cpf;
    }

    public function getCnpj(): ?Cnpj
    {
        if ($this->tipo_pessoa === TipoPessoa::PESSOA_JURIDICA && $this->cnpj === null) {
            $this->cnpj = new Cnpj($this->documento);
        }
        return $this->cnpj;
    }

    public function getTelefoneObj(): Telefone
    {
        if ($this->telefoneObj === null) {
            $this->telefoneObj = new Telefone($this->telefone);
        }
        return $this->telefoneObj;
    }

    public function getEmailObj(): Email
    {
        if ($this->emailObj === null) {
            $this->emailObj = new Email($this->email);
        }
        return $this->emailObj;
    }

    public function getDocumentoFormatado(): string
    {
        return match($this->tipo_pessoa) {
            TipoPessoa::PESSOA_FISICA => $this->getCpf()?->formatted() ?? $this->documento,
            TipoPessoa::PESSOA_JURIDICA => $this->getCnpj()?->formatted() ?? $this->documento,
        };
    }

    public function getTelefoneFormatado(): string
    {
        return $this->getTelefoneObj()->formatted();
    }

    public function isPessoaFisica(): bool
    {
        return $this->tipo_pessoa === TipoPessoa::PESSOA_FISICA;
    }

    public function isPessoaJuridica(): bool
    {
        return $this->tipo_pessoa === TipoPessoa::PESSOA_JURIDICA;
    }

    // Novos métodos para campos específicos
    public function getDataIdentificacao(): ?DateTime
    {
        return $this->isPessoaFisica() 
            ? $this->data_nascimento 
            : $this->data_constituicao;
    }

    public function getDataIdentificacaoFormatada(): ?string
    {
        $data = $this->getDataIdentificacao();
        return $data ? $data->format('d/m/Y') : null;
    }

    public function getDataNascimentoFormatada(): ?string
    {
        return $this->data_nascimento ? $this->data_nascimento->format('d/m/Y') : null;
    }

    public function getDataConstituicaoFormatada(): ?string
    {
        return $this->data_constituicao ? $this->data_constituicao->format('d/m/Y') : null;
    }

    public function getIdade(): ?int
    {
        if (!$this->data_nascimento || !$this->isPessoaFisica()) {
            return null;
        }
        
        return now()->diff($this->data_nascimento)->y;
    }

    public function getTempoConstituicao(): ?int
    {
        if (!$this->data_constituicao || !$this->isPessoaJuridica()) {
            return null;
        }
        
        return now()->diff($this->data_constituicao)->y;
    }

    public function getTipoPessoaLabel(): string
    {
        return $this->tipo_pessoa->label();
    }

    // Mutators para normalização automática
    public function setDocumentoAttribute($value): void
    {
        $this->attributes['documento'] = preg_replace('/[^0-9]/', '', $value);
    }

    public function setTelefoneAttribute($value): void
    {
        $this->attributes['telefone'] = preg_replace('/[^0-9]/', '', $value);
    }

    public function setEmailAttribute($value): void
    {
        $this->attributes['email'] = $value ? strtolower(trim($value)) : null;
    }

    // Scopes para consultas
    public function scopeAtivos($query)
    {
        return $query->whereNull('deleted_at');
    }

    public function scopePorTipoPessoa($query, TipoPessoa $tipo)
    {
        return $query->where('tipo_pessoa', $tipo);
    }

    public function scopePorDocumento($query, string $documento)
    {
        $documentoNormalizado = preg_replace('/[^0-9]/', '', $documento);
        return $query->where('documento', $documentoNormalizado);
    }

    public function scopePorNome($query, string $nome)
    {
        return $query->where('nome', 'like', "%{$nome}%");
    }

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory()
    {
        return \Database\Factories\CooperadoFactory::new();
    }
}
