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
                    <form action="{{route('pegawai.store')}}" method="POST">
                        @csrf <!-- {{ csrf_field() }} -->
                        <div class="card-header">
                            <div class="card-title">Tambah Pegawai</div>
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
                                          value="{{ old('nama') }}"
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
                                            value="{{ old('nip') }}"
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
                                          <option value="11011" {{ old('tim') == '11011' ? 'selected' : ''}}>Umum</option>
                                          <option value="11012" {{old('tim') == '11012' ? 'selected' : ''}}>Statistik Sosial</option>
                                          <option value="11013" {{old('tim') == '11013' ? 'selected' : ''}}>Statistik Ekonomi Produksi</option>
                                          <option value="11015" {{old('tim') == '11015' ? 'selected' : ''}}>Neraca dan Analisis Statistik</option>
                                          <option value="11014" {{old('tim') == '11014' ? 'selected' : ''}}>Statistik Ekonomi Distribusi</option>
                                          <option value="11016" {{old('tim') == '11016' ? 'selected' : ''}}>TI dan Pengolahan</option>
                                          <option value="11017" {{old('tim') == '11017' ? 'selected' : ''}}>Diseminasi, Publisitas, dan Humas</option>
                                          <option value="11018" {{old('tim') == '11018' ? 'selected' : ''}}>Pembinaan Statistik Sektoral</option>
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
                                          value="{{ old('no_rek') }}"
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
                                          <option value="010" {{ old('kec_asal') == '010' ? 'selected' : ''}}>Teupah Selatan</option>
                                          <option value="020" {{old('kec_asal') == '020' ? 'selected' : ''}}>Simeulue Timur</option>
                                          <option value="021" {{old('kec_asal') == '021' ? 'selected' : ''}}>Teupah Barat</option>
                                          <option value="022" {{old('kec_asal') == '022' ? 'selected' : ''}}>Teupah Tengah</option>
                                          <option value="030" {{old('kec_asal') == '030' ? 'selected' : ''}}>Simeulue Tengah</option>
                                          <option value="031" {{old('kec_asal') == '031' ? 'selected' : ''}}>Teluk Dalam</option>
                                          <option value="032" {{old('kec_asal') == '032' ? 'selected' : ''}}>Simeulue Cut</option>
                                          <option value="040" {{old('kec_asal') == '040' ? 'selected' : ''}}>Salang</option>
                                          <option value="050" {{old('kec_asal') == '050' ? 'selected' : ''}}>Simeulue Barat</option>
                                          <option value="051" {{old('kec_asal') == '051' ? 'selected' : ''}}>Alafan</option>
                                        </select>
                                        @if ($errors->has('kec_asal'))
                                        <small class="form-text text-muted">{{ $errors->first('kec_asal') }}</small>
                                        @endif
                                      </div>
                                </div>
                                <input type="hidden" value="{{Request::path()}}" name="path">
                            </div>

                            <hr />

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
                                      <option value="Ketua Tim" {{ old('role') == 'Ketua Tim' ? 'selected' : ''}}>Ketua Tim</option>
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
                            <button type="submit" class="btn btn-success">Tambah Pegawai</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

