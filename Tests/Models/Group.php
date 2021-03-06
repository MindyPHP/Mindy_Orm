<?php

declare(strict_types=1);

/*
 * Studio 107 (c) 2018 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindy\Orm\Tests\Models;

use Mindy\Orm\Fields\CharField;
use Mindy\Orm\Fields\ManyToManyField;
use Mindy\Orm\Model;

/**
 * Class Group.
 *
 * @property string $name
 * @property \Mindy\Orm\Manager $users
 */
class Group extends Model
{
    public static function getFields()
    {
        return [
            'name' => [
                'class' => CharField::class,
            ],
            'users' => [
                'class' => ManyToManyField::class,
                'modelClass' => User::class,
                'through' => Membership::class,
                'link' => ['group_id', 'user_id'],
            ],
        ];
    }
}
