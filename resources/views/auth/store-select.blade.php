<!DOCTYPE html>
<html lang="ku" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>هەڵبژاردنی فرۆشگا - سیستەمی فرۆشتن</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/navbar-global.css') }}">
    <style>
        body {
            background: linear-gradient(135deg, #007bff 0%, #1611bb 100%);
            min-height: 100vh;
            color: #2c3e50;
        }

        .select-wrapper {
            min-height: calc(100vh - 200px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }

        .select-card {
            width: 100%;
            max-width: 640px;
            background: white;
            border-radius: 20px;
            padding: 36px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.25);
        }

        .select-title {
            font-size: 26px;
            font-weight: 800;
            color: #2c3e50;
            margin-bottom: 6px;
        }

        .select-subtitle {
            font-size: 15px;
            color: #7f8c8d;
            margin-bottom: 28px;
        }

        .store-option {
            display: flex;
            align-items: center;
            gap: 16px;
            width: 100%;
            text-align: right;
            border: 2px solid #e9ecef;
            border-radius: 14px;
            padding: 16px 18px;
            margin-bottom: 14px;
            background: white;
            cursor: pointer;
            transition: all .15s ease;
        }

        .store-option:hover {
            border-color: #667eea;
            background: #f5f7ff;
            transform: translateY(-2px);
        }

        .store-icon {
            width: 54px;
            height: 54px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            flex-shrink: 0;
            background: linear-gradient(135deg, #007bff 0%, #1611bb 100%);
            color: white;
        }

        .store-info {
            flex: 1;
        }

        .store-name {
            font-size: 17px;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 2px;
        }

        .store-role {
            font-size: 13px;
            color: #7f8c8d;
        }

        .brand-badge {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, #007bff 0%, #1611bb 100%);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
            margin-bottom: 14px;
        }
    </style>
</head>
<body>

<div class="select-wrapper">
    <div class="select-card">
        <div class="text-center">
            <div class="brand-badge">🏬</div>
            <h2 class="select-title" data-i18n="store_select_title">هەڵبژاردنی فرۆشگا</h2>
            <p class="select-subtitle" data-i18n="store_select_subtitle">کام فرۆشگا دەتەوێت کار لەسەری بکەیت؟</p>
        </div>

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
                <button type="submit" name="store_id" value="{{ $membership->store_id }}" class="store-option">
                    <div class="store-icon">
                        {{ $membership->store->type === 'pharma' ? '💊' : ($membership->store->type === 'market' ? '🛒' : '🏪') }}
                    </div>
                    <div class="store-info">
                        <div class="store-name">{{ $membership->store->name }}</div>
                        <div class="store-role">
                            @if ($membership->is_owner)
                                👑 <span data-i18n="position_owner">خاوەن</span>
                            @else
                                {{ ucfirst(strtolower(str_replace('_', ' ', $membership->role))) }}
                            @endif
                        </div>
                    </div>
                    <div style="font-size:20px;color:#adb5bd;">‹</div>
                </button>
            @endforeach
        </form>

        <div class="text-center mt-3">
            <form method="post" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-link text-muted" data-i18n="login_logout">
                    چوونەدەرەوە
                </button>
            </form>
        </div>
    </div>
</div>

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

<script src="{{ asset('js/jquery-3.4.1.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('js/languages.js') }}"></script>
@include('partials.footer')
</body>
</html>
