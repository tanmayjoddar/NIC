@extends('layouts.app')
@section('content')

{{-- ── Full-screen Loader Overlay ─────────────────────────────────── --}}
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
        Verifying...
    </span>
</div>
<style>
    @keyframes spin { to { transform: rotate(360deg); } }
</style>

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

            <div id="ajax-error" class="alert alert-danger py-2" style="display:none;"></div>

            <form id="signin-form" action="/signin" method="POST" novalidate>
                @csrf
                <div class="mb-3">
                    <label class="form-label text-secondary">Email</label>
                    <input type="email" name="email" id="email"
                        class="form-control bg-dark text-white border-secondary"
                        value="{{ old('email') }}"
                        placeholder="Enter email"/>
                </div>
                <div class="mb-3">
                    <label class="form-label text-secondary">Password</label>
                    <input type="password" name="password" id="password"
                        class="form-control bg-dark text-white border-secondary"
                        placeholder="Enter password"/>

                    {{-- ← ONLY NEW LINE ADDED --}}
                    <div class="text-end mt-1">
                        <a href="/forget-password" class="text-info" style="font-size:0.82rem;">
                            Forgot Password?
                        </a>
                    </div>
                </div>

                {{-- ── CAPTCHA ──────────────────────────────────────── --}}
                <div class="mb-3">
                    <label class="form-label text-secondary">CAPTCHA</label>
                    <div class="d-flex align-items-center gap-3 mb-2">
                        <img id="captcha-img" src="/captcha" alt="CAPTCHA"
                            style="border-radius:6px; cursor:pointer; height:45px;"
                            title="Click to refresh"/>
                        <button type="button" class="btn btn-sm btn-outline-secondary"
                            onclick="refreshCaptcha()">⟳ Refresh</button>
                    </div>
                    <input type="text" name="captcha" id="captcha"
                        class="form-control bg-dark text-white border-secondary"
                        placeholder="Type the answer" autocomplete="off"/>
                </div>

                <button type="submit" id="submit-btn" class="btn btn-primary w-100">
                    Sign In
                </button>
                <p class="mt-3 text-center text-secondary">
                    No account? <a href="/signup" class="text-info">Sign Up</a>
                </p>
            </form>
        </div>
    </div>
</div>

<script>
    function refreshCaptcha() {
        document.getElementById('captcha-img').src = '/captcha?r=' + Date.now();
        const f = document.getElementById('captcha');
        f.value = '';
        f.focus();
    }
    document.getElementById('captcha-img').addEventListener('click', refreshCaptcha);

    document.getElementById('signin-form').addEventListener('submit', async function (e) {
        e.preventDefault();

        const overlay   = document.getElementById('loader-overlay');
        const errorBox  = document.getElementById('ajax-error');
        const btn       = document.getElementById('submit-btn');

        overlay.style.display = 'flex';
        btn.disabled = true;
        errorBox.style.display = 'none';

        const formData = new FormData(this);

        try {
            const res = await fetch('/signin', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                },
                body: formData,
            });

            const data = await res.json();

            if (data.success) {
                window.location.href = data.redirect;
            } else {
                overlay.style.display = 'none';
                btn.disabled = false;
                errorBox.textContent = data.message || 'Something went wrong.';
                errorBox.style.display = 'block';
                refreshCaptcha();
            }
        } catch (err) {
            overlay.style.display = 'none';
            btn.disabled = false;
            errorBox.textContent = 'Network error. Please try again.';
            errorBox.style.display = 'block';
        }
    });
</script>

@endsection
