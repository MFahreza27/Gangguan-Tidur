@extends('layouts.app')

@section('title', 'Data Pertanyaan')

@push('style')
    <link rel="stylesheet" href="{{ asset('vendors/mdi/css/materialdesignicons.min.css') }}">
@endpush


@section('main')
    <div class="content-wrapper">
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="card-title">DATA PERTANYAAN</h4><hr>
                            <a href="{{ route('admin.pertanyaan.create') }}" class="btn btn-primary rounded-0">Tambahkan Data</a>
                        </div> <hr>
                        <div class="table-responsive pt-3">
                            <table class="table table-bordered" id="clientside">
                                <thead>
                                    <tr>
                                        <th width="10%">No</th>
                                        <th width="20%">Kode Gejala</th>
                                        <th class="w-60 text-wrap text-justify">Pertanyaan</th>
                                        <th width="10%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $d)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $d->gejala->kd_gejala ?? 'Tidak tersedia' }}</td>
                                        <td class="text-wrap text-justify">{{ $d->pertanyaan }}</td>
                                        <td>
                                            <a href="{{ route('admin.pertanyaan.edit',['id' => $d->id]) }}" class="btn btn-primary btn-sm mdi mdi-lead-pencil"> Edit</a>
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
                                              <p>Yakin mau menghapus data <b>{{ $d->pertanyaan }}</b> ini?</p>
                                            </div>
                                            <div class="modal-footer">
                                                <form action="{{ route('admin.pertanyaan.delete',['id'=>$d->id]) }}" method="POST">
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

@push('script')
@endpush
