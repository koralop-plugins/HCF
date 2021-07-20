<?php

namespace Koralop\HCF\item\types;

use pocketmine\block\Block;
use pocketmine\item\Item;
use pocketmine\item\Potion;
use pocketmine\math\Vector3;
use pocketmine\Player;

/**
 * Class GlassBottle
 * @package Koralop\HCF\item\types
 */
class GlassBottle extends \pocketmine\item\GlassBottle
{

    /***
     * @param Player $player
     * @param Block $blockReplace
     * @param Block $blockClicked
     * @param int $face
     * @param Vector3 $clickVector
     * @return bool
     */
    public function onActivate(Player $player, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector): bool
    {
        if (in_array($blockClicked->getId(), [Block::STILL_WATER, Block::FLOWING_WATER]) or in_array($blockReplace->getId(), [Block::STILL_WATER, Block::FLOWING_WATER])) {

            if ($player->isSurvival()) {
                $this->count--;
            }

            $item = Item::get(Item::POTION, Potion::WATER, 1);
            if (!$player->getInventory()->canAddItem($item)) {
                return false;
            }

            $player->getInventory()->addItem($item);
        }
        return true;
    }
}