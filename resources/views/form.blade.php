@extends('layouts.app')  {{-- use master layout --}}

@section('content')

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow p-4">

            <h3 class="mb-4 fw-bold">NiC Registration Form</h3>

            {{-- Show success message --}}
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Show validation errors --}}
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- FORM STARTS --}}
            <form action="/submit" method="POST">
                @csrf  {{-- MUST HAVE --}}

                {{-- Name --}}
                <div class="mb-3">
                    <label class="form-label">Full Name</label>
                    <input
                        type="text"
                        name="name"
                        class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name') }}"
                        placeholder="Enter your name"
                    />
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Email --}}
                <div class="mb-3">
                    <label class="form-label">Email Address</label>
                    <input
                        type="email"
                        name="email"
                        class="form-control @error('email') is-invalid @enderror"
                        value="{{ old('email') }}"
                        placeholder="Enter your email"
                    />
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Phone --}}
                <div class="mb-3">
                    <label class="form-label">Phone Number</label>
                    <input
                        type="text"
                        name="phone"
                        class="form-control @error('phone') is-invalid @enderror"
                        value="{{ old('phone') }}"
                        placeholder="Enter your phone"
                    />
                    @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Message --}}
                <div class="mb-3">
                    <label class="form-label">Message</label>
                    <textarea
                        name="message"
                        rows="4"
                        class="form-control @error('message') is-invalid @enderror"
                        placeholder="Write your message"
                    >{{ old('message') }}</textarea>
                    @error('message')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Submit Button --}}
                <button type="submit" class="btn btn-dark w-100">
                    Submit Form
                </button>

            </form>
            {{-- FORM ENDS --}}

        </div>
    </div>
</div>

@endsection
