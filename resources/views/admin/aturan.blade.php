@extends('layouts.app')

@section('title', 'Data Aturan')

@push('style')
    <link rel="stylesheet" href="{{ asset('vendors/mdi/css/materialdesignicons.min.css') }}">
@endpush

@section('main')
    <div class="content-wrapper">
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card rounded-0">
                    <div class="card-body">
                        <h4 class="card-title">Data Aturan</h4><hr><br>
                        <form action="{{ route('admin.aturan.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="idpenyakit" value="{{ request()->route('id') }}">
                            <div class="form-group row">
                                <div class="col-sm-2">
                                    <select name="kode" class="form-control" id="penyakitSelect" onchange="window.location.href=this.value">
                                        <option value="" disabled selected>Pilih Penyakit</option>
                                        @foreach($dataP as $p)
                                            <option value="{{ route('admin.aturan.show', ['id' => $p->id]) }}"
                                                @if($p->id == request()->route('id')) selected @endif>
                                                {{ $p->nama_penyakit }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('kode')
                                        <small>{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        
                            <div class="table-responsive pt-3">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th width="10%" class="text-center">No</th>
                                            <th width="20%" class="text-center">Kode Gejala</th>
                                            <th width="60%" class="text-center text-justify">Deskripsi</th>
                                            <th width="10%" class="text-center">Nilai Pakar</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($dataG as $index => $d)
                                            <tr>
                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                <td class="text-center">{{ $d->kd_gejala }}</td>
                                                <td class="text-justify">{{ $d->deskripsi }}</td>
                                                <td class="text-center">
                                                    <input type="number" step="0.1" min="0" max="1" class="form-control form-control-sm text-center" name="cf[{{ $index }}]"
                                                        value="{{ isset($dataA[$index]) ? $dataA[$index]->nilai_cf : 0 }}">
                                                    <input type="hidden" name="idgejala[]" value="{{ $d->id }}">
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                            <br>
                            <button type="submit" class="btn btn-primary mr-2">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let penyakitSelect = document.getElementById("penyakitSelect");
            let inputs = document.querySelectorAll("input");
            let submitButton = document.querySelector("button[type='submit']");

            function toggleInputs(disable) {
                inputs.forEach(input => {
                    if (input.type !== "hidden") {
                        input.disabled = disable;
                    }
                });
                submitButton.disabled = disable;
            }

            // Cek saat halaman dimuat
            if (!penyakitSelect.value) {
                toggleInputs(true);
            }

            // Cek saat user memilih penyakit
            penyakitSelect.addEventListener("change", function () {
                toggleInputs(false);
            });
        });
    </script>
@endpush

