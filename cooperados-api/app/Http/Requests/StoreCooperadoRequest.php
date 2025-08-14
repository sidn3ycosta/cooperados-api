<?php

namespace App\Infrastructure\Http\Requests;

use App\Domain\Cooperado\Enums\TipoPessoa;
use App\Domain\Cooperado\ValueObjects\{Cpf, Cnpj, Telefone, Email};
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCooperadoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nome' => 'required|string|max:255',
            'documento' => [
                'required',
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
                'unique:cooperados,documento'
            ],
            'tipo_pessoa' => ['required', Rule::in(['PF', 'PJ'])],
            
            // Campos específicos por tipo de pessoa
            'data_nascimento' => [
                'required_if:tipo_pessoa,PF',
                'date',
                'before_or_equal:today',
                function ($attribute, $value, $fail) {
                    $idade = now()->diff(new \DateTime($value))->y;
                    if ($idade < 18) {
                        $fail('Cooperado deve ter pelo menos 18 anos.');
                    }
                }
            ],
            
            'data_constituicao' => [
                'required_if:tipo_pessoa,PJ',
                'date',
                'before_or_equal:today'
            ],
            
            'renda_faturamento' => 'required|numeric|min:0.01|max:999999999.99',
            'telefone' => [
                'required',
                'string',
                'max:15',
                function ($attribute, $value, $fail) {
                    $telefoneNormalizado = preg_replace('/[^0-9]/', '', $value);
                    if (strlen($telefoneNormalizado) < 10 || strlen($telefoneNormalizado) > 11) {
                        $fail('Telefone deve ter 10 ou 11 dígitos.');
                    }
                }
            ],
            'email' => 'nullable|email|max:254',
        ];
    }

    public function messages(): array
    {
        return [
            'nome.required' => 'O nome é obrigatório.',
            'nome.max' => 'O nome não pode ter mais de 255 caracteres.',
            'documento.required' => 'O documento é obrigatório.',
            'documento.unique' => 'Este documento já está cadastrado.',
            'tipo_pessoa.required' => 'O tipo de pessoa é obrigatório.',
            'tipo_pessoa.in' => 'Tipo de pessoa deve ser PF ou PJ.',
            
            // Mensagens específicas para campos de data
            'data_nascimento.required_if' => 'A data de nascimento é obrigatória para pessoa física.',
            'data_nascimento.date' => 'A data de nascimento deve ser uma data válida.',
            'data_nascimento.before_or_equal' => 'A data de nascimento não pode ser no futuro.',
            
            'data_constituicao.required_if' => 'A data de constituição é obrigatória para pessoa jurídica.',
            'data_constituicao.date' => 'A data de constituição deve ser uma data válida.',
            'data_constituicao.before_or_equal' => 'A data de constituição não pode ser no futuro.',
            
            'renda_faturamento.required' => 'A renda/faturamento é obrigatória.',
            'renda_faturamento.numeric' => 'A renda/faturamento deve ser um número.',
            'renda_faturamento.min' => 'A renda/faturamento deve ser maior que zero.',
            'renda_faturamento.max' => 'A renda/faturamento não pode ser maior que 999.999.999,99.',
            'telefone.required' => 'O telefone é obrigatório.',
            'email.email' => 'O email deve ser válido.',
            'email.max' => 'O email não pode ter mais de 254 caracteres.',
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $this->validateDocumento($validator);
            $this->validateDataIdentificacao($validator);
        });
    }

    private function validateDocumento($validator): void
    {
        $documento = $this->input('documento');
        $tipoPessoa = $this->input('tipo_pessoa');

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

    private function validateDataIdentificacao($validator): void
    {
        $tipoPessoa = $this->input('tipo_pessoa');

        if ($tipoPessoa === TipoPessoa::PESSOA_FISICA->value) {
            $dataNascimento = $this->input('data_nascimento');
            if ($dataNascimento) {
                $data = new \DateTime($dataNascimento);
                $hoje = new \DateTime();
                $idade = $hoje->diff($data)->y;
                
                if ($idade < 18) {
                    $validator->errors()->add('data_nascimento', 'Cooperado deve ter pelo menos 18 anos.');
                }
            }
        } elseif ($tipoPessoa === TipoPessoa::PESSOA_JURIDICA->value) {
            $dataConstituicao = $this->input('data_constituicao');
            if ($dataConstituicao) {
                $data = new \DateTime($dataConstituicao);
                $hoje = new \DateTime();
                
                if ($data > $hoje) {
                    $validator->errors()->add('data_constituicao', 'Data de constituição não pode ser no futuro.');
                }
            }
        }
    }
}
