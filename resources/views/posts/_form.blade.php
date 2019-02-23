
<div class="form-group">
  <label for="title-field">TITLE</label>

  <input type="text" class="form-control
  @if($errors->any()) @if($errors->has("model.title")) is-invalid @else is-valid @endif @endif;
  " id="title-field" name="model[title]" value="@if($errors->any()){{ old('model.title') }}@else{{ $post->title or '' }}@endif" required>

  @if($errors->has("model.title"))
    <div class="invalid-feedback">{{ $errors->first("model.title") }}</div>
  @else
    <div class="invalid-feedback">Invalid!</div>
  @endif
</div>


<div class="form-group">
  <label for="body-field">BODY</label>

  <textarea class="form-control
  @if($errors->any()) @if($errors->has("model.body")) is-invalid @else is-valid @endif @endif;
  " id="body-field" name="model[body]" rows="3" >@if($errors->any()){{ old('model.body') }}@else{{ $post->body or '' }}@endif</textarea>

  @if($errors->has("model.body"))
    <div class="invalid-feedback">{{ $errors->first("model.body") }}</div>
  @else
    <div class="invalid-feedback">Invalid!</div>
  @endif
</div>






<div class="form-group">
  <label for="category_id-field">CATEGORY</label>

  <select class="form-control
  @if($errors->any()) @if($errors->has("model.category_id")) is-invalid @else is-valid @endif @endif
  " id="category_id-field" name="model[category_id]">
    <option value=""></option>
  @foreach( $lists["Category"] as $list_key => $list_item )
    <option value="{{ $list_key }}"
      @if($errors->any())
        @if( old('category_id') == $list_key ) selected='selected' @endif
      @else
        @if( isset($post) && $post->category_id==$list_key )  selected='selected' @endif
      @endif
   >{{ $list_item }}</option>
  @endforeach
  </select>
  @if($errors->has("model.category_id"))
    <div class="invalid-feedback">{{ $errors->first("model.category_id") }}</div>
  @else
    <div class="invalid-feedback">Invalid!</div>
  @endif
</div>





<div class="form-group manytomany">
    <label for="pivotspostCheckbox">TAG</label>
    <div class="form-check form-check-inline flex-wrap">
        @foreach($lists['Tag'] as $list_key => $list_item)
        <div class="form-group mb-1">
          <input name="pivots[tag][{{ $list_key }}][id]" type="checkbox" id="pivotsTagCheckbox{{ $list_key }}" value="{{ $list_key }}" class="form-check-input manytomany-trigger
              @if($errors->has('pivots.tag.'.$list_key.'.*')) is-invalid @endif
              "
              @if( $errors->any() && old('pivots.tag.'.$list_key.'.id')) checked
              @elseif( !$errors->any() && isset($post) && $post->tags->find($list_key) ) checked
              @endif
          ><label class="form-check-label mr-2" for="pivotsTagCheckbox{{ $list_key }}">{{ $list_item }}</label>
        </div>
        @endforeach
    </div>
    @if ($errors->has('pivots.post.*.*'))
    <div><span class="text-danger">There were some problems with your pivot input.</span></div>
    @endif

    <!-- manytomany Modal -->
    <div class="modal fade manytomany-modal needs-validation-with-save" id="tagModal" tabindex="-1" role="dialog" aria-labelledby="tagModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title" id="myModalLabel">Tag Option</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          </div>
          <div class="modal-body">
            <div class="form-group">
                <label for="priority-field">PRIORITY</label>
                <input id="priority-field" type="text" class="form-control manytomany-pivot-input" name="pivots-option[priority]">
                <span class="invalid-feedback"></span>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-primary save">Save changes</button>
          </div>
        </div>
      </div>
    </div>

    @if( old('pivots.tag') )
        @foreach( old('pivots.tag') as $tag_id => $tag )
            @foreach( $tag as $pivot_key => $pivot_value )
                @if( $loop->index > 0 )
                    <input type="hidden" name="pivots[tag][{{ $tag_id }}][{{$pivot_key}}]" value="{{$pivot_value}}" parent_name="pivots[tag][{{ $tag_id }}]">
                @endif
            @endforeach
        @endforeach
    @elseif( isset($post) )
        @foreach( $post->tags as $tag )
            @foreach( $tag->pivot->toArray() as $pivot_key => $pivot_value )
                @if( $loop->index > 1 && $pivot_key !== 'created_at' && $pivot_key !== 'updated_at')
                    <input type="hidden" name="pivots[tag][{{ $tag->id }}][{{$pivot_key}}]" value="{{$pivot_value}}" parent_name="pivots[tag][{{ $tag->id }}]">
                @endif
            @endforeach
        @endforeach
    @endif
    @if($errors->has('pivots.tag.*.*'))
        @foreach($errors->get('pivots.tag.*') as $error_key => $error_value)
                    <input type="hidden" name="errors.{{$error_key}}" value="{{$error_value[0]}}" disabled="disabled">
        @endforeach
    @endif

</div>

