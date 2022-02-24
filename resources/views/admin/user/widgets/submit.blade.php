<?php
/**
 * @var \Spatie\Permission\Models\Role $role
 */
?>
<div class="card p-3">
    <div class="mb-3">
        <label class="form-label">Roles:</label>
        @foreach($roles as $role)
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="{{ $role->getQueueableId() }}" name="roles[]" id="role_{{ $role->getQueueableId() }}">
                <label class="form-check-label" for="role_{{ $role->getQueueableId() }}">{{ $role->name }}</label>
            </div>
        @endforeach
    </div>
    <div class="mb-3">
        <input class="form-check-input" type="checkbox" value="1" id="approved" name="approved">
        <label class="form-check-label" for="approved">Approved</label>
    </div>
    <button type="submit" class="btn btn-primary">{{ __('admin/article.submit.button') }}</button>
</div>
