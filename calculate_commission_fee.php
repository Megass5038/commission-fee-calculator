<?php

require_once 'vendor/autoload.php';

use Kalashnik\CommissionTask\Exception\CommissionFeeException;
use Kalashnik\CommissionTask\Factory\Calculator\CommissionFeeCalculatorFactory;
use Kalashnik\CommissionTask\Factory\Formatter\CommissionFormatterFactory;
use Kalashnik\CommissionTask\Factory\Input\FileReaderFactory;
use Kalashnik\CommissionTask\Factory\Input\MapperFactory;
use Kalashnik\CommissionTask\Factory\Repository\OperationRepositoryFactory;


if (empty($argv[1]) || !is_file($argv[1])) {
    fwrite(STDERR, '**ArgumentError** Specify path to an existing file as first argument' . PHP_EOL);
    die;
}

try {
    $reader = FileReaderFactory::getReader($argv[1]);
    $mapper = MapperFactory::getMapper();
    $operationsRepository = OperationRepositoryFactory::getRepository();
    $calculator = CommissionFeeCalculatorFactory::buildCalculator($operationsRepository);
    $formatter = CommissionFormatterFactory::getFormatter();

    foreach ($reader->items() as $item) {
        $operation = $mapper->mapItemToOperation($item);
        $commission = $calculator->calculateCommissionFeeForOperation($operation);
        echo $formatter->formatCommission($commission) . PHP_EOL;
        $operationsRepository->save($operation);
    }
} catch(CommissionFeeException $exception) {
    $errorMsg = vsprintf('**CommissionFeeException** (%s): %s%s',
        [get_class($exception), $exception->getMessage(), PHP_EOL]
    );
    fwrite(STDERR, $errorMsg);
}
