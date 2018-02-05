<?php

declare(strict_types=1);

/*
 * Studio 107 (c) 2018 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindy\Orm\Tests\Models;

use Mindy\Orm\Base;

class DummyModel extends Base
{
    public function update(array $fields = [])
    {
        $state = true;
        if ($state) {
            $this->attributes->resetOldAttributes();
        }

        return $state;
    }

    public function insert(array $fields = [])
    {
        $state = true;
        if ($state) {
            $this->attributes->resetOldAttributes();
        }

        return $state;
    }
}
