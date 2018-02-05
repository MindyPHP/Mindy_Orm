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
use Mindy\Orm\Model;

class CustomPk extends Model
{
    public static function getFields()
    {
        return [
            'id' => [
                'class' => CharField::class,
                'primary' => true,
            ],
        ];
    }
}
