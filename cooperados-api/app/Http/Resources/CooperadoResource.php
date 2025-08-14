<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CooperadoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nome' => $this->nome,
            'documento' => $this->documento,
            'documento_formatado' => $this->getDocumentoFormatado(),
            'tipo_pessoa' => $this->tipo_pessoa->value,
            'tipo_pessoa_label' => $this->getTipoPessoaLabel(),
            'data_nascimento' => $this->data_nascimento?->format('Y-m-d'),
            'data_constituicao' => $this->data_constituicao?->format('Y-m-d'),
            'renda_faturamento' => $this->renda_faturamento,
            'telefone' => $this->telefone,
            'telefone_formatado' => $this->getTelefoneFormatado(),
            'email' => $this->email,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
