<?php declare(strict_types=1);
/**
 * This file is part of the daikon-cqrs/validize project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Daikon\Validize\Validator;

use Daikon\Interop\Assert;
use Daikon\ValueObject\Sha256;

final class Sha256Validator extends Validator
{
    /** @param mixed $input */
    protected function validate($input): Sha256
    {
        Assert::that($input, 'Invalid format.')->string()->notBlank();

        return Sha256::fromNative($input);
    }
}
