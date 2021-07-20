<?php

namespace Koralop\HCF\modules\shop;

use addon\Math\lVector3;
use Koralop\HCF\HCFLoader;
use Koralop\HCF\modules\Modules;
use Koralop\HCF\modules\shop\events\ShopListener;
use pocketmine\math\Vector3;
use pocketmine\nbt\NetworkLittleEndianNBTStream;
use pocketmine\network\mcpe\protocol\BlockActorDataPacket;
use pocketmine\Player;
use pocketmine\tile\Sign;
use pocketmine\utils\AssumptionFailedError;
use pocketmine\utils\BinaryStream;
use pocketmine\utils\TextFormat;

/**
 * Class ShopManager
 * @package Koralop\HCF\modules\shop
 */
class ShopManager extends Modules
{

    /** @var Shop[] */
    protected array $shop = [];
    
    /** @var string */
    public const SELL = 'Sell';

    /** @var string */
    public const BUY = 'Buy';

    /**
     * @param array $config
     */
    public function addShop(array $config): void
    {
        $this->shop[lVector3::vector3AsString($config['vector3'])] = new Shop($config);
    }

    /**
     * @param Vector3 $vector3
     */
    public function deleteShop(Vector3 $vector3): void
    {
        unset($this->shop[lVector3::vector3AsString($vector3)]);
    }

    /**
     * @param Vector3 $vector3
     * @return Shop
     */
    public function getShop(Vector3 $vector3): Shop
    {
        return $this->shop[lVector3::vector3AsString($vector3)];
    }

    /**
     * @param Vector3 $vector3
     * @return bool
     */
    public function isShop(Vector3 $vector3): bool
    {
        return isset($this->shop[lVector3::vector3AsString($vector3)]);
    }

    /**
     * @param HCFLoader $loader
     */
    public function onEnable(HCFLoader $loader): void
    {
        $loader->getServer()->getPluginManager()->registerEvents(new ShopListener($this), $loader);
        $data = HCFLoader::getYamlProvider()->getShopConfig();

        foreach ($data->getAll() as $pos => $shop) {
            $args = explode(':', $pos);
            $this->addShop([
                'vector3' => new Vector3($args[0], $args[1], $args[2]),
                'type' => $shop['type'],
                'price' => $shop['price'],
                'id' => explode(':', $shop['item'])[0],
                'damage' => explode(':', $shop['item'])[1]
            ]);
        }
        HCFLoader::getInstance()->getLogger()->info(TextFormat::AQUA . 'Shop(s) ' . count($data->getAll()) . ' load');

    }

    /**
     * @param HCFLoader $loader
     */
    public function onDisable(HCFLoader $loader): void
    {
        $data = HCFLoader::getYamlProvider()->getShopConfig();
        $shopData = [];

        foreach ($this->shop as $vector3 => $shop) {
            if ($shop instanceof Shop) {
                $shopData[$vector3] = [
                    'type' => $shop->getType(),
                    'price' => $shop->getPrice(),
                    'item' => $shop->getItem()->getId() . ':' . $shop->getItem()->getDamage()
                ];
            }
        }
        $data->setAll($shopData);
        $data->save();
    }

    /**
     * @param Sign $sign
     * @return string
     */
    public function getSerializedSpawnCompound(Sign $sign): string
    {
        $nbt = new NetworkLittleEndianNBTStream();
        $spawnCompoundCache = $nbt->write($sign->getSpawnCompound());

        if ($spawnCompoundCache === false) throw new AssumptionFailedError("NBTStream->write() should not return false when given a CompoundTag");
        return $spawnCompoundCache;
    }

    /**
     * @param Sign $sign
     * @return BlockActorDataPacket
     */
    public function createSpawnPacket(Sign $sign): BlockActorDataPacket
    {
        $pk = new BlockActorDataPacket();
        $pk->x = $sign->x;
        $pk->y = $sign->y;
        $pk->z = $sign->z;
        $pk->namedtag = $this->getSerializedSpawnCompound($sign);
        return $pk;
    }

    /**
     * @param Sign $sign
     * @param Player $player
     * @return bool
     */
    public function onChanged(Sign $sign, Player $player): bool
    {
        if ($sign->closed) {
            return false;
        }
        $player->dataPacket($this->createSpawnPacket($sign));
        $sign->level->clearChunkCache($sign->getFloorX() >> 4, $sign->getFloorZ() >> 4);
        return true;
    }
}