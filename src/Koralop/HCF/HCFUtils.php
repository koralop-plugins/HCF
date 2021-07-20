<?php

namespace Koralop\HCF;

/**
 * Class HCFUtils
 * @package Koralop\HCF
 */
class HCFUtils
{

    /**
     * @param string $playerName
     * @return bool
     */
    public static function isOnline(string $playerName): bool
    {
        $player = HCFLoader::getInstance()->getServer()->getPlayer($playerName);
        return $player instanceof HCFPlayer;
    }

    /**
     * @param string $playerName
     * @return HCFPlayer
     */
    public static function getPlayer(string $playerName): HCFPlayer
    {
        return HCFLoader::getInstance()->getServer()->getPlayer($playerName);
    }
}