@inject('ext', 'App\Extensions')
<form method="post" enctype="multipart/form-data" id="uploadForm">

    <input form="uploadForm" type="file" name="fileToUpload"
           id="fileToUpload" onchange='fileSelected({{$uploadLimit}})'
           style='opacity: 0; position: absolute; z-index: -1; width: 0.1px; height: 0.1px;'
           placeholder="{{$uploadChoose}}" {!!$ext->getHtmlAccept()!!} required/>

    <div id="chooseFileToUpload">
        <label for="fileToUpload" class="btn modalFooterBtn" style="width: 44%; display: inline-block;"
               id="chooseFileToUpload2">
            <div onclick="" style="text-overflow: ellipsis; overflow: hidden; white-space: nowrap;"
                 id="chooseFileToUpload3">
                {{$uploadChoose}}
            </div>
        </label>
    </div>
    <div>
        <input type="text" class="form-control modalFooterBtn"
               style="width: 62%; display: inline-block; background-color: var(--c03) !important;
                border-color: var(--c04) !important;"
               id="fileName" value="" placeholder="{{$uploadChosenName}}" readonly>

        <input type="text" class="form-control modalFooterBtn"
               style="width: 34%; display: inline-block; background-color: var(--c03) !important;
               border-color: var(--c04) !important; text-align: center;"
               id="fileSize" value="" placeholder="{{$uploadChosenSize}}" readonly>
    </div>
    <div>
        <div class="mt-2" style="width: 97%;">
            <div class="progress"
                 style="background-color: var(--c03); height:21px; border: 1px solid var(--c06) !important;">
                <div class="progress-bar bg-success" style="width: 0; height:21px;" id="progressBar"></div>
            </div>
        </div>
    </div>
    <div class="alert alert-success mb-0 mt-2" style="width: 97%; display: none;" id="uploadSuccess">
        Success
    </div>
    <div class="alert alert-danger mb-0 mt-2" style="width: 97%; display: none;" id="uploadError">
        Error
    </div>
    <div class="mb-0 mt-2">
        {!! $ext->getAllHtmlIcons() !!}
    </div>

</form>

