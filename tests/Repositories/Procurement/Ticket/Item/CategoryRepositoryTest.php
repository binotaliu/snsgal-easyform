<?php

use App\Eloquent\Procurement\Ticket\Item\Category;
use App\Repositories\Procurement\Item\CategoryRepository;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CategoryRepositoryTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @var CategoryRepository
     */
    protected $categoryRepository;

    /**
     * @var Category
     */
    protected $category;

    public function setUp()
    {
        parent::setUp();
        $this->categoryRepository = app('App\Repositories\Procurement\Ticket\Item\CategoryRepository');
        $this->category = app('App\Eloquent\Procurement\Ticket\Item\Category');
    }

    /**
     * @return Category
     */
    public function makeCategory()
    {
        return factory(Category::class)->create();
    }

    public function testAddCategory()
    {
        $this->categoryRepository->addCategory('game', 4.25, 40);

        // note: there is no assertion
    }

    public function testGetCategories()
    {
        $expected = 10;
        for ($i = 0; $i < $expected; $i++) {
            $this->makeCategory();
        }

        $actual = $this->categoryRepository->getCategories()->count();
        $this->assertEquals($expected, $actual);
    }

    public function testUpdateCategory()
    {
        $expected = 'doujinshi';

        $category = $this->makeCategory();
        $this->categoryRepository->updateCategory($category->id, $expected, $category->value, $category->lower);

        $actual = $this->category->find($category->id)->name;
        $this->assertEquals($expected, $actual);
    }

    public function testRemoveCategory()
    {
        $expected = 10;
        $category = null;
        for ($i = 0; $i <= $expected; $i++) {
            $category = $this->makeCategory();
        }
        //delete last one
        $this->categoryRepository->removeCategory($category->id);

        $actual = $this->category->all()->count();
        $this->assertEquals($expected, $actual);
    }
}
