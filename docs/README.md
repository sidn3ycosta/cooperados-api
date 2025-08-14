# 🏦 API de Cooperados - Unicred

API completa para cadastro de cooperados desenvolvida em Laravel 11 com arquitetura DDD (Domain-Driven Design) e Clean Architecture.

## 🚀 Tecnologias

- **PHP 8.3**
- **Laravel 11**
- **PostgreSQL 15**
- **Docker & Docker Compose**
- **DDD (Domain-Driven Design)**
- **Clean Architecture**

## 🏗️ Arquitetura

### Estrutura DDD
```
app/
├── Domain/                    # Camada de Domínio
│   └── Cooperado/
│       ├── Entities/         # Entidades de negócio
│       ├── ValueObjects/     # Objetos de valor (CPF, CNPJ, Telefone, Email)
│       ├── Repositories/     # Interfaces dos repositórios
│       ├── Exceptions/       # Exceções de domínio
│       └── Enums/           # Enumerações (TipoPessoa)
├── Application/              # Camada de Aplicação
│   └── Cooperado/
│       ├── DTOs/            # Objetos de transferência de dados
│       ├── Services/        # Casos de uso da aplicação
│       └── Contracts/       # Contratos/interfaces
├── Infrastructure/           # Camada de Infraestrutura
│   ├── Persistence/         # Persistência de dados
│   ├── Http/               # Controllers, Requests, Resources
│   └── Providers/          # Service Providers
└── Shared/                  # Código compartilhado
```

### Padrões Utilizados
- **Repository Pattern** - Abstração de dados
- **Service Layer** - Lógica de negócio
- **DTO Pattern** - Transferência de dados
- **Value Objects** - Validação e encapsulamento
- **Dependency Injection** - Inversão de dependências

## 📋 Funcionalidades

### CRUD Completo
- ✅ **Criar** cooperado (Pessoa Física ou Jurídica)
- ✅ **Visualizar** cooperado por ID
- ✅ **Listar** cooperados com paginação e filtros
- ✅ **Atualizar** cooperado
- ✅ **Excluir** cooperado (Soft Delete)

### Validações Brasileiras
- ✅ **CPF** - Validação completa com dígitos verificadores
- ✅ **CNPJ** - Validação completa com dígitos verificadores
- ✅ **Telefone** - Formato brasileiro (10/11 dígitos)
- ✅ **Email** - Validação RFC + normalização

### Regras de Negócio
- ✅ **Unicidade** de documento (CPF/CNPJ)
- ✅ **Idade mínima** 18 anos para PF
- ✅ **Data de referência** não pode ser no futuro
- ✅ **Renda/Faturamento** deve ser positivo
- ✅ **Soft Delete** - Preserva histórico

## 🚀 Como Executar

### Pré-requisitos
- Docker
- Docker Compose
- Make (opcional)

### 1. Clone o repositório
```bash
git clone <url-do-repositorio>
cd cooperados-api
```

### 2. Configure o ambiente
```bash
# Copie o arquivo de ambiente
cp env.example .env

# Configure as variáveis se necessário
# (As configurações padrão já estão corretas para Docker)
```

### 3. Execute com Docker
```bash
# Inicia todos os serviços
make up

# Ou manualmente:
docker-compose up -d
```

### 4. Configure a aplicação
```bash
# Instala dependências
make composer-install

# Executa migrations
make migrate

# Gera chave da aplicação
make key
```

### 5. Acesse a aplicação
- **API**: http://localhost:8000
- **Adminer (Banco)**: http://localhost:8080

## 🛠️ Comandos Úteis

```bash
# Ver todos os comandos disponíveis
make help

# Gerenciar containers
make up          # Inicia containers
make down        # Para containers
make restart     # Reinicia containers
make status      # Status dos containers

# Desenvolvimento
make shell       # Acessa shell da aplicação
make db-shell    # Acessa shell do banco
make logs        # Visualiza logs

# Laravel
make migrate     # Executa migrations
make test        # Executa testes
make artisan cmd="make:controller TestController"  # Comando Artisan

# Cache
make clear       # Limpa todos os caches
```

## 📚 Endpoints da API

### Base URL
```
http://localhost:8000/api/v1
```

### Endpoints

#### 1. Criar Cooperado
```http
POST /cooperados
```

**Exemplo de Request (Pessoa Física):**
```json
{
    "nome": "João Silva",
    "documento": "123.456.789-09",
    "tipo_pessoa": "PF",
    "data_nascimento": "1990-05-15",
    "renda_faturamento": 5000.00,
    "telefone": "(11) 99999-9999",
    "email": "joao@email.com"
}
```

