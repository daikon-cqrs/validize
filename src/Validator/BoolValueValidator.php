<?php declare(strict_types=1);
/**
 * This file is part of the daikon-cqrs/validize project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Daikon\Validize\Validator;

use Daikon\Interop\Assertion;
use Daikon\ValueObject\BoolValue;

final class BoolValueValidator extends Validator
{
    /** @param mixed $input */
    protected function validate($input): BoolValue
    {
        $value = filter_var($input, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
        Assertion::boolean($value, 'Invalid boolean.');

        return BoolValue::fromNative($value);
    }
}
