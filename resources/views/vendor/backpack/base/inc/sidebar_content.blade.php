<!-- This file is used to store sidebar items, starting with Backpack\Base 0.9.0 -->
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="la la-home nav-icon"></i> {{ trans('backpack::base.dashboard') }}</a></li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('tag') }}'><i class='nav-icon la la-question'></i> Tags</a></li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('user') }}'><i class='nav-icon la la-question'></i> Users</a></li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('plan') }}'><i class='nav-icon la la-question'></i> Plans</a></li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('subscription') }}'><i class='nav-icon la la-question'></i> Subscriptions</a></li>
{{-- <li class='nav-item'><a class='nav-link' href='{{ backpack_url('product') }}'><i class='nav-icon la la-question'></i> Products</a></li> --}}
<li class="nav-item nav-dropdown">
    <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon la la-question"></i> Products</a>
    <ul class="nav-dropdown-items">
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('product') }}"><i class="nav-icon la la-question"></i> <span>List</span></a></li>
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('product-price') }}"><i class="nav-icon la la-question"></i> <span>Prices</span></a></li>        
        <li class='nav-item'><a class='nav-link' href='{{ backpack_url('product-meta') }}'><i class='nav-icon la la-question'></i> Metas</a></li>
    </ul>
</li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('feature') }}'><i class='nav-icon la la-question'></i> Features</a></li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('site') }}'><i class='nav-icon la la-question'></i> Sites</a></li>