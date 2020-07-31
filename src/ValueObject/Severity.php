<?php declare(strict_types=1);
/**
 * This file is part of the daikon-cqrs/validize project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Daikon\Validize\ValueObject;

use Daikon\Interop\Assertion;
use Daikon\ValueObject\ValueObjectInterface;
use ReflectionClass;

final class Severity implements ValueObjectInterface
{
    public const CRITICAL = 400;
    public const ERROR = 300;
    public const NOTICE = 200;
    public const SILENT = 100;
    public const SUCCESS = 0;
    public const UNEXECUTED = -1;

    private int $severity;

    /** @param int|string $severity */
    public static function fromNative($severity): self
    {
        Assertion::integerish($severity. 'Must be an integer.');
        return new self((int)$severity);
    }

    public static function critical(): self
    {
        return new self(self::CRITICAL);
    }

    public static function error(): self
    {
        return new self(self::ERROR);
    }

    public static function notice(): self
    {
        return new self(self::NOTICE);
    }

    public static function silent(): self
    {
        return new self(self::SILENT);
    }

    public static function success(): self
    {
        return new self(self::SUCCESS);
    }

    public static function unexecuted(): self
    {
        return new self(self::UNEXECUTED);
    }

    /** @param self $comparator */
    public function equals($comparator): bool
    {
        Assertion::isInstanceOf($comparator, self::class);
        return $this->toNative() === $comparator->toNative();
    }

    public function isGreaterThanOrEqual(Severity $comparator): bool
    {
        return $this->toNative() >= $comparator->toNative();
    }

    public function toNative(): int
    {
        return $this->severity;
    }

    public function __toString(): string
    {
        return (string)$this->severity;
    }

    private function __construct(int $severity)
    {
        Assertion::inArray($severity, (new ReflectionClass($this))->getConstants(), 'Invalid value.');
        $this->severity = $severity;
    }
}
