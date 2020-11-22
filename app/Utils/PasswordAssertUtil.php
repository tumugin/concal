<?php

namespace App\Utils;

use Webmozart\Assert\Assert;

class PasswordAssertUtil
{
    /**
     * 十分に強力なパスワードかどうか検証する
     *
     * @param string $password
     * @return bool
     */
    public static function isStrongPassword(string $password): bool
    {
        try {
            Assert::minLength($password, 10);
            Assert::regex($password, '/[a-z]/');
            Assert::regex($password, '/[A-Z]/');
            Assert::regex($password, '/[0-9]/');
            // すべてマルチバイト文字でない
            Assert::regex($password, '/^[[:ascii:]]+$/');
            // 制御文字を含まない
            Assert::notRegex($password, '/[\x00-\x1F]/');
            Assert::notRegex($password, '/[\x20]/');
            Assert::notRegex($password, '/[\x7F]/');
            return true;
        } catch (\InvalidArgumentException $ex) {
            return false;
        }
    }
}
