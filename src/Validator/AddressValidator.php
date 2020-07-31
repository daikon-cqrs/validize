<?php declare(strict_types=1);
/**
 * This file is part of the daikon-cqrs/validize project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Daikon\Validize\Validator;

use Daikon\Interop\Assert;
use Daikon\ValueObject\Address;

final class AddressValidator extends Validator
{
    /** @param mixed $input */
    protected function validate($input): Address
    {
        Assert::that($input)->isArray('Must be an array.')->notEmpty('Must not be empty.');

        return Address::fromNative($input);
    }
}
