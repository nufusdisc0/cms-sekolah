@extends('layouts.backend')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Data Alumni</h5>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Lengkap</th>
                            <th>NIS/NIM</th>
                            <th>Tahun Lulus</th>
                            <th>Status Alumni</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($alumni as $index => $row)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $row->full_name }}</td>
                                <td>{{ $row->identity_number }}</td>
                                <td>{{ $row->end_date ? \Carbon\Carbon::parse($row->end_date)->format('Y') : '-' }}</td>
                                <td>
                                    @if($row->is_alumni == 'true')
                                        <span class="badge bg-success">Terverifikasi</span>
                                    @elseif($row->is_alumni == 'unverified')
                                        <span class="badge bg-warning text-dark">Belum Terverifikasi</span>
                                    @endif
                                </td>
                                <td>
                                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal-{{ $row->id }}">Edit</button>
                                </td>
                            </tr>

                            <!-- Edit Modal -->
                            <div class="modal fade" id="editModal-{{ $row->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="{{ route('backend.alumni.update', $row->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Alumni</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label>Nama Lengkap</label>
                                                    <input type="text" name="full_name" class="form-control" value="{{ $row->full_name }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label>NIS/NIM</label>
                                                    <input type="text" name="identity_number" class="form-control" value="{{ $row->identity_number }}">
                                                </div>
                                                <div class="mb-3">
                                                    <label>Tanggal Lulus</label>
                                                    <input type="date" name="end_date" class="form-control" value="{{ $row->end_date }}">
                                                </div>
                                                <div class="mb-3">
                                                    <label>No. HP (WhatsApp)</label>
                                                    <input type="text" name="phone" class="form-control" value="{{ $row->phone }}">
                                                </div>
                                                <div class="mb-3">
                                                    <label>Email</label>
                                                    <input type="email" name="email" class="form-control" value="{{ $row->email }}">
                                                </div>
                                                <div class="mb-3">
                                                    <label>Status Verifikasi</label>
                                                    <select name="is_alumni" class="form-control" required>
                                                        <option value="true" {{ $row->is_alumni == 'true' ? 'selected' : '' }}>Terverifikasi (Aktif sebagai Alumni)</option>
                                                        <option value="unverified" {{ $row->is_alumni == 'unverified' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                                                        <option value="false">Bukan Alumni (Batalkan)</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                            </div>
                                        </form>
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
@endsection
