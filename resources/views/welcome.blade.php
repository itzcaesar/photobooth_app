@extends('layouts.app')

@section('content')
<div class="text-center py-5">
  <h1>Welcome to Poselab!</h1>
  <p class="lead">Snap a photo, save it, and share the fun!</p>
  <a href="{{ url('/capture') }}" class="btn btn-primary btn-lg">Start Photo</a>
</div>
@endsection
