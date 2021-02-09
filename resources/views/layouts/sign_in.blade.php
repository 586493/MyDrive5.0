@include('partials.top01')

<div class="d-flex justify-content-center align-items-center min-vh-100">
    {{--    <div class="container text-center text-light display-1">--}}
    {{--        I am centered vertically--}}
    {{--    </div>--}}
    <form action="{{$authentication}}" class="text-center p-3 " autocomplete="off" method="POST">
        <div class="form-group text-info mb-4">
            <span class="display-3" style="color: var(--c01);">{{$title}}</span>
        </div>
        @csrf
        <div class="form-group">
            <input type="text" name="username"
                   minlength="{{$inputMinLen}}"
                   maxlength="{{$inputMaxLen}}"
                   pattern="[{{$inputChars}}]&lcub;{{$inputMinLen}},{{$inputMaxLen}}&rcub;"
                   title="{{$inputCharsHtmlTitle}}"
                   class="form-control form-control-lg text-center w-75"
                   style="background-color: var(--c04); color: var(--c01); margin: 0 auto;" autocomplete="off"
                   placeholder="{{$login}}" id="username" required>
        </div>
        <div class="form-group">
            <input type="password" name="pswd"
                   minlength="{{$inputMinLen}}"
                   maxlength="{{$inputMaxLen}}"
                   pattern="[{{$inputChars}}]&lcub;{{$inputMinLen}},{{$inputMaxLen}}&rcub;"
                   title="{{$inputCharsHtmlTitle}}"
                   class="form-control form-control-lg text-center w-75"
                   style="background-color: var(--c04); color: var(--c01); margin: 0 auto;"
                   autocomplete="off"
                   placeholder="{{$password}}" id="pswd" required>
        </div>
        @inject('d', 'App\SessionData')
        @if ($d->has($d::$lastErrKey))
            <div class=""
                 style="position: fixed;
                 top: 1.0vh; left: 1.0vw; right: 1.0vw;
                 margin: 0 auto;">
                <div class="alert alert-danger">
                    {{ $d->get($d::$lastErrKey) }}
                </div>
            </div>
        @endif
        @php
            use App\SessionData;
            if (SessionData::has(SessionData::$lastErrKey)) {
                SessionData::remove(SessionData::$lastErrKey);
            }
        @endphp
        <button type="submit"
                class="form-control form-control-lg btn-lg btn-primary w-75 text-lowercase mt-4"
                style="background-color: var(--c05); color: var(--c02); margin: 0 auto;">
            {{$logInBtn}}
        </button>
    </form>
</div>

<script>
    /* CSRF_TOKEN - expired token */
    window.setInterval(function () {
        $.ajax({
            url: '{{$checkCSRF}}',
            type: "POST",
            data: {
                tokenToCmp: '{{ csrf_token() }}',
                _token: '{{ csrf_token() }}',
            }
        }).done(function (response) {
            console.log(response.refresh);
            if (response.refresh) {
                window.location.reload();
            }
        });
    }, 31000);
</script>

@include('partials.bottom01')
