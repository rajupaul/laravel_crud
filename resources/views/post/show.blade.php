@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                              <div class="d-flex justify-content-between" >
                        <div>View Post </div>
                          <div><a href="{{route('posts.index')}}" class="btn btn-success">Back</a></div>
                    </div>
                </div>

                <div class="card-body">

 <table class="table table-striped">
    <thead>
      <tr>
        <th width="20%">Field Name</th>
        <th width="80%"> Value</th>
     
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>Id</td>
        <td>{{$post->id}}</td>
      
      </tr>
      <tr>
        <td>Title</td>
        <td>{{$post->title}}</td>
      
      </tr>
      <tr>
        <td>Description</td>
        <td>{{$post->description}}</td>
      
      </tr>
     <tr>
        <td>Category</td>
        <td>{{$post->category->name}}</td>
      
      </tr>

      <tr>
        <td>tags</td>
        <td>
            @if(count($post->tags))
              @foreach($post->tags as $tag)
               {{$tag->name}} <br>
              @endforeach
            @endif
        </td>
      
      </tr>

      <tr>
        <td>Image</td>
        <td>
            <img width="100%" src="{{asset('post_images/'.$post->image)}}">
        </td>
      
      </tr>

      <tr>
        <td>Created At</td>
        <td>
          {{$post->created_at}}
        </td>
      
      </tr>

    </tbody>
  </table>
        
                </div>
            </div>
        </div>
    </div>
</div>
@endsection