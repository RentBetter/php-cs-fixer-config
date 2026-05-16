# ptgs/php-cs-fixer-config

Shared PHP-CS-Fixer config — one package, one ruleset, every project the same. Change the standard in one place instead of editing N `.php-cs-fixer.dist.php` files.

## Install

```bash
composer require --dev ptgs/php-cs-fixer-config
```

Until this is on Packagist, add the VCS repository to your project `composer.json`:

```json
"repositories": [
    {"type": "vcs", "url": "https://github.com/RentBetter/php-cs-fixer-config"}
]
```

And use `dev-main` as the constraint:

```json
"require-dev": {
    "ptgs/php-cs-fixer-config": "dev-main"
}
```

You do **not** need to declare `friendsofphp/php-cs-fixer` or `kubawerlos/php-cs-fixer-custom-fixers` yourself — they come in as transitive dependencies.

## Use

Drop this into `.php-cs-fixer.dist.php` at your project root:

```php
<?php

return PTGS\PhpCsFixerConfig\StandardConfig::create(__DIR__);
```

Then `vendor/bin/php-cs-fixer fix` works as normal.

## What's in the ruleset

- **Base:** `@Symfony`
- **Risky (enabled):** `declare_strict_types`, `strict_param`
- **FQCN simplification:** `fully_qualified_strict_types` with `import_symbols: true` plus `global_namespace_import` — converts FQCN type hints and PHPDoc into imports. Method bodies are not touched (use Rector's `ImportFullyQualifiedNamesRector` if you need that).
- **Style:** concat with spaces, no cast space, single-line empty body, multiline promoted properties, trailing commas everywhere (arguments, arrays, match, parameters), `@phpdoc_align` left, blank line before `return`/`throw`/`try`/`break`/`continue`/`declare`.
- **Custom fixers** (from `kubawerlos/php-cs-fixer-custom-fixers`): comment surrounded by spaces, constructor empty braces, empty function body, PHPDoc single-line var, PHPDoc types comma spaces, single space before statement, no Doctrine migrations generated comment.
- **Runtime:** parallel runner via `ParallelConfigFactory::detect()`, `setUnsupportedPhpVersionAllowed(true)` for PHP 8.5+.

The complete, authoritative ruleset lives in [`src/StandardConfig.php`](src/StandardConfig.php).

## Override a rule in one project

Prefer changing the standard for everyone. But if you genuinely need a one-off exception, override after `create()`:

```php
<?php

use PTGS\PhpCsFixerConfig\StandardConfig;

$config = StandardConfig::create(__DIR__);
$config->setRules(array_merge(
    StandardConfig::rules(),
    [
        'declare_strict_types' => false,
    ],
));

return $config;
```

## Changing the standard

1. Edit `src/StandardConfig.php`
2. Commit and push to `main`
3. In each consumer, run `composer update ptgs/php-cs-fixer-config`

Because every project pins `dev-main`, a `composer update` is enough — no version bumps needed.

When you change the standard, **run `vendor/bin/php-cs-fixer fix` in every consumer project as part of the same change**, so the new rules are applied uniformly. Drift between "config updated" and "code updated" makes future diffs noisy.

