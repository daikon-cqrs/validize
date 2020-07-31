<?php declare(strict_types=1);
/**
 * This file is part of the daikon-cqrs/validize project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Daikon\Validize\Validator;

use Daikon\Interop\Assert;
use Daikon\ValueObject\Email;
use Egulias\EmailValidator\EmailValidator as EguliasEmailValidator;
use Egulias\EmailValidator\Validation\RFCValidation;

final class EmailValidator extends Validator
{
    /** @param mixed $input */
    protected function validate($input): Email
    {
        Assert::that($input, 'Invalid format.')
            ->string()
            ->notBlank()
            ->satisfy(fn(): bool => (new EguliasEmailValidator)->isValid($input, new RFCValidation));

        return Email::fromNative($input);
    }
}
