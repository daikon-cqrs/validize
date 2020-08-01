<?php declare(strict_types=1);
/**
 * This file is part of the daikon-cqrs/validize project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Daikon\Tests\Validize\ValueObject;

use Daikon\Interop\InvalidArgumentException;
use Daikon\Validize\ValueObject\Severity;
use PHPUnit\Framework\TestCase;

final class SeverityTest extends TestCase
{
    public function testUnprocessed(): void
    {
        $severity = Severity::fromNative(Severity::UNPROCESSED);
        $this->assertEquals(-1, $severity->toNative());
        $this->assertEquals('-1', (string)$severity);
        $this->assertTrue($severity->isUnprocessed());
        $this->assertEquals(Severity::unprocessed(), $severity);
    }

    public function testSuccess(): void
    {
        $severity = Severity::fromNative(Severity::SUCCESS);
        $this->assertEquals(0, $severity->toNative());
        $this->assertEquals('0', (string)$severity);
        $this->assertTrue($severity->isSuccess());
        $this->assertEquals(Severity::success(), $severity);
    }

    public function testSilent(): void
    {
        $severity = Severity::fromNative(Severity::SILENT);
        $this->assertEquals(100, $severity->toNative());
        $this->assertEquals('100', (string)$severity);
        $this->assertTrue($severity->isSilent());
        $this->assertEquals(Severity::silent(), $severity);
    }

    public function testNotice(): void
    {
        $severity = Severity::fromNative(Severity::NOTICE);
        $this->assertEquals(200, $severity->toNative());
        $this->assertEquals('200', (string)$severity);
        $this->assertTrue($severity->isNotice());
        $this->assertEquals(Severity::notice(), $severity);
    }

    public function testError(): void
    {
        $severity = Severity::fromNative(Severity::ERROR);
        $this->assertEquals(300, $severity->toNative());
        $this->assertEquals('300', (string)$severity);
        $this->assertTrue($severity->isError());
        $this->assertEquals(Severity::error(), $severity);
    }

    public function testCritical(): void
    {
        $severity = Severity::fromNative(Severity::CRITICAL);
        $this->assertEquals(400, $severity->toNative());
        $this->assertEquals('400', (string)$severity);
        $this->assertTrue($severity->isCritical());
        $this->assertEquals(Severity::critical(), $severity);
    }

    public function testFromNativeWithNull(): void
    {
        $this->expectException(InvalidArgumentException::class);
        /** @psalm-suppress NullArgument */
        Severity::fromNative(null);
    }

    public function testFromNativeWithInvalidString(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Severity::fromNative('hello');
    }
}
