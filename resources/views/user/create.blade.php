@extends('layouts.app')

@section('content')
<div class="container">
  <div class="page-inner">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <form action="{{route('user.store')}}" method="POST" >
          @csrf <!-- {{ csrf_field() }} -->
            <div class="card-header">
              <div class="card-title">Tambah Pengguna</div>
            </div>
            <div class="card-body">
              <div class="row">

                <div class="col-md-6">

                  <div class="form-group {{$errors->has('nama') ? 'has-error has-feedback' : ''}}">
                    <label for="nama">Nama</label>
                    <input
                      type="text"
                      class="form-control form-control"
                      id="nama"
                      placeholder="Masukkan nama"
                      name="nama"
                      value="{{ old('nama') }}"
                    />
                    @if ($errors->has('nama'))
                    <small id="namaHelp" class="form-text text-muted">{{ $errors->first('nama') }}</small>
                    @endif
                  </div>

                  <div class="form-group {{$errors->has('username') ? 'has-error has-feedback' : ''}}">
                    <label for="username">Username</label>
                    <input
                      type="text"
                      class="form-control"
                      id="username"
                      name="username"
                      placeholder="Masukkan username"
                      value="{{ old('username') }}"
                    />
                    @if ($errors->has('username'))
                    <small class="form-text text-muted">{{ $errors->first('username') }}</small>
                    @else
                    <small  class="form-text text-muted">
                    </small>
                    @endif
                  </div>

                  <div class="form-group  {{$errors->has('role') ? 'has-error has-feedback' : ''}}">
                    <label for="role"
                      >Role</label
                    >
                    <select
                      class="form-select"
                      id="role"
                      name="role"
                    >
                      <option value="">(Pilih salah satu)</option>
                      <option value="Admin" {{ old('role') == 'Admin' ? 'selected' : ''}}>Admin</option>
                      <option value="Pegawai" {{old('role') == 'Pegawai' ? 'selected' : ''}}>Pegawai</option>
                    </select>
                    @if ($errors->has('role'))
                    <small class="form-text text-muted">{{ $errors->first('role') }}</small>
                    @endif
                  </div>

                </div>
                <div class="col-md-6">

                  <div class="form-group {{$errors->has('password') ? 'has-error has-feedback' : ''}}">
                    <label for="password">Password</label>
                    <input
                      type="password"
                      class="form-control"
                      id="password"
                      placeholder="Password"
                      name="password"
                    />
                    @if ($errors->has('password'))
                    <small class="form-text text-muted">{{ $errors->first('password') }}</small>
                    @endif
                  </div>

                  <div class="form-group {{$errors->has('confirm-password') ? 'has-error has-feedback' : ''}}">
                    <label for="confirm-password">Konfirmasi Password</label>
                    <input
                      type="password"
                      class="form-control"
                      id="confirm-password"
                      placeholder="Masukkan password kembali"
                      name="confirm-password"
                    />
                    @if ($errors->has('confirm-password'))
                    <small class="form-text text-muted">{{ $errors->first('confirm-password') }}</small>
                    @endif
                  </div>

                </div>
              </div>
            </div>
            <div class="card-action">
              <button type="submit" class="btn btn-success">Tambah Pengguna</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
