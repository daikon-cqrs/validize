<?php declare(strict_types=1);
/**
 * This file is part of the daikon-cqrs/validize project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Daikon\Validize\Validation;

use Daikon\Validize\ValueObject\Severity;

final class ValidatorDefinition
{
    private string $argument;

    private string $implementor;

    private Severity $severity;

    private array $settings;

    public function __construct(string $argument, string $implementor, Severity $severity, array $settings = [])
    {
        $this->argument = $argument;
        $this->implementor = $implementor;
        $this->severity = $severity;
        $this->settings = $settings;
    }

    public function getArgument(): string
    {
        return $this->argument;
    }

    public function getImplementor(): string
    {
        return $this->implementor;
    }

    public function getSeverity(): Severity
    {
        return $this->severity;
    }

    public function getSettings(): array
    {
        return $this->settings;
    }
}
