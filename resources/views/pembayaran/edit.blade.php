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
                    <form action="{{url('pembayaran/update', $pembayaran->id)}}" method="POST" enctype="multipart/form-data">
                        @csrf <!-- {{ csrf_field() }} -->
                        <div class="card-header">
                            <div class="card-title">Edit Pembayaran</div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group  {{$errors->has('id_kegiatan') ? 'has-error has-feedback' : ''}}">
                                        <label for="id_kegiatan"
                                          >Kegiatan</label
                                        >
                                        <select
                                          class="form-select"
                                          id="single-select-field"
                                          name="id_kegiatan"
                                          data-placeholder="Pilih salah satu"
                                          disabled
                                        >
                                         {{-- @foreach($kegiatans as $k) --}}
                                            <option value="{{$kegiatan->id}}" selected>{{$kegiatan->nama}}</option>
                                        {{-- @endforeach --}}
                                        </select>
                                        @if ($errors->has('id_kegiatan'))
                                        <small class="form-text text-muted">{{ $errors->first('id_kegiatan') }}</small>
                                        @endif
                                    </div>

                                    {{-- <div class="form-group  {{$errors->has('id_mitra') ? 'has-error has-feedback' : ''}}">
                                        <label for="id_mitra"
                                          >Mitra</label
                                        >
                                        <select
                                          class="form-select"
                                          id="id_mitra"
                                          name="id_mitra"
                                          data-placeholder="Pilih salah satu"
                                          disabled
                                        >
                                         @foreach($mitras as $m)
                                            <option value="{{$m->mitra->id}}" {{$pembayaran->mitra_id == $m->mitra->id ? 'selected' : ''}}>{{$m->mitra->nama}}</option>
                                        @endforeach
                                        </select>
                                        @if ($errors->has('id_mitra'))
                                        <small class="form-text text-muted">{{ $errors->first('id_mitra') }}</small>
                                        @endif
                                    </div> --}}

                                </div>

                                <div class="col-md-6">

                                        <div class="form-group {{$errors->has('bukti_pembayaran') ? 'has-error has-feedback' : ''}}">
                                            <label for="bukti_pembayaran">Bukti Pembayaran</label>
                                            <br>
                                            <input
                                                type="file"
                                                class="form-control"
                                                id="bukti_pembayaran"
                                                name="bukti_pembayaran"
                                            />
                                            @if ($errors->has('bukti_pembayaran'))
                                            <small class="form-text text-muted">{{ $errors->first('bukti_pembayaran') }}</small>
                                            @else
                                            <small  class="form-text text-muted">
                                                Kosongkan jika tidak ingin mengubah bukti pembayaran.
                                            </small>
                                            @endif
                                        </div>

                                        {{-- <div class="form-group {{$errors->has('nominal') ? 'has-error has-feedback' : ''}}">
                                            <label for="nominal">Nominal</label>
                                            <input
                                              type="number"
                                              class="form-control"
                                              id="nominal"
                                              name="nominal"
                                              placeholder="(Boleh dikosongkan)"
                                              value="{{ $pembayaran->honor }}"
                                            />
                                            @if ($errors->has('nominal'))
                                            <small class="form-text text-muted">{{ $errors->first('nominal') }}</small>
                                            @else
                                            <small  class="form-text text-muted">
                                            </small>
                                            @endif
                                        </div>  --}}
    
                                </div>

                            </div>

                            <div class="row">
                                <div class="col">
                                    <div class="card">
                                        <div class="card-header">
                                        <h4 class="card-title">Mitra Yang Dibayarkan</h4>
                                        </div>
                                        <div class="card-body">
                                        <div class="table-responsive">
                                            <table
                                            id="basic-datatables"
                                            class="display table table-striped table-hover"
                                            >
                                            <thead>
                                                <tr>
                                                <th>Nama Mitra</th>
                                                <th>Honor</th>
                                                </tr>
                                            </thead>
                                            
                                            <tbody>
                                                @foreach($pembayaran->mitra_dibayar as $m)
                                                <tr>
                                                    <td>{{$m->mitra->nama}} <input type="hidden" name="id_mitra[{{$m->mitra->id}}]" value="{{$m->mitra->id}}" /> </td>
                                                    <td>
                                                        <input type="number" name="honor[{{$m->mitra->id}}]" class="form-control" value="{{$m->honor}}" />
                                                        <small class="form-text text-muted">Honor sebelumnya: Rp. {{number_format($m->honor, 0, ',', '.')}}</small>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                            </table>
                                        </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="id_mitra_sebelumnya" value="{{$pembayaran->mitra_id}}" />
                        <div class="card-action">
                            <button type="submit" class="btn btn-success">Perbarui Pembayaran</button>
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
    $(document).ready(function() {
        $( '#single-select-field' ).select2( {
                theme: "bootstrap-5",
                width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',
                placeholder: $( this ).data( 'placeholder' ),
            } );

            $('#single-select-field').change(function() {
                var kegiatan = $(this).val();
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "GET",
                    url: "{{ url('kegiatan/mitra-belum-dibayar') }}" + '/' + kegiatan,

                    success: function(msg){
                        console.log(msg);
                        $("#id_mitra").empty();
                        $("#id_mitra").append('<option value="">(Pilih salah satu)</option>');
                        if(msg.length > 0){
                            msg.forEach(function(p){
                                $("#id_mitra").append('<option value="'+p.mitra.id+'">'+p.mitra.nama+'</option>');
                            });
                        }

                    },
                    error: function(msg){
                        console.log(msg);
                    }

                });
        });
    });

</script>
@endsection