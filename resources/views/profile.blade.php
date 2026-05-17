@extends('layouts.app')

@section('title', 'Edit Profil')

@push('style')
    <link rel="stylesheet" href="{{ asset('vendors/mdi/css/materialdesignicons.min.css') }}">
    <style>
        .avatar-wrap {
            position: relative;
            width: 120px; height: 120px;
            margin: 0 auto 16px;
        }
        .avatar-wrap img {
            width: 120px; height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #e8f0fe;
            box-shadow: 0 4px 14px rgba(26,115,232,.2);
        }
        .avatar-wrap .avatar-edit {
            position: absolute;
            bottom: 4px; right: 4px;
            width: 32px; height: 32px;
            background: #1a73e8;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer;
            box-shadow: 0 2px 6px rgba(0,0,0,.2);
        }
        .avatar-wrap .avatar-edit i { color: #fff; font-size: 15px; }
        .avatar-wrap input[type=file] { display: none; }

        .profile-sidebar {
            text-align: center;
            padding: 30px 20px;
            border-right: 1px solid #f0f0f0;
        }
        .profile-sidebar .user-name  { font-size: 1.1rem; font-weight: 700; color: #222; margin-top: 8px; }
        .profile-sidebar .user-email { font-size: .82rem; color: #888; }
        .profile-sidebar .user-role  {
            display: inline-block;
            background: #e8f0fe; color: #1a73e8;
            border-radius: 20px; padding: 2px 14px;
            font-size: .75rem; font-weight: 600;
            margin-top: 6px;
        }

        .form-section-title {
            font-size: .75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .8px;
            color: #aaa;
            margin-bottom: 14px;
            padding-bottom: 6px;
            border-bottom: 1px solid #f0f0f0;
        }
        .form-control:focus { border-color: #1a73e8; box-shadow: 0 0 0 3px rgba(26,115,232,.12); }
        .input-icon-wrap { position: relative; }
        .input-icon-wrap .mdi {
            position: absolute; left: 12px; top: 50%; transform: translateY(-50%);
            color: #aaa; font-size: 16px;
        }
        .input-icon-wrap input { padding-left: 36px; }
    </style>
@endpush

@section('main')
<div class="content-wrapper">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="card border-0 shadow-sm rounded overflow-hidden">
                <div class="row no-gutters">

                    {{-- Sidebar Profil --}}
                    <div class="col-md-3 bg-light">
                        <div class="profile-sidebar">
                            <div class="avatar-wrap">
                                <img src="{{ asset('storage/' . ($data1->image ?? 'SIGATUR.PNG')) }}"
                                     alt="Foto Profil" id="avatarPreview">
                                <label class="avatar-edit" for="fotoInput" title="Ganti foto">
                                    <i class="mdi mdi-camera"></i>
                                </label>
                            </div>
                            <div class="user-name">{{ $data1->nama }}</div>
                            <div class="user-email">{{ $data1->email }}</div>
                            <div class="user-role">{{ ucfirst($data1->role ?? 'user') }}</div>

                            <hr class="my-3">
                            <p class="text-muted" style="font-size:.78rem; line-height:1.5;">
                                Klik ikon kamera untuk mengganti foto profil Anda.
                            </p>
                        </div>
                    </div>

                    {{-- Form --}}
                    <div class="col-md-9">
                        <div class="card-body p-4">
                            <h5 class="font-weight-bold mb-4">
                                <i class="mdi mdi-account-edit text-primary mr-2"></i>Edit Profil
                            </h5>

                            <form action="{{ route('profile.update', ['id' => $data1->id]) }}"
                                  method="POST" enctype="multipart/form-data">
                                @csrf

                                {{-- Hidden file input --}}
                                <input type="file" name="foto" id="fotoInput"
                                       accept="image/png,image/jpeg"
                                       onchange="previewAvatar(this)">

                                {{-- Info Dasar --}}
                                <div class="form-section-title">Informasi Dasar</div>

                                <div class="form-group">
                                    <label class="text-muted small font-weight-semibold">Nama Lengkap</label>
                                    <div class="input-icon-wrap">
                                        <i class="mdi mdi-account-outline"></i>
                                        <input type="text" name="nama"
                                               class="form-control @error('nama') is-invalid @enderror"
                                               value="{{ old('nama', $data1->nama) }}"
                                               placeholder="Nama lengkap">
                                    </div>
                                    @error('nama')<small class="text-danger">{{ $message }}</small>@enderror
                                </div>

                                <div class="form-group">
                                    <label class="text-muted small font-weight-semibold">Instansi</label>
                                    <div class="input-icon-wrap">
                                        <i class="mdi mdi-office-building-outline"></i>
                                        <input type="text" name="instansi"
                                               class="form-control"
                                               value="{{ old('instansi', $data1->instansi) }}"
                                               placeholder="Nama instansi / rumah sakit">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="text-muted small font-weight-semibold">Email</label>
                                    <div class="input-icon-wrap">
                                        <i class="mdi mdi-email-outline"></i>
                                        <input type="email" class="form-control bg-light"
                                               value="{{ $data1->email }}" disabled>
                                    </div>
                                    <small class="text-muted">Email tidak dapat diubah.</small>
                                </div>

                                {{-- Keamanan --}}
                                <div class="form-section-title mt-4">Keamanan</div>

                                <div class="form-group">
                                    <label class="text-muted small font-weight-semibold">
                                        Password Baru <span class="text-muted">(kosongkan jika tidak ingin mengubah)</span>
                                    </label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-white">
                                                <i class="mdi mdi-lock-outline text-muted"></i>
                                            </span>
                                        </div>
                                        <input type="password" name="password" id="pwdInput"
                                               class="form-control @error('password') is-invalid @enderror"
                                               placeholder="Password baru">
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-outline-secondary"
                                                    onclick="togglePwd()" tabindex="-1">
                                                <i class="mdi mdi-eye" id="pwdIcon"></i>
                                            </button>
                                        </div>
                                    </div>
                                    @error('password')<small class="text-danger">{{ $message }}</small>@enderror
                                </div>

                                {{-- Tombol --}}
                                <div class="d-flex align-items-center justify-content-between mt-4">
                                    <a href="{{ route('dashboard') }}"
                                       class="btn btn-outline-secondary px-4" style="border-radius:20px;">
                                        <i class="mdi mdi-arrow-left mr-1"></i> Kembali
                                    </a>
                                    <button type="submit" class="btn btn-primary px-4" style="border-radius:20px;">
                                        <i class="mdi mdi-content-save-outline mr-1"></i> Simpan Perubahan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
    function previewAvatar(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = e => document.getElementById('avatarPreview').src = e.target.result;
            reader.readAsDataURL(input.files[0]);
        }
    }
    function togglePwd() {
        const i = document.getElementById('pwdInput');
        const ic = document.getElementById('pwdIcon');
        if (i.type === 'password') {
            i.type = 'text';
            ic.classList.replace('mdi-eye', 'mdi-eye-off');
        } else {
            i.type = 'password';
            ic.classList.replace('mdi-eye-off', 'mdi-eye');
        }
    }
</script>

@if($errors->any())
<script>
    Swal.fire({
        icon: 'error',
        title: 'Terjadi Kesalahan',
        html: '{{ implode("<br>", $errors->all()) }}',
        confirmButtonColor: '#d33',
    });
</script>
@endif

@if(session('success'))
<script>
    const Toast = Swal.mixin({
        toast: true, position: 'top-end',
        showConfirmButton: false, timer: 3000, timerProgressBar: true,
    });
    Toast.fire({ icon: 'success', title: '{{ session("success") }}' });
</script>
@endif
@endpush
