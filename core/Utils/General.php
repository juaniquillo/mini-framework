<?php

namespace Core\Utils;

use Closure;

/**
 * Helper class that provides easy access to useful common php functions.
 *
 * Class Util
 *
 * @package CNZ\Helpers
 * 
 * @source https://github.com/clausnz/php-helpers clausnz/php-helpers
 */
class General
{
    /**
     * Holds the value of $localhost
     *
     * @codeCoverageIgnore
     * @ignore
     */
    const LOCALHOST = '127.0.0.1';

    /**
     * Only for testing.
     *
     * @codeCoverageIgnore
     * @ignore
     */
    private static $isCli;

    /**
     * Get the current ip address of the user.
     *
     * ### user_ip
     * Related global function (description see above).
     *
     * > #### [( jump back )](#available-php-functions)
     *
     * ```php
     * ip(  ): null|string
     * ```
     *
     * #### Example
     * ```php
     * echo ip();
     *
     * // 127.0.0.1
     * ```
     *
     * @return string|null
     * The detected ip address, null if the ip was not detected.
     */
    public static function ip()
    {
        if (php_sapi_name() == 'cli' && self::$isCli) {
            $ip = gethostbyname(gethostname());

            if (!filter_var($ip, FILTER_VALIDATE_IP)) {
                $ip = self::LOCALHOST;
            }

            return $ip;
        }

        return isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : null;
    }

    /**
     * Set if the script is cli. For testing only.
     *
     * @codeCoverageIgnore
     * @ignore
     *
     * @param bool $isCli
     * True or false.
     */
    public static function setIsCli($isCli = true)
    {
        self::$isCli = $isCli;
    }

    /**
     * Creates a secure hash from a given password. Uses the CRYPT_BLOWFISH algorithm.
     * Note: 255 characters for database column recommended!
     *
     * ### crypt_password
     * Related global function (description see above).
     *
     * > #### [( jump back )](#available-php-functions)
     *
     * ```php
     * crypt_password( string $password ): string
     * ```
     *
     * #### Example
     * ```php
     * $password = 'foobar';
     *
     * crypt_password( $password );
     *
     * // $2y$10$6qKwbwTgwQNcmcaw04eSf.QpP3.4T0..bEnY62dd1ozM8L61nb8AC
     * ```
     *
     * @param string $password
     * The password to crypt.
     *
     * @return string
     * The crypted password.
     */
    public static function cryptPassword($password)
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    /**
     * Verifies that a password matches a crypted password (CRYPT_BLOWFISH algorithm).
     *
     * ### is_password
     * Related global function (description see above).
     *
     * > #### [( jump back )](#available-php-functions)
     *
     * ```php
     * is_password( string $password, string $cryptedPassword ): boolean
     * ```
     *
     * #### Example
     * ```php
     * $password = 'foobar';
     * $cryptedPassword = '$2y$10$6qKwbwTgwQNcmcaw04eSf.QpP3.4T0..bEnY62dd1ozM8L61nb8AC';
     *
     * is_password( $password, $cryptedPassword );
     *
     * // bool(true)
     * ```
     *
     * @param string $password
     * The password to test.
     *
     * @param string $cryptedPassword
     * The crypted password (e.g. stored in the database).
     *
     * @return bool
     */
    public static function isPassword($password, $cryptedPassword)
    {
        return password_verify($password, $cryptedPassword);
    }

    /**
     * Dumps the content of the given variable and exits the script.
     *
     * ### dd
     * Related global function (description see above).
     *
     * > #### [( jump back )](#available-php-functions)
     *
     * ```php
     * dd( mixed $var )
     * ```
     *
     * #### Example
     * ```php
     * $array = [
     *      'foo' => 'bar',
     *      'baz' => 'qux'
     * ];
     *
     * dd( $array );
     *
     * // (
     * //     [foo] => bar
     * //     [baz] => qux
     * // )
     * ```
     *
     * @codeCoverageIgnore
     *
     * @param mixed $var
     * The var to dump.
     */
    public static function dd($var)
    {
        self::dump($var);
        die();
    }

    /**
     * Dumps the content of the given variable. Script does NOT stop after call.
     *
     * ### dump
     * Related global function (description see above).
     *
     * > #### [( jump back )](#available-php-functions)
     *
     * ```php
     * dump( mixed $var )
     * ```
     *
     * #### Example
     * ```php
     * $array = [
     *      'foo' => 'bar',
     *      'baz' => 'qux'
     * ];
     *
     * dump( $array );
     *
     * // (
     * //     [foo] => bar
     * //     [baz] => qux
     * // )
     * ```
     *
     * @codeCoverageIgnore
     *
     * @param mixed $var
     * The var to dump.
     */
    public static function dump($var)
    {
        if (is_bool($var)) {
            $var = 'bool(' . ($var ? 'true' : 'false') . ')';
        }

        if (php_sapi_name() === 'cli') {
            print_r($var);
        } else {
            highlight_string("<?php\n" . var_export($var, true));
        }
    }

    public  static function include(Closure $include) : void
    {
        $include->bindTo(new static)();
    }
}
