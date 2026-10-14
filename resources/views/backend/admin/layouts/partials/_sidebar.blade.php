<aside class="main-sidebar elevation-4 sidebar-dark-primary">
    <!-- Brand Logo -->
    <a href="{{route('admin.home')}}" class="brand-link">
      <span class="brand-text font-weight-bolder">      <img src="{{asset('backend/dist/img/short_logo.png')}}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3">
F C P M S 2.0</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar os-theme-light" style="overflow-y: auto;">
      <!-- Sidebar user (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{{asset('backend/dist/img/blank.jpg')}}" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">{{Auth::guard('admin')->user()->name}}</a>
        </div>
      </div>

      <!-- SidebarSearch Form -->
      <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div><div class="sidebar-search-results"><div class="list-group"><a href="#" class="list-group-item"><div class="search-title"><strong class="text-light"></strong>N<strong class="text-light"></strong>o<strong class="text-light"></strong> <strong class="text-light"></strong>e<strong class="text-light"></strong>l<strong class="text-light"></strong>e<strong class="text-light"></strong>m<strong class="text-light"></strong>e<strong class="text-light"></strong>n<strong class="text-light"></strong>t<strong class="text-light"></strong> <strong class="text-light"></strong>f<strong class="text-light"></strong>o<strong class="text-light"></strong>u<strong class="text-light"></strong>n<strong class="text-light"></strong>d<strong class="text-light"></strong>!<strong class="text-light"></strong></div><div class="search-path"></div></a></div></div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        @php($permAdmin = Auth::guard('admin')->user())
        @php($can = fn ($slug) => $permAdmin && $permAdmin->hasPermission($slug))
        <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent nav-compact" data-widget="treeview" role="menu" data-accordion="false">

          {{-- Dashboard --}}
          <li class="nav-item">
            <a href="{{route('admin.home')}}" class="nav-link @if (request()->routeIs('admin.home')) active @endif">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>Dashboard</p>
            </a>
          </li>

          {{-- Project --}}
          @if ($can('projects.view'))
          <li class="nav-item">
            <a href="{{route('admin.projects.index')}}" class="nav-link @if (request()->routeIs('admin.projects.*')) active @endif">
              <i class="nav-icon fas fa-project-diagram"></i>
              <p>Project</p>
            </a>
          </li>
          @endif

          {{-- Bill --}}
          @if ($can('bills.view'))
          <li class="nav-item">
            <a href="{{route('admin.bills.index')}}" class="nav-link @if (request()->routeIs('admin.bills.*')) active @endif">
              <i class="nav-icon fas fa-file-invoice-dollar"></i>
              <p>Bill</p>
            </a>
          </li>
          @endif

          {{-- Settings --}}
          @if ($can('regions.view') || $can('scheme_options.view') || $can('document_types.view') || $can('units.view'))
          <li class="nav-item has-treeview @if (request()->routeIs('admin.regions.*', 'admin.scheme_options.*', 'admin.document_types.*', 'admin.units.*')) menu-open @endif">
            <a href="#" class="nav-link @if (request()->routeIs('admin.regions.*', 'admin.scheme_options.*', 'admin.document_types.*', 'admin.units.*')) active @endif">
              <i class="nav-icon fas fa-cog"></i>
              <p>Settings <i class="right fas fa-angle-left"></i></p>
            </a>
            <ul class="nav nav-treeview">
              @if ($can('regions.view'))
              <li class="nav-item">
                <a href="{{route('admin.regions.index')}}" class="nav-link @if (request()->routeIs('admin.regions.*')) active @endif">
                  <i class="nav-icon fas fa-map-marked-alt"></i>
                  <p>Region</p>
                </a>
              </li>
              @endif
              @if ($can('scheme_options.view'))
              <li class="nav-item">
                <a href="{{route('admin.scheme_options.index')}}" class="nav-link @if (request()->routeIs('admin.scheme_options.*')) active @endif">
                  <i class="nav-icon fas fa-list-ul"></i>
                  <p>Scheme Option</p>
                </a>
              </li>
              @endif
              @if ($can('document_types.view'))
              <li class="nav-item">
                <a href="{{route('admin.document_types.index')}}" class="nav-link @if (request()->routeIs('admin.document_types.*')) active @endif">
                  <i class="nav-icon fas fa-tags"></i>
                  <p>Document Type</p>
                </a>
              </li>
              @endif
              @if ($can('units.view'))
              <li class="nav-item">
                <a href="{{route('admin.units.index')}}" class="nav-link @if (request()->routeIs('admin.units.*')) active @endif">
                  <i class="nav-icon fas fa-balance-scale"></i>
                  <p>Unit</p>
                </a>
              </li>
              @endif
            </ul>
          </li>
          @endif

          {{-- Operations --}}
          @if ($can('packages.view') || $can('schemes.view') || $can('contractors.view') || $can('contractor_users.view'))
          <li class="nav-item has-treeview @if (request()->routeIs('admin.packages.*', 'admin.schemes.*', 'admin.contractors.*', 'admin.contractor_users.*')) menu-open @endif">
            <a href="#" class="nav-link @if (request()->routeIs('admin.packages.*', 'admin.schemes.*', 'admin.contractors.*', 'admin.contractor_users.*')) active @endif">
              <i class="nav-icon fas fa-tasks"></i>
              <p>Operations <i class="right fas fa-angle-left"></i></p>
            </a>
            <ul class="nav nav-treeview">
              @if ($can('packages.view'))
              <li class="nav-item">
                <a href="{{route('admin.packages.index')}}" class="nav-link @if (request()->routeIs('admin.packages.*')) active @endif">
                  <i class="nav-icon fas fa-box"></i>
                  <p>Package</p>
                </a>
              </li>
              @endif
              @if ($can('schemes.view'))
              <li class="nav-item">
                <a href="{{route('admin.schemes.index')}}" class="nav-link @if (request()->routeIs('admin.schemes.*')) active @endif">
                  <i class="nav-icon fas fa-home"></i>
                  <p>Scheme</p>
                </a>
              </li>
              @endif
              @if ($can('contractors.view'))
              <li class="nav-item">
                <a href="{{route('admin.contractors.index')}}" class="nav-link @if (request()->routeIs('admin.contractors.*')) active @endif">
                  <i class="nav-icon fas fa-hard-hat"></i>
                  <p>Contractor</p>
                </a>
              </li>
              @endif
              @if ($can('contractor_users.view'))
              <li class="nav-item">
                <a href="{{route('admin.contractor_users.index')}}" class="nav-link @if (request()->routeIs('admin.contractor_users.*')) active @endif">
                  <i class="nav-icon fas fa-users"></i>
                  <p>Contractor Users</p>
                </a>
              </li>
              @endif
            </ul>
          </li>
          @endif

          {{-- BOQ --}}
          @if ($can('boq_parts.view') || $can('boq_items.view') || $can('boq_sub_items.view') || $can('boq_versions.view') || $can('boq_version_details.view'))
          <li class="nav-item has-treeview @if (request()->routeIs('admin.boq_parts.*', 'admin.boq_items.*', 'admin.boq_sub_items.*', 'admin.boq_versions.*', 'admin.boq_version_details.*')) menu-open @endif">
            <a href="#" class="nav-link @if (request()->routeIs('admin.boq_parts.*', 'admin.boq_items.*', 'admin.boq_sub_items.*', 'admin.boq_versions.*', 'admin.boq_version_details.*')) active @endif">
              <i class="nav-icon fas fa-layer-group"></i>
              <p>BOQ <i class="right fas fa-angle-left"></i></p>
            </a>
            <ul class="nav nav-treeview">
              @if ($can('boq_parts.view'))
              <li class="nav-item">
                <a href="{{route('admin.boq_parts.index')}}" class="nav-link @if (request()->routeIs('admin.boq_parts.*')) active @endif">
                  <i class="nav-icon fas fa-layer-group"></i>
                  <p>BOQ Parts</p>
                </a>
              </li>
              @endif
              @if ($can('boq_items.view'))
              <li class="nav-item">
                <a href="{{route('admin.boq_items.index')}}" class="nav-link @if (request()->routeIs('admin.boq_items.*')) active @endif">
                  <i class="nav-icon fas fa-tasks"></i>
                  <p>BOQ Items</p>
                </a>
              </li>
              @endif
              @if ($can('boq_sub_items.view'))
              <li class="nav-item">
                <a href="{{route('admin.boq_sub_items.index')}}" class="nav-link @if (request()->routeIs('admin.boq_sub_items.*')) active @endif">
                  <i class="nav-icon fas fa-stream"></i>
                  <p>BOQ Sub Items</p>
                </a>
              </li>
              @endif
              @if ($can('boq_versions.view'))
              <li class="nav-item">
                <a href="{{route('admin.boq_versions.index')}}" class="nav-link @if (request()->routeIs('admin.boq_versions.*')) active @endif">
                  <i class="nav-icon fas fa-history"></i>
                  <p>BOQ Versions</p>
                </a>
              </li>
              @endif
              @if ($can('boq_version_details.view'))
              <li class="nav-item">
                <a href="{{route('admin.boq_version_details.index')}}" class="nav-link @if (request()->routeIs('admin.boq_version_details.*')) active @endif">
                  <i class="nav-icon fas fa-table"></i>
                  <p>BOQ Versions Details</p>
                </a>
              </li>
              @endif
            </ul>
          </li>
          @endif

          {{-- Access Control --}}
          @if ($can('admin_users.view') || $can('roles.view'))
          <li class="nav-item has-treeview @if (request()->routeIs('admin.admin_users.*', 'admin.roles.*')) menu-open @endif">
            <a href="#" class="nav-link @if (request()->routeIs('admin.admin_users.*', 'admin.roles.*')) active @endif">
              <i class="nav-icon fas fa-user-shield"></i>
              <p>Access Control <i class="right fas fa-angle-left"></i></p>
            </a>
            <ul class="nav nav-treeview">
              @if ($can('admin_users.view'))
              <li class="nav-item">
                <a href="{{route('admin.admin_users.index')}}" class="nav-link @if (request()->routeIs('admin.admin_users.*')) active @endif">
                  <i class="nav-icon fas fa-user-cog"></i>
                  <p>Admin Users</p>
                </a>
              </li>
              @endif
              @if ($can('roles.view'))
              <li class="nav-item">
                <a href="{{route('admin.roles.index')}}" class="nav-link @if (request()->routeIs('admin.roles.*')) active @endif">
                  <i class="nav-icon fas fa-user-tag"></i>
                  <p>Roles</p>
                </a>
              </li>
              @endif
            </ul>
          </li>
          @endif

          {{-- Audit Log --}}
          @if ($can('audit_logs.view'))
          <li class="nav-item">
            <a href="{{route('admin.audit_logs.index')}}" class="nav-link @if (request()->routeIs('admin.audit_logs.*')) active @endif">
              <i class="nav-icon fas fa-history"></i>
              <p>Audit Log</p>
            </a>
          </li>
          @endif

          {{-- Profile --}}
          <li class="nav-item">
            <a href="{{route('admin.profile.index')}}" class="nav-link @if (request()->routeIs('admin.profile.*')) active @endif">
              <i class="nav-icon fas fa-user"></i>
              <p>Profile</p>
            </a>
          </li>

          {{-- Logout --}}
          <li class="nav-item">
            <a href="{{route('admin.logout')}}" class="nav-link text-danger text-bold">
              <i class="nav-icon fas fa-sign-out-alt"></i>
              <p>Logout</p>
            </a>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
