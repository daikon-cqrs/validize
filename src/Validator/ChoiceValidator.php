<?php declare(strict_types=1);
/**
 * This file is part of the daikon-cqrs/validize project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Daikon\Validize\Validator;

use Daikon\Interop\Assertion;
use Daikon\ValueObject\Text;

final class ChoiceValidator extends Validator
{
    /** @param mixed $input */
    protected function validate($input): Text
    {
        $settings = $this->getSettings();
        $choices = $settings['choices'] ?? [];

        Assertion::notEmpty($choices, 'Available choices must be specified.');
        Assertion::inArray($input, $choices, 'Not a valid choice.');

        return Text::fromNative($input);
    }
}
