@extends('layouts.app')

@section('content')

<div class="row justify-content-center">
    <div class="col-md-6">

        {{-- TOAST --}}
        <div id="toast" class="alert alert-success d-none mb-3">
             Welcome to NiC! You are signed in!
        </div>

        <div class="card shadow p-4">
            <h3 class="mb-4 fw-bold">NIC SIGN IN</h3>

            <div class="mb-3">
                <label class="form-label">Email Address</label>
                <input
                    type="email"
                    id="email"
                    class="form-control"
                    placeholder="Enter your email"
                />
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <input
                    type="password"
                    id="password"
                    class="form-control"
                    placeholder="Enter your password"
                />
            </div>

            <button onclick="fakeSignIn()" class="btn btn-dark w-100">
                Sign In
            </button>

        </div>
    </div>
</div>

{{-- JavaScript handles everything --}}
<script>
function fakeSignIn() {
    const email    = document.getElementById('email').value
    const password = document.getElementById('password').value

    // Basic check
    if (email === '' || password === '') {
        alert('Please fill all fields!')
        return
    }

    document.getElementById('toast').classList.remove('d-none')

    setTimeout(function() {
        window.location.href = '/form'
    }, 2000)
}
</script>

@endsection
