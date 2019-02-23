
<div class="form-group">
  <label for="priority-field">PRIORITY</label>

  <input type="text" class="form-control
  @if($errors->any()) @if($errors->has("model.priority")) is-invalid @else is-valid @endif @endif;
  " id="priority-field" name="model[priority]" value="@if($errors->any()){{ old('model.priority') }}@else{{ $postTag->priority or '' }}@endif" >

  @if($errors->has("model.priority"))
    <div class="invalid-feedback">{{ $errors->first("model.priority") }}</div>
  @else
    <div class="invalid-feedback">Invalid!</div>
  @endif
</div>








