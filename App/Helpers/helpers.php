<?php
// app/Helpers/helpers.php

if (!function_exists('str_limit')) {
    function str_limit(string $value, int $limit = 100, string $end = '…'): string
    {
        if (mb_strlen($value) <= $limit) {
            return $value;
        }
        return mb_substr($value, 0, $limit) . $end;
    }
}
