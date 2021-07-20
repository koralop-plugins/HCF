<?php

namespace Koralop\HCF\provider;

use Koralop\HCF\HCFLoader;

use pocketmine\utils\Config;

/**
 * Class YamlProvider
 * @package Koralop\HCF\provider
 */
class YamlProvider
{

    /** @var HCFLoader */
    protected HCFLoader $loader;

    /**
     * YamlProvider constructor.
     * @param HCFLoader $loader
     */
    public function __construct(HCFLoader $loader)
    {
        $loader->saveResource('config.yml');

        $this->loader = $loader;

        @mkdir($loader->getDataFolder() . 'backups');
        @mkdir($loader->getDataFolder() . 'players');
        @mkdir($loader->getDataFolder() . 'factions');
        @mkdir($loader->getDataFolder() . 'factions' . DIRECTORY_SEPARATOR . 'db');
    }

    /**
     * @return Config
     */
    public function getDefaultConfig(): Config
    {
        return $this->loader->getConfig();
    }

    /**
     * @return array
     */
    public function getCooldowns(): array
    {
        return $this->getDefaultConfig()->get('timers');
    }

    /**
     * @return Config
     */
    public function getDeathsConfig(): Config
    {
        return new Config($this->loader->getDataFolder() . 'deaths.yml', Config::YAML);
    }

    /**
     * @return Config
     */
    public function getKillsConfig(): Config
    {
        return new Config($this->loader->getDataFolder() . 'kills.yml', Config::YAML);

    }

    /**
     * @return Config
     */
    public function getCrateConfig(): Config
    {
        return new Config($this->loader->getDataFolder() . 'backups' . DIRECTORY_SEPARATOR . 'crates.yml', Config::YAML);
    }

    /**
     * @return Config
     */
    public function getKothConfig(): Config
    {
        return new Config($this->loader->getDataFolder() . 'backups' . DIRECTORY_SEPARATOR . 'koth.yml', Config::YAML);
    }

    /**
     * @return Config
     */
    public function getKitConfig(): Config
    {
        return new Config($this->loader->getDataFolder() . 'backups' . DIRECTORY_SEPARATOR . 'kit.yml', Config::YAML);
    }

    /**
     * @return Config
     */
    public function getConfig(): Config
    {
        return new Config($this->loader->getDataFolder() . 'config.yml', Config::YAML);
    }

    /**
     * @return array
     */
    public function getFactionConfig(): array
    {
        return $this->getDefaultConfig()->get('factions');
    }

    /**
     * @return Config
     */
    public function getShopConfig(): Config
    {
        return new Config($this->loader->getDataFolder() . 'backups' . DIRECTORY_SEPARATOR . 'shop.yml', Config::YAML);
    }

    /**
     * @return Config
     */
    public function getTimerConfig(): Config
    {
        return new Config($this->loader->getDataFolder() . 'backups' . DIRECTORY_SEPARATOR . 'timer.yml', Config::YAML);
    }

    /**
     * @return Config
     */
    public function getPartnerConfig(): Config
    {
        return new Config($this->loader->getDataFolder() . 'backups' . DIRECTORY_SEPARATOR . 'partner.yml', Config::YAML);
    }

    /**
     * @return array
     */
    public function getPlayers(): array
    {
        $players = [];
        $files = glob(HCFLoader::getInstance()->getDataFolder() . 'players' . DIRECTORY_SEPARATOR . '*.yml');

        foreach ($files as $file) {
            $a = explode('.', basename($file));
            $players[] = $a[0];
        }
        return $players;
    }

    /**
     * @return array
     */
    public function getCommandsBlockedInCombatTag(): array
    {
        return $this->getDefaultConfig()->get('combat')['command-list'];
    }

    /**
     * @return array
     */
    public function getFactions(): array
    {
        $factions = [];
        $files = glob(HCFLoader::getInstance()->getDataFolder() . 'factions' . DIRECTORY_SEPARATOR . 'db' . DIRECTORY_SEPARATOR . '*.yml');

        foreach ($files as $file) {
            $a = explode('.', basename($file));
            $factions[] = $a[0];
        }
        return $factions;
    }
}