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
        Verifying OTP...
    </span>
</div>
<style>@keyframes spin { to { transform: rotate(360deg); } }</style>

<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow p-4 border-0" style="background-color: #1e2a38;">
            <h3 class="mb-1 fw-bold text-white">Two-Factor Verification</h3>
            <p class="text-secondary mb-4" style="font-size:0.9rem;">
                An OTP has been generated for your session.<br>
                Check the <code>otp_tokens</code> table in the database, copy the 6-digit OTP and enter it below.
            </p>

            {{-- Flash messages (server-side fallback) --}}
            @if(session('success'))
                <div class="alert alert-success py-2">{{ session('success') }}</div>
            @endif

            {{-- AJAX error/success box --}}
            <div id="ajax-error" class="alert alert-danger py-2" style="display:none;"></div>

            <form id="otp-form" action="/otp" method="POST" novalidate>
                @csrf
                <div class="mb-4">
                    <label class="form-label text-secondary fw-semibold">Enter 6-Digit OTP</label>
                    <input
                        type="text"
                        name="otp"
                        id="otp"
                        maxlength="6"
                        inputmode="numeric"
                        pattern="\d{6}"
                        autocomplete="one-time-code"
                        class="form-control form-control-lg bg-dark text-white border-secondary text-center fw-bold"
                        placeholder="000000"
                        style="letter-spacing: 0.5rem; font-size: 1.5rem;"
                    />
                </div>

                <button type="submit" id="otp-btn" class="btn btn-info w-100 fw-bold" style="color:#fff;">
                    Verify OTP &amp; Continue →
                </button>
            </form>

            <div class="text-center mt-3">
                <a href="/signin" class="text-secondary" style="font-size:0.85rem;">← Back to Sign In</a>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('otp-form').addEventListener('submit', async function (e) {
        e.preventDefault();

        const overlay  = document.getElementById('loader-overlay');
        const errorBox = document.getElementById('ajax-error');
        const btn      = document.getElementById('otp-btn');
        const otpInput = document.getElementById('otp');

        if (otpInput.value.trim().length !== 6) {
            errorBox.textContent = 'Please enter a 6-digit OTP.';
            errorBox.style.display = 'block';
            otpInput.focus();
            return;
        }

        overlay.style.display = 'flex';
        btn.disabled = true;
        errorBox.style.display = 'none';

        const formData = new FormData(this);

        try {
            const res = await fetch('/otp', {
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
                // Keep spinner on while redirecting to /form
                window.location.href = data.redirect;
            } else {
                overlay.style.display = 'none';
                btn.disabled = false;
                errorBox.textContent = data.message || 'Verification failed.';
                errorBox.style.display = 'block';
                otpInput.value = '';
                otpInput.focus();
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

