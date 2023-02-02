<aside class="sidebar ">
    <div class="container-fluid">
        <!--[ sidebar:: menu list ]-->
        <div class="flex-grow-1">
            <ul class="menu-list mt-3 rounded-4">
                <!--[ Start:: brand logo and name ]-->
                <li class="brand-icon mb-3 py-1">
                    <a href="{{ route('home') }}" style="width: 160px; display: flex;">
                        @svg('logo.svg', 'logo')
                        <span class="fs-5 ms-2">{{ __('global.appTitle') }}</span>
                    </a>
                </li>
                <!--[ Start:: dashboard ]-->
                <li>
                    <a class="m-link {{ (request()->is('home')) ? 'active' : '' }}" href="{{ url('/home') }}">
                        <i class="me-3 fa fa-home icon-size"></i>
                        <span class="mx-3">{{ __('global.dashboard') }}</span>
                    </a>
                </li>
                <!--[ Start:: Account ]-->
                @if(!empty(Auth()->user()->getRoleNames()) && Auth()->user()->hasExactRoles('SuperAdmin'))
                <li>
                    <a class="m-link {{ (request()->is('users*')) ? 'active' : '' }}" href="{{ route('users.index') }}">
                        <i class="me-3 fa fa-user icon-size"></i>
                        <span class="mx-3">{{ __('global.userManagement') }}</span>
                    </a>
                </li>
                <li>
                    <a class="m-link {{ (request()->is('roles*')) ? 'active' : '' }}" href="{{ route('roles.index') }}">
                        <i class="me-3 fa fa-cog icon-size"></i>
                        <span class="mx-3">{{ __('global.roleManagement') }}</span>
                    </a>
                </li>
                @endif
                <li>
                    @if(request()->is('jobcards*') || request()->is('tasks*') || request()->is('subtasks*'))
                    <a class="m-link active" href="{{ route('jobcards.index') }}">
                        <i class="me-3 fa fa-list-alt icon-size"></i>
                        <span class="mx-3">{{ __('global.jobCard') }}</span>
                    </a>
                    @else
                    <a class="m-link" href="{{ route('jobcards.index') }}">
                        <i class="me-3 fa fa-list-alt icon-size"></i>
                        <span class="mx-3">{{ __('global.jobCard') }}</span>
                    </a>
                    @endif
                </li>
                @if(!empty(Auth()->user()->getRoleNames()) && Auth()->user()->hasExactRoles('SuperAdmin') || Auth()->user()->hasExactRoles('Admin') || Auth()->user()->hasExactRoles('Supervisor'))
                <li class="collapsed">
                    @if(request()->is('export*') || request()->is('tasks*') || request()->is('subtasks*'))
                    <a class="m-link active" data-bs-toggle="collapse" data-bs-target="#menu_apps" href="#">
                        <i class="me-3 fa fa-cloud-download"></i>
                        <span class="mx-3">{{ __('global.export') }}</span>
                        <span class="arrow fa fa-angle-down ms-auto text-end"></span>
                    </a>
                    <!-- Menu: Sub menu ul -->
                    <ul class="sub-menu collapse show" id="menu_apps">
                        <li><a class="ms-link @if(request()->is('export/wps')) active @endif" href="{{ route('export.wps') }}"><span class="mx-3">{{ __('global.WPs') }}</span></a></li>
                        <li><a class="ms-link @if(request()->is('export/tasks')) active @endif" href="{{ route('export.tasks') }}"><span class="mx-3">{{ __('global.tasks') }}</span></a></li>
                        <li><a class="ms-link @if(request()->is('export/subtasks')) active @endif" href="{{ route('export.subtasks') }}"><span class="mx-3">{{ __('global.subTasks') }}</span></a></li>
                    </ul>
                    @else
                    <a class="m-link " data-bs-toggle="collapse" data-bs-target="#menu_apps" href="#">
                        <i class="me-3 fa fa-cloud-download"></i>
                        <span class="mx-3">{{ __('global.export') }}</span>
                        <span class="arrow fa fa-angle-down ms-auto text-end"></span>
                    </a>
                    <!-- Menu: Sub menu ul -->
                    <ul class="sub-menu collapse" id="menu_apps">
                        <li><a class="ms-link" href="{{ route('export.wps') }}"><span class="mx-3">{{ __('global.WPs') }}</span></a></li>
                        <li><a class="ms-link" href="{{ route('export.tasks') }}"><span class="mx-3">{{ __('global.tasks') }}</span></a></li>
                        <li><a class="ms-link" href="{{ route('export.subtasks') }}"><span class="mx-3">{{ __('global.subTasks') }}</span></a></li>
                    </ul>
                    @endif
                </li>
                @endif
                <li>
                    @if(request()->is('contact*'))
                    <a class="m-link active" href="{{ route('contact') }}">
                        <i class="me-3 fa fa-star icon-size"></i>
                        <span class="mx-3">{{ __('global.contact') }}</span>
                    </a>
                    @else
                    <a class="m-link" href="{{ route('contact') }}">
                        <i class="me-3 fa fa-star icon-size"></i>
                        <span class="mx-3">{{ __('global.contact') }}</span>
                    </a>
                    @endif
                </li>
                <li>
                    @if(request()->is('sendmessage*'))
                    <a class="m-link active" href="https://drive.google.com/drive/folders/1fOVUiRXGwKeHq_sOCBNwUuNPM1ef8SlF" target="_blank">
                    <i class="me-3 fa fa-external-link"></i>
                    <span class="mx-3">{{ __('global.guide') }}</span>
                    </a>
                    @else
                    <a class="m-link" href="https://drive.google.com/drive/folders/1fOVUiRXGwKeHq_sOCBNwUuNPM1ef8SlF" target="_blank">
                    <i class="me-3 fa fa-external-link"></i>
                    <span class="mx-3">{{ __('global.guide') }}</span>
                    </a>
                    @endif
                </li>
            </ul>
        </div>
    </div>
</aside>