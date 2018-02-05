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

class Test extends Model
{
    public static function getFields()
    {
        return [
            'name' => [
                'class' => CharField::class,
                'verboseName' => 'Name',
            ],
        ];
    }

    public static function tableName()
    {
        return 'tests_test';
    }
}
