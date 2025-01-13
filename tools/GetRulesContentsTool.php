<?php

namespace App\Tools;

use App\Util\NeedsRulesTrait;
use MCP\Server\Tool\Tool;
use MCP\Server\Tool\Attribute\Tool as ToolAttribute;
use MCP\Server\Tool\Attribute\Parameter as ParameterAttribute;

#[ToolAttribute('mtg_rules_toc', 'Retrieve the table of contents for the Magic: The Gathering Comprehensive Rules')]
class GetRulesContentsTool extends Tool {
    use NeedsRulesTrait;

    protected function doExecute(
        array $arguments
    ): array {
        $rules = $this->getRulesHandle();

        // Seek past the line reading "Contents"
        while ($line = str_replace("\r\n", "\n", fgets($rules))) {
            if ($line == "Contents\n") break;
        }

        fgets($rules); // Seek past blank line.

        $firstHeading = str_replace("\r\n", "\n", fgets($rules)); // i.e. "1. Game Concepts"
        $output = $firstHeading;

        $line = null;

        while ($line = str_replace("\r\n", "\n", fgets($rules))) {
            if ($line == $firstHeading) break;
            $output .= $line;
        }

        return $this->text(rtrim($output));
    }
}
