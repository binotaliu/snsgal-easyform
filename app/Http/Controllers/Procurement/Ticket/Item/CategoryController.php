<?php

namespace App\Http\Controllers\Procurement\Ticket\Item;

use App\Repositories\Procurement\Ticket\Item\CategoryRepository;
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
        'name' => 'required|max:512',
        'value' => 'required|numeric|between:0,100',
        'lower' => 'required|numeric'
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
     * create a new category
     * @param Request $request
     * @return array
     */
    public function store(Request $request)
    {
        $this->validate($request, $this->categoryValidation);

        $this->categoryRepository->addCategory($request->get('name'), (float)$request->get('value'), (int)$request->get('lower'));
        return ['code' => '200', 'msg' => 'OK'];
    }

    /**
     * update an exist category
     * @param Request $request
     * @param int $id
     * @return array
     */
    public function update(Request $request, int $id)
    {
        $this->validate($request, $this->categoryValidation);

        $this->categoryRepository->updateCategory($id, $request->get('name'), (float)$request->get('value'), (int)$request->get('lower'));
        return ['code' => '200', 'msg' => 'OK'];
    }

    /**
     * delete a category
     * @param int $id
     * @return array
     */
    public function destroy(int $id)
    {
        $this->categoryRepository->removeCategory($id);
        return ['code' => '200', 'msg' => 'OK'];
    }
}
