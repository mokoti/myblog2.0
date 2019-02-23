<div class="row">
    <div class="col-md-12">

        @if($posts->count())

            <table class="table table-sm table-striped sp-omit">
              <thead>
                <tr>
                  <th scope="col"><div class="d-flex">
                    @if( method_exists($posts, 'appends') )
                      <a href="javascript:sortByColumn('id')">ID</a>
                      @if( Request::input('q.s') == 'id_asc' )<i class="material-icons">arrow_drop_up</i>
                      @elseif( Request::input('q.s') == 'id_desc' )<i class="material-icons">arrow_drop_down</i> @endif
                    @else
                      ID
                    @endif
                  </div></th>
                  <th scope="col"><div class="d-flex">
                    @if( method_exists($posts, 'appends') )
                      <a href="javascript:sortByColumn('title')">TITLE</a>
                      @if( Request::input('q.s') == 'title_asc' )<i class="material-icons">arrow_drop_up</i>
                      @elseif( Request::input('q.s') == 'title_desc' )<i class="material-icons">arrow_drop_down</i> @endif
                    @else
                      TITLE                    @endif
                  </div></th>

                  <th scope="col">CATEGORY</th>

                  <th scope="col">COMMENT</th>

                  <th scope="col">TAG</th>

                  <th class="text-right" scope="col">OPTIONS</th>
                </tr>
              </thead>
              <tbody>
                @foreach($posts as $post)
                    <tr>
                      <td scope="row"><a href="{{ route('posts.show', $post->id) }}">{{$post->id}}</a></td>
                      <td>{{$post->title}}</td>

                      <td>@if($post->category)<a href="{{ route('categories.show', $post->category->id) }}">{{ $post->category->name }}</a>@else - @endif</td>

                      <td>
                          @foreach($post->comments as $comment)
                                        @if (!$loop->first) , @endif
                                        <a href="{{ route('comments.show', $comment->id) }}">{{ $comment->title }}</a>
                          @endforeach
                      </td>

                      <td>
                          @foreach($post->tags as $tag)
                                        @if (!$loop->first) , @endif
                                        <a href="{{ route('tags.show', $tag->id) }}">{{ $tag->name }}(
                                            PRIORITY:{{ $tag->pivot->priority }}
                                        )</a>
                          @endforeach
                      </td>

                      <td class="text-right">
                        <div class="btn-group" role="group">
                            <a class="btn btn-sm btn-primary" href="{{ route('posts.duplicate', $post->id) }}" data-toggle="tooltip" data-placement="top" title="Duplicate"><i class="material-icons d-block">add_to_photos</i></a>
                            <a class="btn btn-sm btn-warning" href="{{ route('posts.edit', $post->id) }}" data-toggle="tooltip" data-placement="top" title="Edit"><i class="material-icons d-block">edit</i></a>
                            <form method="POST" action="{{ route('posts.destroy', $post->id) }}" accept-charset="UTF-8" style="display: inline;" onsubmit="if(confirm('Delete? Are you sure?')) { return true } else {return false };">
                                {{ method_field('DELETE') }}
                                {{ csrf_field() }}
                                <button type="submit" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Delete"><i class="material-icons d-block">delete</i></button>
                            </form>
                        </div>
                      </td>
                    </tr>
                @endforeach
              </tbody>
            </table>
            @if( method_exists($posts, 'appends') )
              {!! $posts->appends(Request::except('page'))->render() !!}
            @endif
        @else
            <h3 class="text-center alert alert-info">Empty!</h3>
        @endif
    </div>
</div>