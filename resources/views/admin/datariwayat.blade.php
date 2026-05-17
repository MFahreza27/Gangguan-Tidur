@extends('layouts.app')

@section('title', 'Data Riwayat Diagnosa')

@push('style')
    <link rel="stylesheet" href="{{ asset('vendors/mdi/css/materialdesignicons.min.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.23/dist/sweetalert2.min.css" rel="stylesheet">
@endpush


@section('main')
    <div class="content-wrapper">
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h2 class="card-title">Data Riwayat Diagnosa</h2><hr>
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm w-100" id="clientside">
                                <thead>
                                    <tr>
                                        <th width="5%" class="text-center">No</th>
                                        <th width="15%" class="text-center">Foto</th>
                                        <th width="15%" class="text-left">Nama Pasien</th>
                                        <th width="10%" class="text-left">Penyakit</th>
                                        <th width="10%" class="text-center">Persentase</th>
                                        <th width="20%" class="text-center">Solusi</th>
                                        <th width="15%" class="text-center">Tanggal Diagnosa</th>
                                        <th width="5%" class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($riwayat as $d)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td class="text-center">
                                            <img src="{{ asset($d->user && $d->user->image ? 'storage/' . $d->user->image : 'default-avatar.jpg') }}" 
                                                 class="img-thumbnail" 
                                                 width="50">
                                        </td>
                                            <td class="text-left">{{ $d->nama_pasien }}</td>  
                                            <td class="text-left">{{ $d->nama_penyakit }}</td>  
                                            <td class="text-center">{{ number_format($d->probability * 100, 2) }}%</td>  
                                            <td class="text-wrap text-break" style="text-align: justify;">{{ $d->solusi }}</td>  
                                            <td class="text-center">{{ \Carbon\Carbon::parse($d->tanggal)->translatedFormat('d F Y') }}</td>
                                            <td class="text-center">
                                                <a href="{{ route('admin.riwayat.cetak', $d->id) }}" class="btn btn-primary btn-sm mdi mdi-printer"> Cetak PDF</a>
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
                                                <form action="{{ route('admin.datariwayat.delete',['id'=>$d->id]) }}" method="POST">
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
