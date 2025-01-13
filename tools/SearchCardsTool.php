<?php

namespace App\Tools;

use App\Util\SmallCardJsonAdapter;

use MCP\Server\Tool\Tool;
use MCP\Server\Tool\Attribute\Tool as ToolAttribute;
use MCP\Server\Tool\Attribute\Parameter;

use mtgsdk\Card;

#[ToolAttribute('mtg_search_cards', 'Search for Magic: The Gathering cards. Use | for OR search. Use , for AND search.')]
class SearchCardsTool extends Tool {
    protected function doExecute(
        #[Parameter('name', description: 'Card name to search for', required: false)]
        #[Parameter('layout', description: 'Card layout: normal, split, flip, double-faced, token, plane, scheme, phenomenon, leveler, vanguard, aftermath', required: false)]
        #[Parameter('cmc', type: 'number', description: 'Converted mana cost', required: false)]
        #[Parameter('colors', description: 'Colors (Red, Blue, White, Black, Green)', required: false)]
        #[Parameter('colorIdentity', description: 'Color identities (R, U, W, B, G)', required: false)]
        #[Parameter('type', description: 'Card type line', required: false)]
        #[Parameter('supertypes', description: 'Supertypes (Basic, Legendary, Snow, World, Ongoing)', required: false)]
        #[Parameter('types', description: 'Card types (Instant, Sorcery, Artifact, Creature, Enchantment, Land, Planeswalker)', required: false)]
        #[Parameter('subtypes', description: 'Subtypes (e.g. Human, Equipment, Aura)', required: false)]
        #[Parameter('rarity', description: 'Rarity: Common, Uncommon, Rare, Mythic Rare, Special, Basic Land', required: false)]
        #[Parameter('set', description: 'Set code', required: false)]
        #[Parameter('setName', description: 'Full set name', required: false)]
        #[Parameter('text', description: 'Card text to search for', required: false)]
        #[Parameter('flavor', description: 'Flavor text to search for', required: false)]
        #[Parameter('artist', description: 'Artist name', required: false)]
        #[Parameter('number', description: 'Card number in set', required: false)]
        #[Parameter('power', description: 'Creature power', required: false)]
        #[Parameter('toughness', description: 'Creature toughness', required: false)]
        #[Parameter('loyalty', type: 'number', description: 'Planeswalker loyalty', required: false)]
        #[Parameter('language', description: 'Card language. Use this when providing query parameters in a non-English language.', required: false)]
        #[Parameter('gameFormat', description: 'Game format (e.g. Commander, Standard)', required: false)]
        #[Parameter('legality', description: 'Format legality: Legal, Banned, Restricted (default: Legal)', required: false)]
        #[Parameter('orderBy', description: 'Field to order results by', required: false)]
        #[Parameter('random', description: 'If true, return random cards', required: false)]
        #[Parameter('contains', description: 'Filter by field presence', required: false)]
        #[Parameter('id', description: 'Unique card identifier', required: false)]
        #[Parameter('multiverseid', type: 'number', description: 'Multiverse ID from Gatherer', required: false)]
        #[Parameter('page', type: 'number', description: 'Page number (default: 1)', required: false)]
        #[Parameter('pageSize', type: 'number', description: 'Results per page (default: 100, max: 100)', required: false)]
        array $arguments
    ): array {
        try {
            // Ensure sane pagination defaults
            $arguments['page'] = $arguments['page'] ?? 1;
            $arguments['pageSize'] = min($arguments['pageSize'] ?? 100, 100);

            $cards = \mtgsdk\Card::where($arguments)->all();
            return $this->text(json_encode(array_map(
                fn($card) => new SmallCardJsonAdapter($card),
                $cards
            )));
        } catch (\Exception $e) {
            return $this->text(json_encode([
                'error' => 'Failed to search cards',
                'details' => $e->getMessage()
            ]));
        }
    }
}
