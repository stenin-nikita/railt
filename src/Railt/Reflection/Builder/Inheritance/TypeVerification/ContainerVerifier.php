<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Builder\Inheritance\TypeVerification;

use Railt\Reflection\Exceptions\TypeConflictException;
use Railt\Reflection\Contracts\Behavior\AllowsTypeIndication;

/**
 * Class ContainerInheritance
 */
class ContainerVerifier extends AbstractVerifier
{
    /**
     * @param AllowsTypeIndication $a
     * @param AllowsTypeIndication $b
     * @return bool
     * @throws TypeConflictException
     */
    public function verify(AllowsTypeIndication $a, AllowsTypeIndication $b): bool
    {
        switch (true) {
            case $this->isSame($a, $b):
                return true;

            case $a->isList():
                return $this->typeListVerification($a, $b);
        }

        return $this->typeSimpleVerification($a, $b);
    }

    /**
     * @param AllowsTypeIndication $a
     * @param AllowsTypeIndication $b
     * @return bool
     */
    private function isSame(AllowsTypeIndication $a, AllowsTypeIndication $b): bool
    {
        return $this->isSameList($a, $b) && $this->isSameNonNull($a, $b) && $this->isSameListOfNonNulls($a, $b);
    }

    /**
     * @param AllowsTypeIndication $a
     * @param AllowsTypeIndication $b
     * @return bool
     */
    private function isSameList(AllowsTypeIndication $a, AllowsTypeIndication $b): bool
    {
        return $a->isList() === $b->isList();
    }

    /**
     * @param AllowsTypeIndication $a
     * @param AllowsTypeIndication $b
     * @return bool
     */
    private function isSameNonNull(AllowsTypeIndication $a, AllowsTypeIndication $b): bool
    {
        return $a->isNonNull() === $b->isNonNull();
    }

    /**
     * @param AllowsTypeIndication $a
     * @param AllowsTypeIndication $b
     * @return bool
     */
    private function isSameListOfNonNulls(AllowsTypeIndication $a, AllowsTypeIndication $b): bool
    {
        return $a->isListOfNonNulls() === $b->isListOfNonNulls();
    }

    /**
     * @param AllowsTypeIndication $a
     * @param AllowsTypeIndication $b
     * @return bool
     * @throws TypeConflictException
     */
    private function typeListVerification(AllowsTypeIndication $a, AllowsTypeIndication $b): bool
    {
        if (! $b->isList()) {
            return $this->throw('List "%s" can not be redefine by type "%s"',
                $this->relationToString($a),
                $this->relationToString($b)
            );
        }

        if ($a->isNonNull() && ! $b->isNonNull()) {
            return $this->throw('List of "%s" can not be redefine by nullable list "%s"',
                $this->relationToString($a),
                $this->relationToString($b)
            );
        }

        if ($a->isListOfNonNulls() && ! $b->isListOfNonNulls()) {
            return $this->throw('List of type "%s" can not be redefine by nullable type "%s"',
                $this->relationToString($a),
                $this->relationToString($b)
            );
        }

        return true;
    }

    /**
     * @param AllowsTypeIndication $a
     * @param AllowsTypeIndication $b
     * @return bool
     * @throws TypeConflictException
     */
    private function typeSimpleVerification(AllowsTypeIndication $a, AllowsTypeIndication $b): bool
    {
        if ($b->isList()) {
            return $this->throw('Type "%s" can not be redefine by list type "%s"',
                $this->relationToString($a),
                $this->relationToString($b)
            );
        }

        if ($a->isNonNull() && ! $b->isNonNull()) {
            return $this->throw('Type "%s" can not be redefine by nullable type "%s"',
                $this->relationToString($a),
                $this->relationToString($b)
            );
        }

        return true;
    }
}
