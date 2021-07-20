<?php

namespace addon\Math;

use pocketmine\math\Vector3;

/**
 * Class lVector3
 * @package Koralop\Extensions\utils\Math
 */
abstract class lVector3 extends Vector3
{

    /**
     * @param Vector3 $vector3
     * @return string
     */
    public static function vector3AsString(Vector3 $vector3): string
    {
        return $vector3->getX() . ':' . $vector3->getY() . ':' . $vector3->getZ();
    }
}