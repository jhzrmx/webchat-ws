--TEST--
Calling reject() and then then() with invalid type should report unhandled rejection for TypeError
--SKIPIF--
<?php if (PHP_VERSION_ID >= 80000) die("Skipped: PHP 7 only."); ?>
--INI--
# suppress legacy PHPUnit 7 warning for Xdebug 3
xdebug.default_enable=
--FILE--
<?php

use function React\Promise\reject;

require __DIR__ . '/../vendor/autoload.php';

reject(new RuntimeException('foo'))->then(null, function (UnexpectedValueException $unexpected): void { // @phpstan-ignore-line
    echo 'This will never be shown because the types do not match' . PHP_EOL;
});

?>
--EXPECTF--
Unhandled promise rejection with TypeError: Argument 1 passed to {closure}() must be an instance of UnexpectedValueException, instance of RuntimeException given, called in %s/src/Internal/RejectedPromise.php on line %d and defined in %s:%d
Stack trace:
#0 %s/src/Internal/RejectedPromise.php(%d): {closure}(%S)
#1 %s(%d): React\Promise\Internal\RejectedPromise->then(%S)
#2 %A{main}
