<aside class="app-sidebar">
    <div class="app-sidebar__user">
        <i class="fa fa-user-circle" style="font-size: 25px;margin-left: 10px;"></i>
        <div>
            <p class="app-sidebar__user-name">{{auth()->user()->name}}</p>
        </div>
    </div>
    <ul class="app-menu">
        <li><a class="app-menu__item" href="{{route('home')}}"><i class="app-menu__icon fa fa-dashboard"></i><span
                    class="app-menu__label">اللوحة الرئيسية</span></a></li>
        <li class="treeview">
            <a class="app-menu__item" href="#" data-toggle="treeview">
                <i class="app-menu__icon fa fa-wpforms"></i><span
                    class="app-menu__label">الادارة الاكاديمية</span><i
                    class="treeview-indicator fa fa-angle-right"></i>
            </a>
            <ul class="treeview-menu">
                @can('ادارة السنوات الاكاديمية')
                    <li><a class="treeview-item" href="{{route('class.index')}}"
                           rel="noopener"><i class="icon fa fa-circle-o"></i>أدارة الكورسات</a></li>
                @endcan

            </ul>
        </li>
        <li class="treeview">
            <a class="app-menu__item" href="#" data-toggle="treeview"><i
                    class="app-menu__icon fa fa-graduation-cap"></i><span
                    class="app-menu__label">أدارة الطلبة</span><i
                    class="treeview-indicator fa fa-angle-right"></i>
            </a>
            <ul class="treeview-menu">
                @can('أضافة قيد طالب')
                    <li><a class="treeview-item" href="{{route('students.create')}}"
                           rel="noopener"><i class="icon fa fa-circle-o"></i>أضافة قيد طالب</a></li>
                @endcan
                @can('عرض جميع الطلبة')
                    <li><a class="treeview-item" href="{{route('students.index')}}"
                           rel="noopener"><i class="icon fa fa-circle-o"></i>جميع الطلبة</a></li>
                @endcan
                @can('تعديل ترحيل الطلبة')
                    <li><a class="treeview-item" href="{{route('tarheel.index')}}"
                           rel="noopener"><i class="icon fa fa-circle-o"></i>ترحيل</a></li>
                @endcan
                @can('أدارة الاوامر الادارية')
                    <li><a class="treeview-item" href="{{route('orders.index')}}"
                           rel="noopener"><i class="icon fa fa-circle-o"></i>الاوامر الادارية</a></li>
                @endcan
                @can('تعديل معالجة حالات الطلبة')
                    <li><a class="treeview-item" href="{{route('cases.index')}}"
                           rel="noopener"><i class="icon fa fa-circle-o"></i>معالجة حالات الطلبة</a></li>
                @endcan
            </ul>
        </li>
        <li class="treeview">
            <a class="app-menu__item" href="#" data-toggle="treeview"><i
                    class="app-menu__icon fa fa-usd"></i><span class="app-menu__label">المالية</span><i
                    class="treeview-indicator fa fa-angle-right"></i>
            </a>
            <ul class="treeview-menu">
                @can('عرض جميع الدفوعات')
                    <li><a class="treeview-item" href="{{route('fees.index')}}"
                           rel="noopener"><i class="icon fa fa-circle-o"></i>ادخال الدفوعات</a></li>
                @endcan
                @can('البحث عن الوصولات')
                    <li><a class="treeview-item" href="{{route('payments.search.index')}}"
                           rel="noopener"><i class="icon fa fa-circle-o"></i>البحث عن الوصولات</a></li>
                @endcan
                @can('الكشف حسب التأريخ')
                    <li><a class="treeview-item" href="{{route('payments.search_between_dates.index')}}"
                           rel="noopener"><i class="icon fa fa-circle-o"></i>الكشف حسب التأريخ</a></li>
                @endcan
                @can('تخفيض الاقساط')
                    <li><a class="treeview-item" href="{{route('discount.index')}}"
                           rel="noopener"><i class="icon fa fa-circle-o"></i>تخفيض الاقساط</a></li>
                @endcan
                @can('تقرير الدفوعات')
                    <li><a class="treeview-item" href="{{route('payments.report.paid.index')}}"
                           rel="noopener"><i class="icon fa fa-circle-o"></i>تقرير الدفوعات</a></li>
                @endcan
                @can('تقرير تخفيض الاجور')
                    <li><a class="treeview-item" href="{{route('payments.report.discount.index')}}"
                           rel="noopener"><i class="icon fa fa-circle-o"></i>تقرير تخفيض الاجور</a></li>
                @endcan

                    <!-- new routes -->
                @can('تقرير تخفيض الاجور')
                    <li><a class="treeview-item" href="{{route('receipt.print')}}" target="_blank"
                           rel="noopener"><i class="icon fa fa-circle-o"></i>اضافة وصل للطلاب الجدد</a></li>
                @endcan

                    @can('تقرير تخفيض الاجور')
                        <li><a class="treeview-item" href="{{route('receipt.print')}}" target="_blank"
                               rel="noopener"><i class="icon fa fa-circle-o"></i>عرض الوصولات الجدد</a></li>
                    @endcan

            </ul>
        </li>
        @can('تحميل تقارير التسجيل')
            <li class="treeview">
                <a class="app-menu__item" href="#" data-toggle="treeview"><i
                        class="app-menu__icon fa fa-file-text-o"></i><span class="app-menu__label">التقارير</span><i
                        class="treeview-indicator fa fa-angle-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a class="treeview-item" href="{{route('report.iraqi.enrolled')}}"
                           rel="noopener"><i class="icon fa fa-circle-o"></i>مقبولين عراقيين</a></li>
                    <li><a class="treeview-item" href="{{route('report.iraqi.by_stages')}}"
                           rel="noopener"><i class="icon fa fa-circle-o"></i>تقرير حسب المراحل الدراسية</a></li>
                    <li><a class="treeview-item" href="{{route('report.students.by_town')}}"
                           rel="noopener"><i class="icon fa fa-circle-o"></i>تقرير حسب محافظة السكن</a></li>
                <!--  <li><a class="treeview-item" href="{{route('report.students.students_by_date_of_birth')}}"
                                               rel="noopener"><i class="icon fa fa-circle-o"></i>أحصائية اعمار الطلبة</a></li>-->
                    {{--<li><a class="treeview-item" href="{{route('report.students.failed')}}"
                           rel="noopener"><i class="icon fa fa-circle-o"></i>أحصائية الطلبة الراسبين</a></li>--}}
                    <li><a class="treeview-item" href="{{route('student.report.index')}}"
                           rel="noopener"><i class="icon fa fa-circle-o"></i>تقارير حول الطلبة</a></li>

                    <li><a class="treeview-item" href="{{route('report.students.table1')}}"
                           rel="noopener"><i class="icon fa fa-circle-o"></i>جدول رقم 1</a></li>

                    <li><a class="treeview-item" href="{{route('report.students.table2')}}"
                           rel="noopener"><i class="icon fa fa-circle-o"></i>جدول رقم 2</a></li>
                    <li><a class="treeview-item" href="{{route('report.students.table3')}}"
                           rel="noopener"><i class="icon fa fa-circle-o"></i>جدول رقم 3</a></li>
                </ul>
            </li>
        @endcan
        <li class="treeview">
            <a class="app-menu__item" href="#" data-toggle="treeview"><i
                    class="app-menu__icon fa fa-users"></i><span class="app-menu__label">المستخدمين</span><i
                    class="treeview-indicator fa fa-angle-right"></i>
            </a>
            <ul class="treeview-menu">
                @can('أدارة المستخدمين')
                    <li><a class="treeview-item" href="{{route('users.index')}}"
                           rel="noopener"><i class="icon fa fa-circle-o"></i>أدارة المستخدمين</a></li>
                @endcan
                @can('ادارة دور المستخدم')
                    <li><a class="treeview-item" href="{{route('roles.index')}}"
                           rel="noopener"><i class="icon fa fa-circle-o"></i>أدارة دور المستخدم</a></li>
                @endcan
                @can('ادارة الصلاحيات')
                    <li><a class="treeview-item" href="{{route('permissions.index')}}"
                           rel="noopener"><i class="icon fa fa-circle-o"></i>أدارة الصلاحيات</a></li>
                @endcan
            </ul>
        </li>
        <li><a class="app-menu__item" href=""
               onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();">
                <i class="app-menu__icon fa fa-power-off"></i><span
                    class="app-menu__label">تسجيل الخروج</span></a></li>
    </ul>
</aside>
<div class="app-sidebar__overlay" data-toggle="sidebar"></div>
