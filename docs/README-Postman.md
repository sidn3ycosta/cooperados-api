# 🚀 Collection Postman - API de Cooperados

Esta collection do Postman permite testar todos os endpoints da API de Cooperados de forma organizada e eficiente.

## 📁 Arquivos da Collection

- **`Cooperados-API.postman_collection.json`** - Collection principal com todos os endpoints
- **`Cooperados-API.postman_environment.json`** - Variáveis de ambiente para localhost

## 🔧 Como Importar

### 1. Importar a Collection
1. Abra o Postman
2. Clique em **"Import"** (botão azul no canto superior esquerdo)
3. Arraste o arquivo `Cooperados-API.postman_collection.json` ou clique em "Upload Files"
4. Clique em **"Import"**

### 2. Importar o Ambiente
1. No Postman, clique em **"Import"** novamente
2. Arraste o arquivo `Cooperados-API.postman_environment.json`
3. Clique em **"Import"**
4. No seletor de ambiente (canto superior direito), selecione **"Cooperados API - Local"**

## 🌐 Configuração do Ambiente

A collection usa as seguintes variáveis de ambiente:

| Variável | Valor Padrão | Descrição |
|----------|---------------|-----------|
| `base_url` | `http://localhost:8081` | URL base da API |
| `cooperado_id` | (vazio) | ID do cooperado (preenchido automaticamente) |
| `nome_filtro` | (vazio) | Filtro por nome |
| `documento_filtro` | (vazio) | Filtro por documento |
| `tipo_pessoa_filtro` | (vazio) | Filtro por tipo de pessoa |
| `per_page` | `15` | Itens por página |
| `page` | `1` | Número da página |

## 📋 Endpoints Disponíveis

### 🔍 **Health Check**
- **GET** `/up` - Verifica se a aplicação está rodando

### 📊 **Cooperados**
- **GET** `/api/v1/cooperados` - Lista todos os cooperados
- **GET** `/api/v1/cooperados/{id}` - Busca cooperado por ID
- **POST** `/api/v1/cooperados` - Cria novo cooperado
- **PUT** `/api/v1/cooperados/{id}` - Atualiza cooperado
- **DELETE** `/api/v1/cooperados/{id}` - Exclui cooperado (soft delete)

## 🧪 Como Testar

### 1. **Teste Básico - Health Check**
1. Execute o endpoint **"Health Check"**
2. Verifique se retorna status 200 e HTML da página de status

### 2. **Teste de Criação**
1. Execute **"Criar Cooperado - Pessoa Física"**
2. Verifique se retorna status 201 e dados do cooperado criado
3. O ID será automaticamente capturado na variável `cooperado_id`

### 3. **Teste de Listagem**
1. Execute **"Listar Cooperados"**
2. Verifique se retorna status 200 e lista de cooperados
3. Teste com filtros usando as variáveis de ambiente

### 4. **Teste de Busca por ID**
1. Execute **"Buscar Cooperado por ID"**
2. Verifique se retorna status 200 e dados do cooperado

### 5. **Teste de Atualização**
1. Execute **"Atualizar Cooperado"**
2. Verifique se retorna status 200 e dados atualizados

### 6. **Teste de Exclusão**
1. Execute **"Excluir Cooperado"**
2. Verifique se retorna status 204 (sem conteúdo)

### 7. **Teste de Validação**
1. Execute **"Teste de Validação - Dados Inválidos"**
2. Verifique se retorna status 422 com mensagens de erro

### 8. **Teste de Filtros**
1. Execute os endpoints de filtros com diferentes parâmetros
2. Teste paginação alterando `per_page` e `page`

## 📝 Exemplos de Dados

### Pessoa Física (PF)
```json
{
    "nome": "João Silva Santos",
    "documento": "123.456.789-09",
    "tipo_pessoa": "PF",
    "data_nascimento": "1990-05-15",
    "renda_faturamento": 5000.00,
    "telefone": "(11) 99999-9999",
    "email": "joao.silva@email.com"
}
```

### Pessoa Jurídica (PJ)
```json
{
    "nome": "Empresa LTDA",
    "documento": "11.222.333/0001-81",
    "tipo_pessoa": "PJ",
    "data_constituicao": "2010-01-01",
    "renda_faturamento": 100000.00,
    "telefone": "(11) 3333-3333",
    "email": "contato@empresa.com"
}
```

## 🔍 Filtros Disponíveis

### Parâmetros de Query
- `nome` - Busca por nome (case-insensitive)
- `documento` - Busca por documento (CPF/CNPJ)
- `tipo_pessoa` - Filtra por tipo: `PF` ou `PJ`
- `per_page` - Itens por página (padrão: 15)
- `page` - Número da página (padrão: 1)

### Exemplos de Uso
```
GET /api/v1/cooperados?nome=João
GET /api/v1/cooperados?tipo_pessoa=PF
GET /api/v1/cooperados?per_page=5&page=1
GET /api/v1/cooperados?nome=Maria&tipo_pessoa=PF&per_page=10
```

## ⚠️ Validações

### Campos Obrigatórios
- `nome` - Não pode ser vazio
- `documento` - CPF ou CNPJ válido
- `tipo_pessoa` - Deve ser "PF" ou "PJ"
- `data_nascimento` - Data de nascimento para PF (obrigatório)
- `data_constituicao` - Data de constituição para PJ (obrigatório)
- `renda_faturamento` - Número positivo
- `telefone` - Formato brasileiro válido

### Regras de Negócio
- CPF deve ter 11 dígitos e ser matematicamente válido
- CNPJ deve ter 14 dígitos e ser matematicamente válido
- Telefone deve ter DDD válido e formato correto
- Email deve ter formato válido
- Data de referência deve ser uma data válida

## 🚀 Automação

### Script de Teste
A collection inclui um script que:
- Captura automaticamente o ID do cooperado criado
- Armazena na variável `cooperado_id`
- Permite testes sequenciais sem intervenção manual

### Fluxo de Teste Recomendado
1. Health Check
2. Criar Cooperado
3. Listar Cooperados
4. Buscar por ID
5. Atualizar Cooperado
6. Testar Filtros
7. Excluir Cooperado
8. Verificar Soft Delete

## 📊 Respostas da API

### Estrutura Padrão
```json
{
    "data": [...],
    "meta": {
        "total": 0,
        "per_page": 15,
        "current_page": 1,
        "last_page": 1,
        "from": null,
        "to": null
    },
    "links": {
        "first": "...",
        "last": "...",
        "prev": null,
        "next": null
    }
}
```

### Códigos de Status
- `200` - Sucesso (GET, PUT)
- `201` - Criado (POST)
- `204` - Sem conteúdo (DELETE)
- `422` - Erro de validação
- `404` - Não encontrado
- `500` - Erro interno

## 🛠️ Troubleshooting

### Problemas Comuns
1. **API não responde** - Verifique se o Docker está rodando
2. **Erro de conexão** - Verifique a URL base no ambiente
3. **Erro de validação** - Verifique o formato dos dados enviados
4. **ID não encontrado** - Execute primeiro um endpoint de criação

### Verificações
- Docker Compose rodando: `docker compose ps`
- API acessível: `curl http://localhost:8081/up`
- Banco de dados: `docker compose logs db`

## 📚 Recursos Adicionais

- **Adminer**: http://localhost:8080 (gerenciar banco de dados)
- **Logs da API**: `docker compose logs app`
- **Logs do Nginx**: `docker compose logs web`

---

**🎯 Aproveite a collection para testar todos os aspectos da API de Cooperados!**

