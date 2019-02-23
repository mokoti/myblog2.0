@extends('layout')

@section('breadcrumb')
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/" class="d-inline-flex"><i class="material-icons mr-1">home</i> Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('posts.index') }}">{{ $model_title_list['posts'] }}</a></li>
        <li class="breadcrumb-item active">#{{ $post->id }}</li>
      </ol>
    </nav>
@endsection

@section('content')

    <h1 class="d-flex mb-3">
        <i class="material-icons align-self-center mr-1">subject</i>
        <span class="d-inline-block">{{ $model_title_list['posts'] }} / Show #{{$post->id}}</span>
        <form class="ml-auto" method="POST" action="{{ route('posts.destroy', $post->id) }}" accept-charset="UTF-8" style="display: inline;" onsubmit="if(confirm('Delete? Are you sure?')) { return true } else {return false };">
            {{ method_field('DELETE') }}
            {{ csrf_field() }}
            <div class="btn-group" role="group">
                <a class="btn btn-sm btn-primary" href="{{ route('posts.duplicate', $post->id) }}" data-toggle="tooltip" data-placement="top" title="Duplicate"><i class="material-icons d-block">add_to_photos</i></a>
                <a class="btn btn-sm btn-warning" href="{{ route('posts.edit', $post->id) }}" data-toggle="tooltip" data-placement="top" title="Edit"><i class="material-icons d-block">edit</i></a>
                <button type="submit" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Delete"><i class="material-icons d-block">delete</i></button>
            </div>
        </form>
    </h1> 

    <ul class="list-group list-group-flush mt-4">
      <li class="list-group-item d-inline-flex flex-wrap"><div><strong>ID ： </strong></div><div>{{$post->id}}</div></li>

      <li class="list-group-item d-inline-flex flex-wrap"><div><strong>TITLE : </strong></div><div>{{ $post->title }}</div></li>
      <li class="list-group-item d-inline-flex flex-wrap"><div><strong>BODY : </strong></div><div>{{ $post->body }}</div></li>

      <li class="list-group-item d-inline-flex flex-wrap"><div><strong>CATEGORY : </strong></div><div>{{ $post->category->name or '' }}</div></li>

      <li class="list-group-item"><p><strong>COMMENT : </strong></p><div>
        @include('comments._table', ['comments' => $post->comments])
      </div></li>

      <li class="list-group-item d-inline-flex flex-wrap"><div><strong>TAG : </strong></div><div>
        @foreach($post->tags as $my_child)
            @if (!$loop->first) , @endif
            {{ $my_child->name }}(
              PRIORITY:{{ $my_child->pivot->priority }}
            )
        @endforeach </div></li>

    </ul>
    <div class="d-flex justify-content-end mt-3">
        <a class="btn btn-secondary d-inline-flex mr-3" href="{{ route('posts.index') }}"><i class="material-icons">fast_rewind</i> Back</a>
    </div>

@endsection