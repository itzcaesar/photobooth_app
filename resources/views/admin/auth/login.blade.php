@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h3 class="mb-3">Admin Login</h3>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="/admin/login" method="POST">
        @csrf
        <div class="mb-2">
            <label>Email Address</label>
            <input type="email" name="email" class="form-control">
        </div>

        <div class="mb-2">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <button class="btn btn-primary">Login</button>
    </form>
</div>
@endsection