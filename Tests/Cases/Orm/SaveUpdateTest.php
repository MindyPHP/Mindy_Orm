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
 * @date 04/01/14.01.2014 02:38
 */

namespace Tests\Orm;


use Tests\DatabaseTestCase;
use Tests\Models\Solution;
use Tests\Models\User;

class SaveUpdateTest extends DatabaseTestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->initModels([new User]);
    }

    public function tearDown()
    {
        $this->dropModels([new User]);
    }

    public function testSave()
    {
        $model = new User();
        $model->username = 'Anton';
        $model->password = 'VeryGoodP@ssword';
        $this->assertEquals(0, User::objects()->count());
        $this->assertTrue($model->getIsNewRecord());

        $this->assertTrue($model->isValid());
        $this->assertNull($model->pk);
        $this->assertEquals('Anton', $model->username);
        $this->assertEquals('VeryGoodP@ssword', $model->password);

        $saved = $model->save();
        $this->assertTrue($saved);
        $this->assertEquals(1, User::objects()->count());
        $this->assertFalse($model->getIsNewRecord());
        $this->assertEquals(1, $model->pk);
        $this->assertEquals('Anton', $model->username);
        $this->assertEquals('VeryGoodP@ssword', $model->password);
    }

    public function testSaveSelectedField()
    {
        $model = new User();
        $model->username = 'Anton';
        $model->password = 'VeryGoodP@ssword';
        $this->assertEquals(0, User::objects()->count());
        $this->assertTrue($model->getIsNewRecord());
        $this->assertTrue($model->isValid());
        $this->assertNull($model->pk);
        $this->assertEquals('Anton', $model->username);
        $this->assertEquals('VeryGoodP@ssword', $model->password);

        $saved = $model->save(['username']);
        $this->assertTrue($saved);
        $this->assertEquals(1, User::objects()->count());
        $this->assertFalse($model->getIsNewRecord());
        $this->assertEquals(1, $model->pk);
        $this->assertEquals('Anton', $model->username);
        $this->assertEquals('VeryGoodP@ssword', $model->password);

        $model = User::objects()->get();
        $this->assertNull($model->password);
    }

    public function testUpdate()
    {
        $model = new User();

        $this->assertTrue($model->getIsNewRecord());
        $model->username = 'Anton';
        $model->password = 'VeryGoodP@ssword';

        $this->assertTrue($model->getIsNewRecord());
        $saved = $model->save();

        $this->assertFalse($model->getIsNewRecord());

        $this->assertEquals(1, $model->pk);
        $this->assertTrue($saved);

        $model = new User();

        $this->assertTrue($model->getIsNewRecord());

        $model->username = 'Max';
        $model->password = 'VeryGoodP@ssword';

        $this->assertTrue($model->getIsNewRecord());

        $saved = $model->save();

        $this->assertFalse($model->getIsNewRecord());

        $this->assertTrue($saved);
        $this->assertEquals(2, User::objects()->count());
        $this->assertFalse($model->getIsNewRecord());

        $this->assertEquals('Max', $model->username);
        $this->assertEquals('VeryGoodP@ssword', $model->password);

        $tmpFind = User::objects()->get(['id' => 1]);

        $this->assertFalse($tmpFind->getIsNewRecord());

        $this->assertEquals('Anton', $tmpFind->username);
        $this->assertEquals('VeryGoodP@ssword', $tmpFind->password);

        $updated = User::objects()->filter(['id' => 1])->update(['username' => 'Unknown']);
        $model = User::objects()->get(['pk' => 1]);
        $this->assertEquals('Unknown', $model->username);
        $this->assertEquals(1, $updated);

        $this->assertEquals(2, User::objects()->count());
        // Max already has username `Unknown`
        $updated = User::objects()->filter(['id__gte' => 0])->update(['username' => 'Unknown']);
        $this->assertEquals(1, $updated);

        $this->assertEquals(2, User::objects()->count());
        $updated = User::objects()->filter(['id__gte' => 0])->update(['username' => '123']);
        $this->assertEquals(2, $updated);
    }

    public function testGetOrCreate()
    {
        $model = User::objects()->getOrCreate(['username' => 'Max', 'password' => 'VeryGoodP@ssword']);
        $this->assertFalse($model->getIsNewRecord());
        $this->assertEquals('Max', $model->username);
        $this->assertEquals('VeryGoodP@ssword', $model->password);
        $this->assertEquals(1, $model->pk);

        $newUser = new User();
        $newUser->username = 'Anton';
        $newUser->password = 'qwe';
        $newUser->save();
        $this->assertFalse($newUser->getIsNewRecord());
        $this->assertEquals(2, $newUser->pk);

        $queryUser = User::objects()->getOrCreate(['username' => 'Anton']);
        $this->assertEquals('Anton', $queryUser->username);
        $this->assertEquals('qwe', $queryUser->password);
        $this->assertEquals(2, $queryUser->pk);
    }

    public function testUpdateOrCreate()
    {
        $model = User::objects()->getOrCreate(['username' => 'Max', 'password' => 'VeryGoodP@ssword']);
        $this->assertEquals(1, $model->pk);
        $this->assertEquals('Max', $model->username);

        $updatedModel = User::objects()->updateOrCreate(['username' => 'Max'], ['username' => 'Oleg']);
        $this->assertEquals(1, $updatedModel->pk);
        $this->assertEquals('Oleg', $updatedModel->username);

        $updatedModel = User::objects()->updateOrCreate(['username' => 'Vasya'], ['username' => 'Vasya']);
        $this->assertEquals(2, $updatedModel->pk);
        $this->assertEquals('Vasya', $updatedModel->username);
    }

    public function testDelete()
    {
        $model = User::objects()->getOrCreate(['username' => 'Max', 'password' => 'VeryGoodP@ssword']);
        $this->assertNotNull($model->pk);
        $this->assertEquals(1, User::objects()->count());
        $this->assertEquals(1, $model->delete());
        $this->assertEquals(0, User::objects()->count());

        $model = User::objects()->getOrCreate(['username' => 'Max', 'password' => 'VeryGoodP@ssword']);
        $this->assertNotNull($model->pk);
        $this->assertEquals(1, User::objects()->count());
        $this->assertEquals(1, User::objects()->filter(['pk' => 2])->delete());
        $this->assertEquals(0, User::objects()->count());
    }

    public function testDeleteTwo()
    {
        $modelOne = User::objects()->getOrCreate(['username' => 'Max', 'password' => 'VeryGoodP@ssword']);
        $modelTwo = User::objects()->getOrCreate(['username' => 'Anton', 'password' => 'VeryGoodP@ssword']);

        $modelOne->delete();
        $this->assertEquals(1, User::objects()->count());
    }

    public function testDeleteQsTwo()
    {
        $modelOne = User::objects()->getOrCreate(['username' => 'Max', 'password' => 'VeryGoodP@ssword']);
        $modelTwo = User::objects()->getOrCreate(['username' => 'Anton', 'password' => 'VeryGoodP@ssword']);

        User::objects()->filter(['username' => 'Max'])->delete();
        $this->assertEquals(1, User::objects()->count());
    }

    public function testChangedValues()
    {
        $model = new User();

        $this->assertTrue($model->getIsNewRecord());

        $model->username = 'Anton';
        $model->password = 'VeryGoodP@ssword';

        $this->assertEquals($model->getDirtyAttributes(), [
            'username' => 'Anton',
            'password' => 'VeryGoodP@ssword'
        ]);

        $model->save();

        $this->assertEquals($model->getDirtyAttributes(), []);

        $this->assertFalse($model->getIsNewRecord());

        $model->username = 'Vasya';
        $model->username = 'Vasya';

        $this->assertEquals($model->getDirtyAttributes(), [
            'username' => 'Vasya'
        ]);

        $model->save();

        $finded = User::objects()->filter(['pk' => $model->pk])->get();
        $this->assertEquals('Vasya', $finded->username);
        $this->assertEquals($finded->getDirtyAttributes(), []);

        $finded->username = 'Max';
        $this->assertEquals($finded->getDirtyAttributes(), [
            'username' => 'Max'
        ]);
    }

    /**
     * https://github.com/studio107/Mindy_Query/issues/11
     * Issue #11
     */
    public function testIssue11()
    {
        // Fix hhvm test
        date_default_timezone_set('UTC');

        $this->initModels([new Solution]);
        $modelOne = Solution::objects()->getOrCreate([
            'status' => 1,
            'name' => 'test',
            'court' => 'qwe',
            'question' => 'qwe',
            'result' => 'qwe',
            'content' => 'qwe',
        ]);
        $this->assertEquals(1, $modelOne->pk);
        $sql = Solution::objects()->filter(['id' => '1'])->updateSql(['status' => 2]);
        $this->assertEquals('UPDATE `tests_solution` SET `status`=2 WHERE (`id`=\'1\')', $sql);
        $this->dropmodels([new Solution]);
    }
}
