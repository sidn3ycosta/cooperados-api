# 📚 Exemplos de Uso da API de Cooperados

Este arquivo contém exemplos práticos de como usar a API de Cooperados com cURL e outras ferramentas.

## 🚀 Base URL
```
http://localhost:8000/api/v1
```

## 📋 Endpoints Disponíveis

### 1. Criar Cooperado (POST /cooperados)

#### Pessoa Física (CPF)
```bash
curl -X POST http://localhost:8000/api/v1/cooperados \
  -H "Content-Type: application/json" \
  -d '{
    "nome": "João Silva",
    "documento": "123.456.789-09",
    "tipo_pessoa": "PF",
    "data_nascimento": "1990-05-15",
    "renda_faturamento": 5000.00,
    "telefone": "(11) 99999-9999",
    "email": "joao@email.com"
  }'
```

#### Pessoa Jurídica (CNPJ)
```bash
curl -X POST http://localhost:8000/api/v1/cooperados \
  -H "Content-Type: application/json" \
  -d '{
    "nome": "Empresa LTDA",
    "documento": "12.345.678/0001-90",
    "tipo_pessoa": "PJ",
    "data_constituicao": "2010-01-01",
    "renda_faturamento": 100000.00,
    "telefone": "(11) 3333-3333",
    "email": "contato@empresa.com"
  }'
```

#### Sem Email (Opcional)
```bash
curl -X POST http://localhost:8000/api/v1/cooperados \
  -H "Content-Type: application/json" \
  -d '{
    "nome": "Maria Santos",
    "documento": "987.654.321-00",
    "tipo_pessoa": "PF",
    "data_nascimento": "1985-08-20",
    "renda_faturamento": 3500.00,
    "telefone": "(21) 88888-8888"
  }'
```

### 2. Listar Cooperados (GET /cooperados)

#### Listagem Básica
```bash
curl -X GET "http://localhost:8000/api/v1/cooperados"
```

#### Com Paginação
```bash
curl -X GET "http://localhost:8000/api/v1/cooperados?page=1&per_page=10"
```

#### Com Filtros
```bash
# Por nome
curl -X GET "http://localhost:8000/api/v1/cooperados?nome=João"

# Por tipo de pessoa
curl -X GET "http://localhost:8000/api/v1/cooperados?tipo_pessoa=PF"

# Por documento
curl -X GET "http://localhost:8000/api/v1/cooperados?documento=12345678909"

# Combinação de filtros
curl -X GET "http://localhost:8000/api/v1/cooperados?nome=João&tipo_pessoa=PF&page=1&per_page=5"
```

### 3. Visualizar Cooperado (GET /cooperados/{id})

```bash
curl -X GET "http://localhost:8000/api/v1/cooperados/123e4567-e89b-12d3-a456-426614174000"
```

### 4. Atualizar Cooperado (PUT /cooperados/{id})

#### Atualização Parcial
```bash
curl -X PUT http://localhost:8000/api/v1/cooperados/123e4567-e89b-12d3-a456-426614174000 \
  -H "Content-Type: application/json" \
  -d '{
    "nome": "João Silva Santos",
    "renda_faturamento": 6000.00
  }'
```

#### Atualização Completa
```bash
curl -X PUT http://localhost:8000/api/v1/cooperados/123e4567-e89b-12d3-a456-426614174000 \
  -H "Content-Type: application/json" \
  -d '{
    "nome": "João Silva Santos",
    "documento": "123.456.789-09",
    "tipo_pessoa": "PF",
    "data_nascimento": "1990-05-15",
    "renda_faturamento": 6000.00,
    "telefone": "(11) 99999-9999",
    "email": "joao.santos@email.com"
  }'
```

### 5. Excluir Cooperado (DELETE /cooperados/{id})

```bash
curl -X DELETE "http://localhost:8000/api/v1/cooperados/123e4567-e89b-12d3-a456-426614174000"
```

## 🔧 Exemplos com JavaScript (Fetch)

### Criar Cooperado
```javascript
const criarCooperado = async (dados) => {
  try {
    const response = await fetch('http://localhost:8000/api/v1/cooperados', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(dados)
    });
    
    const resultado = await response.json();
    console.log('Cooperado criado:', resultado);
    return resultado;
  } catch (error) {
    console.error('Erro ao criar cooperado:', error);
  }
};

// Uso
criarCooperado({
  nome: "João Silva",
  documento: "123.456.789-09",
  tipo_pessoa: "PF",
  data_nascimento: "1990-05-15",
  renda_faturamento: 5000.00,
  telefone: "(11) 99999-9999",
  email: "joao@email.com"
});
```

### Listar Cooperados
```javascript
const listarCooperados = async (filtros = {}) => {
  try {
    const params = new URLSearchParams(filtros);
    const response = await fetch(`http://localhost:8000/api/v1/cooperados?${params}`);
    const resultado = await response.json();
    console.log('Cooperados:', resultado);
    return resultado;
  } catch (error) {
    console.error('Erro ao listar cooperados:', error);
  }
};

