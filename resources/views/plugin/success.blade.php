@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
               <h1>Hey, {{ Auth::user()->name }}! Let's Get You Hooked Up</h1>
               <h4>Connect & Activate Your License</h4>

               <p>We found your license key and all is looking as it should!</p>
               <a href="{{ $url }}">We found your license key and all is looking as it should!</a>
            </div>
        </div>
    </div>
</div>
@endsection
