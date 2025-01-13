<?php

namespace App\Tools;

use MCP\Server\Tool\Tool;
use MCP\Server\Tool\Attribute\Tool as ToolAttribute;
use MCP\Server\Tool\Attribute\Parameter as ParameterAttribute;

#[ToolAttribute('view_mtg_card_by_id', 'View a Magic: The Gathering card by Multiverse ID. (Returns Image Data)')]
class ViewCardByIdTool extends Tool 
{
    protected function doExecute(
        #[ParameterAttribute('multiverseid', type: 'number', description: 'The Card to view.')]
        array $arguments
    ): array {
        $url = sprintf(
            'http://gatherer.wizards.com/Handlers/Image.ashx?multiverseid=%s&type=card',
            $arguments['multiverseid']
        );

        $context = stream_context_create([
            'http' => [
                'method' => 'GET',
                'header' => [
                    'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                    'Accept: image/jpeg,image/*,*/*;q=0.8',
                    'Accept-Language: en-US,en;q=0.9',
                ],
                'timeout' => 30,
                'follow_location' => 1
            ]
        ]);

        $imageData = @file_get_contents($url, false, $context);
        
        if ($imageData === false) {
            $error = error_get_last();
            throw new \RuntimeException(sprintf(
                "Failed to fetch card image: %s",
                $error['message'] ?? 'Unknown error'
            ));
        }

        return $this->image($imageData, 'image/jpeg');
    }
}
