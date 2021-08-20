<?php

declare(strict_types=1);

namespace Kalashnik\CommissionTask\Tests\Unit\Validation\Validator;

use PHPUnit\Framework\TestCase;
use Illuminate\Config\Repository as ConfigRepository;
use Kalashnik\CommissionTask\Exception\Validation\ValidationException;
use Kalashnik\CommissionTask\Validation\Validator\UserValidator;

class UserValidatorTest extends TestCase
{
    private UserValidator $userValidator;

    public function setUp()
    {
        $config = new ConfigRepository([]);
        $this->userValidator = new UserValidator($config);
    }

    /**
     * @param string|int $userId
     * @param string $userType
     * @dataProvider dataProviderForTestValidate
     */
    public function testValidate($userId, string $userType)
    {
        $this->assertNull(
            $this->userValidator->validate([
                'user_id' => $userId,
                'user_type' => $userType,
            ])
        );
    }

    /**
     * @param string|int $userId
     * @param string $userType
     * @dataProvider dataProviderForTestValidateIntegerException
     */
    public function testValidateIntegerException($userId, string $userType)
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessageRegExp('/"user_id" with value/');

        $this->assertNull(
            $this->userValidator->validate([
                'user_id' => $userId,
                'user_type' => $userType,
            ])
        );
    }

    /**
     * @param string|int $userId
     * @param string $userType
     * @dataProvider dataProviderForTestValidateTypeException
     */
    public function testValidateTypeException($userId, string $userType)
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessageRegExp('/"user_type" with value/');

        $this->assertNull(
            $this->userValidator->validate([
                'user_id' => $userId,
                'user_type' => $userType,
            ])
        );
    }

    public function dataProviderForTestValidate(): array
    {
        return [
            'user 1' => [
                '3',
                'private',
            ],
            'user 2' => [
                4,
                'business',
            ],
            'user 3' => [
                1,
                'business',
            ],
        ];
    }

    public function dataProviderForTestValidateIntegerException(): array
    {
        return [
            'user 1' => [
                '3.25',
                'private',
            ],
            'user 2' => [
                -4,
                'business',
            ],
            'user 3' => [
                'hello',
                'business',
            ],
        ];
    }

    public function dataProviderForTestValidateTypeException(): array
    {
        return [
            'user 1' => [
                1,
                'undefined',
            ],
        ];
    }
}
