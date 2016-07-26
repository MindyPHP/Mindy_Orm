<?php
/**
 *
 *
 * All rights reserved.
 *
 * @author Falaleev Maxim
 * @email max@studio107.ru
 * @version 1.0
 * @company Studio107
 * @site http://studio107.ru
 * @date 04/01/14.01.2014 20:41
 */

namespace Mindy\Orm\Tests;

use Mindy\Query\ConnectionManager;
use Modules\Tests\Models\Category;
use Modules\Tests\Models\Color;
use Modules\Tests\Models\Cup;
use Modules\Tests\Models\Design;
use Modules\Tests\Models\Product;
use Tests\OrmDatabaseTestCase;

abstract class HasManyFieldTest extends OrmDatabaseTestCase
{
    public function getModels()
    {
        return [new Product, new Category, new Cup, new Design, new Color];
    }

    public function testSimple()
    {
        $categoryToys = new Category([
            'name' => 'Toys'
        ]);
        $this->assertTrue($categoryToys->getIsNewRecord());
        $categoryToys->save();
        $this->assertFalse($categoryToys->getIsNewRecord());

        $category_animals = new Category();
        $category_animals->name = 'Animals';
        $category_animals->save();

        $db = $this->getConnection();
        $adapter = $db->getAdapter();
        $tableSql = $adapter->quoteColumn('product');
        $tableAliasSql = $adapter->quoteColumn('product_1');
        $categoryIdSql = $adapter->quoteColumn('category_id');

        $this->assertEquals("SELECT COUNT(*) FROM $tableSql AS $tableAliasSql WHERE ($tableAliasSql.$categoryIdSql='1')", $categoryToys->products->countSql());
        $this->assertEquals(0, $categoryToys->products->count());

        $product_bear = new Product([
            'category' => $categoryToys,
            'name' => 'Bear',
            'price' => 100,
            'description' => 'Funny white bear'
        ]);
        $product_bear->save();

        $this->assertEquals(1, $categoryToys->products->count());

        $product_rabbit = new Product([
            'category' => $category_animals,
            'name' => 'Rabbit',
            'price' => 110,
            'description' => 'Rabbit with carrot'
        ]);
        $product_rabbit->save();

        $this->assertEquals(1, $categoryToys->products->count());

        $product_rabbit->category = $categoryToys;
        $product_rabbit->save();

        $this->assertEquals(2, $categoryToys->products->count());
    }

    public function testThrough()
    {

    }

    public function testMultiple()
    {
        $cup = new Cup();
        $cup->name = 'Amazing cup';
        $cup->save();

        $design = new Design();
        $design->name = 'Dragon';
        $design->cup = $cup;
        $design->save();

        $color = new Color();
        $color->name = 'red';
        $color->cup = $cup;
        $color->save();

        $sql = Cup::objects()->filter(['designs__name' => 'Dragon', 'colors__name' => 'red'])->allSql();
        $this->assertEquals("SELECT `cup_1`.* FROM `cup` `cup_1` LEFT OUTER JOIN `design` `design_2` ON `cup_1`.`id` = `design_2`.`cup_id` LEFT OUTER JOIN `color` `color_3` ON `cup_1`.`id` = `color_3`.`cup_id` WHERE (`design_2`.`name`='Dragon') AND (`color_3`.`name`='red') GROUP BY `cup_1`.`id`", $sql);
    }
}
