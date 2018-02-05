<?php

declare(strict_types=1);

/*
 * Studio 107 (c) 2018 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindy\Orm\Tests\Models;

use Mindy\Orm\Fields\IntField;

class CompositeModel extends DummyModel
{
    public static function getFields()
    {
        return [
            'order_id' => [
                'class' => IntField::class,
                'primary' => true,
            ],
            'user_id' => [
                'class' => IntField::class,
                'primary' => true,
            ],
        ];
    }
}
