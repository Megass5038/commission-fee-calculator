<?php

declare(strict_types=1);

namespace Kalashnik\CommissionTask\Factory\Input;

use Kalashnik\CommissionTask\Service\Input\Reader\File\CSVFileReader;
use Kalashnik\CommissionTask\Service\Input\Reader\ReaderInterface;

class FileReaderFactory
{
    /**
     * Provides FileReader object based on filepath. Currently only CSV supported.
     * @param string $filePath
     * @return ReaderInterface
     */
    public static function getReader(string $filePath): ReaderInterface
    {
        return new CSVFileReader($filePath);
    }
}
