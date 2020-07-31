<?php declare(strict_types=1);
/**
 * This file is part of the daikon-cqrs/validize project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Daikon\Validize\Validator;

use Daikon\Interop\Assert;
use Daikon\ValueObject\IntValue;

final class IntValueValidator extends Validator
{
    /** @param mixed $input */
    protected function validate($input): IntValue
    {
        $settings = $this->getSettings();
        $min = $settings['min'] ?? PHP_INT_MIN;
        $max = $settings['max'] ?? PHP_INT_MAX;

        Assert::that($input)
            ->integerish('Must be an integer.')
            ->greaterOrEqualThan($min, "Must be at least $min.")
            ->lessOrEqualThan($max, "Must be at most $max.");

        return IntValue::fromNative($input);
    }
}
