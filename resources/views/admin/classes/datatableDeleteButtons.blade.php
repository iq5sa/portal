<form method="POST" action="{{route('class.destroy')}}">
    @method('DELETE')
    @csrf
    <input name="class_id" type="hidden" value="{{$class_id}}">
    <button type="submit" class="btn btn-danger btn-sm">
        حذف
    </button>
</form>
