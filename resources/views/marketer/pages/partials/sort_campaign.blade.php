@forelse ($data['my_camp'] as $v)    
<tr>
    <td>{{$data['sl']++}}</td>
    <td>{{$v->name}}</td>
    <td>
        @php
        
        $result = App\Traits\Date::explodeDateTime(' ',$v->created_at);
        @endphp
        {{App\Traits\Date::DbToOriginal('-',$result['date'])}} {{App\Traits\Date::twelveHrTime($result['time'])}}
    </td>
    <td>00</td>
    <td>00</td>
    <td>00</td>
    <td>
        <div class="btn-group">
            <a href="" class="btn btn-outline-secondary">Copy Link</a>
            <a href="" class="btn btn-outline-secondary">Performance</a>
            <a href="" class="btn btn-outline-secondary">Remove</a>
        </div>
    </td>
</tr>
@empty
    <tr>
        <td colspan="7" style="text-align: center;">
            <span class="text-danger">No Data Found !</span>
        </td>
    </tr>
@endforelse