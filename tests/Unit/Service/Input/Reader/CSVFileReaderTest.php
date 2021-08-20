<?php

declare(strict_types=1);

namespace Kalashnik\CommissionTask\Tests\Unit\Service\Input\Reader;

use PHPUnit\Framework\TestCase;
use Kalashnik\CommissionTask\Service\Input\Reader\File\CSVFileReader;

class CSVFileReaderTest extends TestCase
{
    private CSVFileReader $reader;
    /**
     * @var false|resource
     */
    private $tmpFile;
    private array $tmpFileMetaData;

    public function setUp()
    {
        $this->tmpFile = tmpfile();
        $this->tmpFileMetaData = stream_get_meta_data($this->tmpFile);
        file_put_contents($this->tmpFileMetaData['uri'], $this->getCsvContent());
        $this->reader = new CSVFileReader($this->tmpFileMetaData['uri']);
    }

    protected function getCsvContent(): string
    {
        return "2014-12-31,4,private,withdraw,1200.00,EUR
2015-01-01,4,private,withdraw,1000.00,EUR
2016-01-05,4,private,withdraw,1000.00,EUR
";
    }

    public function testFilePath()
    {
        $this->assertSame(
            $this->tmpFileMetaData['uri'],
            $this->reader->getFilePath(),
        );
    }

    public function testFileItems()
    {
        $expectedItems = $this->getExpectedCsvItems();
        $readerItems = [];

        foreach ($this->reader->items() as $item) {
            $readerItems[] = $item;
        }

        $this->assertSame(
            $expectedItems,
            $readerItems
        );
    }


    protected function getExpectedCsvItems(): array
    {
        return [
            [
                '2014-12-31',
                '4',
                'private',
                'withdraw',
                '1200.00',
                'EUR',
            ],
            [
                '2015-01-01',
                '4',
                'private',
                'withdraw',
                '1000.00',
                'EUR',
            ],
            [
                '2016-01-05',
                '4',
                'private',
                'withdraw',
                '1000.00',
                'EUR',
            ],
        ];
    }

    public function tearDown(): void
    {
       if (is_resource($this->tmpFile)) {
           fclose($this->tmpFile);
       }
    }

}
