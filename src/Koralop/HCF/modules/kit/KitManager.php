<?php

namespace Koralop\HCF\modules\kit;

use Koralop\HCF\HCFLoader;
use Koralop\HCF\HCFPlayer;
use Koralop\HCF\modules\kit\commands\KitCommand;
use Koralop\HCF\modules\kit\events\KitListener;
use Koralop\HCF\modules\kit\menu\KitMenu;
use Koralop\HCF\modules\kit\types\Archer;
use Koralop\HCF\modules\kit\types\Bard;
use Koralop\HCF\modules\kit\types\Mage;
use Koralop\HCF\modules\kit\types\Miner;
use Koralop\HCF\modules\kit\types\Rogue;
use Koralop\HCF\modules\Modules;
use pocketmine\item\Item;
use pocketmine\utils\TextFormat;

/**
 * Class KitManager
 * @package Koralop\HCF\modules\kit
 */
class KitManager extends Modules
{

    /** @var  Kit[] */
    protected array $kits = [];
    
    /** @var string */
    public const PREFIX = TextFormat::DARK_GRAY . '[' . TextFormat::BOLD . TextFormat::GOLD . 'Kit' . TextFormat::RESET . TextFormat::DARK_GRAY . '] ' . TextFormat::YELLOW . ' ';

    /** @var string[] */
    public const KIT_IDS = [
        0 => 'Archer',
        1 => 'Bard',
        2 => 'Mage',
        3 => 'Miner',
        4 => 'Rogue'
    ];
    /**
     * @param HCFLoader $loader
     */
    public function onEnable(HCFLoader $loader): void
    {
        $data = HCFLoader::getYamlProvider()->getKitConfig();
        $items = [];
        $armor = [];

        foreach ($data->getAll() as $kitName => $value) {
            $kit = $data->getAll()[$kitName];

            if (isset($kit['items'])) {
                foreach ($kit['items'] as $number => $item) {
                    $items[$kitName][$number] = Item::jsonDeserialize($item);
                }
            }

            if (isset($kit['armorItems'])) {
                foreach ($kit['armorItems'] as $number => $armor) {
                    $armor[$kitName][$number] = Item::jsonDeserialize($armor);
                }
            }

            $this->addKit([
                'items' => (isset($items[$kitName]) ? $items[$kitName] : []),
                'armorItems' => (isset($armor[$kitName]) ? $armor[$kitName] : []),
                'item' => $kit['item'],
                'slot' => $kit['slot'],
                'permission' => $kit['permission'],
                'format' => $kit['format'],
                'name' => $kitName
            ]);
        }

        HCFLoader::getInstance()->getLogger()->info(TextFormat::AQUA . 'Kit(s) ' . count($data->getAll()) . ' load');

        $loader->getServer()->getCommandMap()->register('/kit', new KitCommand());

        $loader->getServer()->getPluginManager()->registerEvents(new KitListener(), $loader);
    }

    /**
     * @param HCFLoader $loader
     */
    public function onDisable(HCFLoader $loader): void
    {
        $data = HCFLoader::getYamlProvider()->getKitConfig();
        $kitData = [];
        $armor = [];
        $items = [];

        foreach ($this->getAllKits() as $kitName => $kit) {
            if ($kit instanceof Kit) {
                foreach ($kit->getItems() as $number => $item) {
                    $items[$kitName][$number] = $item->jsonSerialize();

                }

                foreach ($kit->getArmorItems() as $number => $armor) {
                    $armor[$kitName][$number] = $armor->jsonSerialize();
                }

                $kitData[$kitName] = [
                    'item' => $kit->getItem(),
                    'slot' => $kit->getSlot(),
                    'permission' => $kit->getPermission(),
                    'format' => $kit->getFormat(),
                    'name' => $kitName,
                    'items' => (isset($items[$kitName]) ? $items[$kitName] : []),
                    'armorItems' => (isset($armor[$kitName]) ? $armor[$kitName] : [])
                ];
            }
        }

        $data->setAll($kitData);
        $data->save();
    }

    /**
     * @param array $kit
     */
    public function addKit(array $kit): void
    {
        $this->kits[$kit['name']] = new Kit($kit);
    }

    /**
     * @param string $kitName
     */
    public function removeKit(string $kitName)
    {
        unset($this->kits[$kitName]);
    }

    /**
     * @param string $kitName
     * @return Kit
     */
    public function getKit(string $kitName): Kit
    {
        return $this->kits[$kitName];
    }

    /**
     * @param string $kitName
     * @return bool
     */
    public function isKit(string $kitName): bool
    {
        return isset($this->kits[$kitName]);
    }

    /**
     * @return Kit[]
     */
    public function getAllKits(): array
    {
        return $this->kits;
    }

    /**
     * @param HCFPlayer $player
     * @return KitMenu
     */
    public function sendMenu(HCFPlayer $player): KitMenu
    {
        return new KitMenu($player, $this);
    }

    public function getClassById($id)
    {
        switch ($id) {
            case 0:
                return new Archer();
            case 1:
                return new Bard();
            case 2:
                return new Mage();
            case 3:
                return new Miner();
            case 4:
                return new Rogue();
        }
    }
}