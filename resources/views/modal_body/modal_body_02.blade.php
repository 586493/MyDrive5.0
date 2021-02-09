@inject('timeData', 'App\TimeData')
@inject('userFiles', 'App\UserFiles')
<table class="userDataTable mb-0" style="width: 100%;">
    <colgroup>
        <col style="width: 57%;">
        <col style="width: 43%;">
    </colgroup>
    <tr>
        <td>{{$userDataUsername}}</td>
        <td class="secCol">
            {{$d3->getSessionUser()->username}}
        </td>
    </tr>
    <tr>
        <td>{{$userDataLastActivity}}</td>
        <td class="secCol">
            {{$timeData->timeToStr((int)$d3->getSessionUser()->last_activity_time)}}
        </td>
    </tr>
    <tr>
        <td>{{$userDataUpdatedAt}}</td>
        <td class="secCol">
            {{$timeData->timeToStr((int)$d3->getSessionUser()->updated_at)}}
        </td>
    </tr>
    <tr>
        <td>{{$userDataCreatedAt}}</td>
        <td class="secCol">
            {{$timeData->timeToStr((int)$d3->getSessionUser()->created_at)}}
        </td>
    </tr>
    <tr>
        <td>{{$userDataAuthMethod}}</td>
        <td class="secCol">
            {{$d3->getSessionAuthMethod()}}
        </td>
    </tr>
    <tr>
        <td>{{$userDataStorage}}</td>
        <td class="secCol" id="userDataStorageInfo">
            {!! $userFiles->sizeToString($storageLimit) !!}
        </td>
    </tr>
</table>
