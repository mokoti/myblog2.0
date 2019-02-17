
<div class="form-group">
  <label for="name-field">NAME</label>

  <input type="text" class="form-control
  @if($errors->any()) @if($errors->has("model.name")) is-invalid @else is-valid @endif @endif;
  " id="name-field" name="model[name]" value="@if($errors->any()){{ old('model.name') }}@else{{ $tag->name or '' }}@endif" required>

  @if($errors->has("model.name"))
    <div class="invalid-feedback">{{ $errors->first("model.name") }}</div>
  @else
    <div class="invalid-feedback">Invalid!</div>
  @endif
</div>









<div class="form-group manytomany">
    <label for="pivotstagCheckbox">POST</label>
    <div class="form-check form-check-inline flex-wrap">
        @foreach($lists['Post'] as $list_key => $list_item)
        <div class="form-group mb-1">
          <input name="pivots[post][{{ $list_key }}][id]" type="checkbox" id="pivotsPostCheckbox{{ $list_key }}" value="{{ $list_key }}" class="form-check-input manytomany-trigger
              @if($errors->has('pivots.post.'.$list_key.'.*')) is-invalid @endif
              "
              @if( $errors->any() && old('pivots.post.'.$list_key.'.id')) checked
              @elseif( !$errors->any() && isset($tag) && $tag->posts->find($list_key) ) checked
              @endif
          ><label class="form-check-label mr-2" for="pivotsPostCheckbox{{ $list_key }}">{{ $list_item }}</label>
        </div>
        @endforeach
    </div>
    @if ($errors->has('pivots.tag.*.*'))
    <div><span class="text-danger">There were some problems with your pivot input.</span></div>
    @endif

    <!-- manytomany Modal -->
    <div class="modal fade manytomany-modal needs-validation-with-save" id="postModal" tabindex="-1" role="dialog" aria-labelledby="postModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title" id="myModalLabel">Post Option</h4>
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

    @if( old('pivots.post') )
        @foreach( old('pivots.post') as $post_id => $post )
            @foreach( $post as $pivot_key => $pivot_value )
                @if( $loop->index > 0 )
                    <input type="hidden" name="pivots[post][{{ $post_id }}][{{$pivot_key}}]" value="{{$pivot_value}}" parent_name="pivots[post][{{ $post_id }}]">
                @endif
            @endforeach
        @endforeach
    @elseif( isset($tag) )
        @foreach( $tag->posts as $post )
            @foreach( $post->pivot->toArray() as $pivot_key => $pivot_value )
                @if( $loop->index > 1 && $pivot_key !== 'created_at' && $pivot_key !== 'updated_at')
                    <input type="hidden" name="pivots[post][{{ $post->id }}][{{$pivot_key}}]" value="{{$pivot_value}}" parent_name="pivots[post][{{ $post->id }}]">
                @endif
            @endforeach
        @endforeach
    @endif
    @if($errors->has('pivots.post.*.*'))
        @foreach($errors->get('pivots.post.*') as $error_key => $error_value)
                    <input type="hidden" name="errors.{{$error_key}}" value="{{$error_value[0]}}" disabled="disabled">
        @endforeach
    @endif

</div>

