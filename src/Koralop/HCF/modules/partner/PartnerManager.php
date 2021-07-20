<?php

namespace Koralop\HCF\modules\partner;

use Koralop\HCF\HCFLoader;
use Koralop\HCF\HCFPlayer;
use Koralop\HCF\modules\Modules;
use Koralop\HCF\modules\partner\commands\PartnerCommand;
use Koralop\HCF\modules\partner\events\PartnerListener;
use Koralop\HCF\modules\partner\types\StormBreaker;
use Koralop\HCF\utils\commands\SubCommand;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

/**
 * Class PartnerManager
 * @package Koralop\HCF\modules\partner
 */
class PartnerManager extends Modules
{

    /** @var Item[] */
    protected array $items = [];

    /**
     * @param HCFLoader $loader
     */
    public function onEnable(HCFLoader $loader): void
    {
        $data = HCFLoader::getYamlProvider()->getPartnerConfig();

        foreach ($data->getAll() as $slot => $item)
            $this->items[] = Item::jsonDeserialize($item);

        $items = [
            new StormBreaker()
        ];

        $files = glob(__DIR__ . DIRECTORY_SEPARATOR . 'types' . DIRECTORY_SEPARATOR . '*.php');

        foreach ($files as $file) {

            require($file);

            $dir = str_replace('.php', '', $file);
            $class = new $dir();

            if ($class instanceof lPartner)
                ItemFactory::registerItem($class, true);

        }
        $loader->getServer()->getCommandMap()->register('/pp', new PartnerCommand());
        $loader->getServer()->getPluginManager()->registerEvents(new PartnerListener($this), $loader);
    }

    /**
     * @param HCFLoader $loader
     */
    public function onDisable(HCFLoader $loader): void
    {
        $data = HCFLoader::getYamlProvider()->getPartnerConfig();
        $items = [];

        foreach ($this->items as $item) {
            $items[] = $item->jsonSerialize();
        }

        $data->setAll($items);
        $data->save();
    }

    /**
     * @param Player $player
     * @param int $amount
     */
    public static function givePartnerPackage(Player $player, int $amount): void
    {
        $item = Item::get(ItemIds::ENDER_CHEST, 0, $amount);

        $item->setCustomName(TextFormat::RESET . TextFormat::GOLD . TextFormat::BOLD . 'Partner Package');
        $item->setLore([TextFormat::GRAY . 'Click to open a partner package.']);

        $nbt = $item->getNamedTag();
        $nbt->setString('PartnerPackage', '');
        $item->setCompoundTag($nbt);

        $player->getInventory()->addItem($item);
    }

    /**
     * @param HCFPlayer $player
     * @param int $amount
     * @param int $repeat
     */
    public function randItems(HCFPlayer $player, int $amount, int $repeat = 0): bool
    {
        if ($repeat != $amount) {

            $player->getInventory()->addItem($this->items[array_rand($this->items)]);

            return $this->randItems($player, $amount, $repeat + 1);
        }
        return true;
    }

    /**
     * @param array $items
     */
    public function setItems(array $items): void
    {
        foreach ($items as $slot => $item)
            $this->items[] = $item;
    }
}