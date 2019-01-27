<footer class="main-footer">
    <div class="pull-right hidden-xs">
        {{config('app.name')}}
    </div>
    <strong>Copyright &copy; {{date("Y")}}
        <a target="_blank"
           href="{{config('app.url')}}">{{config('app.name')}}</a>.</strong>
    All rights reserved.
</footer>

@if(config('app.env')=='local')
    {{--<script src="/vendor/js/vue.min.js"></script>--}}
    {{--<script src="/vendor/js/iview.min.js"></script>--}}
    {{--<script src="/vendor/js/element.js"></script>--}}
    <script src="/vendor/js/axios.min.js"></script>
    {{--<script src="/vendor/js/lodash.min.js"></script>--}}
    {{--<script src="/vendor/js/moment.min.js"></script>--}}

    <script src="/vendor/js/jquery.min.js"></script>
    <script src="/vendor/js/bootstrap.min.js"></script>
    <script src="/vendor/js/adminlte.min.js"></script>
    <script src="/vendor/js/jquery.imageMaps.min.js"></script>

    <script src="/js/app.js"></script>
@else
    {{--<script src="{{ config('website.ali.oss.host')}}/vendor/js/vue.min.js"></script>--}}
    {{--<script src="{{ config('website.ali.oss.host')}}/vendor/js/iview.min.js"></script>--}}
    {{--<script src="{{ config('website.ali.oss.host')}}/vendor/js/element.js"></script>--}}
    <script src="{{ config('website.ali.oss.host')}}/vendor/js/axios.min.js"></script>
    {{--<script src="{{ config('website.ali.oss.host')}}/vendor/js/lodash.min.js"></script>--}}
    {{--<script src="{{ config('website.ali.oss.host')}}/vendor/js/moment.min.js"></script>--}}

    <script src="{{ config('website.ali.oss.host')}}/vendor/js/jquery.min.js"></script>
    <script src="{{ config('website.ali.oss.host')}}/vendor/js/bootstrap.min.js"></script>
    <script src="{{ config('website.ali.oss.host')}}/vendor/js/adminlte.min.js"></script>
    <script src="{{ config('website.ali.oss.host')}}/vendor/js/jquery.imageMaps.min.js"></script>

    <script src="{{ config('website.ali.oss.host').'js/admin/'.config('website.web.js.admin').'/app.js' }}"></script>
@endif
{{--当前所选菜单自动展开--}}
<script>
    $(function () {
        $('.sidebar-menu li:not(.treeview) > a').on('click', function () {
            var $parent = $(this).parent().addClass('active');
            $parent.siblings('.treeview.active').find('> a').trigger('click');
            $parent.siblings().removeClass('active').find('li').removeClass('active');
        });

        $(window).on('load', function () {
            $('.sidebar-menu a').each(function () {
                if (this.href === window.location.href) {
                    $(this).parent().addClass('active')
                        .closest('.treeview').addClass('active').addClass('menu-open');
                }
            });

            if (!localStorage.getItem('MenuClass')) {
                localStorage.setItem('MenuClass', null);
            } else {
                $('body').addClass(localStorage.getItem('MenuClass'));
            }

            $('.sidebar-toggle').click(function () {
                if (localStorage.getItem('MenuClass') === 'null') {
                    localStorage.setItem('MenuClass', 'sidebar-collapse');
                } else {
                    localStorage.setItem('MenuClass', null);
                }
            });
        });
    });
</script>
