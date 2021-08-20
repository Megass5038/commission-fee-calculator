<?php

declare(strict_types=1);

namespace Kalashnik\CommissionTask\Service\Input\Reader\File;

use Generator;
use Kalashnik\CommissionTask\Service\Input\Reader\ReaderInterface;

class CSVFileReader implements ReaderInterface
{
    private string $filePath;

    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
    }

    public function getFilePath(): string
    {
        return $this->filePath;
    }

    /**
     * @return Generator<array>
     */
    public function items(): Generator
    {
        $file = fopen($this->filePath, 'rb');

        while (true) {
            $item = fgetcsv($file);
            if ($item === false) {
                break;
            }

            yield $item;
        }

        fclose($file);
    }
}
