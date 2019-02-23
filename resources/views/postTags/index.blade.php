@extends('layout')

@section('breadcrumb')
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/" class="d-inline-flex"><i class="material-icons mr-1">home</i> Home</a></li>

        @if ( $query_params = Request::input('q') )
        
            <li class="breadcrumb-item active"><a href="{{ route('postTags.index') }}">{{ $model_title_list['postTags'] }}</a></li>
            <li class="breadcrumb-item active">condition(  
            @foreach( $query_params as $key => $value )
                @if (!$loop->first) / @endif {{ $key }} : {{ $value }}
            @endforeach
            )</li>
        @else
            <li class="breadcrumb-item active">{{ $model_title_list['postTags'] }}</li>
        @endif

      </ol>
    </nav>
@endsection

@section('content')

    <h1 class="d-flex mb-3">
        <i class="material-icons align-self-center mr-1">view_headline</i>
        <span class="d-inline-block">{{ $model_title_list['postTags'] }}</span>
        <a class="btn btn-success d-inline-flex ml-auto" href="{{ route('postTags.create') }}"><i class="material-icons align-self-center mr-1">add_circle</i><span class="align-self-center">Create</span></a>
    </h1> 

    <p><button class="btn btn-primary btn-sm" type="button" data-toggle="collapse" data-target="#search-area" aria-expanded="false" aria-controls="search-area">Search</button></p>
    <div class="collapse mb-4" id="search-area">
      <div class="card card-body">

        <form id="search" action="{{ route('postTags.index') }}" accept-charset="UTF-8" method="get">
          <input type="hidden" name="q[s]" value="{{ @(Request::input('q')['s']) ?: '' }}" />
          <div class="form-group row">
            <label for="q_id_eq" class="col-sm-2 col-form-label col-form-label-sm">ID</label>
            <div class="col-sm-10">
              <input class="form-control form-control-sm" id="q_id_eq" name="q[id_eq]" type="search" value="{{ @(Request::input('q.id_eq')) }}">
            </div>
          </div>

          <div class="form-group row">
            <label for="q_priority_cont" class="col-sm-2 col-form-label col-form-label-sm">PRIORITY</label>
            <div class="col-sm-10">
              <input class="form-control form-control-sm" id="q_priority_cont" name="q[priority_cont]" type="search" value="{{ @(Request::input('q.priority_cont')) }}">
            </div>
          </div>

          <div class="form-group row mb-0">
              <div class="col-sm-10 offset-sm-2">
                  <input type="submit" name="commit" value="Search" class="btn btn-default btn-sm" />
              </div>
          </div>
        </form>

      </div>
    </div>

    @include('postTags._table')

@endsection