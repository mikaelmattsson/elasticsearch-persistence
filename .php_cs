<?php

$fixers = [
    // PSR-1
    'encoding',
    'short_tag',

    // Symfony
    'blankline_after_open_tag',
    'concat_without_spaces',
    'duplicate_semicolon',
    'empty_return',
    'extra_empty_lines',
    'include',
    'join_function',
    'list_commas',
    'multiline_array_trailing_comma',
    'namespace_no_leading_whitespace',
    'new_with_braces',
    'no_blank_lines_after_class_opening',
    'no_empty_lines_after_phpdocs',
    'object_operator',
    'operators_spaces',
    'phpdoc_align',
    'phpdoc_indent',
    'phpdoc_no_access',
    'phpdoc_no_package',
    'phpdoc_scalar',
    'phpdoc_separation',
    'phpdoc_short_description',
    'phpdoc_to_comment',
    'phpdoc_trim',
    'phpdoc_type_to_var',
    'phpdoc_var_without_name',
    'remove_leading_slash_use',
    'remove_lines_between_uses',
    'return',
    'self_accessor',
    'single_array_no_trailing_comma',
    'single_blank_line_before_namespace',
    'single_quote',
    'spaces_before_semicolon',
    'spaces_cast',
    'standardize_not_equal',
    'ternary_spaces',
    'trim_array_spaces',
    'unary_operators_spaces',
    'unused_use',
    'whitespacy_lines',

    // Contrib

    'multiline_spaces_before_semicolon',
    'newline_after_open_tag',
    'ordered_use',
    'phpdoc_order',
    'short_array_syntax',
];

$in = [
    'src',
    'test'
];

return \PhpCsFixer\Config::create()
    // use default SYMFONY_LEVEL and extra fixers:
    ->fixers($fixers)
    ->finder(
        \PhpCsFixer\Finder::create()
            ->in($in)
    );
