<?php

namespace Koralop\HCF\modules\kit;

use Koralop\HCF\HCFPlayer;

/**
 * Class Kit
 * @package Koralop\HCF\modules\kit
 */
class Kit
{

    /** @var array */
    protected array $kit;

    /**
     * Kit constructor.
     * @param array $kit
     */
    public function __construct(array $kit)
    {
        $this->kit = $kit;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->kit['name'];
    }

    /**
     * @return string
     */
    public function getFormat(): string
    {
        return str_replace('&', '§', $this->kit['format']);
    }

    /**
     * @return string
     */
    public function getPermission(): string
    {
        return $this->kit['permission'];
    }

    /**
     * @return int
     */
    public function getSlot(): int
    {
        return $this->kit['slot'];
    }

    /**
     * @return string
     */
    public function getItem(): string
    {
        return $this->kit['item'];
    }

    /**
     * @return array
     */
    public function getItems(): array
    {
        return $this->kit['items'];
    }

    /**
     * @return array
     */
    public function getArmorItems(): array
    {
        return $this->kit['armorItems'];
    }

    /**
     * @param HCFPlayer $player
     */
    public function setKit(HCFPlayer $player): void
    {
        if (!$player->hasPermission($this->getPermission()))
           return;

        $player->setClass($this->getName());

        $player->getInventory()->setContents($this->getItems());
        $player->getArmorInventory()->setContents($this->getArmorItems());

    }
}