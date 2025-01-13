<?php

namespace App\Tools;

use App\Util\SetJsonAdapter;
use MCP\Server\Tool\Tool;
use MCP\Server\Tool\Attribute\Tool as ToolAttribute;
use MCP\Server\Tool\Attribute\Parameter as ParameterAttribute;
use mtgsdk\Set;

#[ToolAttribute('mtg_recent_sets', 'Finds the most recent Magic: The Gathering sets.')]
class GetMostRecentSetsTool extends Tool
{
    protected function doExecute(
        #[ParameterAttribute('count', type: 'number', description: '. Default: 10', required: false)]
        array $arguments
    ): array {
        $results = Set::all();

        usort($results, function($a, $b) {
            if ($a->releaseDate == $b->releaseDate) return 0;
            return $a->releaseDate > $b->releaseDate ? -1 : 1;
        });

        $results = array_slice($results, 0, $arguments['count']);

        return $this->text(json_encode(array_map(function (Set $set) {
            return new SetJsonAdapter($set);
        }, $results)));
    }
}
