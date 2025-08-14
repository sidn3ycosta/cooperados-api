.PHONY: help up down build logs shell composer-install migrate seed test artisan

help: ## Mostra esta ajuda
	@echo "Comandos disponíveis:"
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-20s\033[0m %s\n", $$1, $$2}'

up: ## Inicia os containers
	docker-compose up -d

down: ## Para os containers
	docker-compose down

build: ## Constrói os containers
	docker-compose build --no-cache

logs: ## Mostra os logs dos containers
	docker-compose logs -f

shell: ## Acessa o shell do container da aplicação
	docker-compose exec app bash

db-shell: ## Acessa o shell do banco de dados
	docker-compose exec db psql -U cooperados_user -d cooperados_db

composer-install: ## Instala as dependências do Composer
	docker-compose exec app composer install

composer-update: ## Atualiza as dependências do Composer
	docker-compose exec app composer update

migrate: ## Executa as migrations
	docker-compose exec app php artisan migrate

migrate-fresh: ## Executa as migrations do zero
	docker-compose exec app php artisan migrate:fresh

seed: ## Executa os seeders
	docker-compose exec app php artisan db:seed

test: ## Executa os testes
	docker-compose exec app php artisan test

artisan: ## Executa um comando Artisan (ex: make artisan cmd="make:controller TestController")
	docker-compose exec app php artisan $(cmd)

key: ## Gera a chave da aplicação
	docker-compose exec app php artisan key:generate

clear: ## Limpa o cache da aplicação
	docker-compose exec app php artisan cache:clear
	docker-compose exec app php artisan config:clear
	docker-compose exec app php artisan route:clear
	docker-compose exec app php artisan view:clear

status: ## Mostra o status dos containers
	docker-compose ps

restart: ## Reinicia os containers
	docker-compose restart

clean: ## Remove containers, volumes e imagens
	docker-compose down -v --rmi all

setup: ## Configuração inicial do projeto
	@echo "Configurando o projeto..."
	@make up
	@echo "Aguardando containers..."
	@sleep 10
	@make migrate
	@make key
	@echo "Projeto configurado com sucesso!"
	@echo "Acesse: http://localhost:8000"
	@echo "Adminer: http://localhost:8080"
