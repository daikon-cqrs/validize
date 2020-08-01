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

    public function getIncident(string $path): ?ValidationIncident
    {
        $index = $this->search(
            fn(ValidationIncident $incident): bool => $incident->getValidatorDefinition()->getPath() === $path
        );
        return $index !== false ? $this->get($index) : null;
    }

    public function getIncidents(Severity $severity): self
    {
        return $this->filter(
            fn(ValidationIncident $incident): bool => $incident->getSeverity()->equals($severity)
        );
    }

    public function getErrors(): self
    {
        return $this->filter(
            fn(ValidationIncident $incident): bool => $incident->getSeverity()->isGreaterThanOrEqual(Severity::error())
        );
    }

    public function getMessages(): array
    {
        $messages = [];
        /** @var ValidationIncident $incident */
        foreach ($this as $incident) {
            $messages[$incident->getValidatorDefinition()->getPath()] = $incident->getMessages();
        }
        return $messages;
    }

    public function getStatusCode(): ?int
    {
        //returns first seen status
        return $this->reduce(
            function (?int $status, ValidationIncident $incident): ?int {
                return $status ?? ($incident->getValidatorDefinition()->getSettings()['status'] ?? null);
            }
        );
    }

    public function isProvided(string $depends): bool
    {
        //@todo handle array $depends
        return $this->reduce(
            function (bool $carry, ValidationIncident $incident) use ($depends): bool {
                $provided = (array)($incident->getValidatorDefinition()->getSettings()['provides'] ?? []);
                return $carry || ($incident->getSeverity()->isSuccess() && in_array($depends, $provided));
            },
            false
        );
    }
}
