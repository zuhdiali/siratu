@extends('layouts.app')

@section('meta')
    <link rel="stylesheet" href="{{asset('select2/css/select2.min.css')}}" />
    <link rel="stylesheet" href="{{asset('select2/css/select2-bootstrap-5-theme.min.css')}}" />
@endsection

@section('content')
<div class="container">
    <div class="page-inner">

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <form action="{{url('kegiatan/update', $kegiatan->id)}}" method="POST" enctype="multipart/form-data">
                        @csrf <!-- {{ csrf_field() }} -->
                        <div class="card-header">
                            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4" >
                                <div>
                                    <h3 class="fw-bold mb-3">Edit Kegiatan</h3>
                                </div>
                                <div class="ms-md-auto py-2 py-md-0">
                                    <a href="{{url('kegiatan/estimasi-honor', $kegiatan->id)}}" class="btn btn-primary btn-round">Edit Estimasi Honor  
                                        <i class="icon-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">

                                    <div class="form-group {{$errors->has('nama') ? 'has-error has-feedback' : ''}}">
                                        <label for="nama">Nama Kegiatan</label>
                                        <input
                                          type="text"
                                          class="form-control"
                                          id="nama"
                                          name="nama"
                                          placeholder="Masukkan nama"
                                          value="{{$kegiatan->nama}}"
                                        />
                                        @if ($errors->has('nama'))
                                        <small class="form-text text-muted">{{ $errors->first('nama') }}</small>
                                        @else
                                        <small  class="form-text text-muted">
                                        </small>
                                        @endif
                                    </div> 

                                    <div class="form-group {{$errors->has('tgl_mulai') ? 'has-error has-feedback' : ''}}">
                                        <label for="tgl_mulai">Tanggal Mulai</label>
                                        <input
                                          type="date"
                                          class="form-control"
                                          id="tgl_mulai"
                                          name="tgl_mulai"
                                          value="{{ $kegiatan->tgl_mulai }}"
                                        />
                                        @if ($errors->has('tgl_mulai'))
                                        <small class="form-text text-muted">{{ $errors->first('tgl_mulai') }}</small>
                                        @else
                                        <small  class="form-text text-muted">
                                        </small>
                                        @endif
                                    </div> 

                                    <div class="form-group {{$errors->has('tgl_selesai') ? 'has-error has-feedback' : ''}}">
                                        <label for="tgl_selesai">Tanggal Selesai</label>
                                        <input
                                          type="date"
                                          class="form-control"
                                          id="tgl_selesai"
                                          name="tgl_selesai"
                                          value="{{ $kegiatan->tgl_selesai}}"
                                        />
                                        @if ($errors->has('tgl_selesai'))
                                        <small class="form-text text-muted">{{ $errors->first('tgl_selesai') }}</small>
                                        @else
                                        <small  class="form-text text-muted">
                                        </small>
                                        @endif
                                    </div> 

                                    <div class="form-group  {{$errors->has('id_pjk') ? 'has-error has-feedback' : ''}}">
                                        <label for="id_pjk">Penanggung Jawab Kegiatan</strong></label
                                        >
                                        <select
                                            class="form-select"
                                            id="id_pjk"
                                            name="id_pjk"
                                        >
                                            <option value="">(Pilih salah satu)</option>
                                            @foreach ($pegawais as $item)
                                                <option value="{{$item->id}}" {{ $kegiatan->id_pjk == $item->id ? 'selected' : ''}}>{{$item->nama}}</option>
                                            @endforeach
                                          
                                        </select>
                                        @if ($errors->has('id_pjk'))
                                        <small class="form-text text-muted">{{ $errors->first('id_pjk') }}</small>
                                        @else
                                        <small  class="form-text text-muted">
                                        </small>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-6">

                                    <div class="form-group  {{$errors->has('satuan_honor_pengawasan') ? 'has-error has-feedback' : ''}}">
                                        <label for="satuan_honor_pengawasan">Satuan Honor <strong>Pengawasan</strong></label
                                        >
                                        <select
                                            class="form-select"
                                            id="satuan_honor_pengawasan"
                                            name="satuan_honor_pengawasan"
                                        >
                                            <option value="">(Pilih salah satu)</option>
                                            <option value="Dokumen" {{ $kegiatan->satuan_honor_pengawasan == 'Dokumen' ? 'selected' : ''}}>Dokumen</option>
                                            <option value="SLS" {{$kegiatan->satuan_honor_pengawasan == 'SLS' ? 'selected' : ''}}>SLS (Satuan Lingkungan Setempat)</option>
                                            <option value="BS" {{$kegiatan->satuan_honor_pengawasan == 'BS' ? 'selected' : ''}}>BS (Blok Sensus)</option>
                                            <option value="Ruta" {{$kegiatan->satuan_honor_pengawasan == 'Ruta' ? 'selected' : ''}}>Rumah Tangga</option>
                                          
                                        </select>
                                        @if ($errors->has('satuan_honor_pengawasan'))
                                        <small class="form-text text-muted">{{ $errors->first('satuan_honor_pengawasan') }}</small>
                                        @else
                                        <small  class="form-text text-muted">
                                            Satuan honor bisa dikosongkan
                                        </small>
                                        @endif
                                    </div>

                                    <div class="form-group {{$errors->has('honor_pengawasan') ? 'has-error has-feedback' : ''}}">
                                        <label for="honor_pengawasan">Honor <strong>Pengawasan</strong></label>
                                        <input
                                          type="number"
                                          class="form-control"
                                          id="honor_pengawasan"
                                          name="honor_pengawasan"
                                          placeholder="Masukkan honor per satuan"
                                          value="{{ $kegiatan->honor_pengawasan }}"
                                        />
                                        @if ($errors->has('honor_pengawasan'))
                                        <small class="form-text text-muted">{{ $errors->first('honor_pengawasan') }}</small>
                                        @else
                                        <small  class="form-text text-muted">
                                            (isian honor dapat dikosongkan jika satuan honor belum dipilih)
                                        </small>
                                        @endif
                                    </div> 

                                    <div class="form-group  {{$errors->has('satuan_honor_pencacahan') ? 'has-error has-feedback' : ''}}">
                                        <label for="satuan_honor_pencacahan">Satuan Honor <strong>Pencacahan</strong></label
                                        >
                                        <select
                                            class="form-select"
                                            id="satuan_honor_pencacahan"
                                            name="satuan_honor_pencacahan"
                                        >
                                            <option value="">(Pilih salah satu)</option>
                                            <option value="Dokumen" {{ $kegiatan->satuan_honor_pencacahan == 'Dokumen' ? 'selected' : ''}}>Dokumen</option>
                                            <option value="SLS" {{$kegiatan->satuan_honor_pencacahan == 'SLS' ? 'selected' : ''}}>SLS (Satuan Lingkungan Setempat)</option>
                                            <option value="BS" {{$kegiatan->satuan_honor_pencacahan == 'BS' ? 'selected' : ''}}>BS (Blok Sensus)</option>
                                            <option value="Ruta" {{$kegiatan->satuan_honor_pencacahan == 'Ruta' ? 'selected' : ''}}>Rumah Tangga</option>
                                          
                                        </select>
                                        @if ($errors->has('satuan_honor_pencacahan'))
                                        <small class="form-text text-muted">{{ $errors->first('satuan_honor_pencacahan') }}</small>
                                        @else
                                        <small  class="form-text text-muted">
                                            Satuan honor bisa dikosongkan
                                        </small>
                                        @endif
                                    </div>

                                    <div class="form-group {{$errors->has('honor_pencacahan') ? 'has-error has-feedback' : ''}}">
                                        <label for="honor_pencacahan">Honor <strong>Pencacahan</strong></label>
                                        <input
                                          type="number"
                                          class="form-control"
                                          id="honor_pencacahan"
                                          name="honor_pencacahan"
                                          placeholder="Masukkan honor per satuan"
                                          value="{{ $kegiatan->honor_pencacahan }}"
                                        />
                                        @if ($errors->has('honor_pencacahan'))
                                        <small class="form-text text-muted">{{ $errors->first('honor_pencacahan') }}</small>
                                        @else
                                        <small  class="form-text text-muted">
                                            (isian honor dapat dikosongkan jika satuan honor belum dipilih)
                                        </small>
                                        @endif
                                    </div> 

                                </div>

                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <hr />
                                    <div class="{{$errors->has('pegawai[]') ? 'has-error has-feedback' : ''}}">
                                        <label for="pegawai[]" >Pegawai Terlibat</label>
                                        <select class="form-select" id="pegawai" name="pegawai[]" multiple="multiple">
                                            @foreach ($pegawais as $item)
                                            <option value="{{$item->id}}"
                                                {{in_array($item->id, $kegiatan->pegawai->pluck('id')->toArray()) ? 'selected' : ''}}>
                                                {{$item->nama}}</option>
                                                {{-- <option value="{{$item->id}}">{{$item->nama}}</option> --}}
                                            @endforeach
                                        </select>
                                        @if ($errors->has('pegawai[]'))
                                        <small class="form-text text-muted">{{ $errors->first('pegawai[]') }}</small>
                                        @else
                                        <small class="form-text text-muted">
                                        </small>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <hr />
                                    <div class="{{$errors->has('mitra[]') ? 'has-error has-feedback' : ''}}">
                                        <label for="mitra[]">Mitra Terlibat</label>
                                        <select class=" form-select" id="mitra" name="mitra[]" multiple="multiple">
                                            @foreach ($mitras as $item)
                                            <option value="{{$item->id}}"
                                                {{in_array($item->id, $kegiatan->mitra->pluck('id')->toArray()) ? 'selected' : ''}}>
                                                {{$item->nama}}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('mitra[]'))
                                        <small class="form-text text-muted">{{ $errors->first('mitra[]') }}</small>
                                        @else
                                        <small class="form-text text-muted">
                                        </small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-action">
                            <button type="submit" class="btn btn-success">Edit Kegiatan</button>
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
<script>
    $( '#pegawai' ).select2({
        theme: "bootstrap-5",
        width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',
        placeholder: $( this ).data( 'placeholder' ),
        closeOnSelect: false,
    }  );

    $( '#mitra' ).select2( {
        theme: "bootstrap-5",
        width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',
        placeholder: $( this ).data( 'placeholder' ),
        closeOnSelect: false,
    } );
</script>
@endsection