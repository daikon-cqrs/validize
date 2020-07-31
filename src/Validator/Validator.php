<?php declare(strict_types=1);
/**
 * This file is part of the daikon-cqrs/validize project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Daikon\Validize\Validator;

use Daikon\Interop\Assertion;
use Daikon\Interop\InvalidArgumentException;
use Daikon\Interop\RuntimeException;
use Daikon\Validize\Validation\ValidatorDefinition;
use Psr\Http\Message\ServerRequestInterface;

abstract class Validator implements ValidatorInterface
{
    public const ATTR_PAYLOAD = '_payload';

    private ValidatorDefinition $validatorDefinition;

    private array $imports = [];

    public function __construct(ValidatorDefinition $validatorDefinition)
    {
        $this->validatorDefinition = $validatorDefinition;
    }

    public function __invoke(ServerRequestInterface $request): ServerRequestInterface
    {
        //@todo support multiple arguments in a validator
        $argument = $this->validatorDefinition->getArgument();
        $settings = $this->validatorDefinition->getSettings();
        $payload = $request->getAttribute(self::ATTR_PAYLOAD, []);

        $validationMethod = 'validate'.ucfirst(ltrim($argument, '_'));
        $validationCallback = [$this, $validationMethod];
        $validationCallback = is_callable($validationCallback) ? $validationCallback : [$this, 'validate'];
        if (!is_callable($validationCallback)) {
            throw new RuntimeException("Missing required validation method 'validate' or '$validationMethod'.");
        }

        //@todo better convert body and query params to attributes
        //@todo use 'source' settings for headers
        $queryParams = [];
        parse_str($request->getUri()->getQuery(), $queryParams);
        $mergedInput = array_merge($this->parseBody($request), $queryParams, $request->getAttributes());

        if (!array_key_exists($argument, $mergedInput)) {
            if ($settings['required'] ?? true) {
                throw new InvalidArgumentException('Missing required input.');
            }
            if (!array_key_exists('default', $settings)) {
                return $request;
            }
            $result = $settings['default'];
        } else {
            if (array_key_exists('import', $settings)) {
                foreach ((array)$settings['import'] as $import) {
                    if (!array_key_exists($import, $payload)) {
                        throw new InvalidArgumentException("Missing required import '$import'.");
                    }
                    $this->imports[$import] = $payload[$import];
                }
            }
            $result = $validationCallback($mergedInput[$argument]);
        }

        return $request->withAttribute(
            self::ATTR_PAYLOAD,
            array_merge_recursive($payload, [($settings['export'] ?? $argument) => $result])
        );
    }

    protected function getSettings(): array
    {
        return $this->validatorDefinition->getSettings();
    }

    protected function getImports(): array
    {
        return $this->imports;
    }

    protected function getArgument(): string
    {
        return $this->validatorDefinition->getArgument();
    }

    private function parseBody(ServerRequestInterface $request): array
    {
        $contentType = $request->getHeaderLine('Content-Type');

        if (strpos(trim($contentType), 'application/json') === 0) {
            $data = json_decode((string)$request->getBody(), true);
        } else {
            $data = $request->getParsedBody();
        }

        Assertion::nullOrIsArray($data, 'Failed to parse data from request body.');

        return (array)$data;
    }
}
