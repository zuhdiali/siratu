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
                <form action="{{url('kegiatan/update-terlibat', $kegiatan->id)}}" method="POST">
                    @csrf <!-- {{ csrf_field() }} -->
                    <div class="card-header">
                        <div class="card-title">Edit Pegawai & Mitra Terlibat</div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="{{$errors->has('pegawai[]') ? 'has-error has-feedback' : ''}}">
                                    <label for="pegawai[]" >Pegawai</label>
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
                                <div class="{{$errors->has('mitra[]') ? 'has-error has-feedback' : ''}}">
                                    <label for="mitra[]">Mitra</label>
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
                        <button type="submit" class="btn btn-success">Simpan</button>
                        <a href="{{url('kegiatan')}}" class="btn btn-danger">Kembali</a>
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