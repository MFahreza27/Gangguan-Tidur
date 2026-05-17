@extends('layouts.auth')

@section('title', 'Login')

@section('main')
    <h4 class="mb-1 font-weight-bold">Selamat Datang</h4>
    <p class="text-muted mb-4" style="font-size:0.9rem;">Silakan masuk ke akun Anda</p>

    <form class="pt-2" action="{{ route('loginproses') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="email" class="text-muted small font-weight-semibold">Email</label>
            <input type="email" name="email" id="email"
                class="form-control form-control-lg @error('email') is-invalid @enderror"
                placeholder="contoh@email.com"
                value="{{ old('email') }}"
                autocomplete="email" autofocus>
            @error('email')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label for="password" class="text-muted small font-weight-semibold">Password</label>
            <div class="input-group">
                <input type="password" name="password" id="password"
                    class="form-control form-control-lg @error('password') is-invalid @enderror"
                    placeholder="Masukkan password"
                    autocomplete="current-password">
                <div class="input-group-append">
                    <button type="button" class="btn btn-outline-secondary" id="togglePassword" tabindex="-1"
                        style="border-left:0; border-radius:0 4px 4px 0;">
                        <i class="mdi mdi-eye" id="toggleIcon"></i>
                    </button>
                </div>
            </div>
            @error('password')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">
                <i class="mdi mdi-login mr-1"></i> Masuk
            </button>
        </div>

        <div class="text-center mt-4 font-weight-light">
            Belum punya akun? <a href="{{ route('register') }}" class="text-primary font-weight-bold">Daftar di sini</a>
        </div>
    </form>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="{{ asset('vendors/mdi/css/materialdesignicons.min.css') }}">

    @if($message = Session::get('failed'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Login Gagal',
                text: '{{ $message }}',
                confirmButtonColor: '#d33',
            });
        </script>
    @endif

    @if($message = Session::get('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil Logout',
                text: '{{ $message }}',
                timer: 2000,
                showConfirmButton: false,
            });
        </script>
    @endif

    <script>
        document.getElementById('togglePassword').addEventListener('click', function () {
            const pwd = document.getElementById('password');
            const icon = document.getElementById('toggleIcon');
            if (pwd.type === 'password') {
                pwd.type = 'text';
                icon.classList.replace('mdi-eye', 'mdi-eye-off');
            } else {
                pwd.type = 'password';
                icon.classList.replace('mdi-eye-off', 'mdi-eye');
            }
        });
    </script>
@endsection
