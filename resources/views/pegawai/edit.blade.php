@extends('layouts.app')

@section('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
<div class="container">
    <div class="page-inner">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <form action="{{url('pegawai/update', $pegawai->id)}}" method="POST">
                        @csrf <!-- {{ csrf_field() }} -->
                        <div class="card-header">
                            <div class="card-title">Edit Data Pegawai</div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">

                                    <div class="form-group {{$errors->has('nama') ? 'has-error has-feedback' : ''}}">
                                        <label for="nama">Nama Pegawai</label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            id="nama"
                                            name="nama"
                                            placeholder="Masukkan nama"
                                            value="{{ $pegawai->nama }}"
                                        />
                                        @if ($errors->has('nama'))
                                        <small class="form-text text-muted">{{ $errors->first('nama') }}</small>
                                        @else
                                        <small  class="form-text text-muted">
                                        </small>
                                        @endif
                                    </div>

                                    <div class="form-group {{$errors->has('nip') ? 'has-error has-feedback' : ''}}">
                                        <label for="nip">NIP Pegawai</label>
                                        <input
                                          type="number"
                                          class="form-control"
                                          id="nip"
                                          name="nip"
                                          placeholder="Masukkan NIP (Nomor Induk Pegawai)"
                                          value="{{ $pegawai->nip }}"
                                        />
                                        @if ($errors->has('nip'))
                                        <small class="form-text text-muted">{{ $errors->first('nip') }}</small>
                                        @else
                                        <small  class="form-text text-muted">
                                        </small>
                                        @endif
                                    </div> 
                                    
                                    <div class="form-group  {{$errors->has('tim') ? 'has-error has-feedback' : ''}}">
                                        <label for="tim"
                                          >Tim</label
                                        >
                                        <select
                                          class="form-select"
                                          id="tim"
                                          name="tim"
                                        >
                                          <option value="">(Pilih salah satu)</option>
                                          <option value="11011" {{ $pegawai->tim == '11011' ? 'selected' : ''}}>Umum</option>
                                          <option value="11012" {{$pegawai->tim == '11012' ? 'selected' : ''}}>Statistik Sosial</option>
                                          <option value="11013" {{$pegawai->tim == '11013' ? 'selected' : ''}}>Statistik Ekonomi Produksi</option>
                                          <option value="11015" {{$pegawai->tim == '11015' ? 'selected' : ''}}>Neraca dan Analisis Statistik</option>
                                          <option value="11014" {{$pegawai->tim == '11014' ? 'selected' : ''}}>Statistik Ekonomi Distribusi</option>
                                          <option value="11016" {{$pegawai->tim == '11016' ? 'selected' : ''}}>IPDS</option>
                                        </select>
                                        @if ($errors->has('tim'))
                                        <small class="form-text text-muted">{{ $errors->first('tim') }}</small>
                                        @endif
                                    </div>


                                </div>

                                <div class="col-md-6">
                                    <div class="form-group {{$errors->has('no_rek') ? 'has-error has-feedback' : ''}}">
                                        <label for="no_rek">Nomor Rekening</label>
                                        <input
                                          type="number"
                                          class="form-control"
                                          id="no_rek"
                                          name="no_rek"
                                          placeholder="Masukkan Nomor Rekening BSI"
                                          value="{{ $pegawai->no_rek }}"
                                        />
                                        @if ($errors->has('no_rek'))
                                        <small class="form-text text-muted">{{ $errors->first('no_rek') }}</small>
                                        @else
                                        <small  class="form-text text-muted">
                                        </small>
                                        @endif
                                    </div> 

                                    <div class="form-group  {{$errors->has('kec_asal') ? 'has-error has-feedback' : ''}}">
                                        <label for="kec_asal"
                                          >Kecamatan Asal</label
                                        >
                                        <select
                                          class="form-select"
                                          id="kec_asal"
                                          name="kec_asal"
                                        >
                                          <option value="">(Pilih salah satu)</option>
                                          <option value="010" {{ $pegawai->kec_asal == '010' ? 'selected' : ''}}>Teupah Selatan</option>
                                          <option value="020" {{$pegawai->kec_asal == '020' ? 'selected' : ''}}>Simeulue Timur</option>
                                          <option value="021" {{$pegawai->kec_asal == '021' ? 'selected' : ''}}>Teupah Barat</option>
                                          <option value="022" {{$pegawai->kec_asal == '022' ? 'selected' : ''}}>Teupah Tengah</option>
                                          <option value="030" {{$pegawai->kec_asal == '030' ? 'selected' : ''}}>Simeulue Tengah</option>
                                          <option value="031" {{$pegawai->kec_asal == '031' ? 'selected' : ''}}>Teluk Dalam</option>
                                          <option value="032" {{$pegawai->kec_asal == '032' ? 'selected' : ''}}>Simeulue Cut</option>
                                          <option value="040" {{$pegawai->kec_asal == '040' ? 'selected' : ''}}>Salang</option>
                                          <option value="050" {{$pegawai->kec_asal == '050' ? 'selected' : ''}}>Simeulue Barat</option>
                                          <option value="051" {{$pegawai->kec_asal == '051' ? 'selected' : ''}}>Alafan</option>
                                        </select>
                                        @if ($errors->has('kec_asal'))
                                        <small class="form-text text-muted">{{ $errors->first('kec_asal') }}</small>
                                        @endif
                                      </div>

                                      <div class="form-group {{$errors->has("flag") ? 'has-error has-feedback' : ''}}">
                                        <label for="flag">Flag</label>
                                        <select
                                            class="form-select"
                                            id="flag"
                                            name="flag"
                                        >
                                            <option>(Pilih salah satu)</option>
                                            
                                            <option value="Aktif" {{ $pegawai->flag == null ? "selected" : ""}}>
                                                Aktif
                                            </option>
                                            <option value="Tidak Aktif" {{ $pegawai->flag != null ? "selected" : ""}}>
                                                Tidak Aktif
                                            </option>
                                        </select>
                                        @if ($errors->has("flag"))
                                        <small class="form-text text-muted">{{ $errors->first("flag") }}</small>
                                        @endif

                                    </div>
                                </div>
                                
                            </div>

                            <hr/>

                            <div class="row">

                                <div class="col-md-6">


                                    <div class="form-group {{$errors->has('username') ? 'has-error has-feedback' : ''}}">
                                        <label for="username">Username</label>
                                        <input
                                          type="text"
                                          class="form-control"
                                          id="username"
                                          name="username"
                                          placeholder="Masukkan username"
                                          value="@if(old('username')) {{ old('username') }} @else{{$pegawai->username}}@endif"
                                          {{-- Kepala BPS & Admin tidak bisa diubah usernamenya --}}
                                          @if(($pegawai->id == 0) || $pegawai->id == 1) disabled @endif
                                        />
                                        @if ($errors->has('username'))
                                        <small class="form-text text-muted">{{$errors->first('username')}}</small>
                                        @else
                                        <small  class="form-text text-muted">
                                        </small>
                                        @endif
                                      </div>

                                    <div class="form-group  {{$errors->has('role') ? 'has-error has-feedback' : ''}}">
                                        <label for="role">Role</label>
                                        <select
                                            class="form-select"
                                            id="role"
                                            name="role"
                                            {{-- Kepala BPS & Admin tidak bisa diubah rolenya --}}
                                            @if(($pegawai->id == 0) || $pegawai->id == 1) disabled @endif
                                        >
                                            <option value="">(Pilih salah satu)</option>
                                            <option value="Admin" {{ $pegawai->role == 'Admin' ? 'selected' : ''}}>Admin</option>
                                            <option value="Pegawai" {{ $pegawai->role == 'Pegawai' ? 'selected' : ''}}>Pegawai</option>
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
                                        @else
                                        <small class="form-text text-muted">Kosongkan jika tidak ingin mengubah password.</small>
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
                                        @else
                                        <small class="form-text text-muted">Kosongkan jika tidak ingin mengubah password.</small>
                                        @endif
                                    </div>

                                </div>
                            </div>
                            <input type="hidden" value="{{Request::path()}}" name="path">
                        </div>
                        <div class="card-action">
                            <button type="submit" class="btn btn-success">Edit Pegawai</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

