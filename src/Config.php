<?php

namespace Devorto\Ini;

use InvalidArgumentException;
use LogicException;

class Config
{
    /**
     * @var array
     */
    protected array $config = [];

    /**
     * @param array $files Ini files to parse.
     * @param SectionFilter[] $sectionFilters Optional section filters to tweak a config section.
     */
    public function __construct(array $files, SectionFilter ...$sectionFilters)
    {
        foreach ($files as $file) {
            if (!file_exists($file)) {
                throw new InvalidArgumentException(sprintf('File "%s" does not exist.', $file));
            }

            $config = parse_ini_file($file, true);
            if (false === $config) {
                throw new InvalidArgumentException(sprintf('Unable to parse ini file "%s".', $file));
            }

            $this->config = array_merge($this->config, $config);
        }

        foreach ($sectionFilters as $sectionFilter) {
            $section = $sectionFilter->getSection();
            if (empty($this->config[$section])) {
                throw new LogicException(sprintf(
                    'Section "%s" does not exist, required by filter "%s".',
                    $section,
                    get_class($sectionFilter)
                ));
            }

            foreach ($this->config[$section] as $key => &$value) {
                $value = $sectionFilter->applyFilter($key, $value);
            }
        }
    }

    /**
     * @param string $section
     * @param string $key
     *
     * @return string
     */
    public function value(string $section, string $key): string
    {
        return $this->config[$section][$key];
    }
}
