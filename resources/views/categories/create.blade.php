@extends('layout')

@section('breadcrumb')
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/" class="d-inline-flex"><i class="material-icons mr-1">home</i> Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('categories.index') }}">{{ $model_title_list['categories'] }}</a></li>
        <li class="breadcrumb-item active">Create</li>
      </ol>
    </nav>
@endsection

@section('content')

    <h1 class="d-flex mb-3">
        <i class="material-icons align-self-center mr-1">add_circle</i>
        <span class="d-inline-block">{{ $model_title_list['categories'] }} / Create</span>
    </h1> 

    <div class="row">
      <div class="col-md-12">
        <form method="POST" action="{{ route('categories.store') }}" accept-charset="UTF-8" class="needs-validation" novalidate>
          {{ csrf_field() }}

@include('categories._form', ['mode' => 'create'])

          <div class="d-flex justify-content-end">
            <a class="btn btn-secondary d-inline-flex mr-3" href="{{ route('categories.index') }}"><i class="material-icons mr-1">fast_rewind</i> Back</a>
            <button type="submit" class="btn btn-primary">Create</button>
          </div>

        </form>
      </div>
    </div>

@endsection