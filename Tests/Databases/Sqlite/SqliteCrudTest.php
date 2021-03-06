<?php

declare(strict_types=1);

/*
 * Studio 107 (c) 2018 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindy\Orm\Tests\Databases\Sqlite;

use Mindy\Orm\Tests\Basic\CrudTest;

class SqliteCrudTest extends CrudTest
{
    public $driver = 'sqlite';
}
