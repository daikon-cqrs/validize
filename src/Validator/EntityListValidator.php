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
        $path = $this->getPath();

        Assert::that($input)
            ->isArray('Must be an array.')
            ->satisfy(function (array $items) use ($path): void {
                $formatAssertion = Assert::lazy();
                $typeAssertion = Assert::lazy();
                foreach ($items as $index => $item) {
                    $formatAssertion
                        ->that($item, $path)
                        ->isArray($path."[$index] must be an array.")
                        ->notEmpty($path."[$index] must not be empty.");
                    $typeAssertion
                        ->that($item, $path)
                        ->keyExists(
                            EntityInterface::TYPE_KEY,
                            'Required input for '.$path."[$index] '".EntityInterface::TYPE_KEY."' is missing."
                        );
                }
                $formatAssertion->verifyNow();
                $typeAssertion->verifyNow();
            });

        return EntityList::fromNative($input);
    }
}
