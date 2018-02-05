<?php

declare(strict_types=1);

/*
 * Studio 107 (c) 2018 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindy\Orm\Tests\Models;

use Mindy\Orm\Manager;
use Mindy\Orm\Model;

class InstanceTestModel extends Model
{
    public static function objectsManager($instance = null)
    {
        if ($instance) {
            return 123;
        }
        $className = get_called_class();

        return new Manager($instance ? $instance : new $className());
    }
}
