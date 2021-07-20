<?php

namespace Koralop\HCF\tile\types;

use Koralop\HCF\inventory\types\DropperInventory;
use pocketmine\inventory\Inventory;
use pocketmine\inventory\InventoryHolder;
use pocketmine\item\Item;
use pocketmine\level\Level;
use pocketmine\math\Vector3;
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\Player;
use pocketmine\tile\Container;
use pocketmine\tile\ContainerTrait;
use pocketmine\tile\Nameable;
use pocketmine\tile\NameableTrait;
use pocketmine\tile\Spawnable;

/**
 * Class Dropper
 * @package Koralop\HCF\tile\types
 */
class Dropper extends Spawnable implements InventoryHolder, Container, Nameable
{

    use NameableTrait, ContainerTrait;

    /** @var DropperInventory|null */
    private ?DropperInventory $inventory = null;

    /** @var CompoundTag */
    private CompoundTag $nbt;

    /**
     * Hopper constructor.
     *
     * @param Level $level
     * @param CompoundTag $nbt
     */
    public function __construct(Level $level, CompoundTag $nbt)
    {
        parent::__construct($level, $nbt);
        $this->inventory = new DropperInventory($this);
        $this->loadItems($nbt);
        $this->scheduleUpdate();
    }

    /**
     * @param CompoundTag $nbt
     * @param Vector3 $pos
     * @param int|null $face
     * @param Item|null $item
     * @param Player|null $player
     */
    protected static function createAdditionalNBT(CompoundTag $nbt, Vector3 $pos, ?int $face = null, ?Item $item = null, ?Player $player = null): void
    {
        $nbt->setTag(new ListTag('Items', [], NBT::TAG_Compound));
        if ($item !== null and $item->hasCustomName()) {
            $nbt->setString('CustomName', $item->getCustomName());
        }
    }

    /**
     * @return DropperInventory|Inventory
     */
    public function getRealInventory()
    {
        return $this->inventory;
    }

    /**
     * @return int
     */
    public function getSize(): int
    {
        return 5;
    }

    /**
     * @return string
     */
    public function getDefaultName(): string
    {
        return 'Dropper';
    }

    /**
     * @param CompoundTag $nbt
     */
    public function addAdditionalSpawnData(CompoundTag $nbt): void
    {
        if ($this->hasName()) {
            $nbt->setTag($this->nbt->getTag('CustomName'));
        }
    }

    public function close(): void
    {
        if (!$this->isClosed()) {
            foreach ($this->getInventory()->getViewers() as $viewer) {
                $viewer->removeWindow($this->getInventory());
            }
            parent::close();
        }
    }

    /**
     * @return DropperInventory|Inventory
     */
    public function getInventory()
    {
        return $this->inventory;

    }

    /**
     * @return CompoundTag
     */
    public function saveNBT(): CompoundTag
    {
        $this->saveItems($this->nbt);
        return parent::saveNBT();
    }

    /**
     * @param CompoundTag $nbt
     */
    protected function readSaveData(CompoundTag $nbt): void
    {
        $this->nbt = $nbt;
    }

    /**
     * @param CompoundTag $nbt
     */
    protected function writeSaveData(CompoundTag $nbt): void
    {
    }
}