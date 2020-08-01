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
use Daikon\ValueObject\TextMap;

final class TextMapValidator extends Validator
{
    private const MAX_SIZE = 0;
    private const MIN_TEXT_LEN = 1;
    private const MAX_TEXT_LEN = 80;

    /** @param mixed $input */
    protected function validate($input): TextMap
    {
        Assertion::isArray($input, 'Must be an array.');

        $path = $this->getPath();
        $settings = $this->getSettings();
        $size = $settings['size'] ?? self::MAX_SIZE;
        $keys = $settings['keys'] ?? [];
        $min = $settings['min'] ?? self::MIN_TEXT_LEN;
        $max = $settings['max'] ?? self::MAX_TEXT_LEN;

        if ($size > 0) {
            Assertion::maxCount($input, $size, "Must have at most $size size.");
        }

        $formatAssertion = Assert::lazy();
        foreach ($input as $key => $item) {
            $formatAssertion
                ->that($key, $path)
                ->string('Key must be a string.')
                ->notBlank('Key must not be blank.');

            if (!empty($keys)) {
                $formatAssertion->inArray($keys, "Key '$key' is not allowed.");
            }

            $formatAssertion
                ->that($item, $path)
                ->string($path."[$key] must be a string.")
                ->betweenLength($min, $max, $path."[$key] must be between $min and $max characters.");
        }
        $formatAssertion->verifyNow();

        return TextMap::fromNative($input);
    }
}
