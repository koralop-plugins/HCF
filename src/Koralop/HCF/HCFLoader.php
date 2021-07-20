<?php

namespace Koralop\HCF;

use Koralop\HCF\block\BlockManager;
use Koralop\HCF\entity\EntityManager;
use Koralop\HCF\events\InventoryListener;
use Koralop\HCF\events\ItemListener;
use Koralop\HCF\events\PlayerListener;
use Koralop\HCF\factions\FactionManager;
use Koralop\HCF\item\ItemManager;
use Koralop\HCF\modules\ModulesManager;
use Koralop\HCF\player\cooldowns\CooldownManager;
use Koralop\HCF\player\data\DataManager;
use Koralop\HCF\player\PlayerManager;
use Koralop\HCF\provider\YamlProvider;
use Koralop\HCF\scheduler\HCFTask;
use Koralop\HCF\scoreboard\ScoreboardManager;
use Koralop\HCF\tile\TileManager;
use muqsit\invmenu\InvMenu;
use muqsit\invmenu\InvMenuHandler;
use pocketmine\plugin\PluginBase;

/**
 * Class HCFLoader
 * @package Koralop\HCF
 */
class HCFLoader extends PluginBase
{

    /** @var HCFLoader */
    protected static HCFLoader $loader;
    /** @var YamlProvider */
    protected static YamlProvider $yamlProvider;
    /** @var PlayerManager */
    protected static PlayerManager $playerManager;
    /** @var FactionManager */
    protected static FactionManager $factionManager;
    /** @var ModulesManager */
    protected static ModulesManager $modulesManager;
    /** @var ScoreboardManager */
    protected static ScoreboardManager $scoreboardManager;

    /** @var CooldownManager|null */
    private ?CooldownManager $cooldownManager = null;

    public function onLoad()
    {
        self::$loader = $this;
    }

    public function onEnable(): void
    {

        if (!InvMenuHandler::isRegistered()) {
            InvMenuHandler::register($this);
        }


        $this->getServer()->getPluginManager()->registerEvents(new HCFListener(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new PlayerListener(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new ItemListener(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new InventoryListener(), $this);

        $this->getScheduler()->scheduleRepeatingTask(new HCFTask($this), 20);

        self::$scoreboardManager = new ScoreboardManager($this);

        self::$yamlProvider = new YamlProvider($this);
        self::$playerManager = new PlayerManager($this);
        self::$factionManager = new FactionManager($this);
        self::$modulesManager = new ModulesManager();

        self::$modulesManager->onEnable($this);

        $this->cooldownManager = new CooldownManager;

        new TileManager();
        new ItemManager();
        new BlockManager();
        new EntityManager();
    }

    /**
     * @return HCFLoader
     */
    public static function getInstance(): HCFLoader
    {
        return self::$loader;
    }

    /**
     * @return YamlProvider
     */
    public static function getYamlProvider(): YamlProvider
    {
        return self::$yamlProvider;
    }

    /**
     * @return PlayerManager
     */
    public static function getPlayerManager(): PlayerManager
    {
        return self::$playerManager;
    }

    /**
     * @return FactionManager
     */
    public static function getFactionManager(): FactionManager
    {
        return self::$factionManager;
    }

    /**
     * @return ModulesManager
     */
    public static function getModulesManager(): ModulesManager
    {
        return self::$modulesManager;
    }

    /**
     * @return ScoreboardManager
     */
    public static function getScoreboardManager(): ScoreboardManager
    {
        return self::$scoreboardManager;
    }

    /**
     * @return CooldownManager
     */
    public function getCooldownManager(): CooldownManager
    {
        return $this->cooldownManager;
    }
}
