DOCKER_EXEC = docker compose exec app

help: ## Show this help
	@printf "\033[33m%s:\033[0m\n" 'Available commands'
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z0-9_-]+:.*?## / {printf "  \033[32m%-18s\033[0m %s\n", $$1, $$2}' $(MAKEFILE_LIST)

setup: ## Setup app
	docker compose stop
	yes | docker compose rm -v
	docker compose up -d --build

run: ## Run test command
	$(DOCKER_EXEC) bin/console app:test

phpstan: ## Code errors and bugs analyser
	$(DOCKER_EXEC) vendor/bin/phpstan analyse

phpcs: ## Code style analyzer
	$(DOCKER_EXEC) vendor/bin/phpcs

phpcbf: ## Code style fixer
	$(DOCKER_EXEC) vendor/bin/phpcbf

phpmd: ## Code smells detector
	$(DOCKER_EXEC) vendor/bin/phpmd ./src text rulesets.xml

check: ## Run all checkers
	chmod +x ./scripts/check.sh
	$(DOCKER_EXEC) scripts/check.sh

test: ## Run tests
	$(DOCKER_EXEC) vendor/bin/phpunit

add-check-githook: ## Add code checker in githook pre-commit (local php is required)
	chmod +x ./scripts/check.sh
	chmod +x ./.githooks/pre-commit
	git config core.hooksPath .githooks