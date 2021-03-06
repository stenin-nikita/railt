<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\Reflection;

use Railt\Reflection\Contracts\Document;
use Railt\Reflection\Contracts\Types\ArgumentType;
use Railt\Reflection\Contracts\Types\InputType;

/**
 * Class InputTestCase
 */
class InputTestCase extends AbstractReflectionTestCase
{
    /**
     * @return array
     * @throws \Psr\Cache\InvalidArgumentException
     * @throws \Railt\Parser\Exceptions\CompilerException
     * @throws \Railt\Parser\Exceptions\UnexpectedTokenException
     * @throws \Railt\Parser\Exceptions\UnrecognizedTokenException
     */
    public function provider(): array
    {
        $schema = <<<GraphQL
"""
 # This an Input type example
"""
input Test { 
    id: ID! = "Hell OR World"
}
GraphQL;

        return $this->dataProviderDocuments($schema);
    }

    /**
     * @dataProvider provider
     *
     * @param Document $document
     * @return void
     */
    public function testInputName(Document $document): void
    {
        /** @var InputType $input */
        $input = $document->getType('Test');

        static::assertNotNull($input);
        static::assertSame('Test', $input->getName());
    }

    /**
     * @dataProvider provider
     *
     * @param Document $document
     * @return void
     */
    public function testInputType(Document $document): void
    {
        /** @var InputType $input */
        $input = $document->getType('Test');
        static::assertNotNull($input);

        static::assertSame('Input', $input->getTypeName());
    }


    /**
     * @dataProvider provider
     *
     * @param Document $document
     * @return void
     */
    public function testInputDescription(Document $document): void
    {
        /** @var InputType $input */
        $input = $document->getType('Test');
        static::assertNotNull($input);

        $description = 'This an Input type example';

        static::assertSame($description, $input->getDescription());
    }

    /**
     * @dataProvider provider
     *
     * @param Document $document
     * @return void
     * @throws \PHPUnit\Framework\AssertionFailedError
     */
    public function testInputDeprecation(Document $document): void
    {
        /** @var InputType $input */
        $input = $document->getType('Test');
        static::assertNotNull($input);

        static::assertFalse($input->isDeprecated());
        static::assertSame('', $input->getDeprecationReason());
    }

    /**
     * @dataProvider provider
     *
     * @param Document $document
     * @return void
     * @throws \PHPUnit\Framework\AssertionFailedError
     */
    public function testInputIdField(Document $document): void
    {
        /** @var InputType $input */
        $input = $document->getType('Test');
        static::assertNotNull($input);

        /** @var ArgumentType $id */
        $id = $input->getArgument('id');
        static::assertNotNull($id);

        static::assertSame('id', $id->getName());
        static::assertSame('ID', $id->getType()->getName());
        static::assertTrue($id->isNonNull());
        static::assertSame('Hell OR World', $id->getDefaultValue());
    }
}
