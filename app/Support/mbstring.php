<?php

/**
 * Fallbacks for PHP environments where ext-mbstring is unavailable.
 *
 * Symfony's mbstring polyfill intentionally leaves a few native functions out,
 * but Laravel and its console dependencies still call them.
 */
if (! function_exists('mb_split')) {
    function mb_split(string $pattern, string $string, int $limit = -1): array|false
    {
        $delimiter = '~';
        $regex = $delimiter.str_replace($delimiter, '\\'.$delimiter, $pattern).$delimiter.'u';

        return preg_split($regex, $string, $limit);
    }
}

if (! function_exists('mb_strimwidth')) {
    function mb_strimwidth(
        string $string,
        int $start,
        int $width,
        string $trim_marker = '',
        ?string $encoding = null
    ): string {
        $encoding ??= mb_internal_encoding() ?: 'UTF-8';
        $string = mb_substr($string, $start, null, $encoding);

        if (mb_strwidth($string, $encoding) <= $width) {
            return $string;
        }

        $targetWidth = max(0, $width - mb_strwidth($trim_marker, $encoding));
        $result = '';

        foreach (mb_str_split($string, 1, $encoding) as $character) {
            if (mb_strwidth($result.$character, $encoding) > $targetWidth) {
                break;
            }

            $result .= $character;
        }

        return $result.$trim_marker;
    }
}

if (! function_exists('mb_strcut')) {
    function mb_strcut(string $string, int $start, ?int $length = null, ?string $encoding = null): string
    {
        $encoding ??= mb_internal_encoding() ?: 'UTF-8';
        $cut = $length === null ? substr($string, $start) : substr($string, $start, $length);

        while ($cut !== '' && ! mb_check_encoding($cut, $encoding)) {
            $cut = substr($cut, 0, -1);
        }

        return $cut;
    }
}
