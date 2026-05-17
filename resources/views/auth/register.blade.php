@extends('layouts.auth')

@section('title', 'Daftar')

@section('main')
    <h4 class="mb-1 font-weight-bold">Buat Akun Baru</h4>
    <p class="text-muted mb-4" style="font-size:0.9rem;">Isi data di bawah untuk mendaftar</p>

    <form class="pt-2" action="{{ route('registerproses') }}" method="POST">
        @csrf

        <div class="form-group">
            <label class="text-muted small font-weight-semibold">Nama Lengkap</label>
            <input type="text" name="nama"
                class="form-control form-control-lg @error('nama') is-invalid @enderror"
                placeholder="Nama lengkap"
                value="{{ old('nama') }}">
            @error('nama')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label class="text-muted small font-weight-semibold">Email</label>
            <input type="email" name="email"
                class="form-control form-control-lg @error('email') is-invalid @enderror"
                placeholder="contoh@email.com"
                value="{{ old('email') }}">
            @error('email')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label class="text-muted small font-weight-semibold">Instansi <span class="text-muted">(opsional)</span></label>
            <input type="text" name="instansi"
                class="form-control form-control-lg"
                placeholder="Nama instansi / rumah sakit"
                value="{{ old('instansi') }}">
        </div>

        <div class="form-group">
            <label class="text-muted small font-weight-semibold">Password</label>
            <div class="input-group">
                <input type="password" name="password" id="password"
                    class="form-control form-control-lg @error('password') is-invalid @enderror"
                    placeholder="Minimal 6 karakter">
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

        <div class="form-group">
            <label class="text-muted small font-weight-semibold">Konfirmasi Password</label>
            <div class="input-group">
                <input type="password" name="password_confirmation" id="password_confirmation"
                    class="form-control form-control-lg"
                    placeholder="Ulangi password">
                <div class="input-group-append">
                    <button type="button" class="btn btn-outline-secondary" id="toggleConfirm" tabindex="-1"
                        style="border-left:0; border-radius:0 4px 4px 0;">
                        <i class="mdi mdi-eye" id="toggleIconConfirm"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">
                <i class="mdi mdi-account-plus mr-1"></i> Daftar
            </button>
        </div>

        <div class="text-center mt-4 font-weight-light">
            Sudah punya akun? <a href="{{ route('login') }}" class="text-primary font-weight-bold">Masuk di sini</a>
        </div>
    </form>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="{{ asset('vendors/mdi/css/materialdesignicons.min.css') }}">

    @if($errors->any())
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Pendaftaran Gagal',
                html: '{!! nl2br(e(implode("\n", $errors->all()))) !!}',
                confirmButtonColor: '#d33',
            });
        </script>
    @endif

    @if(session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Pendaftaran Gagal',
                text: '{{ session('error') }}',
                confirmButtonColor: '#d33',
            });
        </script>
    @endif

    @if(session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: '{{ session('success') }}',
                timer: 2000,
                showConfirmButton: false,
            });
        </script>
    @endif

    <script>
        function toggleVis(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon  = document.getElementById(iconId);
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('mdi-eye', 'mdi-eye-off');
            } else {
                input.type = 'password';
                icon.classList.replace('mdi-eye-off', 'mdi-eye');
            }
        }
        document.getElementById('togglePassword').addEventListener('click', () => toggleVis('password', 'toggleIcon'));
        document.getElementById('toggleConfirm').addEventListener('click', () => toggleVis('password_confirmation', 'toggleIconConfirm'));
    </script>
@endsection
