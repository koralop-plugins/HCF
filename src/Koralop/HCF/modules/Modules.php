<?php

namespace Koralop\HCF\modules;

use Koralop\HCF\HCFLoader;

/**
 * Class Modules
 * @package Koralop\HCF\modules
 */
abstract class Modules
{

    /**
     * Modules constructor.
     */
    public function __construct()
    {
        $this->onEnable(HCFLoader::getInstance());
    }

    public function __destruct()
    {
        $this->onDisable(HCFLoader::getInstance());
    }

    /**
     * @param HCFLoader $loader
     */
    abstract public function onEnable(HCFLoader $loader): void;

    /**
     * @param HCFLoader $loader
     */
    abstract public function onDisable(HCFLoader $loader): void;
}