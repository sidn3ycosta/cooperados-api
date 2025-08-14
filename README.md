# 🏦 API de Cooperados - Unicred

## 📋 Visão Geral

Este é o repositório principal da **API de Cooperados** desenvolvida para a Unicred, implementando arquitetura **DDD (Domain-Driven Design)** com **Laravel 11**.

## 🚀 Quick Start

### Pré-requisitos
- Docker
- Docker Compose
- Make (opcional)

### Execução Rápida
```bash
# Navegar para o projeto
cd cooperados-api

# Executar com Docker
make up
# ou
docker compose up -d

# Acessar a API
# http://localhost:8081
```

## 📚 Documentação

- **📖 Documentação Técnica Completa**: [docs/README.md](docs/README.md)
- **🔧 Exemplos de API**: [docs/EXEMPLOS_API.md](docs/EXEMPLOS_API.md)
- **📱 Guia Postman**: [docs/README-Postman.md](docs/README-Postman.md)

## 🧭 Navegação Rápida para Desenvolvedores

**🎯 Primeira visita**: Leia `README.md` da raiz  
**🚀 Quer executar**: Siga o Quick Start  
**🔧 Quer detalhes técnicos**: Vá para `docs/README.md`  
**📱 Quer testar**: Use `docs/README-Postman.md`  
**💡 Quer exemplos**: Consulte `docs/EXEMPLOS_API.md`

## 🏗️ Estrutura do Projeto

```
📁 / (Raiz - Repositório Principal)
├── 📁 docs/ (Documentação Completa)
│   ├── 📄 README.md (Documentação Técnica Detalhada)
│   ├── 📄 EXEMPLOS_API.md (Exemplos de Uso)
│   ├── 📄 README-Postman.md (Guia Postman)
│   ├── 📄 Cooperados-API.postman_collection.json
│   └── 📄 Cooperados-API.postman_environment.json
└── 📁 cooperados-api/ (Projeto Laravel + DDD)
    ├── 🏗️ app/ (DDD: Domain, Application, Infrastructure)
    ├── 📁 database/ (Migrations, Factories, Seeders)
    ├── 📁 tests/ (Testes Unitários e Feature)
    ├── 📁 routes/ (Rotas Laravel)
    ├── 📁 config/ (Configurações Laravel)
    ├── 📁 docker/ (Configurações Docker)
    ├── 📄 docker-compose.yml
    ├── 📄 Dockerfile
    ├── 📄 Makefile
    ├── 📄 composer.json
    └── 📄 artisan
```

## 🎯 O que é este Projeto?

**API REST** para cadastro de cooperados desenvolvida como **teste técnico** para a Unicred, demonstrando:

- **Arquitetura DDD** bem implementada
- **Clean Architecture** com separação de responsabilidades
- **Validações brasileiras** (CPF/CNPJ, telefone, email)
- **Padrões de desenvolvimento** profissionais
- **Containerização** com Docker

## 🛠️ Tecnologias Principais

- **Backend**: Laravel 11 + PHP 8.3
- **Banco**: PostgreSQL 15
- **Containerização**: Docker + Docker Compose
- **Testes**: PHPUnit
- **Arquitetura**: DDD + Clean Architecture

## 📝 Licença

MIT License

---

**💡 Dica**: Para informações técnicas detalhadas, consulte a [documentação completa](docs/README.md).
