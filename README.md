# Commission-fee-calculator

## Requirements
- PHP 7.4
- Composer V1

## Installation

```composer install
composer install
```

Configs located at `src/config/*.php`

Some of them use environment variables. You can directly export them or via file.
Supported env variables are in the file `.env.example`.

`EXCHANGERATESAPI_KEY` - API key https://exchangeratesapi.io/

`EXCHANGE_RATE_PROVIDER` - provider for exchange rates. 
Currently supported "stub" and "exchangeratesapi". 
"Stub" provides rates from array (`src/config/exchange_rate/providers/stub/rates`),
it is default provider.

**Tip:** to export env from file:
```
cp .env.example .env
vi .env
export $(cat .env | xargs)
``` 

## Examples
Calculate commission for `samples/input.csv`:

`php calculate_commission_fee.php samples/input.csv`

Run tests:

`composer run tests`

Run single test for `samples/input.csv` and `samples/expected_output.csv`:

`composer run test-sample-input`

Run `php-cs-fixer`:

`composer run fix-cs`
