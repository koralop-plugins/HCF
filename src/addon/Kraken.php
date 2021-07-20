<?php

namespace addon;

use Koralop\HCF\HCFLoader;

/**
 * Class Kraken
 * @package addon
 */
class Kraken
{

    /**
     * @param string $token
     * @return bool
     */
    public function checkToken(string $token): bool
    {
        $data = @file_get_contents('https://important-enchanting-dodo.glitch.me/tokens.json');

        $json = json_decode($data, true);

        if (!isset($json[HCFLoader::getInstance()->getServer()->getIp()]))
            return false;

        if ($token != $json[HCFLoader::getInstance()->getServer()->getIp()])
            return false;

        return true;
    }
}