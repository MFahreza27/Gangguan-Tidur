@extends('layouts.app')

@section('title', 'Tambah Pertanyaan')

@push('style')
    <link rel="stylesheet" href="{{ asset('vendors/select2/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/select2-bootstrap-theme/select2-bootstrap.min.css') }}">
@endpush


@section('main')
    <div class="content-wrapper">
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">FORM TAMBAH PERTANYAAN</h4><br>
                        <form action="{{ route('admin.pertanyaan.store') }}" method="POST" enctype="multipart/form-data" class="forms-sample">
                            @csrf
                            <div class="form-group row">
                                <label for="kode" class="col-sm-3 col-form-label">Kode</label>
                                <div class="col-sm-9">
                                    <select name="idgejala" class="form-control">
                                        @foreach($data2 as $d2)
                                            <option value="{{ $d2->id }}">{{ $d2->kd_gejala }}</option>
                                        @endforeach
                                    </select>
                                    @error('idgejala')
                                        <small>{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="exampleInputPassword2" class="col-sm-3 col-form-label">Pertanyaan</label>
                                <div class="col-sm-9">
                                    <textarea name="pertanyaan" class="form-control" rows="3" placeholder="Pertanyaan"></textarea>
                                    @error('pertanyaan')
                                        <small>{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary mr-2">Submit</button>
                            <a href="{{ route('admin.gejala') }}" class="btn btn-danger mdi mdi-delete">Kembali</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if ($errors->any())
        <script>
            Swal.fire({
                icon: "error",
                title: "Terjadi Kesalahan!",
                html: "{!! implode('<br>', $errors->all()) !!}",
                confirmButtonColor: "#d33",
                padding: '1.5rem',
                confirmButtonColor: '#d33',
            });
        </script>
    @endif
    
    @if (session('success'))
        <script>
            Swal.fire({
                icon: "success",
                title: "Berhasil!",
                text: "{{ session('success') }}",
                confirmButtonColor: "#28a745",
                padding: '1.5rem',
                confirmButtonColor: '#d33',
            });
        </script>
    @endif
@endsection

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @if ($errors->any())
    <script>
        Swal.fire({
            icon: "error",
            title: "Terjadi Kesalahan!",
            html: "{!! implode('<br>', $errors->all()) !!}",
            confirmButtonColor: "#d33",
            padding: '1.5rem',
            confirmButtonColor: '#d33',
        });
    </script>
@endif

@if (session('success'))
    <script>
        Swal.fire({
            icon: "success",
            title: "Berhasil!",
            text: "{{ session('success') }}",
            confirmButtonColor: "#28a745",
            padding: '1.5rem',
            confirmButtonColor: '#d33',
        });
    </script>
@endif


@push('script')
    <script src="{{ asset('vendors/typeahead.js/typeahead.bundle.min.js') }}"></script>
    <script src="{{ asset('vendors/select2/select2.min.js') }}"></script>
    <script src="{{ asset('js/file-upload.js') }}"></script>
    <script src="{{ asset('js/typeahead.js') }}"></script>
    <script src="{{ asset('js/select2.js') }}"></script>
@endpush
