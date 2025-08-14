<?php

namespace App\Infrastructure\Http\Requests;

use App\Domain\Cooperado\Enums\TipoPessoa;
use App\Domain\Cooperado\ValueObjects\{Cpf, Cnpj, Telefone, Email};
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCooperadoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $cooperadoId = $this->route('cooperado');
        
        return [
            'nome' => 'sometimes|string|max:255',
            'documento' => [
                'sometimes',
                'string',
                'max:18',
                function ($attribute, $value, $fail) {
                    $tipoPessoa = $this->input('tipo_pessoa');
                    $documentoNormalizado = preg_replace('/[^0-9]/', '', $value);
                    
                    if ($tipoPessoa === TipoPessoa::PESSOA_FISICA->value) {
                        if (strlen($documentoNormalizado) !== 11) {
                            $fail('CPF deve ter 11 dígitos.');
                        }
                    } elseif ($tipoPessoa === TipoPessoa::PESSOA_JURIDICA->value) {
                        if (strlen($documentoNormalizado) !== 14) {
                            $fail('CNPJ deve ter 14 dígitos.');
                        }
                    }
                },
                Rule::unique('cooperados', 'documento')->ignore($cooperadoId)
            ],
            'tipo_pessoa' => ['sometimes', Rule::in(['PF', 'PJ'])],
            'data_referencia' => 'sometimes|date|before_or_equal:today',
            'renda_faturamento' => 'sometimes|numeric|min:0.01|max:999999999.99',
            'telefone' => [
                'sometimes',
                'string',
                'max:15',
                function ($attribute, $value, $fail) {
                    $telefoneNormalizado = preg_replace('/[^0-9]/', '', $value);
                    if (strlen($telefoneNormalizado) < 10 || strlen($telefoneNormalizado) > 11) {
                        $fail('Telefone deve ter 10 ou 11 dígitos.');
                    }
                }
            ],
            'email' => 'sometimes|nullable|email|max:254',
        ];
    }

    public function messages(): array
    {
        return [
            'nome.string' => 'O nome deve ser uma string.',
            'nome.max' => 'O nome não pode ter mais de 255 caracteres.',
            'documento.string' => 'O documento deve ser uma string.',
            'documento.unique' => 'Este documento já está cadastrado.',
            'tipo_pessoa.in' => 'Tipo de pessoa deve ser PF ou PJ.',
            'data_referencia.date' => 'A data de referência deve ser uma data válida.',
            'data_referencia.before_or_equal' => 'A data de referência não pode ser no futuro.',
            'renda_faturamento.numeric' => 'A renda/faturamento deve ser um número.',
            'renda_faturamento.min' => 'A renda/faturamento deve ser maior que zero.',
            'renda_faturamento.max' => 'A renda/faturamento não pode ser maior que 999.999.999,99.',
            'telefone.string' => 'O telefone deve ser uma string.',
            'email.email' => 'O email deve ser válido.',
            'email.max' => 'O email não pode ter mais de 254 caracteres.',
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $this->validateDocumento($validator);
            $this->validateDataReferencia($validator);
        });
    }

    private function validateDocumento($validator): void
    {
        $documento = $this->input('documento');
        $tipoPessoa = $this->input('tipo_pessoa');

        if ($documento && $tipoPessoa) {
            try {
                if ($tipoPessoa === TipoPessoa::PESSOA_FISICA->value) {
                    new Cpf($documento);
                } elseif ($tipoPessoa === TipoPessoa::PESSOA_JURIDICA->value) {
                    new Cnpj($documento);
                }
            } catch (\Exception $e) {
                $validator->errors()->add('documento', $e->getMessage());
            }
        }
    }

    private function validateDataReferencia($validator): void
    {
        $dataReferencia = $this->input('data_referencia');
        $tipoPessoa = $this->input('tipo_pessoa');

        if ($dataReferencia && $tipoPessoa) {
            $data = new \DateTime($dataReferencia);
            $hoje = new \DateTime();

            if ($tipoPessoa === TipoPessoa::PESSOA_FISICA->value) {
                $idade = $hoje->diff($data)->y;
                if ($idade < 18) {
                    $validator->errors()->add('data_referencia', 'Cooperado deve ter pelo menos 18 anos.');
                }
            }
        }
    }
}
