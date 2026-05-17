@extends('layouts.app')
@section('title', 'Data User')

@push('style')
    <link rel="stylesheet" href="{{ asset('vendors/mdi/css/materialdesignicons.min.css') }}">
@endpush


@section('main')
    <div class="content-wrapper">
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Data User</h4>
                        <a href="{{ route('admin.user.create') }}" class="btn btn-primary">Tambahkan Data</a>
                        <div class="table-responsive pt-3">
                            <table class="table table-bordered" id="clientside">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Foto</th>
                                        <th>Nama</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $d)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td><img src="{{ asset('storage/foto-user/'.$d->image) }}" alt=""></td>
                                        <td>{{ $d->name }}</td>
                                        <td>{{ $d->email }}</td>
                                        <td>{{ $d->role }}</td>
                                        <td>
                                            <a href="{{ route('admin.user.edit',['id' => $d->id]) }}" class="btn btn-primary mdi mdi-lead-pencil">Edit</a>
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
                                              <p>Yakin Mau menghapus User <b>{{ $d->name }}</b> ini ?</p>
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

@section('scripts')
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
    <script>
        $(document).ready( function () {
            $('#clientside').DataTable();
        } );
    </script>
@endsection


@push('script')
@endpush
