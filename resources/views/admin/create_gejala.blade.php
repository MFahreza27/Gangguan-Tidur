@extends('layouts.app')

@section('title', 'Tambah Data Gejala')

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
                        <h4 class="card-title">FORM TAMBAH DATA GEJALA</h4><hr><br>
                        <form action="{{ route('admin.gejala.store') }}" method="POST" enctype="multipart/form-data" class="forms-sample">
                            @csrf
                            <div class="form-group row">
                                <label for="exampleInputUsername2" class="col-sm-3 col-form-label">Kode</label>
                                <div class="col-sm-9">
                                    <input type="text" name="kode" class="form-control"  placeholder="Kode gejala">
                                    @error('kode')
                                        <small>{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="exampleInputPassword2" class="col-sm-3 col-form-label">Deskripsi</label>
                                <div class="col-sm-9">
                                    <textarea name="deskripsi" class="form-control" rows="3" placeholder="Deskripsi"></textarea>
                                    @error('deskripsi')
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

@if(Session::has('error'))
    <script>
        Swal.fire({
            icon: "error",
            title: "Terjadi Kesalahan!",
            text: "{{ Session::get('error') }}",
            confirmButtonColor: "#d33",
            padding: '1.5rem',
            confirmButtonColor: '#d33',
        });
    </script>
@endif

@if(Session::has('success'))
    <script>
        Swal.fire({
            icon: "success",
            title: "Berhasil!",
            text: "{{ Session::get('success') }}",
            confirmButtonColor: "#3085d6",
            padding: '1.5rem',
            confirmButtonColor: '#d33',
        });
    </script>
@endif

@endsection


@push('script')
    <script src="{{ asset('vendors/typeahead.js/typeahead.bundle.min.js') }}"></script>
    <script src="{{ asset('vendors/select2/select2.min.js') }}"></script>
    <script src="{{ asset('js/file-upload.js') }}"></script>
    <script src="{{ asset('js/typeahead.js') }}"></script>
    <script src="{{ asset('js/select2.js') }}"></script>
@endpush
