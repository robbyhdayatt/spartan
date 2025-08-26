@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header"><h3 class="card-title">Manajemen User</h3><div class="card-tools"><a href="#" id="btn-create" class="btn btn-primary btn-sm">Buat User Baru</a></div></div>
        <div class="card-body">
            @if (session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
            @if (session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif
            <table class="table table-bordered table-hover">
                <thead><tr><th>No</th><th>Nama Karyawan</th><th>Jabatan</th><th>Username</th><th>Role</th><th>Status</th><th>Aksi</th></tr></thead>
                <tbody>
                    @forelse ($users as $user)
                    <tr>
                        <td>{{ $loop->iteration + $users->firstItem() - 1 }}</td>
                        <td>{{ $user->karyawan->nama_karyawan ?? 'N/A' }}</td>
                        <td>{{ $user->karyawan->jabatan->nama_jabatan ?? 'N/A' }}</td>
                        <td>{{ $user->username }}</td>
                        <td class="text-capitalize">{{ $user->role_level }}</td>
                        <td>{!! $user->status_aktif ? '<span class="badge badge-success">Aktif</span>' : '<span class="badge badge-danger">Tidak Aktif</span>' !!}</td>
                        <td>
                            @can('manage-master-data')
                            <a href="#" class="btn btn-warning btn-sm btn-edit" data-id="{{ $user->id_user }}" data-username="{{ $user->username }}" data-role_level="{{ $user->role_level }}">Edit</a>
                            @endcan
                            
                            @can('delete-data')
                            <form action="{{ route('users.destroy', $user->id_user) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus user ini?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                            @endcan
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center">Data user tidak ditemukan.</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-3">{{ $users->links() }}</div>
        </div>
    </div>
</div>

<div class="modal fade" id="formModal" tabindex="-1"><div class="modal-dialog modal-lg"><div class="modal-content">
<div class="modal-header"><h5 class="modal-title" id="formModalLabel">Form User</h5><button type="button" class="close" data-dismiss="modal"><span>&times;</span></button></div>
<form id="form" action="" method="POST">
    @csrf <div id="form-method"></div>
    <div class="modal-body">
        <div class="form-group create-only">
            <label>Pilih Karyawan</label>
            <select name="id_karyawan" id="id_karyawan" class="form-control" required>
                <option value="">-- Pilih Karyawan yang Belum Punya Akun --</option>
                @foreach($karyawans as $karyawan)
                <option value="{{ $karyawan->id_karyawan }}">{{ $karyawan->nama_karyawan }} ({{ $karyawan->jabatan->nama_jabatan ?? 'Tanpa Jabatan' }})</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" id="username" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Role Level</label>
            <select name="role_level" id="role_level" class="form-control" required>
                <option value="admin">Admin</option>
                <option value="manager">Manager</option>
                <option value="supervisor">Supervisor</option>
                <option value="staff">Staff</option>
                <option value="viewer">Viewer</option>
            </select>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input id="password" type="password" class="form-control" name="password">
            <small class="form-text text-muted edit-only">Kosongkan jika tidak ingin mengubah password.</small>
        </div>
        <div class="form-group">
            <label for="password-confirm">Konfirmasi Password</label>
            <input id="password-confirm" type="password" class="form-control" name="password_confirmation">
        </div>
    </div>
    <div class="modal-footer"><button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary">Simpan</button></div>
</form>
</div></div></div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#btn-create').on('click', function(e) {
        e.preventDefault();
        $('#form')[0].reset();
        $('#form-method').empty();
        $('#formModalLabel').text('Buat User Baru');
        $('.create-only').show();
        $('.edit-only').hide();
        $('#password').prop('required', true);
        $('#form').attr('action', '{{ route("users.store") }}');
        $('#formModal').modal('show');
    });

    $('.btn-edit').on('click', function(e) {
        e.preventDefault();
        $('#form')[0].reset();
        $('#form-method').html('@method("PUT")');
        $('#formModalLabel').text('Edit User');
        $('.create-only').hide();
        $('.edit-only').show();
        $('#password').prop('required', false);

        let data = $(this).data();
        $('#username').val(data.username);
        $('#role_level').val(data.role_level);

        let url = '{{ route("users.update", ":id") }}'.replace(':id', data.id);
        $('#form').attr('action', url);
        $('#formModal').modal('show');
    });
});
</script>
@endpush