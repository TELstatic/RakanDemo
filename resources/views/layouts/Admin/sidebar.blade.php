<aside class="main-sidebar">
    <section class="sidebar">
        <div class="user-panel">
            <div class="pull-left image">
                <img src="{{ Auth::guard('admin')->user()->avatar }}" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p>{{ Auth::guard('admin')->user()->name }}</p>
                <a href="javascript:;"><i
                            class="fa fa-circle text-success"></i> {{ isset(Auth::guard('admin')->user()->getRoleNames()[0]) ? Auth::guard('admin')->user()->getRoleNames()[0] : 'Default' }}
                </a>
            </div>
        </div>
        <form action="javascript:;" method="get" target="_blank" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="keyword" class="form-control" placeholder="Search..." disabled>
                <span class="input-group-btn">
                <button type="submit" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i></button>
            </span>
            </div>
        </form>

        @if(session('role'))
            {!! $AdminMenu[session('role')] !!}
        @endif
    </section>
</aside>