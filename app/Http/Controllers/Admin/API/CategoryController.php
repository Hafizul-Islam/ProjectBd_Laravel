<?php

namespace App\Http\Controllers\Admin\API;

use App\Http\Controllers\Controller;
use App\Model\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    
/*    public function index()
    {
        $category = Category::get()->all();
        return $category;
    }*/
    public function index()
    {
        $categories = Category::latest()->get();

        return response()->json([
           "categories" => $categories
        ],200);
    }
   
    public function store(Request $request)
    {
        $data = $this->validate($request,[
            'name' => 'required|unique:categories',
            'slug' => ''
        ]);
        $data['slug'] = \Str::slug($request->name);
        Category::create($data);
    }

   
    public function show($id)
    {
        //
    }

   
    public function update(Request $request,Category $category)
    {
        $data = $this->validate($request,[
            'name' => 'required|unique:categories',
            'slug' => ''
        ]);
        $category->update($data);
        return ["message" => "update successfully"];
    }

    
    public function destroy(Category $category)
    {
        //$this->authorize('delete',$category);

        $category->delete();
        /*Category::findOrFail($id)->delete();*/
       return ["message" => "ok"];
    }
}
