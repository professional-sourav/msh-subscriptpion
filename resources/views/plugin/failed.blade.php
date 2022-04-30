@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
               <h1>Hey, {{ Auth::user()->name }}!</h1>
               <h4>We have not found any license in your account</h4>

               <p>Please purchase one of the subscription and continue.</p>
               <span><a href="{{ route("plans") }}">Click here</a> to see the available plans.</span>
            </div>
        </div>
    </div>
</div>
@endsection
