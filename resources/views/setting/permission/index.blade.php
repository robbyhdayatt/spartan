@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <form action="{{ route('permissions.update') }}" method="POST">
        @csrf
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Manajemen Hak Akses Detail</h3>
                <div class="card-tools">
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </div>
            <div class="card-body">
                @if (session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
                <p>Atur hak akses spesifik untuk setiap user. User dengan role <strong>Admin</strong> akan selalu memiliki semua akses.</p>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm">
                        <thead>
                            <tr>
                                <th style="width: 20%;">Modul</th>
                                @foreach ($users as $user)
                                    <th class="text-center">{{ $user->karyawan->nama_karyawan ?? $user->username }}<br><small class="text-muted text-capitalize">{{ $user->role_level }}</small></th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($modules as $module)
                                <tr>
                                    <td class="text-capitalize font-weight-bold">{{ str_replace(['.', '_'], ' ', $module) }}</td>
                                    @foreach ($users as $user)
                                        @php
                                            $p = $permissions[$user->id_user][$module] ?? null;
                                        @endphp
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center">
                                                <div class="custom-control custom-checkbox mx-1" title="Read">
                                                    <input type="checkbox" class="custom-control-input" id="read_{{ $user->id_user }}_{{ $module }}" name="permissions[{{$user->id_user}}][{{$module}}][read]" {{ ($p && $p->can_read) ? 'checked' : '' }}>
                                                    <label class="custom-control-label" for="read_{{ $user->id_user }}_{{ $module }}">R</label>
                                                </div>
                                                <div class="custom-control custom-checkbox mx-1" title="Create">
                                                    <input type="checkbox" class="custom-control-input" id="create_{{ $user->id_user }}_{{ $module }}" name="permissions[{{$user->id_user}}][{{$module}}][create]" {{ ($p && $p->can_create) ? 'checked' : '' }}>
                                                    <label class="custom-control-label" for="create_{{ $user->id_user }}_{{ $module }}">C</label>
                                                </div>
                                                <div class="custom-control custom-checkbox mx-1" title="Update">
                                                    <input type="checkbox" class="custom-control-input" id="update_{{ $user->id_user }}_{{ $module }}" name="permissions[{{$user->id_user}}][{{$module}}][update]" {{ ($p && $p->can_update) ? 'checked' : '' }}>
                                                    <label class="custom-control-label" for="update_{{ $user->id_user }}_{{ $module }}">U</label>
                                                </div>
                                                <div class="custom-control custom-checkbox mx-1" title="Delete">
                                                    <input type="checkbox" class="custom-control-input" id="delete_{{ $user->id_user }}_{{ $module }}" name="permissions[{{$user->id_user}}][{{$module}}][delete]" {{ ($p && $p->can_delete) ? 'checked' : '' }}>
                                                    <label class="custom-control-label" for="delete_{{ $user->id_user }}_{{ $module }}">D</label>
                                                </div>
                                            </div>
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection