# Directory Crawler

![StyleCi](https://github.styleci.io/repos/817050761/shield)

Directory Crawler PHP is a simple PHP library for recursively crawling through directories and listing files and
directories.

## Features

- Recursively crawls through a specified directory.
- Retrieves a list of files and directories within the specified directory.
- Retrieves a list of classes inside the repository (PSR4 structured repositories).

## Installation

You can install the package via Composer:

```bash
composer require webdevcave/directory-crawler-php
```

## Usage

```php
<?php

require_once 'vendor/autoload.php';

use WebdevCave\DirectoryCrawler\Crawler;

// Set the directory path to crawl
$path = '/path/to/directory';

$crawler = new Crawler($path);

// Get all files and directories
$contents = $crawler->contents();

// Get all files
$files = $crawler->files();

// Get all directories
$directories = $crawler->directories();

// List classes inside the directories
$namespace = 'My\\Project\\';
$enforce = false; //Faster
//$enforce = true; //Reliable but slower. May cause performance issues, depending on the number of occurrences.
$classes = $crawler->classes($namespace, $enforce);

print_r(compact('path', 'contents', 'files', 'directories', 'classes')); //Show results
```

## Contributing

Contributions are welcome! Fork the repository, make your changes, and submit a pull request. Please ensure to write
tests for any new functionality or bug fixes.

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.
