<section class="sidebar">
    <ul class="sidebar-menu">

        <li class="header">{{ Carbon\Carbon::now()->formatLocalized('%A %d %B %Y') }}</li>

        <li class="treeview">
            <a href="{{ url('admin') }}">
                <i class="fa fa-suitcase"></i>
                <span>{{ t('Dashboard') }}</span>
            </a>
        </li>

        <li class="treeview">
            <a href="#">
                <i class="ion ion-cube"></i>
                <span>{{ t('Products') }}</span>
                <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
                <li><a href="{{ route('admin.productcategories', ['type' => 'approved']) }}"><i class="fa fa-sitemap"></i> {{ t('Categories') }}</a></li>
                <li><a href="{{ route('admin.products', ['type' => 'approved']) }}"><i class="fa fa-reorder"></i> {{ t('List') }}</a></li>
                <li><a href="{{ route('admin.products', ['type' => 'featured']) }}"><i class="fa fa-star"></i> {{ t('Featured') }}</a></li>
                <li><a href="{{ route('admin.products', ['type' => 'approvalRequired']) }}"><i class="fa fa-legal"></i> {{ t('Approval') }}</a></li>
                <li><a href="{{ route('admin.products.bulkupload', ['type' => 'approvalRequired']) }}"><i class="fa fa-upload"></i> {{ t('Bulk Create') }}</a></li>
            </ul>
        </li>

        <li class="treeview">
            <a href="#">
                <i class="fa fa-book"></i>
                <span>Blogs</span>
                <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
                <li><a href="{{ route('admin.blogs') }}"><i class="fa fa-reorder"></i> {{ t('List') }}</a></li>
                <li><a href="{{ route('admin.blogs.create') }}"><i class="fa fa-plus"></i> {{ t('Create') }}</a></li>
            </ul>
        </li>

      {{--   <li class="treeview">
            <a href="#">
                <i class="fa fa-comments"></i>
                <span>Comments</span>
                <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
                <li><a href="{{ route('admin.comments', ['type' => 'approved']) }}"><i class="fa fa-reorder"></i> All</a></li>
                <li><a href="{{ route('admin.products', ['type' => 'featured']) }}"><i class="fa fa-star"></i> Featured Comments</a></li>
            </ul>
        </li> --}}


       @if (auth()->user()->isSuper())

            <li class="treeview">
                <a href="#">
                    <i class="fa fa-file"></i>
                    <span>{{ t('Pages') }}</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ route('admin.pages') }}"><i class="fa fa-reorder"></i> {{ t('List') }}</a></li>
                    <li><a href="{{ route('admin.pages.create') }}"><i class="fa fa-plus"></i> {{ t('Create') }}</a></li>
                </ul>
            </li>

            <li class="treeview">
                <a href="#">
                    <i class="fa fa-user"></i>
                    <span>{{ t('Users') }}</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ route('admin.users', ['type' => 'approved']) }}"><i class="fa fa-reorder"></i> {{ t('List') }}</a></li>
                    <li><a href="{{ route('admin.users', ['type' => 'approvalRequired']) }}"><i class="fa fa-legal"></i> {{ t('Approval') }}</a></li>
                    <li><a href="{{ route('admin.users', ['type' => 'banned']) }}"><i class="fa fa-ban"></i> {{ t('Banned') }}</a></li>
                    <li><a href="{{ route('admin.users.add') }}"><i class="fa fa-plus"></i> {{ t('Create') }}</a></li>
                    <li><a href="{{ route('admin.profiles') }}"><i class="fa fa-group"></i>{{ t('Profiles') }}</a></li>
                </ul>
            </li>

            <li class="treeview">
                <a href="{{ route('admin.reports') }}">
                    <i class="fa fa-exclamation-triangle"></i>
                    <span>{{ t('Reports') }}</span>
                </a>
            </li>

            <li class="treeview">
                <a href="#">
                    <i class="fa fa-gear"></i>
                    <span>{{ t('Settings') }}</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ route('admin.settings.details') }}"><i class="fa fa-circle-o"></i>{{ t('Details') }}</a></li>
                    <li><a href="{{ route('admin.settings.limits') }}"><i class="fa fa-circle-o"></i>{{ t('Limits') }}</a></li>
                    <li><a href="{{ route('admin.settings.cache') }}"><i class="fa fa-circle-o"></i>{{ t('Cache') }}</a></li>
                    <li><a href="{{ route('admin.settings.sitemap') }}"><i class="fa fa-circle-o"></i>{{ t('Sitemap') }}</a></li>
                </ul>
            </li>

        @endif

    </ul>
</section>