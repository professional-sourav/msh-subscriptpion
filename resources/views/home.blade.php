@extends('layouts.app')

@section('content')
<div class="container">
    <div class="justify-content-center">
        <div class="row">
            @if ( $subscriptions )              
                    
                @foreach( $subscriptions as $subscription )
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-header">{{ Str::title($subscription->name) }}</div>
            
                            <div class="card-body">
                                <a 
                                href="{{ route("subscription.cancel", ['plan' => $subscription->name]) }}" 
                                class="btn btn-danger">{{ __("Cancel Subscription") }}</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif            
        </div>
    </div>
</div>
@endsection
