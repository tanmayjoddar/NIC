@extends('layouts.app')
@section('content')

{{-- Bootstrap Icons CDN (add once; if already in your layout, remove this line) --}}
@push('head')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
@endpush

<style>
    /* ── Photo preview circle ── */
    #photoPreview {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: #f8f9fa;
        border: 2px dashed #ced4da;
        margin: 0 auto;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        transition: border-color .2s;
    }
    #photoPreview.has-photo {
        border: 2px solid #198754;
    }
    #photoPreview i {
        font-size: 2.4rem;
        color: #adb5bd;
    }
    #photoPreview img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 50%;
    }

    /* ── Camera feed ── */
    #cameraBox video {
        width: 100%;
        border-radius: 8px;
        background: #000;
        display: block;
    }

    /* ── Subtle card lift ── */
    .card {
        border: none;
        border-radius: 1rem;
    }
</style>

<div class="row justify-content-center py-4">
    <div class="col-md-6">
        <div class="card shadow p-4">

            <h3 class="mb-4 fw-bold">Fill the Form</h3>

            {{-- ── Success ── --}}
            @if(session('success'))
                <div class="alert alert-success d-flex align-items-center gap-2">
                    <i class="bi bi-check-circle-fill fs-5"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            {{-- ── Validation errors ── --}}
            @if($errors->any())
                <div class="alert alert-danger d-flex align-items-start gap-2">
                    <i class="bi bi-exclamation-triangle-fill fs-5 mt-1 flex-shrink-0"></i>
                    <ul class="mb-0 ps-0" style="list-style:none;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="/submit" method="POST" id="mainForm" novalidate>
                @csrf

                {{-- ════════════════════════════════
                     PHOTO SECTION
                ════════════════════════════════ --}}
                <div class="mb-4">
                    <label class="form-label fw-semibold">Profile Photo</label>

                    {{-- Circle preview --}}
                    <div class="text-center mb-3">
                        <div id="photoPreview">
                            <i class="bi bi-person-fill"></i>
                        </div>
                    </div>

                    {{-- Action buttons --}}
                    <div class="d-flex gap-2 justify-content-center mb-2">
                        <button type="button"
                                class="btn btn-outline-secondary btn-sm"
                                onclick="document.getElementById('fileInput').click()">
                            <i class="bi bi-upload me-1"></i> Upload Photo
                        </button>

                        <button type="button"
                                class="btn btn-outline-dark btn-sm"
                                id="openCameraBtn"
                                onclick="openCamera()">
                            <i class="bi bi-camera-fill me-1"></i> Enable Camera
                        </button>
                    </div>

                    {{-- Hidden file picker (images only) --}}
                    <input type="file"
                           id="fileInput"
                           accept="image/*"
                           style="display:none"
                           onchange="handleFile(event)">

                    {{-- Camera section --}}
                    <div id="cameraBox" style="display:none;" class="mt-3">
                        <video id="video" autoplay playsinline></video>
                        <div class="d-flex gap-2 mt-2">
                            <button type="button"
                                    class="btn btn-dark btn-sm w-100"
                                    onclick="capturePhoto()">
                                <i class="bi bi-camera me-1"></i> Capture
                            </button>
                            <button type="button"
                                    class="btn btn-outline-secondary btn-sm w-100"
                                    onclick="closeCamera()">
                                <i class="bi bi-x-lg me-1"></i> Cancel
                            </button>
                        </div>
                    </div>

                    {{-- Off-screen canvas used for capture --}}
                    <canvas id="canvas" style="display:none;"></canvas>

                    {{-- Hidden input that carries base64 to Laravel --}}
                    <input type="hidden" name="photo" id="photoBase64">
                </div>
                {{-- ════════════════════════════════
                     END PHOTO SECTION
                ════════════════════════════════ --}}

                {{-- Full Name --}}
                <div class="mb-3">
                    <label class="form-label" for="name">
                        <i class="bi bi-person me-1 text-secondary"></i> Full Name
                    </label>
                    <input type="text"
                           id="name"
                           name="name"
                           class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name') }}"
                           placeholder="Enter your full name"
                           autocomplete="name">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Email --}}
                <div class="mb-3">
                    <label class="form-label" for="email">
                        <i class="bi bi-envelope me-1 text-secondary"></i> Email Address
                    </label>
                    <input type="email"
                           id="email"
                           name="email"
                           class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email') }}"
                           placeholder="you@example.com"
                           autocomplete="email">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Phone --}}
                <div class="mb-3">
                    <label class="form-label" for="phone">
                        <i class="bi bi-telephone me-1 text-secondary"></i> Phone Number
                    </label>
                    <input type="tel"
                           id="phone"
                           name="phone"
                           class="form-control @error('phone') is-invalid @enderror"
                           value="{{ old('phone') }}"
                           placeholder="+91 XXXXX XXXXX"
                           autocomplete="tel">
                    @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- State --}}
                <div class="mb-3">
                    <label class="form-label" for="state_code">
                        <i class="bi bi-geo-alt me-1 text-secondary"></i> State
                    </label>
                    <select id="state_code"
                            name="state_code"
                            class="form-select @error('state_code') is-invalid @enderror">
                        <option value="">Select State</option>
                        @foreach(($states ?? collect()) as $state)
                            <option value="{{ $state->state_code }}" @selected(old('state_code') == $state->state_code)>
                                {{ $state->state_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('state_code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- District --}}
                <div class="mb-3">
                    <label class="form-label" for="district_code">
                        <i class="bi bi-building me-1 text-secondary"></i> District
                    </label>
                    <select id="district_code"
                            name="district_code"
                            class="form-select @error('district_code') is-invalid @enderror"
                            disabled>
                        <option value="">Select District</option>
                    </select>
                    @error('district_code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Sub-district --}}
                <div class="mb-3">
                    <label class="form-label" for="subdistrict_code">
                        <i class="bi bi-signpost me-1 text-secondary"></i> Sub-district
                    </label>
                    <select id="subdistrict_code"
                            name="subdistrict_code"
                            class="form-select @error('subdistrict_code') is-invalid @enderror"
                            disabled>
                        <option value="">Select Sub-district (optional)</option>
                    </select>
                    @error('subdistrict_code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Block --}}
                <div class="mb-3">
                    <label class="form-label" for="block_code">
                        <i class="bi bi-diagram-3 me-1 text-secondary"></i> Block
                    </label>
                    <select id="block_code"
                            name="block_code"
                            class="form-select @error('block_code') is-invalid @enderror"
                            disabled>
                        <option value="">Select Block (optional)</option>
                    </select>
                    @error('block_code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Message --}}
                <div class="mb-4">
                    <label class="form-label" for="message">
                        <i class="bi bi-chat-left-text me-1 text-secondary"></i> Message
                    </label>
                    <textarea id="message"
                              name="message"
                              rows="4"
                              class="form-control @error('message') is-invalid @enderror"
                              placeholder="Write your message here…"
                    >{{ old('message') }}</textarea>
                    @error('message')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Submit --}}
                <button type="submit" class="btn btn-dark w-100" id="submitBtn">
                    <i class="bi bi-send-fill me-2"></i> Submit Form
                </button>

            </form>

        </div>
    </div>
</div>

<script>
    'use strict';

    let cameraStream = null;
    const oldDistrictCode = "{{ old('district_code', '') }}";
    const oldSubdistrictCode = "{{ old('subdistrict_code', '') }}";
    const oldBlockCode = "{{ old('block_code', '') }}";

    /* ── Upload from device → base64 ── */
    function handleFile(event) {
        const file = event.target.files[0];
        if (!file) return;

        // Guard: images only (extra client-side safety)
        if (!file.type.startsWith('image/')) {
            alert('Please select a valid image file.');
            return;
        }

        const reader = new FileReader();
        reader.onload = function (e) { setPhoto(e.target.result); };
        reader.onerror = function ()  { alert('Failed to read the selected file.'); };
        reader.readAsDataURL(file);

        // Reset input so the same file can be re-selected if needed
        event.target.value = '';
    }

    /* ── Open camera ── */
    function openCamera() {
        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            alert('Camera access is not supported in this browser.');
            return;
        }

        document.getElementById('cameraBox').style.display = 'block';
        document.getElementById('openCameraBtn').disabled = true;

        navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user' } })
            .then(function (stream) {
                cameraStream = stream;
                document.getElementById('video').srcObject = stream;
            })
            .catch(function (err) {
                console.error('Camera error:', err);
                alert('Camera access denied. Please allow camera permission and try again.');
                closeCamera();
            });
    }

    /* ── Capture still from camera ── */
    function capturePhoto() {
        const video  = document.getElementById('video');
        const canvas = document.getElementById('canvas');

        if (!video.videoWidth) {
            alert('Camera is not ready yet. Please wait a moment.');
            return;
        }

        canvas.width  = video.videoWidth;
        canvas.height = video.videoHeight;
        canvas.getContext('2d').drawImage(video, 0, 0);

        const base64 = canvas.toDataURL('image/jpeg', 0.85); // quality 85 %
        setPhoto(base64);
        closeCamera();
    }

    /* ── Close / release camera ── */
    function closeCamera() {
        if (cameraStream) {
            cameraStream.getTracks().forEach(function (t) { t.stop(); });
            cameraStream = null;
        }
        document.getElementById('video').srcObject = null;
        document.getElementById('cameraBox').style.display = 'none';
        document.getElementById('openCameraBtn').disabled = false;
    }

    /* ── Set preview + store base64 ── */
    function setPhoto(base64) {
        const preview = document.getElementById('photoPreview');
        preview.innerHTML = '<img src="' + base64 + '" alt="Profile photo preview">';
        preview.classList.add('has-photo');
        document.getElementById('photoBase64').value = base64;
    }

    function resetSelect(selectEl, placeholderText) {
        selectEl.innerHTML = '';
        const option = document.createElement('option');
        option.value = '';
        option.textContent = placeholderText;
        selectEl.appendChild(option);
        selectEl.value = '';
        selectEl.disabled = true;
    }

    function fillSelect(selectEl, items, valueKey, textKey, placeholderText, selectedValue) {
        resetSelect(selectEl, placeholderText);
        items.forEach(function (item) {
            const option = document.createElement('option');
            option.value = item[valueKey];
            option.textContent = item[textKey];
            selectEl.appendChild(option);
        });
        selectEl.disabled = false;
        if (selectedValue) {
            selectEl.value = String(selectedValue);
        }
    }

    function loadDistricts(stateCode, selectedDistrict) {
        const districtSelect = document.getElementById('district_code');
        const subdistrictSelect = document.getElementById('subdistrict_code');
        const blockSelect = document.getElementById('block_code');

        resetSelect(districtSelect, 'Select District');
        resetSelect(subdistrictSelect, 'Select Sub-district (optional)');
        resetSelect(blockSelect, 'Select Block (optional)');

        if (!stateCode) {
            return Promise.resolve();
        }

        return fetch('/api/lgd/districts?state_code=' + encodeURIComponent(stateCode))
            .then(function (res) { return res.json(); })
            .then(function (data) {
                fillSelect(districtSelect, data, 'district_code', 'district_name', 'Select District', selectedDistrict);
            });
    }

    function loadSubdistricts(districtCode, selectedSubdistrict) {
        const subdistrictSelect = document.getElementById('subdistrict_code');

        resetSelect(subdistrictSelect, 'Select Sub-district (optional)');

        if (!districtCode) {
            return Promise.resolve();
        }

        return fetch('/api/lgd/subdistricts?district_code=' + encodeURIComponent(districtCode))
            .then(function (res) { return res.json(); })
            .then(function (data) {
                fillSelect(subdistrictSelect, data, 'subdistrict_code', 'subdistrict_name', 'Select Sub-district (optional)', selectedSubdistrict);
            });
    }

    function loadBlocks(districtCode, selectedBlock) {
        const blockSelect = document.getElementById('block_code');

        resetSelect(blockSelect, 'Select Block (optional)');

        if (!districtCode) {
            return Promise.resolve();
        }

        return fetch('/api/lgd/blocks?district_code=' + encodeURIComponent(districtCode))
            .then(function (res) { return res.json(); })
            .then(function (data) {
                fillSelect(blockSelect, data, 'block_code', 'block_name', 'Select Block (optional)', selectedBlock);
            });
    }

    const stateSelect = document.getElementById('state_code');
    const districtSelect = document.getElementById('district_code');

    stateSelect.addEventListener('change', function () {
        loadDistricts(stateSelect.value, '')
            .catch(function () {
                resetSelect(document.getElementById('district_code'), 'Select District');
                resetSelect(document.getElementById('subdistrict_code'), 'Select Sub-district (optional)');
                resetSelect(document.getElementById('block_code'), 'Select Block (optional)');
            });
    });

    districtSelect.addEventListener('change', function () {
        Promise.all([
            loadSubdistricts(districtSelect.value, ''),
            loadBlocks(districtSelect.value, ''),
        ]).catch(function () {
            resetSelect(document.getElementById('subdistrict_code'), 'Select Sub-district (optional)');
            resetSelect(document.getElementById('block_code'), 'Select Block (optional)');
        });
    });

    if (stateSelect.value) {
        loadDistricts(stateSelect.value, oldDistrictCode)
            .then(function () {
                if (oldDistrictCode) {
                    return Promise.all([
                        loadSubdistricts(oldDistrictCode, oldSubdistrictCode),
                        loadBlocks(oldDistrictCode, oldBlockCode),
                    ]);
                }
                return Promise.resolve();
            })
            .catch(function () {
                resetSelect(document.getElementById('district_code'), 'Select District');
                resetSelect(document.getElementById('subdistrict_code'), 'Select Sub-district (optional)');
                resetSelect(document.getElementById('block_code'), 'Select Block (optional)');
            });
    }

    /* ── Prevent double-submit ── */
    document.getElementById('mainForm').addEventListener('submit', function () {
        const btn = document.getElementById('submitBtn');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Submitting…';
    });

    /* ── Release camera if page is closed/navigated away ── */
    window.addEventListener('beforeunload', function () {
        if (cameraStream) {
            cameraStream.getTracks().forEach(function (t) { t.stop(); });
        }
    });
</script>

@endsection
