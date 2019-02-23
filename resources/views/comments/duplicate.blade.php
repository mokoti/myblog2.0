@extends('layout')

@section('breadcrumb')
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/" class="d-inline-flex"><i class="material-icons mr-1">home</i> Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('comments.index') }}">{{ $model_title_list['comments'] }}</a></li>
        <li class="breadcrumb-item active">Duplicate</li>
      </ol>
    </nav>
@endsection

@section('content')

    <h1 class="d-flex mb-3">
        <i class="material-icons align-self-center mr-1">add_to_photos</i>
        <span class="d-inline-block">{{ $model_title_list['comments'] }} / Duplicate #{{$comment->id}}</span>
    </h1> 

    <div class="row">
      <div class="col-md-12">
        <form method="POST" action="{{ route('comments.store') }}" accept-charset="UTF-8" class="needs-validation" novalidate>
          {{ csrf_field() }}

@include('comments._form', ['mode' => 'duplicate'])

          <div class="d-flex justify-content-end">
            <a class="btn btn-secondary d-inline-flex mr-3" href="{{ route('comments.index') }}"><i class="material-icons mr-1">fast_rewind</i> Back</a>
            <button type="submit" class="btn btn-primary">Duplicate</button>
          </div>

        </form>
      </div>
    </div>

@endsection