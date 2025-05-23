@extends('layouts.app')

@section('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{asset('select2/css/select2.min.css')}}" />
    <link rel="stylesheet" href="{{asset('select2/css/select2-bootstrap-5-theme.min.css')}}" />
@endsection

@section('content')
<div class="container">
    <div class="page-inner">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <form action="{{route('sbks.store')}}" method="POST">
                        @csrf <!-- {{ csrf_field() }} -->
                        <div class="card-header">
                            <div class="card-title">Tambah SBKS</div>
                        </div>
                        <div class="card-body">

                            <div class="row">
                                <div class="col-md-6">

                                    <div class="form-group {{$errors->has('nama_kegiatan') ? 'has-error has-feedback' : ''}}">
                                        <label for="nama_kegiatan">Nama Kegiatan</label>
                                        <input
                                          type="text"
                                          class="form-control"
                                          id="nama_kegiatan"
                                          name="nama_kegiatan"
                                          placeholder="Masukkan nama kegiatan"
                                          value="{{ old('nama_kegiatan') }}"
                                        />
                                        @if ($errors->has('nama_kegiatan'))
                                        <small class="form-text text-muted">{{ $errors->first('nama_kegiatan') }}</small>
                                        @else
                                        <small  class="form-text text-muted">
                                        </small>
                                        @endif
                                    </div> 

                                    <div class="form-group  {{$errors->has('satuan') ? 'has-error has-feedback' : ''}}">
                                        <label for="satuan">Satuan Honor </label>
                                        <select
                                            class="form-select"
                                            id="satuan"
                                            name="satuan"
                                        >
                                            <option value="">(Pilih salah satu)</option>
                                            <option value="Dokumen" {{ old('satuan') == 'Dokumen' ? 'selected' : ''}}>Dokumen</option>
                                            <option value="SLS" {{old('satuan') == 'SLS' ? 'selected' : ''}}>SLS (Satuan Lingkungan Setempat)</option>
                                            <option value="BS" {{old('satuan') == 'BS' ? 'selected' : ''}}>BS (Blok Sensus)</option>
                                            <option value="Ruta" {{old('satuan') == 'Ruta' ? 'selected' : ''}}>Rumah Tangga</option>
                                            <option value="OK" {{old('satuan') == 'OK' ? 'selected' : ''}}>Orang Kegiatan (OK)</option>
                                            <option value="OH" {{old('satuan') == 'OH' ? 'selected' : ''}}>Orang Harian (OH)</option>
                                            <option value="OB" {{old('satuan') == 'OB' ? 'selected' : ''}}>Orang Bulan (OB)</option>
                                            <option value="Segmen" {{old('satuan') == 'Segmen' ? 'selected' : ''}}>Segmen</option>
                                            <option value="EA" {{old('satuan') == 'EA' ? 'selected' : ''}}>Enumeration Area (EA)</option>
                                            <option value="Responden" {{old('satuan') == 'Responden' ? 'selected' : ''}}>Responden</option>
                                            <option value="Pasar" {{old('satuan') == 'Pasar' ? 'selected' : ''}}>Pasar</option>
                                          
                                        </select>
                                        @if ($errors->has('satuan'))
                                        <small class="form-text text-muted">{{ $errors->first('satuan') }}</small>
                                        @else
                                        <small  class="form-text text-muted">
                                            {{-- Satuan honor bisa dikosongkan --}}
                                        </small>
                                        @endif
                                    </div>

                                    <div class="form-group {{$errors->has('honor_per_satuan') ? 'has-error has-feedback' : ''}}">
                                        <label for="honor_per_satuan">Honor per satuan</label>
                                        <input
                                          type="number"
                                          class="form-control"
                                          id="honor_per_satuan"
                                          name="honor_per_satuan"
                                          placeholder="Masukkan honor per satuan"
                                          value="{{ old('honor_per_satuan') }}"
                                        />
                                        @if ($errors->has('honor_per_satuan'))
                                        <small class="form-text text-muted">{{ $errors->first('honor_per_satuan') }}</small>
                                        @else
                                        <small  class="form-text text-muted">
                                            {{-- (isian honor dapat dikosongkan jika satuan honor belum dipilih) --}}
                                        </small>
                                        @endif
                                    </div> 

                                    <div class="form-group  {{$errors->has('pjk') ? 'has-error has-feedback' : ''}}">
                                        <label for="pjk">Penanggung Jawab Kegiatan</strong></label
                                        >
                                        <select
                                            class="form-select"
                                            id="pjk"
                                            name="pjk"
                                        >
                                            <option value="">(Pilih salah satu)</option>
                                            @foreach ($pegawais as $item)
                                                <option value="{{$item->id}}" {{ old('pjk') ? (old('pjk') == $item->id ? 'selected' : '') : (Auth::user()->id == $item->id ? 'selected' : '')}}>{{$item->nama}}</option>
                                            @endforeach
                                          
                                        </select>
                                        @if ($errors->has('pjk'))
                                        <small class="form-text text-muted">{{ $errors->first('pjk') }}</small>
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
                                          <option value="11011" {{ old('tim') ? (old('tim') == "11011" ? 'selected' : '') : (Auth::user()->tim == "11011" ? 'selected' : '')}}>Umum</option>
                                          <option value="11012" {{ old('tim') ? (old('tim') == "11012" ? 'selected' : '') : (Auth::user()->tim == "11012" ? 'selected' : '')}}>Statistik Sosial</option>
                                          <option value="11013" {{ old('tim') ? (old('tim') == "11013" ? 'selected' : '') : (Auth::user()->tim == "11013" ? 'selected' : '')}}>Statistik Ekonomi Produksi</option>
                                          <option value="11015" {{ old('tim') ? (old('tim') == "11015" ? 'selected' : '') : (Auth::user()->tim == "11015" ? 'selected' : '')}}>Neraca dan Analisis Statistik</option>
                                          <option value="11014" {{ old('tim') ? (old('tim') == "11014" ? 'selected' : '') : (Auth::user()->tim == "11014" ? 'selected' : '')}}>Statistik Ekonomi Distribusi</option>
                                          <option value="11016" {{ old('tim') ? (old('tim') == "11016" ? 'selected' : '') : (Auth::user()->tim == "11016" ? 'selected' : '')}}>TI dan Pengolahan</option>
                                          <option value="11017" {{ old('tim') ? (old('tim') == "11017" ? 'selected' : '') : (Auth::user()->tim == "11017" ? 'selected' : '')}}>Diseminasi, Publisitas, dan Humas</option>
                                          <option value="11018" {{ old('tim') ? (old('tim') == "11018" ? 'selected' : '') : (Auth::user()->tim == "11018" ? 'selected' : '')}}>Pembinaan Statistik Sektoral</option>
                                        </select>
                                        @if ($errors->has('tim'))
                                        <small class="form-text text-muted">{{ $errors->first('tim') }}</small>
                                        @endif
                                    </div>

                                </div>

                                <div class="col-md-6">
                                    
                                    <div class="form-group {{$errors->has('singkatan_resmi') ? 'has-error has-feedback' : ''}}">
                                        <label for="singkatan_resmi">Singkatan Resmi</label>
                                        <input
                                          type="text"
                                          class="form-control"
                                          id="singkatan_resmi"
                                          name="singkatan_resmi"
                                          placeholder="Singkatan resmi dari kegiatan"
                                          value="{{ old('singkatan_resmi') }}"
                                        />
                                        @if ($errors->has('singkatan_resmi'))
                                        <small class="form-text text-muted">{{ $errors->first('singkatan_resmi') }}</small>
                                        @else
                                        <small  class="form-text text-muted">
                                            Misal: SUSENAS, KSA, SKTNP, VHTS, dll. (Kosongkan jika tidak ada singkatan)
                                        </small>
                                        @endif
                                    </div> 

                                    <div class="form-group">
                                        <label>Tugas dalam kegiatan</label><br />
                                        <div class="d-flex">
                                            <div class="form-check">
                                                <input
                                                class="form-check-input"
                                                type="radio"
                                                name="tugas"
                                                id="PPL"
                                                value="PPL"
                                                {{ old('tugas') == 'PPL' ? 'checked' : '' }}
                                                />
                                                <label
                                                class="form-check-label"
                                                for="PPL"
                                                >
                                                PPL
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input
                                                class="form-check-input"
                                                type="radio"
                                                name="tugas"
                                                id="PML"
                                                value="PML"
                                                {{ old('tugas') == 'PML' ? 'checked' : '' }}
                                                />
                                                <label
                                                class="form-check-label"
                                                for="PML"
                                                >
                                                PML
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input
                                                class="form-check-input"
                                                type="radio"
                                                name="tugas"
                                                id="Pengolahan"
                                                value="Pengolahan"
                                                {{ old('tugas') == 'Pengolahan' ? 'checked' : '' }}
                                                />
                                                <label
                                                class="form-check-label"
                                                for="Pengolahan"
                                                >
                                                Pengolahan
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                
                                    <div class="form-group">
                                        <label>Apakah ada di Simeulue?</label><br />
                                        <div class="d-flex">
                                            <div class="form-check">
                                                <input
                                                class="form-check-input"
                                                type="radio"
                                                name="ada_di_simeulue"
                                                id="Ada"
                                                value="1"
                                                {{ old('ada_di_simeulue') == '1' ? 'checked' : '' }}
                                                checked
                                                />
                                                <label
                                                class="form-check-label"
                                                for="1"
                                                >
                                                Ada
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input
                                                class="form-check-input"
                                                type="radio"
                                                name="ada_di_simeulue"
                                                id="TidakAda"
                                                value="0"
                                                {{ old('ada_di_simeulue') == '0' ? 'checked' : '' }}
                                                />
                                                <label
                                                class="form-check-label"
                                                for="0"
                                                >
                                                Tidak Ada
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group {{$errors->has('beban_anggaran') ? 'has-error has-feedback' : ''}}">
                                        <label for="beban_anggaran">Beban anggaran BOS</label>
                                        <input
                                          type="text"
                                          class="form-control"
                                          id="beban_anggaran"
                                          name="beban_anggaran"
                                          placeholder="Misal: 2904.BMA.006.005.521213"
                                          value="{{old('beban_anggaran')}}"
                                        />
                                        @if ($errors->has('beban_anggaran'))
                                        <small class="form-text text-muted">{{ $errors->first('beban_anggaran') }}</small>
                                        @else
                                        <small  class="form-text text-muted">
                                            Misal: 2904.BMA.006.005.521213
                                        </small>
                                        @endif
                                    </div> 

                                </div>

                            </div>

                        </div>
                        <div class="card-action">
                            <button type="submit" class="btn btn-success">Tambah SBKS</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>

        
    </div>
</div>
@endsection

@section('script')
<script src="{{asset('select2/js/select2.full.min.js')}}"></script>


</script>
@endsection