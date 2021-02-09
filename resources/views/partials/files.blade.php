@inject('d4', 'App\SessionData')
@inject('uf', 'App\UserFiles')

<div class=""
     id="{{$fileActionID}}"
     style="position: fixed;
                 top: 1.0vh; left: 1.0vw; right: 1.0vw;
                 margin: 0 auto;
                 display: none;
">
    <div class="alert alert-danger" id="{{$fileActionErrID}}"
         style="text-align: center; display: none;">
        err
    </div>
    <div class="alert alert-success" id="{{$fileActionOkID}}"
         style="text-align: center; display: none;">
        ok
    </div>
</div>

<input type='hidden' id='{!!$filesContentHashID!!}' value=''>
<div class="m-3">
    <div class="d-flex flex-wrap" style="color: var(--c01);" id="myFiles">

    </div>
</div>
<br><br><br><br>
<br><br><br><br>
<br><br><br><br>
<br><br><br><br>
<br><br><br><br>
