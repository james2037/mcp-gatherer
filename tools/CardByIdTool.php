<?php

namespace App\Tools;

use App\Util\CardJsonAdapter;
use MCP\Server\Tool\Tool;
use MCP\Server\Tool\Attribute\Tool as ToolAttribute;
use MCP\Server\Tool\Attribute\Parameter as ParameterAttribute;
use mtgsdk\Card;

#[ToolAttribute('mtg_card_by_id', 'Get a Magic: The Gathering card by ID. Includes much more than search result, including rulings, legality, foreign names, other printings, flavor text, artist.')]
class CardByIdTool extends Tool
{
    protected function doExecute(
        #[ParameterAttribute('id', type: 'number', description: 'Required. Can be ID or Multiverse ID')]
        array $arguments
    ): array {
        try {
            $card = Card::find($arguments['id']);
            if (!$card) {
                return $this->text(json_encode(['error' => 'Card not found']));
            }
            return $this->text(json_encode(new CardJsonAdapter($card)));
        } catch (\Exception $e) {
            return $this->text(json_encode([
                'error' => 'Failed to fetch card data',
                'details' => $e->getMessage()
            ]));
        }
    }
}
