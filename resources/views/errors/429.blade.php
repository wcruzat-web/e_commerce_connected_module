@extends('errors.layout')

@section('title', 'Too Many Requests')
@section('code', '429')
@section('image', asset('images/errors/429.webp'))
@section('message', 'You\'ve made too many requests. Please wait a moment and try again.')
