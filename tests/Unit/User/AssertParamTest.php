<?php

namespace Tests\Unit\User;

use App\Utils\PasswordAssertUtil;
use Tests\TestCase;

class AssertParamTest extends TestCase
{
    /**
     * @dataProvider dataProviderSuccessCase
     */
    public function testAssertStrongPasswordSuccessPattern(string $test_password): void
    {
        $this->assertTrue(PasswordAssertUtil::isStrongPassword($test_password));
    }

    public function dataProviderSuccessCase(): array
    {
        return [
            ['ErusaErusa12345'],
            ['Erusa_Erusa12345'],
            ['Erusa12345'],
        ];
    }

    /**
     * @dataProvider dataProviderFailCase
     *
     */
    public function testAssertStrongPasswordFailPattern(string $test_password): void
    {
        $this->assertFalse(PasswordAssertUtil::isStrongPassword($test_password));
    }

    public function dataProviderFailCase(): array
    {
        return [
            [''],
            ['erusa_is_kawaii'],
            ['amupi'],
            ['erusa12345'],
            ['Erusa1234'],
        ];
    }
}
