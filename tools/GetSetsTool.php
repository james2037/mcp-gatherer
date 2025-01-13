<?php

namespace App\Tools;

use App\Util\SetJsonAdapter;
use MCP\Server\Tool\Tool;
use MCP\Server\Tool\Attribute\Tool as ToolAttribute;
use MCP\Server\Tool\Attribute\Parameter as ParameterAttribute;
use mtgsdk\Set;

#[ToolAttribute('mtg_find_sets', 'Finds Magic: The Gathering sets with optional filters.')]
class GetSetsTool extends Tool
{
    protected function doExecute(
        #[ParameterAttribute('name', type: 'string', description: 'Filters by name. Pipe separated list.', required: false)]
        #[ParameterAttribute('block', type: 'string', description: 'Filter by block. Pipe separated list.', required: false)]
        #[ParameterAttribute('page', type: 'number', description: 'Gets next page of results. Default: 1. Page Size: 25', required: false)]
        array $arguments
    ): array {
        $search = [
            'page' => $arguments['page'] ?? 1,
            'pageSize' => 25,
        ];

        foreach (['name', 'block'] as $arg) {
            if (!empty($arguments[$arg])) {
                $search[$arg] = $arguments[$arg];
            }
        }

        $results = Set::where($search)->all();

        return $this->text(json_encode(array_map(function (Set $set) {
            return new SetJsonAdapter($set);
        }, $results)));
    }
}
