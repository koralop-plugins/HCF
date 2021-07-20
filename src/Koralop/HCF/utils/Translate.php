<?php

namespace Koralop\HCF\utils;

use pocketmine\utils\TextFormat;

/**
 * Class Translation
 * @package Koralop\HCF\utils
 */
final class Translate
{


    /**
     * @param string $message
     * @param array $args
     * @return string
     */
    public static function getMessage(string $message, array $args = []): string
    {
        $result = $message;

        foreach ($args as $arg => $value) {
            $result = str_replace('%' . $arg . '%', $value, $message);
        }

        return str_replace(['&', '%line%'], ['§', PHP_EOL], $result);
    }

}