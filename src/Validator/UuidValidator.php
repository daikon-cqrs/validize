<?php declare(strict_types=1);
/**
 * This file is part of the daikon-cqrs/validize project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Daikon\Validize\Validator;

use Daikon\ValueObject\Uuid;

final class UuidValidator extends Validator
{
    /** @param mixed $input */
    protected function validate($input): Uuid
    {
        return Uuid::fromNative($input);
    }
}
