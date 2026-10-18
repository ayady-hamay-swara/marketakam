@extends('layouts.gate')
@section('title_key', 'store_select_title')
@section('title_default', 'هەڵبژاردنی فرۆشگا')

@section('content')
    <h2 class="gate-title" data-i18n="store_select_title">هەڵبژاردنی فرۆشگا</h2>
    <p class="gate-sub" data-i18n="store_select_subtitle">کام فرۆشگا دەتەوێت کار لەسەری بکەیت؟</p>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="post" id="storeSelectForm">
        @csrf

        @foreach ($memberships as $membership)
            <button type="submit" name="store_id" value="{{ $membership->store_id }}" class="gate-row store-option">
                <span class="mark">
                    @include('partials.icon', ['name' => $membership->store->type === 'pharma' ? 'pharma' : ($membership->store->type === 'market' ? 'cart' : 'bag'), 'size' => 20])
                </span>
                <span>
                    <div class="nm">{{ $membership->store->name }}</div>
                    <div class="meta">
                        @if ($membership->is_owner)
                            <span data-i18n="position_owner">خاوەن</span>
                        @else
                            {{ ucfirst(strtolower(str_replace('_', ' ', $membership->role))) }}
                        @endif
                    </div>
                </span>
                <span class="go">@include('partials.icon', ['name' => 'chev-right', 'size' => 18, 'class' => 'flip-rtl'])</span>
            </button>
        @endforeach
    </form>

    <div class="gate-section">
        <form method="post" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="gate-quiet" data-i18n="login_logout">چوونەدەرەوە</button>
        </form>
    </div>
@endsection

@push('scripts')
<script>
    // Each button submits its own store_id via the shared form's action.
    document.querySelectorAll('.store-option').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            const form = document.getElementById('storeSelectForm');
            form.action = "{{ url('/select-store') }}/" + this.value;
            form.submit();
        });
    });
</script>
@endpush
