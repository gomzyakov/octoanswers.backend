<?php

class Mapper_Category__update__negative__ID__Test extends Abstract_DB_TestCase
{
    protected $setUpDB = ['ru' => ['categories']];

    public function test_Exception_when_category_ID_not_exists()
    {
        $category = new \Model\Category();
        $category->id = 2215;
        $category->title = 'impossible';

        $this->expectExceptionMessage('Category with ID 2215 not exists');
        $category = (new Category_Mapper('ru'))->update($category);
    }

    public function test_Exception_when_category_ID_equal_zero()
    {
        $category = new \Model\Category();
        $category->id = 0;
        $category->title = 'car';

        $this->expectExceptionMessage('Category id param 0 must be greater than or equal to 1');
        $category = (new Category_Mapper('ru'))->update($category);
    }

    public function test_Exception_when_category_ID_below_zero()
    {
        $category = new \Model\Category();
        $category->id = -1;
        $category->title = 'guf';

        $this->expectExceptionMessage('Category id param -1 must be greater than or equal to 1');
        $category = (new Category_Mapper('ru'))->update($category);
    }
}
