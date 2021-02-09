<div class="form-group">
    <input type="text" class="form-control" id="prolongUsername"
           style="background-color: var(--c04); color: var(--c01)"
           value="{{$d3->getSessionUser()->username}}" readonly>
</div>
<div class="form-group mb-0">
    <input type="password" class="form-control" id="prolongPswd"
           style="background-color: var(--c04); color: var(--c01)"
           placeholder="{{$password}}" required>
</div>
