<div class="bg-white border-bottom shadow-sm">
    <div class="container-fluid">
        <ul class="nav nav-pills justify-content-center py-2">
            <li class="nav-item"><a class="nav-link {{ request()->is('dev-view') ? 'active' : '' }}" href="{{ url('/dev-view') }}">Dev View</a></li>
            <li class="nav-item"><a class="nav-link {{ request()->is('home') ? 'active' : '' }}" href="{{ url('/home') }}">Home</a></li>
        </ul>
    </div>
</div>
