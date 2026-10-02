<nav class="navbar navbar-expand-lg navbar-dark bg-primary global-navbar">
<div class="bg-white border-bottom shadow-sm">

    <div class="container-fluid">

        <ul class="nav nav-pills justify-content-center py-2">
    @include('global-partial-1')

    <li class="nav-item"><a class="nav-link {{ request()->is('home') ? 'active' : '' }}" href="{{ url('/home') }}">Home</a></li>

    <li class="nav-item"><a class="nav-link {{ request()->is('manage-items') ? 'active' : '' }}" href="{{ url('/manage-items') }}">Items</a></li>

    <li class="nav-item"><a class="nav-link {{ request()->is('search-orders') ? 'active' : '' }}" href="{{ url('/search-orders') }}">Orders</a></li>

    <li class="nav-item"><a class="nav-link {{ request()->is('pos-checkout') ? 'active' : '' }}" href="{{ url('/pos-checkout') }}">POS</a></li>

    @include('global-partial-2')
        </ul>

    </div>

</div>
</nav>

