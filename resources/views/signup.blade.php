@extends('layouts.app')
@section('content')

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow p-4 border-0" style="background-color: #1e2a38;">
            <h3 class="mb-4 fw-bold text-white">Create Account</h3>

            @if($errors->any())
                <div class="alert alert-warning">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </div>
            @endif

            <form action="/signup" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label text-secondary">Full Name</label>
                    <input type="text" name="name"
                        class="form-control bg-dark text-white border-secondary @error('name') is-invalid @enderror"
                        value="{{ old('name') }}" placeholder="Enter your name"/>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label text-secondary">Email</label>
                    <input type="email" name="email"
                        class="form-control bg-dark text-white border-secondary @error('email') is-invalid @enderror"
                        value="{{ old('email') }}" placeholder="Enter your email"/>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label text-secondary">Phone</label>
                    <input type="text" name="phone"
                        class="form-control bg-dark text-white border-secondary @error('phone') is-invalid @enderror"
                        value="{{ old('phone') }}" placeholder="Enter your phone"/>
                    @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label text-secondary">Password</label>
                    <input type="password" name="password"
                        class="form-control bg-dark text-white border-secondary @error('password') is-invalid @enderror"
                        placeholder="Min 6 characters"/>
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <button type="submit" class="btn btn-primary w-100">Sign Up</button>
                <p class="mt-3 text-center text-secondary">Have an account? <a href="/signin" class="text-info">Sign In</a></p>
            </form>
        </div>
    </div>
</div>
@endsection