**Exemplo de Request (Pessoa Jurídica):**
```json
{
    "nome": "Empresa LTDA",
    "documento": "12.345.678/0001-90",
    "tipo_pessoa": "PJ",
    "data_constituicao": "2010-01-01",
    "renda_faturamento": 100000.00,
    "telefone": "(11) 3333-3333",
    "email": "contato@empresa.com"
}
```

#### 2. Listar Cooperados
```http
GET /cooperados?page=1&per_page=15&nome=João&tipo_pessoa=PF
```

**Parâmetros de Query:**
- `page` - Página atual (padrão: 1)
- `per_page` - Itens por página (padrão: 15)
- `nome` - Filtro por nome (busca parcial)
- `documento` - Filtro por documento
- `tipo_pessoa` - Filtro por tipo (PF/PJ)

#### 3. Visualizar Cooperado
```http
GET /cooperados/{id}
```

#### 4. Atualizar Cooperado
```http
PUT /cooperados/{id}
```

**Exemplo de Request (atualização parcial):**
```json
{
    "nome": "João Silva Santos",
    "renda_faturamento": 6000.00
}
```

#### 5. Excluir Cooperado
```http
DELETE /cooperados/{id}
```

## 🧪 Testes

### Executar Testes
```bash
make test
```

### Cobertura de Testes
- **Unitários**: Value Objects, Services, DTOs
- **Feature**: Endpoints da API
- **Integração**: Repositórios, Validações

## 📊 Banco de Dados

### Estrutura da Tabela
```sql
CREATE TABLE cooperados (
    id UUID PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    documento VARCHAR(18) UNIQUE NOT NULL,
    tipo_pessoa ENUM('PF', 'PJ') NOT NULL,
    data_nascimento DATE NULL,
data_constituicao DATE NULL,
    renda_faturamento DECIMAL(15,2) NOT NULL,
    telefone VARCHAR(15) NOT NULL,
    email VARCHAR(254) NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    deleted_at TIMESTAMP NULL
);
```

### Índices
- `documento` - UNIQUE (inclui soft-deleted)
- `nome` - Para busca por nome
- `tipo_pessoa` - Para filtros
- `data_nascimento` - Para ordenação de PFs
- `data_constituicao` - Para ordenação de PJs
- `renda_faturamento` - Para relatórios

## 🔒 Segurança

### Validações
- **Input Sanitization** - Limpeza automática de dados
- **SQL Injection** - Protegido pelo Eloquent ORM
- **XSS Protection** - Headers de segurança do Laravel
- **Rate Limiting** - Proteção contra ataques de força bruta

### Soft Delete
- **Preserva histórico** de todos os registros
- **Mantém unicidade** de documentos mesmo deletados
- **Auditoria completa** de operações

## 🚀 Deploy

### Produção
```bash
# Build da imagem
make build

# Deploy
docker-compose -f docker-compose.prod.yml up -d
```

### Variáveis de Ambiente
```bash
APP_ENV=production
APP_DEBUG=false
DB_HOST=your-db-host
DB_DATABASE=your-db-name
DB_USERNAME=your-db-user
DB_PASSWORD=your-db-password
```

## 📈 Monitoramento

### Logs
- **Estruturados** em formato JSON
- **Correlação** por request ID
- **Níveis** de log configuráveis

### Métricas
- **Performance** de endpoints
- **Uso de memória** e CPU
- **Queries** de banco de dados

## 🤝 Contribuição

### Padrões de Código
- **PSR-12** - Padrões de codificação
- **PHPStan** - Análise estática
- **Laravel Pint** - Formatação automática

### Commits
- **Conventional Commits** - Padrão de mensagens
- **Branch Strategy** - Git Flow
- **Code Review** - Obrigatório para PRs

## 📝 Licença

Este projeto foi desenvolvido para o processo seletivo da **Unicred**.

## 👨‍💻 Autor

**Sidney Costa** - Software Engineer Sênior

---

## 🎯 Diferenciais do Projeto

### 1. **Arquitetura Sólida**
- DDD implementado corretamente
- Separação clara de responsabilidades
- Código testável e manutenível

### 2. **Validações Brasileiras**
- CPF/CNPJ com algoritmos oficiais
- Telefone no formato brasileiro
- Regras de negócio específicas

### 3. **Performance**
- Índices otimizados no banco
- Paginação eficiente
- Cache configurado

### 4. **DevOps**
- Docker com um comando
- Makefile para produtividade
- CI/CD ready

### 5. **Qualidade**
- Testes automatizados
- Cobertura de código
- Padrões PSR-12
