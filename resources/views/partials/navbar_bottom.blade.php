@inject('d1', 'App\SessionData')

<nav class="navbar fixed-bottom flex-nowrap d-flex justify-content-between nav01">

    <div class="navBtn01 flex-fill p-1 mx-1" onclick="showModal(2)">
        @include('svg.svg_cloud_upload_01')
    </div>

    <div class="navBtn01 flex-fill p-1 mx-1" onclick="showModal(1)">
        @include('svg.svg_person_outline_01')
    </div>

    <div class="navBtn02 flex-fill mx-1">
        <h1><small id="{{$timerID}}">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</small></h1>
    </div>

    <div class="navBtn01 flex-fill p-1 mx-1" onclick="showModal(0)">
        @include('svg.svg_more_time_01')
    </div>

    <div class="navBtn01 flex-fill p-1 mx-1" onclick="logOut('{{$logout}}')">
        @include('svg.svg_exit_to_app_01')
    </div>

</nav>
