@can('user manage permission')
    <script id="permission-row" type="text/x-handlebars-template">
        <tr>
            <td>
                <select class="form-control input-sm" name="tag_permissions[@{{index}}][tag_id]" required>
                    <option value="">Select {{ucfirst(config('settings.tags_label_singular'))}}</option>
                    @foreach($tags as $tag)
                        <option value="{{$tag->id}}">{{$tag->name}}</option>
                    @endforeach
                </select>
            </td>
            @foreach (config('constants.TAG_LEVEL_PERMISSIONS')  as $perm)
                <td><label>
                        <input name="tag_permissions[@{{index}}][{{$perm}}]" type="checkbox" class="iCheck-helper"
                               value="1">
                    </label></td>
            @endforeach
            <td>
                <button onclick="removeRow(this)" class="btn btn-danger btn-xs" title="Remove row"><i
                        class="fa fa-trash"></i></button>
            </td>
        </tr>
    </script>
    <script>
        @php
            $groupTagPerm = groupTagsPermissions(optional($user ?? null)->getAllPermissions());
        @endphp
        let rowIndex = 0;

        function addRow() {
            var template = Handlebars.compile($("#permission-row").html());
            var html = template({index: rowIndex});
            $(html).appendTo("#permission-body");
            registerIcheck();
            rowIndex++;
        }
        function removeRow(elem) {
            $(elem).parents("tr").remove();
        }
        window.onload = function () {
            @foreach($groupTagPerm as $key=>$value)
                addRow();
                $("#permission-body>tr:last-child").find("select[name^='tag_permissions']").val('{{$value['tag_id']}}');
                @foreach($value['permissions'] as $perm)
                $("#permission-body>tr:last-child").find("input[name$='[{{$perm}}]']").attr('checked','checked');
                @endforeach
            @endforeach
            registerIcheck();
        }
    </script>
