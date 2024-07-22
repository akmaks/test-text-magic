SHELL = /bin/bash
PHP_BIN = php
DC_RUN_ARGS = --rm --user "$(shell id -u):$(shell id -g)"

help: ## Show this help
	@printf "\033[33m%s:\033[0m\n" 'Available commands'
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z0-9_-]+:.*?## / {printf "  \033[32m%-18s\033[0m %s\n", $$1, $$2}' $(MAKEFILE_LIST)

up: ## Start app
	yes | bin/console doctrine:fixtures:load
	@printf "\n   \e[30;42m %s \033[0m\n\n" 'Navigate your browser to ⇒ http://127.0.0.1:8000'
	APP_UID=$(shell id -u) APP_GID=$(shell id -g) docker-compose up --build --detach --remove-orphans; \
    $(PHP_BIN) -S localhost:8000 -t public;

phpstan: ## Code errors and bugs analyser
	vendor/bin/phpstan analyse

phpcs: ## Code style analyzer
	./vendor/bin/phpcs

phpcbf: ## Code style fixer
	./vendor/bin/phpcbf

phpmd: ## Code smells detector
	./vendor/bin/phpmd ./src text rulesets.xml

add-githooks: ## Add code checker in githook pre-commit
	chmod +x ./scripts/check.sh
	chmod +x ./.githooks/pre-commit
	git config core.hooksPath .githooks