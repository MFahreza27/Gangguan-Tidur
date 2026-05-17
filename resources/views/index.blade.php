@extends('layouts.app')

@section('title', 'Data Pengguna')

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
                            <h4 class="card-title">DATA PENGGUNA</h4> <hr>
                            <a href="{{ route('admin.user.create') }}" class="btn btn-primary rounded-0">Tambahkan Data</a>
                        </div> <hr>
                        <div class="table-responsive pt-3">
                            <table class="table table-bordered" id="clientside">
                                <thead>
                                    <tr>
                                        <th width="10%" class="text-center">No</th>
                                        <th width="10%" class="text-center">Foto</th>
                                        <th width="25%" class="text-center">Nama</th>
                                        <th width="25%" class="text-center">Email</th>
                                        <th width="20%" class="text-center">Role</th>
                                        <th width="10%" class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $d)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td class="text-center"><img src="{{ asset('storage/'.$d->image) }}" alt="" style="width: 50px; height: 50px;"></td>
                                        <td>{{ $d->nama }}</td>
                                        <td>{{ $d->email }}</td>
                                        <td class="text-center">{{ $d->role }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('admin.user.edit',['id' => $d->id]) }}" class="btn btn-sm btn-primary mdi mdi-lead-pencil"> Edit</a>
                                            <a data-toggle="modal" data-target="#modal-hapus{{ $d->id }}" class="btn btn-sm btn-danger mdi mdi-delete"> Hapus</a>
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
                                                    <p>Yakin mau menghapus <b>{{ $d->nama }}</b> ini?</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <form action="{{ route('admin.user.delete',['id'=>$d->id]) }}" method="POST">
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
        </div>
    </div>
@endsection

@push('script')
@endpush