// Uso
listarCooperados({
  page: 1,
  per_page: 10,
  nome: 'João',
  tipo_pessoa: 'PF'
});
```

## 🐍 Exemplos com Python (Requests)

### Criar Cooperado
```python
import requests
import json

def criar_cooperado(dados):
    url = "http://localhost:8000/api/v1/cooperados"
    headers = {"Content-Type": "application/json"}
    
    try:
        response = requests.post(url, json=dados, headers=headers)
        response.raise_for_status()
        return response.json()
    except requests.exceptions.RequestException as e:
        print(f"Erro ao criar cooperado: {e}")
        return None

# Uso
dados = {
    "nome": "João Silva",
    "documento": "123.456.789-09",
    "tipo_pessoa": "PF",
    "data_nascimento": "1990-05-15",
    "renda_faturamento": 5000.00,
    "telefone": "(11) 99999-9999",
    "email": "joao@email.com"
}

resultado = criar_cooperado(dados)
print(f"Cooperado criado: {resultado}")
```

### Listar Cooperados
```python
def listar_cooperados(filtros=None):
    url = "http://localhost:8000/api/v1/cooperados"
    
    try:
        response = requests.get(url, params=filtros)
        response.raise_for_status()
        return response.json()
    except requests.exceptions.RequestException as e:
        print(f"Erro ao listar cooperados: {e}")
        return None

# Uso
filtros = {
    "page": 1,
    "per_page": 10,
    "nome": "João",
    "tipo_pessoa": "PF"
}

resultado = listar_cooperados(filtros)
print(f"Cooperados encontrados: {resultado}")
```

## 📱 Exemplos com Postman

### Collection JSON para Postman
```json
{
  "info": {
    "name": "API Cooperados",
    "description": "API para gerenciamento de cooperados"
  },
  "item": [
    {
      "name": "Criar Cooperado PF",
      "request": {
        "method": "POST",
        "header": [
          {
            "key": "Content-Type",
            "value": "application/json"
          }
        ],
        "body": {
          "mode": "raw",
          "raw": "{\n  \"nome\": \"João Silva\",\n  \"documento\": \"123.456.789-09\",\n  \"tipo_pessoa\": \"PF\",\n  \"data_nascimento\": \"1990-05-15\",\n  \"renda_faturamento\": 5000.00,\n  \"telefone\": \"(11) 99999-9999\",\n  \"email\": \"joao@email.com\"\n}"
        },
        "url": {
          "raw": "http://localhost:8000/api/v1/cooperados",
          "protocol": "http",
          "host": ["localhost"],
          "port": "8000",
          "path": ["api", "v1", "cooperados"]
        }
      }
    },
    {
      "name": "Listar Cooperados",
      "request": {
        "method": "GET",
        "url": {
          "raw": "http://localhost:8000/api/v1/cooperados?page=1&per_page=10",
          "protocol": "http",
          "host": ["localhost"],
          "port": "8000",
          "path": ["api", "v1", "cooperados"],
          "query": [
            {
              "key": "page",
              "value": "1"
            },
            {
              "key": "per_page",
              "value": "10"
            }
          ]
        }
      }
    }
  ]
}
```

## 🧪 Testando Validações

### CPF Inválido
```bash
curl -X POST http://localhost:8000/api/v1/cooperados \
  -H "Content-Type: application/json" \
  -d '{
    "nome": "João Silva",
    "documento": "123.456.789-00",
    "tipo_pessoa": "PF",
    "data_nascimento": "1990-05-15",
    "renda_faturamento": 5000.00,
    "telefone": "(11) 99999-9999"
  }'
```

**Resposta esperada:**
```json
{
  "message": "O CPF '12345678900' é inválido.",
  "errors": {
    "documento": ["O CPF '12345678900' é inválido."]
  }
}
```

### CNPJ Inválido
```bash
curl -X POST http://localhost:8000/api/v1/cooperados \
  -H "Content-Type: application/json" \
  -d '{
    "nome": "Empresa LTDA",
    "documento": "12.345.678/0001-00",
    "tipo_pessoa": "PJ",
    "data_constituicao": "2010-01-01",
    "renda_faturamento": 100000.00,
    "telefone": "(11) 3333-3333"
  }'
```

### Telefone Inválido
```bash
curl -X POST http://localhost:8000/api/v1/cooperados \
  -H "Content-Type: application/json" \
  -d '{
    "nome": "João Silva",
    "documento": "123.456.789-09",
    "tipo_pessoa": "PF",
    "data_nascimento": "1990-05-15",
    "renda_faturamento": 5000.00,
    "telefone": "(11) 9999-9999"
  }'
