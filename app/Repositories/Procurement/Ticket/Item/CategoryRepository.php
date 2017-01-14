<?php


namespace App\Repositories\Procurement\Ticket\Item;



use App\Eloquent\Procurement\Ticket\Item\Category;
use Illuminate\Database\Eloquent\SoftDeletes;

class CategoryRepository
{
    use SoftDeletes;

    /**
     * @var Category
     */
    protected $category;

    public function __construct(Category $category)
    {
        $this->category = $category;
    }

    public function getCategories()
    {
        return $this->category->all();
    }

    public function addCategory(string $name, float $value, int $lower)
    {
        return $this->category->create([
            'name' => $name,
            'value' => $value,
            'lower' => $lower
        ]);
    }

    public function updateCategory(int $id, string $name, float $value, int $lower)
    {
        return $this->category->find($id)->update([
            'name' => $name,
            'value' => $value,
            'lower' => $lower
        ]);
    }

    public function removeCategory(int $id)
    {
        return $this->category->find($id)->delete();
    }
}