@extends('backend.admin.layouts.app')
@section('title', 'Roles')

@section('content')

<section class="content">
    <div class="row">
        <div class="card card-body bg-gray-light">
            <div class="card-header">
                <h3 class="card-title">Add New Role</h3>
            </div>
            <form role="form" method="POST" action="{{ route('admin.roles.store') }}">
                @csrf
                <div class="card-body">
                    <div>
                        @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Role Name</label>
                                <input type="text" required class="form-control" name="name" id="name" placeholder="Enter Role Name" value="{{ old('name') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="description">Description</label>
                                <input type="text" class="form-control" name="description" id="description" placeholder="Enter Description" value="{{ old('description') }}">
                            </div>
                        </div>
                    </div>

                    <div class="form-check mb-3">
                        <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" @if (old('is_active', 1) == 1) checked @endif>
                        <label class="form-check-label" for="is_active">Is Active</label>
                    </div>

                    <hr>
                    <h5>Permissions</h5>
                    <div class="row">
                        @foreach ($permissions as $module => $modulePermissions)
                            <div class="col-md-4">
                                <div class="card card-outline card-primary">
                                    <div class="card-header">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input check-module" id="module_{{ $module }}" data-module="{{ $module }}">
                                            <label class="form-check-label font-weight-bold" for="module_{{ $module }}">{{ config("permissions.modules.$module", $module) }}</label>
                                        </div>
                                    </div>
                                    <div class="card-body py-2">
                                        @foreach ($modulePermissions as $permission)
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input perm-{{ $module }}" data-module="{{ $module }}" id="perm_{{ $permission->id }}" name="permissions[]" value="{{ $permission->id }}" @if (in_array($permission->id, old('permissions', []))) checked @endif>
                                                <label class="form-check-label" for="perm_{{ $permission->id }}">{{ config("permissions.actions.$permission->action", $permission->action) }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
                    <a href="{{ route('admin.roles.index') }}" class="btn btn-default">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</section>

@endsection

@section('script')
    <script type="text/javascript">
        $(document).on('change', '.check-module', function() {
            var module = $(this).data('module');
            $('.perm-' + module).prop('checked', $(this).is(':checked'));
        });
        $(document).on('change', '.form-check-input[name="permissions[]"]', function() {
            if (!$(this).is(':checked')) {
                $('.check-module[data-module="' + $(this).data('module') + '"]').prop('checked', false);
            }
        });
    </script>
@endsection