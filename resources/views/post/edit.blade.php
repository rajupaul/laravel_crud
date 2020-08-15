@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    
                    <div class="d-flex justify-content-between" >
                        <div>Edit Post </div>
                          <div><a href="{{route('posts.index')}}" class="btn btn-success">Back</a></div>
                    </div>
                </div>

                <div class="card-body">
                 <form action="{{route('posts.update',$post->id)}}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                      <label for="title">Title :</label>
                      <input type="text" value="{{ (old('title')) ? old('title') : $post->title }}" class="form-control"  id="title" placeholder="Enter title" name="title" >
                      @if($errors->any('title'))
                        <span class="text-danger"> {{$errors->first('title')}}</span>
                      @endif
                    </div>
                    <div class="form-group">
                      <label for="description">Description :</label>
                      <textarea class="form-control" id="description" placeholder="Enter description" name="description">{{ (old('description')) ? old('description') : $post->description }}</textarea>
                        @if($errors->any('description'))
                        <span class="text-danger"> {{$errors->first('description')}}</span>
                      @endif
                    </div>

                    <div class="form-group">
                      
                      <button type="button" data-toggle="collapse" data-target="#demo" class="btn btn-success">View Image</button>

                      <div id="demo" class="collapse">
                      <img src="{{asset('post_images/'.$post->image)}}">
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="image">Image :</label>
                      <input type="file" class="form-control " id="image" placeholder="Choose an image" name="image" >
                      @if($errors->any('image'))
                        <span class="text-danger"> {{$errors->first('image')}}</span>
                      @endif
                    </div>
                    <div class="form-group">
                      <label for="category">Category :</label>
                      <select class="form-control" id="category" name="category">
                        <option value="">Select Category</option>

                        @if(count($categories))
                          @foreach($categories as $category)
                             <option value="{{$category->id}}" 

                             @if(old('category') && old('category') ==$category->id)
                              {{'selected'}}
                             @elseif($post->category_id==$category->id)
                             {{'selected'}}
                             @endif

                              >{{$category->name}}</option>
                          @endforeach
                        @endif
                        
                      </select>
                    @if($errors->any('category'))
                        <span class="text-danger"> {{$errors->first('category')}}</span>
                      @endif
                    </div>
                    <div class="form-group">
                      <label for="tags">Tags :</label>
                      <select class="form-control" id="tags" name="tags[]" multiple>
                        <option value="">Select Tags</option>
                          @if(count($tags))
                          @foreach($tags as $tag)
                             <option value="{{$tag->id}}" 
                             @if(old('tags') && in_array($tag->id,old('tags')))
                              {{'selected'}}

                             @elseif(in_array($tag->id,$post->getTagsIdArray()) )
                             {{'selected'}}
                             @endif
                             >{{$tag->name}}</option>
                          @endforeach
                        @endif
                      </select>
                               @if($errors->any('tags'))
                        <span class="text-danger"> {{$errors->first('tags')}}</span>
                      @endif
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                  </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('javascript')
<script type="text/javascript">
 $("#category").select2({
    placeholder: "Select a category",
    allowClear: true
  });

  $("#tags").select2({
    placeholder: "Select tags",
    allowClear: true
  });
</script>
@endsection