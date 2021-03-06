<?php

declare(strict_types=1);

/*
 * Studio 107 (c) 2018 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindy\Orm\Tests\Models;

use Mindy\Orm\Fields\HasManyField;
use Mindy\Orm\Model;

class BookCategory extends Model
{
    public static function getFields()
    {
        return [
            'category_set' => [
                'class' => HasManyField::class,
                'modelClass' => Book::class,
            ],
            'categories' => [
                'class' => HasManyField::class,
                'modelClass' => Book::class,
            ],
        ];
    }
}
