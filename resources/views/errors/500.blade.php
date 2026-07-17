@extends('errors.layout')

@section('title', 'Server Error')
@section('code', '500')
@section('image', asset('images/errors/500.webp'))
@section('message', 'Something went wrong on our end. Please try again later.')
