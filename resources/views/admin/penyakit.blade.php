@extends('layouts.app')

@section('title', 'Data Penyakit')

@push('style')
    <link rel="stylesheet" href="{{ asset('vendors/mdi/css/materialdesignicons.min.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.23/dist/sweetalert2.min.css" rel="stylesheet">
@endpush


@section('main')
    <div class="content-wrapper">
        {{-- <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Data Penyakit</h4>
                        <a href="{{ route('admin.penyakit.create') }}" class="btn btn-primary">Tambahkan Data</a>
                        <div class="table-responsive pt-3">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th width = "10%" >No</th>
                                        <th width = "10%"  >Kode Penyakit</th>
                                        <th width = "20%"  >Nama</th>
                                        <th width = "50%"  >Deskripsi</th>
                                        <th width = "10%"  >Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $d)
                                    <tr>
                                        <td > {{ $loop->iteration }}</td>
                                        <td > {{ $d->kd_penyakit }}</td>
                                        <td > {{ $d->nama_penyakit }}</td>
                                        <td>{{ $d->deskripsi }}</td>
                                        <td>
                                            <a href="{{ route('admin.penyakit.edit',['id' => $d->id]) }}" class="btn btn-primary mdi mdi-lead-pencil">Edit</a>
                                            <a data-toggle="modal" data-target="#modal-hapus{{ $d->id }}" class="btn btn-danger mdi mdi-delete">Hapus</a>
                                        </td>
                                    </tr>
                                    <div class="modal" tabindex="-1" role="dialog" id="modal-hapus{{ $d->id }}">
                                        <div class="modal-dialog" role="document">
                                          <div class="modal-content">
                                            <div class="modal-header">
                                              <h5 class="modal-title">Konfirmasi Hapus Data</h5>
                                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                              </button>
                                            </div>
                                            <div class="modal-body">
                                              <p>Yakin Mau menghapus data <b>{{ $d->nama_penyakit }}</b> ini ?</p>
                                            </div>
                                            <div class="modal-footer">
                                                <form action="{{ route('admin.penyakit.delete',['id'=>$d->id]) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-primary">Ya, Hapus</button>
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                </form>
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="card-title">DATA PENYAKIT</h4>
                            <a href="{{ route('admin.penyakit.create') }}" class="btn btn-primary rounded-0">Tambahkan Data</a>
                        </div> <hr>
                        <div class="table-responsive">
                            <table class="table table-bordered" id="clientside">
                                <thead>
                                    <tr>
                                        <th width="10%">No</th>
                                        <th width="10%">Kode Penyakit</th>
                                        <th width="20%">Nama</th>
                                        <th class="w-50 text-wrap text-justify">Deskripsi</th>
                                        <th width="10%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $d)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $d->kd_penyakit }}</td>
                                        <td class="w-auto">{{ $d->nama_penyakit }}</td> 
                                        <td class="text-wrap text-justify">{{ $d->deskripsi }}</td>
                                        <td>
                                            <a href="{{ route('admin.penyakit.edit', ['id' => $d->id]) }}" class="btn btn-primary btn-sm mdi mdi-lead-pencil"> Edit</a>
                                            <a data-toggle="modal" data-target="#modal-hapus{{ $d->id }}" class="btn btn-danger btn-sm mdi mdi-delete"> Hapus</a>
                                        </td>
                                    </tr>
                                    <div class="modal fade" id="modal-hapus{{ $d->id }}" tabindex="-1" role="dialog">
                                        <div class="modal-dialog" role="document">
                                          <div class="modal-content">
                                            <div class="modal-header">
                                              <h5 class="modal-title">Konfirmasi Hapus Data</h5>
                                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                              </button>
                                            </div>
                                            <div class="modal-body">
                                              <p>Yakin Mau menghapus data <b>{{ $d->kd_gejala }}</b> ini?</p>
                                            </div>
                                            <div class="modal-footer">
                                                <form action="{{ route('admin.penyakit.delete',['id'=>$d->id]) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-primary btn-sm">Ya, Hapus</button>
                                                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Batal</button>
                                                </form>
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if ($errors->any())
    <script>
        Swal.fire({
            icon: "error",
            title: "Terjadi Kesalahan!",
            html: "{!! implode('<br>', $errors->all()) !!}",
            confirmButtonColor: "#d33",
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
        });
    </script>
@endif


@push('script')

@endpush    
