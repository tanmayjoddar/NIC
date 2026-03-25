@extends('layouts.app')
@section('content')

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow p-4 border-0" style="background-color: #1e2a38;">
            <h3 class="mb-4 fw-bold text-white">Welcome Back</h3>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-warning">{{ session('error') }}</div>
            @endif
            @if($errors->any())
                <div class="alert alert-warning">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </div>
            @endif

            <form action="/signin" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label text-secondary">Email</label>
                    <input type="email" name="email"
                        class="form-control bg-dark text-white border-secondary @error('email') is-invalid @enderror"
                        value="{{ old('email') }}"
                        placeholder="Enter email"/>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label text-secondary">Password</label>
                    <input type="password" name="password"
                        class="form-control bg-dark text-white border-secondary @error('password') is-invalid @enderror"
                        placeholder="Enter password"/>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary w-100">Sign In</button>
                <p class="mt-3 text-center text-secondary">No account? <a href="/signup" class="text-info">Sign Up</a></p>
            </form>
        </div>
    </div>
</div>
@endsection
