<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Base;

use Railt\Reflection\Base\Containers\BaseTypesContainer;
use Railt\Reflection\Contracts\Document;
use Railt\Reflection\Contracts\Types\SchemaType;

/**
 * Class BaseDocument
 */
abstract class BaseDocument extends BaseType implements Document
{
    use BaseTypesContainer;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var SchemaType
     */
    protected $schema;

    /**
     * @return string
     */
    public function getName(): string
    {
        return (string)$this->name;
    }

    /**
     * @return null|SchemaType
     */
    public function getSchema(): ?SchemaType
    {
        return $this->resolve()->schema;
    }

    /**
     * @return string
     */
    public function getTypeName(): string
    {
        return 'Document';
    }

    /**
     * @return array
     */
    public function __sleep(): array
    {
        return \array_merge(parent::__sleep(), [
            'name',
            'types',
            'schema',
        ]);
    }
}
