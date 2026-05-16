<?php

declare(strict_types=1);

namespace PTGS\PhpCsFixerConfig;

use PhpCsFixer\Config;
use PhpCsFixer\Finder;
use PhpCsFixer\Runner\Parallel\ParallelConfigFactory;
use PhpCsFixerCustomFixers as KubaWerlos;

/**
 * Standard PHP-CS-Fixer config.
 *
 * Default usage in a project's `.php-cs-fixer.dist.php`:
 *
 *     <?php
 *     return PTGS\PhpCsFixerConfig\StandardConfig::create(__DIR__);
 *
 * Override rules locally (rare — prefer changing the standard for everyone):
 *
 *     <?php
 *     $config = PTGS\PhpCsFixerConfig\StandardConfig::create(__DIR__);
 *     $config->setRules(array_merge(
 *         PTGS\PhpCsFixerConfig\StandardConfig::rules(),
 *         ['declare_strict_types' => false],
 *     ));
 *     return $config;
 */
final class StandardConfig
{
    public static function create(string $rootDir): Config
    {
        $finder = new Finder()
            ->in($rootDir)
            ->exclude('var')
        ;

        return new Config()
            ->setParallelConfig(ParallelConfigFactory::detect())
            ->setRiskyAllowed(true)
            ->registerCustomFixers(new KubaWerlos\Fixers())
            ->setRules(self::rules())
            ->setFinder($finder)
            ->setUnsupportedPhpVersionAllowed(true)
        ;
    }

    /**
     * @return array<string, mixed>
     */
    public static function rules(): array
    {
        return [
            '@Symfony' => true,
            'blank_line_before_statement' => ['statements' => ['break', 'continue', 'declare', 'return', 'throw', 'try']],
            'cast_spaces' => ['space' => 'none'],
            'concat_space' => ['spacing' => 'one'],
            'declare_strict_types' => true,
            'function_declaration' => ['closure_fn_spacing' => 'none', 'closure_function_spacing' => 'none'],
            'fully_qualified_strict_types' => ['import_symbols' => true],
            'global_namespace_import' => ['import_classes' => true, 'import_functions' => false, 'import_constants' => false],
            'increment_style' => ['style' => 'post'],
            'multiline_promoted_properties' => true,
            'multiline_whitespace_before_semicolons' => ['strategy' => 'new_line_for_chained_calls'],
            'phpdoc_align' => ['align' => 'left'],
            'single_line_empty_body' => true,
            'single_line_throw' => false,
            'strict_param' => true,
            'trailing_comma_in_multiline' => ['after_heredoc' => true, 'elements' => ['arguments', 'arrays', 'match', 'parameters']],

            // KubaWerlos custom fixers
            KubaWerlos\Fixer\CommentSurroundedBySpacesFixer::name() => true,
            KubaWerlos\Fixer\ConstructorEmptyBracesFixer::name() => true,
            KubaWerlos\Fixer\EmptyFunctionBodyFixer::name() => true,
            KubaWerlos\Fixer\NoDoctrineMigrationsGeneratedCommentFixer::name() => true,
            KubaWerlos\Fixer\PhpdocSingleLineVarFixer::name() => true,
            KubaWerlos\Fixer\PhpdocTypesCommaSpacesFixer::name() => true,
            KubaWerlos\Fixer\SingleSpaceBeforeStatementFixer::name() => true,
        ];
    }
}
