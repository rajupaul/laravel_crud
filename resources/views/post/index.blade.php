@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between" >
                        <div>Posts</div>
                          <div><a href="{{route('posts.create')}}" class="btn btn-success">Create Post</a></div>
                    </div>
                </div>

                <div class="card-body">
                 <div class="mb-2">
                      <form class="form-inline" action="">
                      <label for="category_filter">Filter By Category &nbsp;</label>
                       <select class="form-control" id="category_filter" name="category">
                        <option value="">Select Category</option>
                       @if(count($categories))
                          @foreach($categories as $category)
                             <option value="{{$category->name}}"  {{(Request::query('category') && Request::query('category')==$category->name)?'selected':''}}  >{{$category->name}}</option>
                          @endforeach
                        @endif

               
                      </select>
                      <label for="keyword">&nbsp;&nbsp;</label>
                      <input type="text" class="form-control"  name="keyword" placeholder="Enter keyword" id="keyword">
                      <span>&nbsp;</span> 
                       <button type="button" onclick="search_post()" class="btn btn-primary" >Search</button>
                       @if(Request::query('category') || Request::query('keyword'))
                        <a class="btn btn-success" href="{{route('posts.index')}}">Clear</a>
                       @endif

                    </form>
                  </div>
                  <div class="table-responsive">
                    <table style="width: 100%;" class="table table-stripped ">
                      <thead>
                        <tr>
                          <th>ID</th>
                          <th>Title</th>
                          <th>Created By</th>
                          <th>Category</th>
                          <th>Total Comments 
                       
                            @if(Request::query('sortByComments') && Request::query('sortByComments')=='asc')
                                      <a href="javascript:sort('desc')" ><i class="fas fa-sort-down"></i></a>
                            @elseif(Request::query('sortByComments') && Request::query('sortByComments')=='desc')
                            <a href="javascript:sort('asc')" ><i class="fas fa-sort-up"></i></a>
                            @else
                                     <a href="javascript:sort('asc')" ><i class="fas fa-sort"></i></a>
                            @endif
                    
                          </th>
                          <th>Actions</th>
                        </tr>
                      </thead>
                      <tbody>
                        @if(count($posts))
                          @foreach($posts as $post)
                        <tr>
                            <td >{{$post->id}}</td>
                            <td style="width:35%">{{$post->title}}</td>
                            <td >{{$post->user->name}}</td>
                            <td >{{$post->category->name}}</td>
                            <td align="center">{{$post->comments_count}}</td>
                            <td  style="width:250px;">
                              <a  href="{{route('posts.show',$post->id)}}" class="btn btn-primary">View</a>
                              <a href="{{route('posts.edit',$post->id)}}" class="btn btn-success">Edit</a>
                              <a href="javascript:delete_post('{{route('posts.destroy',$post->id)}}')" class="btn btn-danger">Delete</a>
                            </td>
                          </tr>


                          @endforeach
                        @else

                          <tr>
                            <td colspan="6" >No posts found</td>
        
                          </tr>
                        @endif

                
                      </tbody>
                    </table>
  @if(count($posts))
   {{$posts->appends(Request::query())->links()}}
  @endif

                  </div>
                </div>
            </div>
        </div>
    </div>
</div>

<form id="post_delete_form" method="post" action="">
  @csrf
  @method('DELETE')
</form>


@endsection

@section('javascript')
<script type="text/javascript">
  var query=<?php echo json_encode((object)Request::only(['category','keyword','sortByComments'])); ?>;


  function search_post(){

    Object.assign(query,{'category': $('#category_filter').val()});
    Object.assign(query,{'keyword': $('#keyword').val()});

    window.location.href="{{route('posts.index')}}?"+$.param(query);

  }

  function sort(value){
    Object.assign(query,{'sortByComments': value});

    window.location.href="{{route('posts.index')}}?"+$.param(query);
  }

  function delete_post(url){

    swal({
      title: "Are you sure?",
      text: "Once deleted, you will not be able to recover this post!",
      icon: "warning",
      buttons: true,
      dangerMode: true,
    })
    .then((willDelete) => {
      if (willDelete) {
        $('#post_delete_form').attr('action',url);
         $('#post_delete_form').submit();
      } 
    });


  }


</script>
@endsection