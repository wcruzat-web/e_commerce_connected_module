@extends('errors.layout')

@section('title', 'Unauthorized')
@section('code', '401')
@section('image', asset('images/errors/401.gif'))
@section('message', 'Psst... this area is off-limits. Got the secret handshake?')

@section('animation')
<style>
    @keyframes peek {
        0%, 100% { transform: translateY(0) scaleX(1); }
        25% { transform: translateY(-12px) scaleX(1); }
        50% { transform: translateY(0) scaleX(-1); }
        75% { transform: translateY(-12px) scaleX(-1); }
    }
    @keyframes blink {
        0%, 45%, 55%, 100% { opacity: 1; }
        50% { opacity: 0; }
    }
    @keyframes fadeShh {
        0%, 100% { opacity: 0.2; }
        50% { opacity: 0.7; }
    }
    @keyframes driftNinja {
        0%, 100% { transform: translate(0, 0) rotate(0deg); }
        25% { transform: translate(20px, -15px) rotate(5deg); }
        75% { transform: translate(-15px, 10px) rotate(-4deg); }
    }
</style>
<span class="e" style="font-size:64px; opacity:0.2; top:5%; left:8%; animation: peek 3s ease-in-out infinite, driftNinja 12s ease-in-out infinite;">🥷</span>
<span class="e" style="font-size:32px; opacity:0.15; top:3%; right:12%; animation: blink 2.5s ease-in-out infinite;">👀</span>
<span class="e" style="font-size:28px; opacity:0.15; bottom:25%; left:4%; animation: blink 2.5s ease-in-out infinite 0.8s;">👀</span>
<span class="e" style="font-size:28px; opacity:0.15; top:50%; right:3%; animation: blink 2.5s ease-in-out infinite 1.5s;">👀</span>
<span class="e" style="font-size:22px; opacity:0.12; bottom:8%; left:15%; animation: fadeShh 2.5s ease-in-out infinite;">🤫</span>
<span class="e" style="font-size:22px; opacity:0.12; top:30%; left:1%; animation: fadeShh 2.5s ease-in-out infinite 1s;">🤫</span>
<span class="e" style="font-size:48px; opacity:0.15; bottom:15%; right:10%; animation: peek 3.5s ease-in-out infinite 0.5s;">🥷</span>
@endsection
