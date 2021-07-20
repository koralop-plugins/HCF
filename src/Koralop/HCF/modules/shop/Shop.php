<?php

namespace Koralop\HCF\modules\shop;

use pocketmine\item\Item;
use pocketmine\math\Vector3;

/**
 * Class Shop
 * @package Koralop\HCF\modules\shop
 */
class Shop
{

    /** @var string */
    protected string $type;

    /** @var int */
    protected int $price;

    /** @var Item */
    protected Item $item;

    /** @var Vector3 */
    protected Vector3 $vector3;

    /**
     * Shop constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->type = $config['type'];
        $this->price = $config['price'];
        $this->item = Item::get($config['id'], $config['damage'], 1);
        $this->vector3 = $config['vector3'];
    }

    /**
     * @return Vector3
     */
    public function getVector3(): Vector3
    {
        return $this->vector3;
    }

    /**
     * @return int
     */
    public function getPrice(): int
    {
        return $this->price;
    }

    /**
     * @return Item
     */
    public function getItem(): Item
    {
        return $this->item;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }
}