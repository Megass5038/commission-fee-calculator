<?php

declare(strict_types=1);

namespace Kalashnik\CommissionTask\Tests\Feature\Calculator;

use PHPUnit\Framework\TestCase;
use Kalashnik\CommissionTask\Entity\ExchangeRate\ExchangeRate;
use Kalashnik\CommissionTask\Factory\Config\ConfigFactory;
use Kalashnik\CommissionTask\Repository\Operation\FromArray;
use Kalashnik\CommissionTask\Service\Calculator\CommissionFee as CommissionFeeCalculator;
use Kalashnik\CommissionTask\Service\Calculator\Money;
use Kalashnik\CommissionTask\Service\Formatter\Commission as CommissionFormatter;
use Kalashnik\CommissionTask\Service\Input\Mapper\CSV\Mapper;
use Kalashnik\CommissionTask\Service\Input\Reader\File\CSVFileReader;
use Kalashnik\CommissionTask\Service\Math\Math;
use Kalashnik\CommissionTask\Service\Strategy\CommissionFee\Chooser\Chooser as StrategyChooser;
use Kalashnik\CommissionTask\Validation\Validator\OperationValidator;
use Kalashnik\CommissionTask\Validation\Validator\UserValidator;

/**
 * Class CommissionFeeTest
 * Complex test which initiates all dependencies of application and check commission fee
 * for a known sequence of operations.
 * File input - samples/input.csv
 * Expected output - samples/expected_output.csv
 */
class CommissionFeeTest extends TestCase
{
    private CSVFileReader $reader;
    private Mapper $mapper;
    private FromArray $operationsRepository;
    private CommissionFeeCalculator $commissionFeeCalculator;
    private CommissionFormatter $formatter;

    public function setUp()
    {
        $this->reader = new CSVFileReader("samples/input.csv");
        $config = ConfigFactory::getConfig();
        $this->mapper = new Mapper($config, new OperationValidator($config), new UserValidator($config));
        $this->operationsRepository = new FromArray();
        $moneyCalculator = new Money(
            new Math(6), $this->getStubExchangeRate()
        );
        $strategyChooser = new StrategyChooser($config, $this->operationsRepository, $moneyCalculator);
        $this->commissionFeeCalculator = new CommissionFeeCalculator($config, $strategyChooser);
        $this->formatter = new CommissionFormatter($config, new Math(6));

    }

    private function getStubExchangeRate(): ExchangeRate
    {
        return new ExchangeRate(
            [
                'USD' => '1.1497',
                'JPY' => '129.53',
            ],
            'EUR',
            null
        );
    }

    public function testCommissionFee(): void
    {
        $lineNum = 0;
        $expectedFee = $this->getExpectedCommissionFee();

        foreach ($this->reader->items() as $item) {
            $operation = $this->mapper->mapItemToOperation($item);
            $commission = $this->commissionFeeCalculator->calculateCommissionFeeForOperation($operation);
            $formattedCommission = $this->formatter->formatCommission($commission);
            $this->assertSame($expectedFee[$lineNum], $formattedCommission);
            $lineNum++;
            $this->operationsRepository->save($operation);
        }
    }

    public function getExpectedCommissionFee(): array
    {
        $expectedOutputStr = file_get_contents("samples/expected_output.csv");
        return explode(PHP_EOL, $expectedOutputStr);
    }
}
