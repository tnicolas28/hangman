.PHONY: test phpstan cs-fix cs-check deptrac quality help

help: ## Affiche cette aide
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | \
	awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-15s\033[0m %s\n", $$1, $$2}'

test: ## Lance les tests PHPUnit
	php vendor/bin/phpunit

phpstan: ## Analyse statique PHPStan
	php -d memory_limit=512M vendor/bin/phpstan analyse

cs-fix: ## Corrige le style de code
	php vendor/bin/php-cs-fixer fix --allow-risky=yes

cs-check: ## Verifie le style de code (sans modifier)
	php vendor/bin/php-cs-fixer fix --dry-run --diff --allow-risky=yes

deptrac: ## Verifie les regles d'architecture hexagonale
	php vendor/bin/deptrac analyse

quality: cs-check phpstan deptrac test ## Lance tous les controles qualite
	@echo "Tout est vert !"
