<?php declare(strict_types=1);
/**
 * This file is part of the daikon-cqrs/validize project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Daikon\Validize\Validator;

use Daikon\Interop\Assert;
use Daikon\Interop\Assertion;

final class ArrayValidator extends Validator
{
    /** @param mixed $input */
    protected function validate($input): array
    {
        $name = $this->getName();
        $settings = $this->getSettings();
        $values = $settings['values'] ?? [];

        Assertion::notEmpty($values, 'Permitted values must be specified.');

        Assert::that($input)
            ->isArray('Must be an array.')
            ->satisfy(function (array $items) use ($name, $values): void {
                $formatAssertion = Assert::lazy();
                foreach ($items as $key => $item) {
                    $formatAssertion
                        ->that($item, $name)
                        ->notBlank($name."[$key] must not be blank.")
                        ->inArray($values, $name."[$key] '$item' is not a valid value.");
                }
                $formatAssertion->verifyNow();
            });

        return $input;
    }
}
