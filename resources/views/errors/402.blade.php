@extends('errors.layout')

@section('title', 'Payment Required')
@section('code', '402')
@section('image', asset('images/errors/402.webp'))
@section('message', 'This one costs money. And no, IOUs don\'t count.')

@section('animation')
<style>
    @keyframes spinCoin {
        0%, 100% { transform: rotateY(0deg) scale(1); }
        25% { transform: rotateY(180deg) scale(1.15); }
        50% { transform: rotateY(360deg) scale(1); }
        75% { transform: rotateY(540deg) scale(1.15); }
    }
    @keyframes reach {
        0%, 100% { transform: translateX(0) rotate(0deg); }
        50% { transform: translateX(40px) rotate(12deg); }
    }
    @keyframes shrug {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-8px); }
    }
    @keyframes driftCoin {
        0%, 100% { transform: translate(0, 0); }
        33% { transform: translate(15px, -20px); }
        66% { transform: translate(-10px, 10px); }
    }
</style>
<span class="e" style="font-size:56px; opacity:0.2; top:6%; left:4%; animation: spinCoin 3s ease-in-out infinite, driftCoin 10s ease-in-out infinite;">🪙</span>
<span class="e" style="font-size:50px; opacity:0.18; top:15%; right:8%; animation: reach 2.5s ease-in-out infinite;">💸</span>
<span class="e" style="font-size:40px; opacity:0.15; bottom:30%; left:3%; animation: shrug 2.5s ease-in-out infinite;">🤷</span>
<span class="e" style="font-size:42px; opacity:0.15; bottom:10%; right:5%; animation: spinCoin 3.5s ease-in-out infinite 0.5s;">🪙</span>
<span class="e" style="font-size:36px; opacity:0.12; top:45%; left:10%; animation: reach 3s ease-in-out infinite 0.7s;">💸</span>
<span class="e" style="font-size:34px; opacity:0.12; bottom:40%; right:12%; animation: shrug 3s ease-in-out infinite 1s;">🤷</span>
<span class="e" style="font-size:44px; opacity:0.15; top:60%; left:2%; animation: spinCoin 4s ease-in-out infinite 1.2s;">🪙</span>
@endsection
