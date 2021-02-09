<form action="{{$rename}}" class="" autocomplete="off" method="POST">
    <input type="hidden" name="path" id="renamePathInput" value="" required>
    <div class="form-group">
        <label for="renameOldNameInput">{!! $renameOldNameLabel !!}</label>
        <input type="text" class="form-control"
               name="oldName" id="renameOldNameInput"
               style="background-color: var(--c04); color: var(--c01)"
               value="" required readonly>
    </div>
    <div class="form-group">
        <label for="renameNewNameInput" style="display: block;">{!! $renameNewNameLabel !!}</label>
        <div class="d-flex">
            <input type="text" class="form-control flex-fill mr-1"
                   name="newName" id="renameNewNameInput"
                   style="background-color: var(--c04); color: var(--c01); display: inline-block; min-width: 64% !important;"
                   maxlength="64"
                   placeholder="">
            <input type="text" class="form-control flex-fill"
                   name="extension" id="renameExtInput"
                   style="background-color: var(--c04); color: var(--c01); display: inline-block; max-width: 30% !important;"
                   readonly>
        </div>
    </div>
</form>
<div class="form-group mb-3">
    <label for="">{!! $renameCleanLabel !!}
        &nbsp;&nbsp;<div class="spinner-border spinner-border-sm text-info"
                         style="display: none;" id="validationProgress"></div>
    </label>
    <input type="text" class="form-control"
           id="validatedNewName"
           style="background-color: var(--c04); color: var(--c01);"
           placeholder="{!! $renameCleanPlaceholder !!}"
           required readonly>
</div>
<script>
    $(document).ready(function () {
        newNameChangedListener('{{$clean}}', document.getElementById("renameNewNameInput"));
    });
</script>
