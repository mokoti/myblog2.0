
<div class="form-group">
  <label for="title-field">TITLE</label>

  <input type="text" class="form-control
  @if($errors->any()) @if($errors->has("model.title")) is-invalid @else is-valid @endif @endif;
  " id="title-field" name="model[title]" value="@if($errors->any()){{ old('model.title') }}@else{{ $comment->title or '' }}@endif" required>

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
  " id="body-field" name="model[body]" rows="3" >@if($errors->any()){{ old('model.body') }}@else{{ $comment->body or '' }}@endif</textarea>

  @if($errors->has("model.body"))
    <div class="invalid-feedback">{{ $errors->first("model.body") }}</div>
  @else
    <div class="invalid-feedback">Invalid!</div>
  @endif
</div>






<div class="form-group">
  <label for="post_id-field">POST</label>

  <select class="form-control
  @if($errors->any()) @if($errors->has("model.post_id")) is-invalid @else is-valid @endif @endif
  " id="post_id-field" name="model[post_id]">
    <option value=""></option>
  @foreach( $lists["Post"] as $list_key => $list_item )
    <option value="{{ $list_key }}"
      @if($errors->any())
        @if( old('post_id') == $list_key ) selected='selected' @endif
      @else
        @if( isset($comment) && $comment->post_id==$list_key )  selected='selected' @endif
      @endif
   >{{ $list_item }}</option>
  @endforeach
  </select>
  @if($errors->has("model.post_id"))
    <div class="invalid-feedback">{{ $errors->first("model.post_id") }}</div>
  @else
    <div class="invalid-feedback">Invalid!</div>
  @endif
</div>




