<?php

namespace Webdevcave\DirectoryCrawler;

use DateTimeImmutable;

class Crawler
{
    private readonly string $dir;
    private array $directories = [];
    private bool $fed = false;
    private array $files = [];
    private ?DateTimeImmutable $lastUpdated = null;

    /**
     * @param string $dir
     */
    public function __construct(string $dir)
    {
        $this->dir = realpath($dir);
    }

    /**
     * List both files and directories.
     *
     * @return string[]
     */
    public function contents(): array
    {
        $this->feed();

        return [...$this->directories, ...$this->files];
    }

    /**
     * Crawl searching for classes in a PSR-4 based directory structure.
     * $enforce will check each item class declaration. Safer but might be slow. Default: false (skip check).
     * $namespace string MUST end with a backslash (\).
     *
     * @param string $namespace
     * @param bool   $enforce
     *
     * @return string[]
     */
    public function classes(string $namespace, bool $enforce = false): array
    {
        $this->feed();

        $classes = [];
        $namespace = rtrim($namespace, DIRECTORY_SEPARATOR);
        $start = mb_strlen($this->dir) + 1;

        foreach ($this->files() as $file) {
            $className = $namespace.str_replace('/', '\\', mb_substr($file, $start, -4));

            if ($enforce && !class_exists($className)) {
                continue;
            }

            $classes[] = $className;
        }

        return $classes;
    }

    /**
     * @return string[]
     */
    public function directories(): array
    {
        $this->feed();

        return $this->directories;
    }

    /**
     * @return string[]
     */
    public function files(): array
    {
        $this->feed();

        return $this->files;
    }

    /**
     * Get the update datetime based on the most recent updated item.
     *
     * @return DateTimeImmutable|null
     */
    public function lastUpdated(): DateTimeImmutable|null
    {
        $this->feed();

        return $this->lastUpdated;
    }

    /**
     * Fill information.
     */
    private function feed(string $dir = null): void
    {
        if ($this->fed) {
            return;
        }

        $dir = $dir ?? $this->dir;
        $lastUpdated = false;

        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') {
                continue;
            }

            $path = $dir.DIRECTORY_SEPARATOR.$item;

            if (is_dir($path)) {
                $this->feed($path);
                $this->directories[] = $path;
                continue;
            }

            $updated = filemtime($path);

            if ($updated !== false && $updated > $lastUpdated) {
                $lastUpdated = $updated;
            }

            $this->files[] = $path;
        }

        if ($lastUpdated !== false) {
            $this->lastUpdated = DateTimeImmutable::createFromFormat('U', $lastUpdated);
        }

        $this->fed = true;
    }
}
