@extends('layouts.app')
@section('content')

{{-- Loader overlay — same style as otp.blade.php --}}
<div id="loader-overlay" style="
    display: none;
    position: fixed; inset: 0;
    background: rgba(10, 17, 30, 0.85);
    z-index: 9999;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    gap: 16px;
">
    <div style="
        width: 52px; height: 52px;
        border: 5px solid #1e2a38;
        border-top-color: #00bfff;
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
    "></div>
    <span style="color:#00bfff; font-family: monospace; font-size: 0.9rem; letter-spacing:1px;">
        Resetting Password...
    </span>
</div>
<style>@keyframes spin { to { transform: rotate(360deg); } }</style>

<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow p-4 border-0" style="background-color: #1e2a38;">

            <h3 class="mb-1 fw-bold text-white">Reset Password</h3>
            <p class="text-secondary mb-4" style="font-size:0.9rem;">
                OTP verified. Enter your new password below.
            </p>

            @if(session('error'))
                <div class="alert alert-danger py-2">{{ session('error') }}</div>
            @endif

            {{-- Client-side error box --}}
            <div id="error-box" class="alert alert-danger py-2" style="display:none;"></div>

            <form id="reset-form" action="/reset-password" method="POST" novalidate>
                @csrf

                {{-- New Password --}}
                <div class="mb-3">
                    <label class="form-label text-secondary">
                        <i class="bi bi-lock me-1"></i>New Password
                    </label>
                    <input
                        type="password"
                        name="password"
                        id="password"
                        class="form-control bg-dark text-white border-secondary @error('password') is-invalid @enderror"
                        placeholder="Min 6 characters"
                    />
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Confirm Password --}}
                <div class="mb-4">
                    <label class="form-label text-secondary">
                        <i class="bi bi-lock-fill me-1"></i>Confirm Password
                    </label>
                    <input
                        type="password"
                        name="password_confirmation"
                        id="password_confirmation"
                        class="form-control bg-dark text-white border-secondary"
                        placeholder="Repeat your new password"
                    />
                    {{-- No @error needed — Laravel's confirmed rule reports on password field --}}
                </div>

                <button type="submit" id="reset-btn" class="btn btn-info w-100 fw-bold" style="color:#fff;">
                    <i class="bi bi-check-circle me-2"></i>Reset Password
                </button>

            </form>

            <div class="text-center mt-3">
                <a href="/signin" class="text-secondary" style="font-size:0.85rem;">
                    ← Back to Sign In
                </a>
            </div>

        </div>
    </div>
</div>

<script>
    document.getElementById('reset-form').addEventListener('submit', function (e) {
        const errorBox    = document.getElementById('error-box');
        const password    = document.getElementById('password').value;
        const confirm     = document.getElementById('password_confirmation').value;

        // ── Client-side checks before hitting server ─
        if (password.length < 6) {
            e.preventDefault();
            errorBox.textContent = 'Password must be at least 6 characters.';
            errorBox.style.display = 'block';
            return;
        }

        if (password !== confirm) {
            e.preventDefault();
            errorBox.textContent = 'Passwords do not match.';
            errorBox.style.display = 'block';
            return;
        }

        // ── All good — show loader while form submits ─
        errorBox.style.display = 'none';
        document.getElementById('loader-overlay').style.display = 'flex';
        document.getElementById('reset-btn').disabled = true;
    });
</script>

@endsection
