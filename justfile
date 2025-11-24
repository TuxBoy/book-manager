# Justfile pour simplifier les commandes dev

# -----------------------------------------
# Variables
# -----------------------------------------
php-cs-fixer := "vendor/bin/php-cs-fixer"
phpunit := "vendor/bin/phpunit"
phpstan := "vendor/bin/phpstan"

# -----------------------------------------
# Commandes
# -----------------------------------------

dev:
    symfony server:start -d
    docker compose up -d

# Vérifie le style de code Symfony sans modifier les fichiers
cs-check:
    {{php-cs-fixer}} fix --dry-run --diff --allow-risky=yes

# Corrige le code automatiquement selon Symfony standards
cs-fix:
    {{php-cs-fixer}} fix --allow-risky=yes

# Lance tous les tests PHPUnit (unitaires + fonctionnels)
test:
    {{phpunit}} --testdox

# Lance PHPStan (analyse statique)
phpstan:
    {{phpstan}} analyse --no-progress

# Lance tests + PHPStan + CS check (tout en une commande)
ci:
    just test
    just phpstan
    just cs-check
