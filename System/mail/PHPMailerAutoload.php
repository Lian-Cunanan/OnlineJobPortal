<?php
/**
 * PHPMailer SPL autoloader.
 * PHP Version 5+
 * @package PHPMailer
 * @link https://github.com/PHPMailer/PHPMailer/ The PHPMailer GitHub project
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 */

/**
 * PHPMailer SPL autoloader.
 * @param string $classname The name of the class to load
 */
function PHPMailerAutoload($classname)
{
    $filename = __DIR__ . DIRECTORY_SEPARATOR . 'class.' . strtolower($classname) . '.php';
    if (is_readable($filename)) {
        require $filename;
    }
}

// SPL autoloading was introduced in PHP 5.1.2
spl_autoload_register('PHPMailerAutoload');

// If additional fallback is needed for even older versions of PHP (less than 5.1.2),
// you should consider upgrading PHP as it is no longer supported.
?>
