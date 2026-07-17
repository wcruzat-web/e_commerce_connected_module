@extends('errors.layout')

@section('title', 'Page Not Found')
@section('code', '404')
@section('image', asset('images/errors/404.webp'))
@section('message', 'We looked everywhere. Behind the couch, under the rug... nothing.')

@section('animation')
<style>
    @keyframes floatSpace {
        0%, 100% { transform: translateY(0) rotate(0deg); }
        25% { transform: translateY(-20px) rotate(-5deg); }
        50% { transform: translateY(-10px) rotate(5deg); }
        75% { transform: translateY(-25px) rotate(-3deg); }
    }
    @keyframes orbit {
        0% { transform: rotate(0deg) translateX(0); }
        100% { transform: rotate(360deg) translateX(0); }
    }
    @keyframes twinkle {
        0% { opacity: 0.1; transform: scale(0.8); }
        100% { opacity: 0.6; transform: scale(1.3); }
    }
    @keyframes bob {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-8px); }
    }
    @keyframes driftSpace {
        0%, 100% { transform: translate(0, 0); }
        33% { transform: translate(25px, -15px); }
        66% { transform: translate(-15px, 20px); }
    }
</style>
<span class="e" style="font-size:60px; opacity:0.2; top:3%; left:5%; animation: floatSpace 4s ease-in-out infinite, driftSpace 14s ease-in-out infinite;">👨‍🚀</span>
<span class="e" style="font-size:35px; opacity:0.15; top:10%; right:3%; animation: orbit 6s linear infinite;">🪐</span>
<span class="e" style="font-size:30px; opacity:0.15; bottom:30%; right:8%; animation: orbit 10s linear infinite;">🌍</span>
<span class="e" style="font-size:24px; opacity:0.12; bottom:10%; left:6%; animation: orbit 12s linear infinite;">🌑</span>
<span class="e" style="font-size:18px; opacity:0.25; top:2%; left:45%; animation: twinkle 1.5s ease-in-out infinite alternate;">✨</span>
<span class="e" style="font-size:20px; opacity:0.2; top:20%; right:15%; animation: twinkle 1.8s ease-in-out infinite alternate 0.3s;">⭐</span>
<span class="e" style="font-size:22px; opacity:0.2; bottom:20%; left:2%; animation: twinkle 2s ease-in-out infinite alternate 0.6s;">🌟</span>
<span class="e" style="font-size:16px; opacity:0.2; top:45%; left:12%; animation: twinkle 1.3s ease-in-out infinite alternate 0.9s;">💫</span>
<span class="e" style="font-size:18px; opacity:0.2; bottom:40%; right:2%; animation: twinkle 1.7s ease-in-out infinite alternate 1.2s;">✨</span>
<span class="e" style="font-size:20px; opacity:0.2; top:65%; right:15%; animation: twinkle 2.2s ease-in-out infinite alternate 0.4s;">⭐</span>
<span class="e" style="font-size:16px; opacity:0.2; bottom:5%; left:50%; animation: twinkle 1.6s ease-in-out infinite alternate 0.7s;">🌟</span>
<span class="e" style="font-size:22px; opacity:0.15; bottom:40%; left:20%; animation: bob 2.5s ease-in-out infinite;">🔭</span>
<span class="e" style="font-size:60px; opacity:0.15; top:55%; left:3%; animation: floatSpace 5s ease-in-out infinite 1s;">👨‍🚀</span>
<span class="e" style="font-size:35px; opacity:0.12; top:70%; right:5%; animation: orbit 8s linear infinite 2s;">🪐</span>
@endsection
