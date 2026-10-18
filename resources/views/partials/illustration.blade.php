{{-- Hand-drawn empty-state illustrations. Usage: @include('partials.illustration', ['name' => 'cart|people|card']) --}}
@php
$ill = [
 'cart'   => '<path d="M22 28 l14 1.5 l11 50 q1 5 6 5 l50 -1.5 q5 0 6 -5 l8 -33 l-76 -3" opacity=".9"/><path d="M54 52 l2 14 M70 50 l1 16 M86 50 l-1 16 M101 49 l-3 15" opacity=".45"/><circle cx="58" cy="97" r="5.5"/><circle cx="104" cy="96" r="5.5"/><path d="M118 20 q6 -8 14 -2 M126 32 q8 -4 12 3" opacity=".35"/>',
 'people' => '<circle cx="64" cy="42" r="15" opacity=".9"/><path d="M34 98 q1 -28 30 -28 q29 0 31 28" opacity=".9"/><circle cx="108" cy="50" r="11" opacity=".45"/><path d="M92 98 q2 -20 22 -20 q16 1 20 20" opacity=".45"/><path d="M26 30 q4 -9 12 -5" opacity=".35"/>',
 'card'   => '<path d="M28 36 q0 -8 8 -8 l86 1 q8 0 8 8 l-1 44 q0 8 -8 8 l-85 -1 q-8 0 -8 -8 z" opacity=".9"/><path d="M28 50 l102 1" opacity=".6"/><path d="M40 72 q10 -6 20 0 t20 0" opacity=".45"/><path d="M108 70 l14 0" opacity=".45"/>',
];
@endphp
<svg class="ill" viewBox="0 0 160 120" width="{{ $width ?? 160 }}" height="{{ round(($width ?? 160) * .75) }}" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">{!! $ill[$name] ?? '' !!}</svg>
