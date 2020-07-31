<?php declare(strict_types=1);
/**
 * This file is part of the daikon-cqrs/validize project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Daikon\Validize\Validator;

use Daikon\Interop\Assertion;
use Daikon\ValueObject\Timestamp;

final class TimestampValidator extends Validator
{
    /** @param mixed $input */
    protected function validate($input): Timestamp
    {
        $settings = $this->getSettings();
        $before = $settings['before'] ?? false;
        $after = $settings['after'] ?? false;

        if (!$input instanceof Timestamp) {
            Assertion::notEmpty($input, 'Must not be empty.');
            $timestamp = Timestamp::fromNative($input);
        } else {
            $timestamp = $input;
        }

        if ($before !== false) {
            Assertion::true(
                $timestamp->isBefore(Timestamp::fromNative($before)),
                'Timestamp must be before given time.'
            );
        }

        if ($after !== false) {
            Assertion::true(
                $timestamp->isAfter(Timestamp::fromNative($after)),
                'Timestamp must be after given time.'
            );
        }

        return $timestamp;
    }
}
