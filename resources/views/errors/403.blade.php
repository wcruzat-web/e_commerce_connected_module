@extends('errors.layout')

@section('title', 'Forbidden')
@section('code', '403')
@section('image', asset('images/errors/403.webp'))
@section('message', $exception->getMessage() ?: 'You shall not pass! ...Unless you have the right role.')

@section('animation')
<style>
    @keyframes block {
        0%, 100% { transform: translateY(0) scale(1); }
        30% { transform: translateY(-15px) scale(1.15); }
        60% { transform: translateY(0) scale(1); }
    }
    @keyframes swing {
        0%, 100% { transform: rotate(-25deg) translateX(0); }
        50% { transform: rotate(15deg) translateX(15px); }
    }
    @keyframes burn {
        0% { transform: scale(1) translateY(0); opacity: 0.5; }
        100% { transform: scale(1.4) translateY(-8px); opacity: 1; }
    }
    @keyframes floatGandalf {
        0%, 100% { transform: translateY(0) rotate(-3deg); }
        50% { transform: translateY(-12px) rotate(3deg); }
    }
</style>
<span class="e" style="font-size:60px; opacity:0.2; top:4%; left:6%; animation: block 2s ease-in-out infinite;">🛡️</span>
<span class="e" style="font-size:50px; opacity:0.18; top:8%; right:4%; animation: swing 2.5s ease-in-out infinite;">⚔️</span>
<span class="e" style="font-size:30px; opacity:0.15; bottom:25%; left:3%; animation: burn 0.8s ease-in-out infinite alternate;">🔥</span>
<span class="e" style="font-size:28px; opacity:0.15; bottom:20%; left:8%; animation: burn 0.8s ease-in-out infinite alternate 0.3s;">🔥</span>
<span class="e" style="font-size:32px; opacity:0.15; bottom:28%; right:5%; animation: burn 0.8s ease-in-out infinite alternate 0.6s;">🔥</span>
<span class="e" style="font-size:35px; opacity:0.12; bottom:15%; right:12%; animation: burn 0.8s ease-in-out infinite alternate 0.2s;">🔥</span>
<span class="e" style="font-size:36px; opacity:0.18; top:55%; left:1%; animation: block 2.5s ease-in-out infinite 0.5s;">🛡️</span>
<span class="e" style="font-size:28px; opacity:0.15; top:40%; right:1%; animation: swing 3s ease-in-out infinite 0.8s;">⚔️</span>
<span class="e" style="font-size:40px; opacity:0.2; top:20%; left:30%; animation: floatGandalf 3.5s ease-in-out infinite;">🧙</span>
<span class="e" style="font-size:34px; opacity:0.15; bottom:35%; right:8%; animation: floatGandalf 4s ease-in-out infinite 1s;">🧙</span>
@endsection
