<?php declare(strict_types=1);
/**
 * This file is part of the daikon-cqrs/validize project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Daikon\Validize\Validator;

use Daikon\Interop\Assertion;

final class EmptyValidator extends Validator
{
    /** @param mixed $input */
    protected function validate($input): void
    {
        Assertion::noContent($input, 'Must be empty.');
    }
}
