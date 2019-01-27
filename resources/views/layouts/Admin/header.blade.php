<header class="main-header">
    <a href="/" class="logo">
        <span class="logo-mini"><b>{{config('app.name')}}</b></span>
        <span class="logo-lg"><b>{{config('app.name')}}</b></span>
    </a>
    <nav class="navbar navbar-static-top" role="navigation">
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">

                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="{{Auth::guard('admin')->user()->avatar}}" class="user-image" alt="User Image">
                        <span class="hidden-xs">{{ Auth::guard('admin')->user()->name }}</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="user-header">
                            <img src="{{ Auth::guard('admin')->user()->avatar}}" class="img-circle" alt="User Image">
                            <p>
                                {{ Auth::guard('admin')->user()->name }}
                                <small>{{ Auth::guard('admin')->user()->last_login }}</small>
                                <small>{{ Auth::guard('admin')->user()->last_ip }}</small>
                                <small>{{ Auth::guard('admin')->user()->created_at }}</small>
                            </p>
                        </li>
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="{{route('admin.profile')}}" class="btn btn-default btn-flat">个人中心</a>
                            </div>
                            <div class="pull-right">

                                <a href="{{ route('admin.logout') }}"
                                   class="btn btn-default btn-flat"
                                   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    安全退出
                                </a>

                                <form id="logout-form" action="{{ route('admin.logout') }}" method="POST"
                                      style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>