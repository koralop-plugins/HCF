<?php

namespace Koralop\HCF\block\types;

use pocketmine\block\Block;
use pocketmine\block\MonsterSpawner as PMMonsterSpawner;
use pocketmine\entity\Entity;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\Player;
use pocketmine\tile\Tile;
use ReflectionClass;

class MobSpawner extends PMMonsterSpawner
{

    /** @var int */
    private int $entityId = -1;

    /**
     * @param Item $item
     * @param Player|null $player
     * @return bool
     */
    public function onActivate(Item $item, Player $player = null): bool
    {
        if ($item->getId() == Item::SPAWN_EGG) {
            $tile = $this->getLevel()->getTile($this);
            if (!$tile instanceof \Koralop\HCF\tile\types\MobSpawner) {
                $this->entityId = $item->getDamage();
                $this->generateSpawnerTile();
                return true;
            }
        }
        return false;
    }

    /**
     * @param Item $item
     *
     * @return bool
     */
    public function isBreakable(Item $item): bool
    {
        return false;
    }

    /**
     * @param Item $item
     * @param Block $blockReplace
     * @param Block $blockClicked
     * @param int $face
     * @param Vector3 $clickVector
     * @param Player|null $player
     *
     * @return bool
     */
    public function place(Item $item, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector, Player $player = null): bool
    {
        $parent = parent::place($item, $blockReplace, $blockClicked, $face, $clickVector, $player);
        if ($item->getNamedTag()->hasTag("EntityId")) {
            $this->entityId = $item->getNamedTag()->getInt("EntityId", -1);
            if ($this->entityId > 10) {
                $this->getLevel()->setBlock($this, $this, true, false);
                $this->generateSpawnerTile();
            }
        }
        return $parent;
    }

    private function generateSpawnerTile(): void
    {
        $tile = $this->getLevel()->getTile($this);
        if (!$tile instanceof \Koralop\HCF\tile\types\MobSpawner) {
            $nbt = \Koralop\HCF\tile\types\MobSpawner::createNBT($this);
            $nbt->setString(Tile::TAG_ID, Tile::MOB_SPAWNER);
            $tile = new \Koralop\HCF\tile\types\MobSpawner($this->getLevel(), $nbt);
        }
        $tile->setSpawnEntityType($this->entityId);
        $this->getLevel()->addTile($tile);
    }

    /**
     * @return int
     */
    public function getXpDropAmount(): int
    {
        return 0;
    }

    /**
     * @param Item $item
     *
     * @return array
     */
    public function getDrops(Item $item): array
    {
        return [];
    }

    /**
     * @return string
     */
    public function getEntityType(): string
    {
        $class = new ReflectionClass(Entity::class);
        $ids = array_flip($class->getConstants());
        $id = $ids[$this->entityId];
        $name = implode("", explode(" ", ucwords(strtolower(implode(" ", explode("_", $id))))));
        return $name;
    }
}