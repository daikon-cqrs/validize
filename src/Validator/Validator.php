<?php declare(strict_types=1);
/**
 * This file is part of the daikon-cqrs/validize project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Daikon\Validize\Validator;

use Daikon\Validize\Validation\ValidatorDefinition;

abstract class Validator implements ValidatorInterface
{
    private ValidatorDefinition $validatorDefinition;

    public function __invoke(ValidatorDefinition $validatorDefinition)
    {
        $this->validatorDefinition = $validatorDefinition;
        $path = $validatorDefinition->getPath();
        //@todo path/method mapping
        $validationMethod = 'validate'.ucfirst(trim($path, ' _'));
        $validationCallable = [$this, $validationMethod];
        $validationCallable = is_callable($validationCallable) ? $validationCallable : [$this, 'validate'];
        return $validationCallable($validatorDefinition->getArgument());
    }

    protected function getPath(): string
    {
        return $this->validatorDefinition->getPath();
    }

    protected function getSettings(): array
    {
        return $this->validatorDefinition->getSettings();
    }

    protected function getImports(): array
    {
        return $this->validatorDefinition->getImports();
    }
}
