<div class="bg-white border-bottom shadow-sm">
    <div class="container-fluid">
        <ul class="nav nav-pills justify-content-center py-2">
            <li class="nav-item"><a class="nav-link {{ request()->is('manager') ? 'active' : '' }}" href="{{ url('/manager') }}">Manager</a></li>
        </ul>
    </div>
</div>
