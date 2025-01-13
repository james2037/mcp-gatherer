<?php

namespace App\Util;

use mtgsdk\Card;

class SmallCardJsonAdapter implements \JsonSerializable {
    private $card;
    
    public function __construct(Card $card) {
        $this->card = $card;
    }
    
    public function jsonSerialize(): array {
        // Extract all properties based on the docblock
        $results = array_filter([
            'name' => $this->card->name ?? null,
            'layout' => $this->card->layout ?? null,
            'manaCost' => $this->card->manaCost ?? null,
            //'cmc' => $this->card->cmc ?? null,
            //'colors' => $this->card->colors ?? null,
            'names' => $this->card->names ?? null,
            'type' => $this->card->type ?? null,
            //'supertypes' => $this->card->supertypes ?? null,
            //'subtypes' => $this->card->subtypes ?? null,
            //'types' => $this->card->types ?? null,
            'rarity' => $this->card->rarity ?? null,
            'text' => $this->card->text ?? null,
            //'flavor' => $this->card->flavor ?? null,
            //'artist' => $this->card->artist ?? null,
            'number' => $this->card->number ?? null,
            'power' => $this->card->power ?? null,
            'toughness' => $this->card->toughness ?? null,
            'loyalty' => $this->card->loyalty ?? null,
            'multiverseid' => $this->card->multiverseid ?? null,
            //'variations' => $this->card->variations ?? null,
            'watermark' => $this->card->watermark ?? null,
            'border' => $this->card->border ?? null,
            'timeshifted' => $this->card->timeshifted ?? null,
            'hand' => $this->card->hand ?? null,
            'life' => $this->card->life ?? null,
            //'releaseDate' => $this->card->releaseDate ?? null,
            //'starter' => $this->card->starter ?? null,
            //'printings' => $this->card->printings ?? null,
            //'originalText' => $this->card->originalText ?? null,
            //'originalType' => $this->card->originalType ?? null,
            //'source' => $this->card->source ?? null,
            //'imageUrl' => $this->card->imageUrl ?? null,
            'set' => $this->card->set ?? null,
            //'setName' => $this->card->setName ?? null,
            'id' => $this->card->id ?? null,
            //'legalities' => $this->card->legalities ?? null,
            //'rulings' => $this->card->rulings ?? null,
            //'foreignNames' => $this->card->foreignNames ?? null
        ]);

        if ($results['layout'] == 'normal') unset($results['layout']);

        return $results;
    }
}
