<?php

namespace App\Http\Controllers\Admin\API;

use App\Http\Controllers\Controller;
use App\Model\CategoryPost;
use App\Model\Post;
use App\Model\Post_Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;

class PostController extends Controller
{ 
    
   /**/
    public function index()
    {
        $post = Post::latest()->get();

        return response()->json([
           "post" => $post
        ],200);
    }
    public function store(Request $request)
    {

        $data = $this->validate($request,[
            'title'    => 'required|unique:posts',
            'category' => 'required',
            'tag'      => 'required',
            'body'     => 'required'
        ]);


        if($request->photo){
            $name = time().'.' . explode('/', explode(':', substr($request->photo, 0, strpos($request->photo, ';')))[1])[1];
            \Image::make($request->photo)->save(public_path('img/profile/').$name);
            $request->merge(['photo' => $name]);
        }

        $data['slug'] = \Str::slug($request->title);
        $post = new Post;
        $post->user_id = $request->user_id; 
        $post->title   = $data['title']; 
        $post->slug    = $data['slug']; 
        $post->image   = $name;
        $post->body    = $data['body'];
        $post->save(); 
    
        $post->tags()->sync($request->tag);
        $post->categorys()->sync($request->category);

        return ["data submit successfully"];
     


    }

    public function show($id)
    {
        
    }
    public function update(Request $request,Post $post)
    {


        $data = $this->validate($request,[
            'title'    => 'required|unique:posts',
            'category' => 'required',
            'tag'      => 'required',
            'body'     => 'required',

        ]);

        $currentPhoto = $post->image;

        if($request->photo != $currentPhoto){

            $name = time().'.' . explode('/', explode(':', substr($request->photo, 0, strpos($request->photo, ';')))[1])[1];
            \Image::make($request->photo)->save(public_path('img/profile/').$name);
            $request->merge(['photo' => $name]);

            $userPhoto = public_path('img/profile/').$currentPhoto;

            if(file_exists($userPhoto)){

                @unlink($userPhoto);
                
            }
           
        }

        $data['user_id'] =  $request->user_id ; 

        /*$post = Post::find($id);*/
        $post->user_id = $data['user_id']; 
        $post->title = $data['title']; 
        $post->image   = $name;
        $post->body = $data['body'];
        $post->save(); 
        
        $post->tags()->sync($request->tag);
        $post->categorys()->sync($request->category);

         return [
         'message' => 'Post updated successfully'
        ];

    }

   
    public function destroy(Post $post)
    {
        $cat_post = CategoryPost::where("post_id","=",$post->id);
        $cat_post->delete();

        $tag_post = Post_Tag::where("post_id","=",$post->id);
        $tag_post->delete();
        
        $post->delete();

        $currentPhoto = $post->image;

        $userPhoto = public_path('img/profile/').$currentPhoto;

        if(file_exists($userPhoto)) {

            @unlink($userPhoto);
                
        }

        return [
         'message' => 'Photo deleted successfully'
        ];
    }

    public function storeImage($customer)
    {
        if(request()->has('image')){
            $customer->update([
                'image' => request()->image->store('uploads','public'),
            ]);
        }
        $image = Image::make(public_path('storage/'.$customer->image))->fit(200,200);
        $image->save();
    }
}