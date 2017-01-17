<?php

namespace App\Http\Controllers\Procurement\Item;

use App\Repositories\Procurement\Item\CategoryRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    /**
     * @var CategoryRepository
     */
    protected $categoryRepository;

    /**
     * validation for category
     * @var array
     */
    private $categoryValidation = [
        'categories.*.name' => 'required|max:512',
        'categories.*.value' => 'required|numeric|between:0,100',
        'categories.*.lower' => 'required|numeric'
    ];

    /**
     * CategoryController constructor.
     * @param CategoryRepository $categoryRepository
     */
    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * list all categories
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function index()
    {
        return $this->categoryRepository->getCategories();
    }

    /**
     * handle category update
     * @param Request $request
     * @return array
     */
    public function store(Request $request)
    {
        $this->validate($request, $this->categoryValidation);

        foreach ($request->get('categories') as $category) {
            if (!empty($category['deleted_at']) && $category['deleted_at']) {
                $this->categoryRepository->removeCategory($category['id']);
                continue;
            } elseif (!empty($category['new']) && $category['new']) {
                $this->categoryRepository->addCategory($category['name'], (float)$category['value'], (int)$category['lower']);
                continue;
            }
            $this->categoryRepository->updateCategory($category['id'], $category['name'], (float)$category['value'], (int)$category['lower']);
        }
        return ['code' => '200', 'msg' => 'OK'];
    }
}
