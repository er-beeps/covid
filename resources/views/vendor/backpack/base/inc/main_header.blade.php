<header class="{{ config('backpack.base.header_class') }}">
  <!-- Logo -->
  <button class="navbar-toggler sidebar-toggler d-lg-none" type="button" data-toggle="sidebar-show">
    <span class="navbar-toggler-icon"></span>
  </button>
  <a class="navbar-brand nav" href="{{ url(config('backpack.base.home_link')) }}"> <a href="{{ url(config('backpack.base.home_link')) }}"><img id ="science_hub_logo" src="{{ asset('img/sciencehub.png') }}" data-toggle="tooltip" title="Science Hub" /></a>
    {{ trans('general.project_logo')}} &nbsp &nbsp<sub><small> Beta - V 0.5</small></sub>
  </a>
  <button class="navbar-toggler sidebar-toggler d-md-down-none" type="button" data-toggle="sidebarlg-show">
    <span class="navbar-toggler-icon"></span>
  </button>
  @include(backpack_view('inc.menu'))
</header>
