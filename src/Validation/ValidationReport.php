<?php declare(strict_types=1);
/**
 * This file is part of the daikon-cqrs/validize project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Daikon\Validize\Validation;

use Daikon\DataStructure\TypedList;
use Daikon\Validize\ValueObject\Severity;

final class ValidationReport extends TypedList
{
    public function __construct(iterable $incidents = [])
    {
        $this->init($incidents, [ValidationIncident::class]);
    }

    public function getIncidents(Severity $severity): self
    {
        return $this->filter(
            fn(ValidationIncident $incident): bool => $incident->getSeverity()->equals($severity)
        );
    }

    public function getErrors(): self
    {
        return $this->getIncidents(Severity::error())->append($this->getIncidents(Severity::critical()));
    }

    public function getMessages(): array
    {
        $messages = [];
        /** @var ValidationIncident $incident */
        foreach ($this as $incident) {
            foreach ($incident->getMessages() as $message) {
                $messages[$incident->getValidatorDefinition()->getArgument()][] = $message;
            }
        }
        return $messages;
    }

    public function getStatusCode(): ?int
    {
        //returns first seen
        return $this->reduce(
            function (?int $status, ValidationIncident $incident): ?int {
                return $status ?? ($incident->getValidatorDefinition()->getSettings()['status'] ?? null);
            }
        );
    }

    public function isProvided(string $provides): bool
    {
        return $this->getIncidents(Severity::success())->reduce(
            function (bool $carry, ValidationIncident $incident) use ($provides): bool {
                return $carry
                    || $provides === ($incident->getValidatorDefinition()->getSettings()['provides'] ?? null);
            },
            false
        );
    }
}
