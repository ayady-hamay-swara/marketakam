<!DOCTYPE html>
<html lang="ku" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ڕێکخستنەکان</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/navbar-global.css') }}">
    <style>
        body {
            background: #f4f7fb;
        }
        .settings-card {
            max-width: 720px;
            margin: 40px auto;
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            padding: 28px;
        }
        .sample-row {
            display: flex;
            gap: 12px;
            align-items: center;
            flex-wrap: wrap;
        }
    </style>
</head>
<body>
@include('cashier.partials.nav')

<div class="container">
    <div class="settings-card">
        <h2>ڕێکخستنەکان</h2>
        <p class="text-muted">قەبارەی نرخی دراوەکان سنووری 250 IQD دەبێت.</p>

        <div class="mt-4">
            <label for="currencyFloorSample">نرخی تاقیكردنەوە</label>
            <div class="sample-row mt-2">
                <input type="number" id="currencyFloorSample" class="form-control" value="487" min="0" step="1" style="max-width: 220px;">
                <button type="button" id="btnApplyFloor" class="btn btn-primary">250 IQD - Floor</button>
                <button type="button" class="btn btn-secondary" onclick="window.location.href='/manage-items'">Back to items</button>
            </div>
            <div class="mt-3">
                <strong>ئەنجام:</strong>
                <span id="floorResult">250 IQD</span>
            </div>
        </div>

        <div class="mt-4 p-3 border rounded bg-light">
            <h5>قەبارەی کەمکردنەوە</h5>
            <ul class="mb-0">
                <li>0 - 249 → 0</li>
                <li>250 - 499 → 250</li>
                <li>500 - 749 → 500</li>
                <li>750 - 999 → 750</li>
            </ul>
        </div>
    </div>
</div>

<script src="{{ asset('js/jquery-3.4.1.min.js') }}"></script>
<script>
    function floorTo250(value) {
        const numeric = Number(value);
        if (!Number.isFinite(numeric) || numeric < 0) return 0;
        return Math.floor(numeric / 250) * 250;
    }

    $('#btnApplyFloor').on('click', function () {
        const raw = $('#currencyFloorSample').val();
        const result = floorTo250(raw);
        $('#currencyFloorSample').val(result);
        $('#floorResult').text(result + ' IQD');
    });
</script>
</body>
</html>
