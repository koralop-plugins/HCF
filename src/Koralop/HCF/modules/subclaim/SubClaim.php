<?php

namespace Koralop\HCF\modules\subclaim;

use addon\Math\lVector3;

/**
 * Class SubClaim
 * @package Koralop\HCF\modules\subclaim
 */
class SubClaim
{

    /** @var string|null */
    private ?string $playerName = null;
    /** @var array|null */
    private ?array $data = null;

    /**
     * SubClaim constructor.
     * @param string $playerName
     * @param array $data
     */
    public function __construct(string $playerName, array $data)
    {
        $this->playerName = $playerName;

        $this->data = $data;
    }

    /**
     * @return lVector3
     */
    public function getPos(): lVector3
    {
        return $this->data['pos'];
    }

    /**
     * @return array
     */
    public function getChest(): array
    {
        return $this->data['pos'];
    }
}