<?php declare(strict_types=1);
/**
 * This file is part of the daikon-cqrs/validize project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Daikon\Validize\Validator;

use Daikon\Interop\Assert;
use Daikon\Entity\EntityInterface;
use Daikon\Entity\EntityList;
use Daikon\Validize\Validator\Validator;

final class EntityListValidator extends Validator
{
    /** @param mixed $input */
    protected function validate($input): EntityList
    {
        $name = $this->getName();

        Assert::that($input)
            ->isArray('Must be an array.')
            ->satisfy(function (array $items) use ($name): void {
                $formatAssertion = Assert::lazy();
                $typeAssertion = Assert::lazy();
                foreach ($items as $index => $item) {
                    $formatAssertion
                        ->that($item, $name)
                        ->isArray($name."[$index] must be an array.")
                        ->notEmpty($name."[$index] must not be empty.");
                    $typeAssertion
                        ->that($item, $name)
                        ->keyExists(
                            EntityInterface::TYPE_KEY,
                            'Required input for '.$name."[$index] '".EntityInterface::TYPE_KEY."' is missing."
                        );
                }
                $formatAssertion->verifyNow();
                $typeAssertion->verifyNow();
            });

        return EntityList::fromNative($input);
    }
}
