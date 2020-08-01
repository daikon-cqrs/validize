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
    private string $path;

    private Severity $severity;

    private array $settings;

    /** @var mixed */
    private $argument;

    private array $imports = [];

    public function __construct(string $path, Severity $severity, array $settings = [])
    {
        $this->path = $path;
        $this->severity = $severity;
        $this->settings = $settings;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getSeverity(): Severity
    {
        return $this->severity;
    }

    public function getSettings(): array
    {
        return $this->settings;
    }

    /** @return mixed */
    public function getArgument()
    {
        return $this->argument;
    }

    /** @param mixed $argument */
    public function withArgument($argument): self
    {
        $copy = clone $this;
        $copy->argument = is_object($argument) ? clone $argument : $argument;
        return $copy;
    }

    public function getImports(): array
    {
        return $this->imports;
    }

    /** @param mixed $import */
    public function withImport(string $path, $import): self
    {
        $copy = clone $this;
        $copy->imports[$path] = is_object($import) ? clone $import : $import;
        return $copy;
    }
}