```

### Documento Duplicado
```bash
# Primeiro, criar um cooperado
curl -X POST http://localhost:8000/api/v1/cooperados \
  -H "Content-Type: application/json" \
  -d '{
    "nome": "João Silva",
    "documento": "123.456.789-09",
    "tipo_pessoa": "PF",
    "data_nascimento": "1990-05-15",
    "renda_faturamento": 5000.00,
    "telefone": "(11) 99999-9999"
  }'

# Depois, tentar criar outro com o mesmo documento
curl -X POST http://localhost:8000/api/v1/cooperados \
  -H "Content-Type: application/json" \
  -d '{
    "nome": "João Silva Santos",
    "documento": "123.456.789-09",
    "tipo_pessoa": "PF",
    "data_nascimento": "1990-05-15",
    "renda_faturamento": 6000.00,
    "telefone": "(11) 88888-8888"
  }'
```

**Resposta esperada:**
```json
{
  "message": "O documento '123.456.789-09' já está cadastrado no sistema."
}
```

## 📊 Respostas da API

### Sucesso (201 - Criado)
```json
{
  "message": "Cooperado criado com sucesso.",
  "data": {
    "id": "123e4567-e89b-12d3-a456-426614174000",
    "nome": "João Silva",
    "documento": "12345678909",
    "documento_formatado": "123.456.789-09",
    "tipo_pessoa": "PF",
    "tipo_pessoa_label": "Pessoa Física",
    "data_nascimento": "1990-05-15",
    "renda_faturamento": 5000.00,
    "telefone": "11999999999",
    "telefone_formatado": "(11) 99999-9999",
    "email": "joao@email.com",
    "created_at": "2024-01-15 10:30:00",
    "updated_at": "2024-01-15 10:30:00"
  }
}
```

### Listagem (200 - OK)
```json
{
  "data": [
    {
      "id": "123e4567-e89b-12d3-a456-426614174000",
      "nome": "João Silva",
      "documento": "12345678909",
      "documento_formatado": "123.456.789-09",
      "tipo_pessoa": "PF",
      "tipo_pessoa_label": "Pessoa Física",
      "data_nascimento": "1990-05-15",
      "renda_faturamento": 5000.00,
      "telefone": "11999999999",
      "telefone_formatado": "(11) 99999-9999",
      "email": "joao@email.com",
      "created_at": "2024-01-15 10:30:00",
      "updated_at": "2024-01-15 10:30:00"
    }
  ],
  "meta": {
    "total": 1,
    "per_page": 15,
    "current_page": 1,
    "last_page": 1,
    "from": 1,
    "to": 1
  },
  "links": {
    "first": "http://localhost:8000/api/v1/cooperados?page=1",
    "last": "http://localhost:8000/api/v1/cooperados?page=1",
    "prev": null,
    "next": null
  }
}
```

### Erro de Validação (422 - Unprocessable Entity)
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "documento": ["O CPF '12345678900' é inválido."],
    "telefone": ["Telefone deve ter 10 ou 11 dígitos. Fornecido: 9 dígitos."]
  }
}
```

### Erro de Conflito (409 - Conflict)
```json
{
  "message": "O documento '123.456.789-09' já está cadastrado no sistema."
}
```

### Erro de Não Encontrado (404 - Not Found)
```json
{
  "message": "Cooperado não encontrado."
}
```

## 🔍 Dicas de Uso

### 1. **Formatação de Documentos**
- A API aceita documentos com ou sem máscara
- CPF: `123.456.789-09` ou `12345678909`
- CNPJ: `12.345.678/0001-90` ou `12345678000190`

### 2. **Formatação de Telefones**
- Fixo: `(11) 3333-3333` ou `1133333333`
- Celular: `(11) 99999-9999` ou `11999999999`

### 3. **Datas**
- Formato: `YYYY-MM-DD`
- Para PF: Data de nascimento (deve ter ≥ 18 anos)
- Para PJ: Data de constituição

### 4. **Valores Monetários**
- Formato: `1234.56` (ponto como separador decimal)
- Mínimo: `0.01`
- Máximo: `999999999.99`

### 5. **Filtros de Listagem**
- `nome`: Busca parcial (case-insensitive)
- `documento`: Busca exata
- `tipo_pessoa`: `PF` ou `PJ`
- `page`: Página atual (padrão: 1)
- `per_page`: Itens por página (padrão: 15, máximo: 100)

### 6. **Tratamento de Erros**
- Sempre verifique o status HTTP
- Erros de validação retornam 422
- Conflitos (documento duplicado) retornam 409
- Recursos não encontrados retornam 404
- Erros internos retornam 500

## 🚀 Próximos Passos

1. **Teste todos os endpoints** com os exemplos acima
2. **Implemente no seu frontend** usando os exemplos de JavaScript
3. **Configure autenticação** se necessário
4. **Monitore os logs** para debugging
5. **Implemente cache** para melhorar performance

---

**💡 Dica:** Use o Adminer (http://localhost:8080) para visualizar os dados diretamente no banco PostgreSQL!
