<?php declare(strict_types=1);
/**
 * This file is part of the daikon-cqrs/validize project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Daikon\Validize\Validator;

use Daikon\Interop\Assert;
use Daikon\Interop\Assertion;
use Daikon\ValueObject\TextList;

final class TextListValidator extends Validator
{
    private const MAX_SIZE = 0;
    private const MIN_TEXT_LEN = 1;
    private const MAX_TEXT_LEN = 80;

    /** @param mixed $input */
    protected function validate($input): TextList
    {
        Assertion::isArray($input, 'Must be an array.');

        $name = $this->getArgument();
        $settings = $this->getSettings();
        $size = $settings['size'] ?? self::MAX_SIZE;
        $min = $settings['min'] ?? self::MIN_TEXT_LEN;
        $max = $settings['max'] ?? self::MAX_TEXT_LEN;

        if ($size > 0) {
            Assertion::maxCount($input, $size, "Must have at most $size items.");
        }

        $formatAssertion = Assert::lazy();
        foreach ($input as $index => $item) {
            $formatAssertion
                ->that($item, $name)
                ->string($name."[$index] must be a string.")
                ->betweenLength($min, $max, $name."[$index] must be between $min and $max characters.");
        }
        $formatAssertion->verifyNow();

        return TextList::fromNative($input);
    }
}
