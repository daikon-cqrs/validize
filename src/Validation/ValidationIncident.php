<?php declare(strict_types=1);
/**
 * This file is part of the daikon-cqrs/validize project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Daikon\Validize\Validation;

use Daikon\Validize\ValueObject\Severity;

final class ValidationIncident
{
    private ValidatorDefinition $validatorDefinition;

    private Severity $severity;

    /** @var string[] */
    private array $messages = [];

    public function __construct(ValidatorDefinition $validatorDefinition, Severity $severity)
    {
        $this->validatorDefinition = $validatorDefinition;
        $this->severity = $severity;
    }

    public function addMessage(string $message): self
    {
        $this->messages[] = $message;
        return $this;
    }

    public function getValidatorDefinition(): ValidatorDefinition
    {
        return $this->validatorDefinition;
    }

    public function getSeverity(): Severity
    {
        return $this->severity;
    }

    public function getMessages(): array
    {
        return $this->messages;
    }
}
