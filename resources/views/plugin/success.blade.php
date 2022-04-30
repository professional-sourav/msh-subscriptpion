@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <form role="form" action="" method="POST">
                    {{ csrf_field() }}
                    <h1>Hey, {{ Auth::user()->name }}! Let's Get You Hooked Up</h1>
                    <h4>Connect & Activate Your License</h4>

                    <p>We found your license key and all is looking as it should!</p>
                    <input type="hidden" name="_activate_plugin" value="{{ $url }}">
                    <input type="hidden" name="_active_subscription_id" value="{{ base64_encode($subscription->id) }}">
                    <button class="btn btn-primary" type="submit" id="btn_activate_plugin">Activate</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
