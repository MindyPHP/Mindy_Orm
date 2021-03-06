<?php

declare(strict_types=1);

/*
 * Studio 107 (c) 2018 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindy\Orm\Fields;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class TimestampField extends IntField
{
    public function getValidationConstraints()
    {
        return array_merge(parent::getValidationConstraints(), [
            new Assert\Callback(function ($value, ExecutionContextInterface $context, $payload) {
                if (false == preg_match('/^[1-9][0-9]*$/', (string) $value)) {
                    $context->buildViolation('Incorrect value')
                        ->atPath($this->getAttributeName())
                        ->addViolation();
                }
            }),
        ]);
    }
}
