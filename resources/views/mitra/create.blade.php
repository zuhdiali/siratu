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
                    <form action="{{route('mitra.store')}}" method="POST">
                        @csrf <!-- {{ csrf_field() }} -->
                        <div class="card-header">
                            <div class="card-title">Tambah Mitra</div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">

                                    <div class="form-group {{$errors->has('nama') ? 'has-error has-feedback' : ''}}">
                                        <label for="nama">Nama Mitra</label>
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

                                    <div class="form-group {{$errors->has('id_mitra') ? 'has-error has-feedback' : ''}}">
                                        <label for="id_mitra">ID Mitra</label>
                                        <input
                                          type="number"
                                          class="form-control"
                                          id="id_mitra"
                                          name="id_mitra"
                                          placeholder="Masukkan ID Mitra"
                                          value="{{ old('id_mitra') }}"
                                        />
                                        @if ($errors->has('id_mitra'))
                                        <small class="form-text text-muted">{{ $errors->first('id_mitra') }}</small>
                                        @else
                                        <small  class="form-text text-muted">
                                        </small>
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

                            </div>
                        </div>
                        <div class="card-action">
                            <button type="submit" class="btn btn-success">Tambah Mitra</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
