@extends('layouts.app')

@section('title', 'Edit Data Pertanyaan')

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
                        <h4 class="card-title">FORM EDIT DATA PERTANYAAN</h4> <br>
                        <form action="{{ route('admin.pertanyaan.update',['id' => $data->id]) }}" method="POST" class="forms-sample">
                            @csrf
                            <div class="form-group row">
                                <label for="kode" class="col-sm-3 col-form-label">Kode</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" value="{{ $data2->firstWhere('id', $data->idgejala)->kd_gejala ?? '' }}" readonly>
                                    <input type="hidden" name="idgejala" value="{{ $data->idgejala }}">
                                    @error('idgejala')
                                        <small>{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label for="exampleInputPassword2" class="col-sm-3 col-form-label">Pertanyaan</label>
                                <div class="col-sm-9">
                                    <textarea name="pertanyaan" class="form-control" rows="3" placeholder="Masukkan pertanyaan">{{ $data->pertanyaan }}</textarea>
                                    @error('pertanyaan')
                                        <small>{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-primary mr-2">Submit</button>
                            <a href="{{ route('admin.pertanyaan') }}" class="btn btn-danger mdi mdi-delete">Kembali</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="{{ asset('vendors/typeahead.js/typeahead.bundle.min.js') }}"></script>
    <script src="{{ asset('vendors/select2/select2.min.js') }}"></script>
    <script src="{{ asset('js/file-upload.js') }}"></script>
    <script src="{{ asset('js/typeahead.js') }}"></script>
    <script src="{{ asset('js/select2.js') }}"></script>
@endpush
