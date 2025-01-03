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
                    <form action="{{url('mitra/update', $mitra->id)}}" method="POST">
                        @csrf <!-- {{ csrf_field() }} -->
                        <div class="card-header">
                            <div class="card-title">Edit Data Mitra</div>
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
                                            value="{{ $mitra->nama }}"
                                        />
                                        @if ($errors->has('nama'))
                                        <small class="form-text text-muted">{{ $errors->first('nama') }}</small>
                                        @else
                                        <small  class="form-text text-muted">
                                        </small>
                                        @endif
                                    </div>

                                    <div class="form-group {{$errors->has('id_mitra') ? 'has-error has-feedback' : ''}}">
                                        <label for="id_mitra">NIP Mitra</label>
                                        <input
                                          type="number"
                                          class="form-control"
                                          id="id_mitra"
                                          name="id_mitra"
                                          placeholder="Masukkan ID Mitra"
                                          value="{{ $mitra->id_mitra }}"
                                        />
                                        @if ($errors->has('id_mitra'))
                                        <small class="form-text text-muted">{{ $errors->first('id_mitra') }}</small>
                                        @else
                                        <small  class="form-text text-muted">
                                        </small>
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
                                            
                                            <option value="Aktif" {{ $mitra->flag == null ? "selected" : ""}}>
                                                Aktif
                                            </option>
                                            <option value="Tidak Aktif" {{ $mitra->flag != null ? "selected" : ""}}>
                                                Tidak Aktif
                                            </option>
                                        </select>
                                        @if ($errors->has("flag"))
                                        <small class="form-text text-muted">{{ $errors->first("flag") }}</small>
                                        @endif
                                        <hr/>
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
                                          value="{{ $mitra->no_rek }}"
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
                                          <option value="010" {{ $mitra->kec_asal == '010' ? 'selected' : ''}}>Teupah Selatan</option>
                                          <option value="020" {{$mitra->kec_asal == '020' ? 'selected' : ''}}>Simeulue Timur</option>
                                          <option value="021" {{$mitra->kec_asal == '021' ? 'selected' : ''}}>Teupah Barat</option>
                                          <option value="022" {{$mitra->kec_asal == '022' ? 'selected' : ''}}>Teupah Tengah</option>
                                          <option value="030" {{$mitra->kec_asal == '030' ? 'selected' : ''}}>Simeulue Tengah</option>
                                          <option value="031" {{$mitra->kec_asal == '031' ? 'selected' : ''}}>Teluk Dalam</option>
                                          <option value="032" {{$mitra->kec_asal == '032' ? 'selected' : ''}}>Simeulue Cut</option>
                                          <option value="040" {{$mitra->kec_asal == '040' ? 'selected' : ''}}>Salang</option>
                                          <option value="050" {{$mitra->kec_asal == '050' ? 'selected' : ''}}>Simeulue Barat</option>
                                          <option value="051" {{$mitra->kec_asal == '051' ? 'selected' : ''}}>Alafan</option>
                                        </select>
                                        @if ($errors->has('kec_asal'))
                                        <small class="form-text text-muted">{{ $errors->first('kec_asal') }}</small>
                                        @endif
                                      </div>

                                </div>

                            </div>
                        </div>
                        <div class="card-action">
                            <button type="submit" class="btn btn-success">Edit Mitra</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

