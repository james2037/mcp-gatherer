<?php

namespace App\Util;

trait NeedsRulesTrait
{
    /**
     * @return resource
     */
    protected function getRulesHandle(): mixed
    {
        if (!$this->rulesExists()) {
            $this->downloadRules();
        }

        return fopen($this->localRulesPath(), "r");
    }

    private function localRulesPath(): string
    {
        return $this->config['storage_path'] . '/mtg_comp_rules.txt';
    }

    private function remoteRulesPath(): string
    {
        return $this->config['rules_url'];
    }

    private function rulesExists(): bool
    {
        return file_exists($this->localRulesPath());
    }

    private function downloadRules(): int|false
    {
        $remote = fopen($this->remoteRulesPath(), "r");
        $local = fopen($this->localRulesPath(), "w");

        $bytes = stream_copy_to_stream($remote, $local);

        fclose($remote);
        fclose($local);

        return $bytes;
    }
}