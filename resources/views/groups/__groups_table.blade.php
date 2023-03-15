<table class="table text-center">
    <thead>
    <tr>
        <th>{{__('Name')}}</th>
        <th>{{__('Description')}}</th>
        <th>{{__('Actions')}}</th>
    </tr>
    </thead>
    <tbody>
    @forelse($groups as $group)
        <tr>
            <td>{{$group->name}}</td>
            <td>{{$group->description?$group->description:__("No description")}}</td>
            <td>
                <a href="{{route('groups.show',$group)}}" class="btn btn-info">{{__('View')}}</a>
                @if($index==1)
                    <a href="{{route('groups.edit',$group)}}" class="btn btn-warning">{{__('Edit')}}</a>

                @elseif($index==2)
                    <a href="{{route('groups.leave',$group)}}" class="btn btn-danger">{{__('Leave')}}</a>

                @endif
                {{--                            <a href="{{route('groups.destroy',$group)}}" class="btn btn-danger">{{__('Delete')}}</a>--}}
            </td>
        </tr>

    @empty
        <tr>
            <td colspan="3">
                @if($index==1)
                    {{__("No groups created")}}
                @elseif($index==2)
                    {{__("No shared groups")}}

                @endif
            </td>
        </tr>
    @endforelse

    </tbody>
</table>
