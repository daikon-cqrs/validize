<?php declare(strict_types=1);
/**
 * This file is part of the daikon-cqrs/validize project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Daikon\Validize\Validator;

use Daikon\Validize\Validation\ValidatorDefinition;

interface ValidatorInterface
{
    /** @return mixed */
    public function __invoke(ValidatorDefinition $validatorDefinition);
}
