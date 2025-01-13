<?php

namespace App\Tools;

use App\Util\NeedsRulesTrait;

use MCP\Server\Tool\Tool;
use MCP\Server\Tool\Attribute\Tool as ToolAttribute;
use MCP\Server\Tool\Attribute\Parameter as ParameterAttribute;

#[ToolAttribute('mtg_rules_chapter', 'Get a section of the Magic: The Gathering Comprehensive Rules')]
class GetRulesChapterTool extends Tool {
    use NeedsRulesTrait;

    protected function doExecute(
        #[ParameterAttribute('section', type: 'string', description: 'The section to load. Allowable forms: 1, 100, 100.1, 100.1a')]
        array $arguments
    ): array {
        $rules = $this->getRulesHandle();

        // Seek past the line reading "Contents"
        while ($line = str_replace("\r\n", "\n", fgets($rules))) {
            if ($line == "Contents\n") break;
        }

        fgets($rules); // Seek past blank line.

        $firstHeading = str_replace("\r\n", "\n", fgets($rules)); // i.e. "1. Game Concepts"

        $line = null;

        while ($line = str_replace("\r\n", "\n", fgets($rules))) {
            if ($line == $firstHeading) break;
        } // Seek to first heading

        $scanFor = $arguments['section']; // We will look for lines that begin with $scanFor

        do {
            if (substr($line, 0, strlen($scanFor)) == $scanFor) break;
        } while($line = str_replace("\r\n", "\n", fgets($rules))); // Seek to the first line matching our $scanFor

        $output = $line;

        while ($line = str_replace("\r\n", "\n", fgets($rules))) {
            if ($line != "\n" && substr($line, 0, strlen($scanFor)) != $scanFor && substr($line, 0, strlen("Example:")) != "Example:") break;

            $output .= $line;
        } // Output all newlines and lines starting with $scanFor

        fclose($rules);

        return $this->text(rtrim($output));
    }
}
