@extends('errors.layout')

@section('title', 'Session Expired')
@section('code', '419')
@section('image', asset('images/errors/419.webp'))
@section('message', 'Your session has expired. Please refresh and try again.')
