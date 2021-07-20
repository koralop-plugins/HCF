<?php

namespace Koralop\HCF\modules\partner;

use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\Item;

/**
 * Class lPartner
 * @package Koralop\HCF\modules\partner
 */
abstract class lPartner extends Item
{

    /**
     * lPartner constructor.
     * @param int $id
     * @param int $meta
     * @param string $name
     * @param array $lore
     * @param EnchantmentInstance[] $ce
     */
    public function __construct(int $id, int $meta = 0, string $name = '', array $lore = [], array $ce = [])
    {
        $this->setCustomName($name);
        $this->setLore($lore);

        $nbt = $this->getNamedTag();
        $nbt->setString('ability', $name);

        $this->setCompoundTag($nbt);

        foreach ($ce as $enchant)
            $this->addEnchantment($enchant);

        parent::__construct($id, $meta);
    }

    abstract function onInteract(PlayerInteractEvent $event): void;

    abstract function onDamageEntity(EntityDamageByEntityEvent $event): void;
}