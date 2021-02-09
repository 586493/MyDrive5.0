@include('partials.top01')

@include('partials.navbar_bottom')

@inject('d2', 'App\SessionData')

<div>
    @include('partials.files')
</div>

<div id="{{$modalDivID}}">
    @include('partials.modal')
</div>

{{ Form::hidden($timeHiddenID, $d2->getSessionLogoutTime(), array('id' => $timeHiddenID)) }}

<script src="{{ asset('res/js/session.js') }}"></script>
<script src="{{ asset('res/js/modal.js') }}"></script>
<script src="{{ asset('res/js/upload.js') }}"></script>
<script src="{{ asset('res/js/files.js') }}"></script>
<script src="{{ asset('res/js/filesActions.js') }}"></script>

<script>
    window.onload = onLoadFunction("{{$timeHiddenID}}", "{{$timerID}}");
</script>

@include('partials.bottom01')