@endcan
<div class="box box-primary">
    <div class="box-header no-border">
        <h3 class="box-title">User Detail</h3>

        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
        </div>
    </div>
    <div class="box-body">
        <div class="row">
            <!-- Name Field -->
            <div class="form-group col-sm-6 {{ $errors->has('name') ? 'has-error' :'' }}">
                <label class="control-label">Name</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', optional($user ?? null)->name) }}" required>
                {!! $errors->first('name','<span class="help-block">:message</span>') !!}
            </div>

            <!-- Email Field -->
            <div class="form-group col-sm-6 {{ $errors->has('email') ? 'has-error' :'' }}">
                <label for="email">Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', optional($user ?? null)->email) }}" required>
                {!! $errors->first('email','<span class="help-block">:message</span>') !!}
            </div>

            <!-- Username Field -->
            <div class="form-group col-sm-6 {{ $errors->has('username') ? 'has-error' :'' }}">
                <label for="username">Username</label>
                <input type="text" name="username" class="form-control" value="{{ old('username', optional($user ?? null)->username) }}" required>
                {!! $errors->first('username','<span class="help-block">:message</span>') !!}
            </div>


            <!-- Address Field -->
            <div class="form-group col-sm-6 {{ $errors->has('address') ? 'has-error' :'' }}">
                <label for="address">Address</label>
                <input type="text" name="address" class="form-control" value="{{ old('address', optional($user ?? null)->address) }}">
                {!! $errors->first('address','<span class="help-block">:message</span>') !!}
            </div>

            <!-- Password Field -->
            <div class="form-group col-sm-6 {{ $errors->has('password') ? 'has-error' :'' }}">
                <label>
                    Password
                    <span class="text-muted">(Leave blank if you don't want to change)</span>
                </label>
                <!-- Password Field --> 
                {{-- If user is being created, show password field --}}
                {{-- If user is being edited, show password field with a note --}}
                <input type="password" name="password" class="form-control" value="{{ old('password') }}" {{ isset($user) ? '' : 'required' }}>
                {!! $errors->first('password','<span class="help-block">:message</span>') !!}
            </div>

            {{--Status Filed--}}
            <div class="form-group col-sm-6 {{ $errors->has('status') ? 'has-error' :'' }}">
            {{-- Status Field --}}
            <label for="">Status</label>
            <select name="status" id="status">
                <option value="">{{ __('Select Status') }}</option>
                <option value="{{ config('constants.STATUS.ACTIVE') }}" {{ old('status', optional($user ?? null)->status) == config('constants.STATUS.ACTIVE') ? 'selected' : '' }}>
                    {{ config('constants.STATUS.ACTIVE') }}
                </option>
                <option value="{{ config('constants.STATUS.BLOCK') }}" {{ old('status', optional($user ?? null)->status) == config('constants.STATUS.BLOCK') ? 'selected' : '' }}>
                    {{ config('constants.STATUS.BLOCK') }}
                </option>
            </select>
               {!! $errors->first('status','<span class="help-block">:message</span>') !!}
            </div>

            <!-- Description Field -->
            <div class="form-group col-sm-12 col-lg-12 {{ $errors->has('description') ? 'has-error' :'' }}">
                <label for="description">Description</label>
                {{-- Use a WYSIWYG editor for the description field --}}
                {{-- This allows for rich text formatting --}}
                {{-- Use the 'b-wysihtml5-editor' class for the WYSIWYG editor --}}
                {{-- The 'b-wysihtml5-editor' class is used to initialize the WYSIWYG editor --}}
                <textarea name="description" id="description" class="form-control b-wysihtml5-editor"></textarea>
                {!! $errors->first('description','<span class="help-block">:message</span>') !!}
            </div>
        </div>
    </div>
</div>
@can('user manage permission')
    <div class="box box-primary">
        <div class="box-header no-border">
            <h3 class="box-title">Global Permissions</h3>

            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="box-body">
            <div class="row">
                <div class="col-sm-12">
                    <div class="row">
                        <div class="col-sm-3">
                            <label class="control-label">User</label><br>
                            @foreach(config('constants.GLOBAL_PERMISSIONS.USERS') as $permission_name=>$permission_label)
                                <div class="form-group">
                                    <label>
                                        <input name="global_permissions[]" type="checkbox" class="iCheck-helper"
                                               value="{{$permission_name}}" {{optional($user ?? null)->can($permission_name)?'checked':''}}>
                                        &nbsp;{{ucfirst($permission_label)}} Users
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        <div class="col-sm-3">
                            <label class="control-label">{{ucfirst(config('settings.tags_label_plural'))}}</label><br>
                            @foreach(config('constants.GLOBAL_PERMISSIONS.TAGS') as $permission_name=>$permission_label)
                                <div class="form-group">
                                    <label>
                                        <input name="global_permissions[]" type="checkbox" class="iCheck-helper"
                                               value="{{$permission_name}}" {{optional($user ?? null)->can($permission_name)?'checked':''}}>
                                        &nbsp;{{ucfirst($permission_label)}} {{ucfirst(config('settings.tags_label_plural'))}}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        <div class="col-sm-3">
                            <label class="control-label">{{ucfirst(config('settings.prestations_label_plural'))}}</label><br>
                            @foreach(config('constants.GLOBAL_PERMISSIONS.PRESTATIONS') as $permission_name=>$permission_label)
                                <div class="form-group">
                                    <label>
                                        <input name="global_permissions[]" type="checkbox" class="iCheck-helper"
                                               value="{{$permission_name}}" {{optional($user ?? null)->can($permission_name)?'checked':''}}>
                                        &nbsp;{{ucfirst($permission_label)}} {{ucfirst(config('settings.prestations_label_plural'))}}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        <div class="col-sm-3">
                            <label
                                class="control-label">{{ucfirst(config('settings.document_label_plural'))}}</label><br>
                            @foreach(config('constants.GLOBAL_PERMISSIONS.DOCUMENTS') as $permission_name=>$permission_label)
                                <div class="form-group">
                                    <label>
                                        <input name="global_permissions[]" type="checkbox" class="iCheck-helper"
                                               value="{{$permission_name}}" {{optional($user ?? null)->can($permission_name)?'checked':''}}>
                                        &nbsp;{{ucfirst($permission_label)}} {{ucfirst(config('settings.document_label_plural'))}}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="box box-primary">
        <div class="box-header no-border">
            <h3 class="box-title">{{ucfirst(config('settings.tags_label_plural'))}} Wise Permissions</h3>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="box-body">
            <div class="row">
                <div class="col-sm-12">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Select {{ucfirst(config('settings.tags_label_singular'))}}</th>
                            @foreach (config('constants.TAG_LEVEL_PERMISSIONS')  as $perm)
                                <th>{{ucfirst($perm)}}</th>
                            @endforeach
                        </tr>
                        </thead>
                        <tbody id="permission-body">

                        </tbody>
                        <tfoot>
                        <tr>
                            <td colspan="6">
                                <button type="button" onclick="addRow()" class="btn btn-info btn-xs">Add
                                    new {{config('settings.tags_label_singular')}}</button>
                            </td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endcan
<!-- Submit Field -->
<div class="form-group">
    <button type="submit" class="btn btn-primary">Save</button>
    <a href="{!! route('users.index') !!}" class="btn btn-default">Cancel</a>
</div>
