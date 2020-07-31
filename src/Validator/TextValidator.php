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

final class TextValidator extends Validator
{
    private const MIN_LEN = 1;
    private const MAX_LEN = 80;

    /** @param mixed $input */
    protected function validate($input): Text
    {
        $settings = $this->getSettings();
        $min = $settings['min'] ?? self::MIN_LEN;
        $max = $settings['max'] ?? self::MAX_LEN;

        Assertion::string($input, 'Must be a string.');
        $text = trim($input);
        Assertion::betweenLength($text, $min, $max, "Must be between $min and $max characters.");

        return Text::fromNative($text);
    }
}
