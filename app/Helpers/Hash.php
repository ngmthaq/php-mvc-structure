<?php

namespace App\Helpers;

use Exception;

final class Hash
{
    final public static function make(string $string, string $algo = "sha256")
    {
        if (in_array($algo, hash_algos())) {
            return hash($algo, $string);
        }

        throw new Exception("Hashing algorithms is not supported");
    }

    final public static function check(string $raw, string $hashed, string $algo = "sha256"): bool
    {
        return hash_equals($hashed, self::make($raw, $algo));
    }
}
