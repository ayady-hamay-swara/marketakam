<div class="bg-white border-bottom shadow-sm">
    <div class="container-fluid">
        <ul class="nav nav-pills justify-content-center py-2">
            <li class="nav-item"><a class="nav-link {{ request()->is('store-select') ? 'active' : '' }}" href="{{ url('/store-select') }}">Store Select</a></li>
            <li class="nav-item"><a class="nav-link {{ request()->is('manage-employees') ? 'active' : '' }}" href="{{ url('/manage-employees') }}">Employees</a></li>
        </ul>
    </div>
</div>
