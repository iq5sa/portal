<header class="app-header">
    <a class="app-header__logo" href="#">نظام شؤون الطلبة</a>
    <!-- Sidebar toggle button-->
    <a class="app-sidebar__toggle" href="#" data-toggle="sidebar"
       aria-label="Hide Sidebar"></a>
    <!-- Navbar Right Menu-->
    <ul class="app-nav">


        <!--Notification Menu-->
        <li class="dropdown"><a class="app-nav__item" href="#" data-toggle="dropdown" aria-label="Show notifications"><i
                    class="fa fa-bell-o fa-lg"></i>
                <span class="badge badge-warning navbar-badge">15</span>
            </a>
            <ul class="app-notification dropdown-menu dropdown-menu-right">
                <li class="app-notification__title">قائمة الاشعارات</li>
                <div class="app-notification__content">
                    @foreach(auth()->user()->notifications as $notification)
                        <li><a class="app-notification__item" href="javascript:;"><span
                                    class="app-notification__icon"><span class="fa-stack fa-lg"><i
                                            class="fa fa-circle fa-stack-2x {{$notification->data['color']}}"></i><i
                                            class="{{$notification->data['icon']}} fa-stack-1x fa-inverse"></i></span></span>
                                <div>
                                    <p class="app-notification__message">{{$notification->data['message']}}</p>
                                    <p class="app-notification__meta">{{$notification->created_at->format('Y-m-d')}}</p>
                                </div>
                            </a>
                        </li>
                    @endforeach
                </div>
                <li class="app-notification__footer"><a href="#">عرض جميع الاشعارات</a></li>
            </ul>
        </li>

        <!-- User Menu-->
        <li class="dropdown">
            <a class="app-nav__item" href="#" data-toggle="dropdown"
               aria-label="Open Profile Menu">
                <i class="fa fa-user fa-lg"></i>
            </a>
            <ul class="dropdown-menu settings-menu dropdown-menu-right">
                <li><a class="dropdown-item" href=""
                       onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                        <i class="fa fa-sign-out fa-lg"></i> تسجيل
                        الخروج </a>
                </li>
            </ul>
        </li>
    </ul>
</header>
