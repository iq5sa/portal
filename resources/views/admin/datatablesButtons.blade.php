<div class="btn-group">
    <button type="button" class="btn dropdown-toggle btn-sm" data-toggle="dropdown">المزيد</button>
    <div class="dropdown-menu text-right">
        <a href="{{route('requests.show',$id)}}" class="dropdown-item">
            عرض التفاصيل
        </a>
        <a href="{{route('download.form.pdf',$id)}}" class="dropdown-item">
            تحميل الاستمارة
        </a>
        <a href="{{route('requests.download.documents',$id)}}" class="dropdown-item">
            تحميل الوثائق
        </a>
    </div>
</div>

