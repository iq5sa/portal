<div class="btn-group btn-group-sm">
    <a href="{{route('settings.edit',$id)}}" class="btn btn-info btn-sm">
        تعديل
    </a>
    <form method="POST" action="{{route('settings.destroy',$id)}}">
        {{ csrf_field() }}
        {{ method_field('DELETE') }}

        <input type="submit" class="btn btn-danger btn-sm" value="حذف">
    </form>

    
</div>
