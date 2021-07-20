<?php

namespace Koralop\HCF\modules\koth;

use Koralop\HCF\HCFLoader;
use Koralop\HCF\modules\koth\commands\KothCommand;
use Koralop\HCF\modules\Modules;
use pocketmine\level\Position;
use pocketmine\utils\TextFormat;

/**
 * Class KothManager
 * @package Koralop\HCF\modules\koth
 */
class KothManager extends Modules
{

    /** @var Koth[] */
    protected array $koth = [];

    /** @var string|null */
    protected ?string $enable = null;

    /** @var string */
    public const PREFIX = TextFormat::DARK_GRAY . '[' . TextFormat::BOLD . TextFormat::GOLD . 'KoTH' . TextFormat::RESET . TextFormat::DARK_GRAY . '] ' . TextFormat::YELLOW . ' ';

    /**
     * @param HCFLoader $loader
     */
    public function onEnable(HCFLoader $loader): void
    {
        $data = HCFLoader::getYamlProvider()->getKothConfig();

        foreach ($data->getAll() as $kothName => $koth) {
            $this->addKoth([
                'pos1' => new Position($koth['pos1'][0], $koth['pos1'][1], $koth['pos1'][2], HCFLoader::getInstance()->getServer()->getLevelByName($koth['pos1'][3])),
                'pos2' => new Position($koth['pos2'][0], $koth['pos2'][1], $koth['pos2'][2], HCFLoader::getInstance()->getServer()->getLevelByName($koth['pos2'][3])),
                'name' => $kothName
            ]);
        }

        HCFLoader::getInstance()->getLogger()->info(TextFormat::AQUA . 'Koth(s) ' . count($data->getAll()) . ' load');
        $loader->getServer()->getCommandMap()->register('/koth', new KothCommand($this));
    }

    public function onDisable(HCFLoader $loader): void
    {
        $data = HCFLoader::getYamlProvider()->getKothConfig();
        $kothData = [];

        foreach ($this->koth as $kothName => $koth) {
            $kothData[$kothName] = [
                'pos1' => [
                    $koth->getPosition1()->getFloorX(),
                    $koth->getPosition1()->getFloorY(),
                    $koth->getPosition1()->getFloorZ(),
                    $koth->getPosition1()->getLevel()->getName()
                ],
                'pos2' => [
                    $koth->getPosition2()->getFloorX(),
                    $koth->getPosition2()->getFloorY(),
                    $koth->getPosition2()->getFloorZ(),
                    $koth->getPosition2()->getLevel()->getName()
                ]
            ];
        }

        $data->setAll($kothData);
        $data->save();
    }

    /**
     * @param array $config
     */
    public function addKoth(array $config): void
    {
        $this->koth[$config['name']] = new Koth($config);
    }

    /**
     * @param string $kothName
     */
    public function removeKoth(string $kothName): void
    {
        unset($this->koth[$kothName]);
    }

    /**
     * @param string $kothName
     * @return Koth
     */
    public function getKoth(string $kothName): Koth
    {
        return $this->koth[$kothName];
    }

    /**
     * @param string $kothName
     * @return bool
     */
    public function isKoth(string $kothName): bool
    {
        return isset($this->koth[$kothName]);
    }

    /**
     * @return array
     */
    public function getAllKoths(): array
    {
        return $this->koth;
    }

    /**
     * @return string|null
     */
    public function getKothEnable(): ?string
    {
        return $this->enable;
    }

    /**
     * @param string|null $kothName
     */
    public function setKothEnable(?string $kothName): void
    {
        $this->enable = $kothName;
    }
}