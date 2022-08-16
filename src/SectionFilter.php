<?php

namespace Devorto\Ini;

/**
 * Use to modify
 */
interface SectionFilter
{
    /**
     * Get ini section on which to apply this filter.
     *
     * @return string section name.
     */
    public function getSection(): string;

    /**
     * Applies filter to configuration value.
     *
     * @param string $key Configuration key (cannot be modified).
     * @param string $value Configuration value.
     *
     * @return string The modified configuration value.
     */
    public function applyFilter(string $key, string $value): string;
}
