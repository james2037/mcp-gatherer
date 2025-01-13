<?php

namespace App\Util;

use mtgsdk\Set;

class SetJsonAdapter implements \JsonSerializable {
    private $set;
    
    public function __construct(Set $set) {
        $this->set = $set;
    }
    
    public function jsonSerialize(): array {
        $result = array_filter([
            'code' => $this->set->code ?? null,
            'name' => $this->set->name ?? null,
            'type' => $this->set->type ?? null,
            'border' => $this->set->border ?? null,
            'mkm_id' => $this->set->mkm_id ?? null,
            'mkm_name' => $this->set->mkm_name ?? null,
            'releaseDate' => $this->set->releaseDate ?? null,
            'gathererCode' => $this->set->gathererCode ?? null,
            'magicCardsInfoCode' => $this->set->magicCardsInfoCode ?? null,
            'booster' => $this->set->booster ?? null,
            'oldCode' => $this->set->oldCode ?? null,
            'block' => $this->set->block ?? null,
            'onlineOnly' => $this->set->onlineOnly ?? null
        ]);

        if (array_key_exists('booster', $result)) {
            $boosterResult = [];

            foreach($result['booster'] as $cardRarity) {
                if (!array_key_exists($cardRarity, $boosterResult)) {
                    $boosterResult[$cardRarity] = 0;
                }

                $boosterResult[$cardRarity]++;
            }

            $result['booster'] = $boosterResult;
        }

        return $result;
    }
}
