@extends('layouts.app')

@section('title', 'Edit Data Solusi')

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
                        <h4 class="card-title">FORM EDIT DATA SOLUSI</h4> <br>
                        <form action="{{ route('admin.solusi.update',['id' => $data->id]) }}" method="POST" class="forms-sample">
                            @csrf
                            <div class="form-group row" hidden>
                                <label for="kode" class="col-sm-3 col-form-label">Kode</label>
                                <div class="col-sm-9">
                                    <select name="kode" class="form-control">
                                        @foreach($data2 as $d2)
                                            <option value="{{ $d2->id }}" 
                                                    @if($d2->id == $data->idpenyakit) selected @endif>
                                                {{ $d2->kd_penyakit }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('kode')
                                        <small>{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="idpenyakit" class="col-sm-3 col-form-label">Kode Penyakit</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" value="{{ $data->penyakit->kd_penyakit ?? '' }}" readonly>
                                    <input type="hidden" name="idpenyakit" value="{{ $data->idpenyakit }}">
                                    
                                    @error('idpenyakit')
                                        <small>{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label for="exampleInputPassword2" class="col-sm-3 col-form-label">Solusi</label>
                                <div class="col-sm-9">
                                    <textarea name="solusi" class="form-control" rows="3" placeholder="Solusi">{{ $data->solusi }}</textarea>
                                    @error('solusi')
                                        <small>{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-primary mr-2">Submit</button>
                            <a href="{{ route('admin.solusi') }}" class="btn btn-danger mdi mdi-delete">Kembali</a>
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
