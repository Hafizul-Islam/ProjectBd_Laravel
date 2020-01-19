<?php

namespace App\Http\Controllers\Admin\API;

use App\Http\Controllers\Controller;
use App\Model\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
   
   public function index()
    {
        $tag = Tag::latest()->get();

        return response()->json([
           "tag" => $tag
        ],200);
    }
    
    public function store(Request $request)
    {
        $data = $this->validate($request,[
            'name' => 'required|unique:tags',
            'slug' => ''
        ]);
        $data['slug'] = \Str::slug($request->name);
        Tag::create($data);
    }

   
    public function show($id)
    {
        //
    }

   
    public function update(Request $request,Tag $tag)
    {
        $data = $this->validate($request,[
            'name' => 'required|unique:tags',
            'slug' => ''
        ]);
        $tag->update($data);
        return ["message" => "update tag successfully"];
    }

    
    public function destroy(Tag $tag)
    {
        //$this->authorize('delete',$category);

        $tag->delete();
        /*Category::findOrFail($id)->delete();*/
       return ["message" => "ok"];
    }
}
