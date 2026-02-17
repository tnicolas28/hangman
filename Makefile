.PHONY: test phpstan cs-fix cs-check quality help

help: ## Affiche cette aide
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | \
	awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-15s\033[0m %s\n", $$1, $$2}'

test: ## Lance les tests PHPUnit
	php vendor/bin/phpunit

phpstan: ## Analyse statique PHPStan
	php vendor/bin/phpstan analyse

cs-fix: ## Corrige le style de code
	php vendor/bin/php-cs-fixer fix --allow-risky=yes

cs-check: ## Verifie le style de code (sans modifier)
	php vendor/bin/php-cs-fixer fix --dry-run --diff --allow-risky=yes

quality: cs-check phpstan test ## Lance tous les controles qualite
	@echo "Tout est vert !"
