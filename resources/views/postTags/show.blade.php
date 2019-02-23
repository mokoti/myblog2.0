@extends('layout')

@section('breadcrumb')
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/" class="d-inline-flex"><i class="material-icons mr-1">home</i> Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('postTags.index') }}">{{ $model_title_list['postTags'] }}</a></li>
        <li class="breadcrumb-item active">#{{ $postTag->id }}</li>
      </ol>
    </nav>
@endsection

@section('content')

    <h1 class="d-flex mb-3">
        <i class="material-icons align-self-center mr-1">subject</i>
        <span class="d-inline-block">{{ $model_title_list['postTags'] }} / Show #{{$postTag->id}}</span>
        <form class="ml-auto" method="POST" action="{{ route('postTags.destroy', $postTag->id) }}" accept-charset="UTF-8" style="display: inline;" onsubmit="if(confirm('Delete? Are you sure?')) { return true } else {return false };">
            {{ method_field('DELETE') }}
            {{ csrf_field() }}
            <div class="btn-group" role="group">
                <a class="btn btn-sm btn-primary" href="{{ route('postTags.duplicate', $postTag->id) }}" data-toggle="tooltip" data-placement="top" title="Duplicate"><i class="material-icons d-block">add_to_photos</i></a>
                <a class="btn btn-sm btn-warning" href="{{ route('postTags.edit', $postTag->id) }}" data-toggle="tooltip" data-placement="top" title="Edit"><i class="material-icons d-block">edit</i></a>
                <button type="submit" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Delete"><i class="material-icons d-block">delete</i></button>
            </div>
        </form>
    </h1> 

    <ul class="list-group list-group-flush mt-4">
      <li class="list-group-item d-inline-flex flex-wrap"><div><strong>ID ： </strong></div><div>{{$postTag->id}}</div></li>

      <li class="list-group-item d-inline-flex flex-wrap"><div><strong>PRIORITY : </strong></div><div>{{ $postTag->priority }}</div></li>




    </ul>
    <div class="d-flex justify-content-end mt-3">
        <a class="btn btn-secondary d-inline-flex mr-3" href="{{ route('postTags.index') }}"><i class="material-icons">fast_rewind</i> Back</a>
    </div>

@endsection