<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use App\Tag;
use App\Post;
class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
      $data['categories']=Category::orderBy('id','desc')->get();

      $post_query=Post::withCount('comments')->where('user_id',auth()->id());
    
      if($request->category){
        $post_query->whereHas('category',function($q) use ($request){
         $q->where('name',$request->category);
        });
      }

      if($request->keyword){
       $post_query->where('title','LIKE','%'.$request->keyword.'%');
      }


      if($request->sortByComments && in_array($request->sortByComments, ['asc','desc'])){
       $post_query->orderBy('comments_count',$request->sortByComments);
      }
      $data['posts']=$post_query->orderBy('id','DESC')->paginate(2);
      return view('post.index',$data);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
 
       $data['categories']=Category::orderBy('id','desc')->get();
       $data['tags']=Tag::orderBy('id','desc')->get();
       return view('post.create',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
         'title'=>'required|max:255',
         'description'=>'required',
         'image'=>'required|mimes:jpeg,jpg,png',
         'category'=>'required',
         'tags'=>'required|array'
        ],[
         'category.required'=>'Please select a category.',
         'tags.required'=>'Please select atlest one tag.'
        ]);

        if($request->hasFile('image')){

            $image=$request->file('image');

            $image_name=time().'.'.$image->extension();
            $image->move(public_path('post_images'),$image_name);
        }

        $post=Post::create([
         'title'=>$request->title,
         'description'=>$request->description,
         'image'=>$image_name,
         'user_id'=>auth()->id(),
         'category_id'=>$request->category
        ]);

        $post->tags()->sync($request->tags);
        
        return redirect()->route('posts.index')->with('success','Post successfully created');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $data['post']=$post=Post::findOrFail($id);

        // if($post->user_id !=auth()->id()){
        //  abort(403);
        // }

       $this->authorize('view', $post);
       return view('post.show',$data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

      
       $data['post']=$post=Post::findOrFail($id);
         $this->authorize('update', $post);

       $data['categories']=Category::orderBy('id','desc')->get();
       $data['tags']=Tag::orderBy('id','desc')->get();
       return view('post.edit',$data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {



        $post=Post::findOrFail($id);

             $this->authorize('update', $post);
         $request->validate([
         'title'=>'required|max:255',
         'description'=>'required',
         'image'=>'nullable|mimes:jpeg,jpg,png',
         'category'=>'required',
         'tags'=>'required|array'
        ],[
         'category.required'=>'Please select a category.',
         'tags.required'=>'Please select atlest one tag.'
        ]);


        if($request->hasFile('image')){

            $image=$request->file('image');

            $image_name=time().'.'.$image->extension();
            $image->move(public_path('post_images'),$image_name);

            $old_path=public_path().'post_images/'.$post->image;

            if(\File::exists($old_path)){
             \File::delete($old_path);
            }

        }else{
           $image_name =$post->image;
        }

        $post->update([
         'title'=>$request->title,
         'description'=>$request->description,
         'image'=>$image_name,
         'user_id'=>auth()->id(),
         'category_id'=>$request->category
        ]);

        $post->tags()->sync($request->tags);

        return redirect()->route('posts.index')->with('success','Post successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
         $post=Post::findOrFail($id);
     $this->authorize('delete', $post);
         $old_path=public_path().'post_images/'.$post->image;

        if(\File::exists($old_path)){
         \File::delete($old_path);
        }

        $post->delete();


        return redirect()->route('posts.index')->with('success','Post successfully deleted.');
    }
}
