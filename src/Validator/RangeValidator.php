<?php declare(strict_types=1);
/**
 * This file is part of the daikon-cqrs/validize project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Daikon\Validize\Validator;

use Daikon\Interop\Assert;
use Daikon\ValueObject\Range;

final class RangeValidator extends Validator
{
    private const SIZE_LIMIT = 50;

    /** @param mixed $input */
    protected function validate($input): Range
    {
        $settings = $this->getSettings();
        $limit = $settings['limit'] ?? self::SIZE_LIMIT;

        Assert::that($input)->string('Must be a string.')->regex('/^\[\d+,\d+\]$/', 'Invalid format.');
        
        $nativeRange = array_map('intval', explode(',', trim($input, '[]')));
        $range = Range::fromNative($nativeRange);

        Assert::that($range->getSize())
            ->greaterOrEqualThan(0, 'Range size must be greater than 0.')
            ->lessOrEqualThan($limit, "Range size must be at most $limit.");

        return $range;
    }
}
