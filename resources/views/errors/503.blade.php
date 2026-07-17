@extends('errors.layout')

@section('title', 'Service Unavailable')
@section('code', '503')
@section('image', asset('images/errors/503.webp'))
@section('message', 'We\'re down for maintenance. Be back shortly!')
