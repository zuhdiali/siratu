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
                    <form action="{{url('pembayaran/store/'. $objek)}}" method="POST" enctype="multipart/form-data">
                        @csrf <!-- {{ csrf_field() }} -->
                        <div class="card-header">
                            <div class="card-title">Tambah Pembayaran</div>
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
                                        >
                                        <option value="">(Pilih salah satu)</option>
                                         @foreach($kegiatans as $k)
                                            <option value="{{$k->id}}" >{{$k->nama}}</option>
                                        @endforeach
                                        </select>
                                        @if ($errors->has('id_kegiatan'))
                                        <small class="form-text text-muted">{{ $errors->first('id_kegiatan') }}</small>
                                        @endif
                                    </div>

                                    {{-- @if($objek == 'mitra')
                                    <div class="form-group  {{$errors->has('is_translok') ? 'has-error has-feedback' : ''}}">
                                        <input type="checkbox" name="is_translok" id="is_translok" />
                                        @if ($errors->has('is_translok'))
                                        <small class="form-text text-muted">{{ $errors->first('is_translok') }}</small>
                                        @endif
                                        <label for="is_translok"
                                          >Apakah translok</label
                                        >
                                    </div>
                                    @endif --}}

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
                                              value="{{ old('nominal') }}"
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
                                        <h4 class="card-title">@if($objek == 'mitra')Mitra @else Pegawai @endif Yang Belum Dibayarkan</h4>
                                        </div>
                                        <div class="card-body">
                                        <div class="table-responsive">
                                            <table
                                            id="basic-datatables"
                                            class="display table table-striped table-hover"
                                            >
                                            <thead>
                                                <tr>
                                                <th>Nama @if($objek == 'mitra')Mitra @else Pegawai @endif</th>
                                                <th>Honor</th>
                                                </tr>
                                            </thead>
                                            
                                            <tbody>

                                            </tbody>
                                            </table>
                                        </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-action">
                            <input type="hidden" name="tipe_pembayaran" value="{{$objek}}" />
                            <button type="submit" class="btn btn-success">Buat Pembayaran</button>
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
            ajaxMitra();
        });

        // $('#is_translok').change(function() {
        //     if($('#single-select-field').val()!= ''){
        //         ajaxMitra();
        //     }
        //     // console.log($(this).val());
        // });

        function ajaxMitra() {
            var kegiatan = $('#single-select-field').val();
            var is_translok = $('#is_translok').is(':checked');
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "GET",
                url: "{{ url('kegiatan') }}"  + '/' + ('{{$objek}}' == 'mitra' ? 'mitra-belum-dibayar' : 'pegawai-belum-dibayar') + '/' + kegiatan,
                // data: {
                //     is_translok: is_translok
                // },


                success: function(msg){
                    console.log(msg);
                    $('#basic-datatables').DataTable().clear().destroy();
                    if(msg.length > 0){
                        if('{{$objek}}' == 'mitra'){
                            msg.forEach(function(p){
                                $('table tbody').append('<tr><td>'+p.mitra.nama+'<input type="hidden" name="id_mitra['+p.mitra.id+']" value="'+p.mitra.id+'" /> </td><td><input type="number" name="honor['+p.mitra.id+']" class="form-control" /></td></tr> ');
                            });
                        }else{
                            msg.forEach(function(p){
                                $('table tbody').append('<tr><td>'+p.pegawai.nama+'<input type="hidden" name="id_pegawai['+p.pegawai.id+']" value="'+p.pegawai.id+'" /> </td><td><input type="number" name="translok['+p.pegawai.id+']" class="form-control" /></td></tr> ');
                            });
                        }
                    }
                    $('#basic-datatables').DataTable();


                    // console.log(msg);
                    // $("#id_mitra").empty();
                    // $("#id_mitra").append('<option value="">(Pilih salah satu)</option>');
                    // if(msg.length > 0){
                    //     msg.forEach(function(p){
                    //         $("#id_mitra").append('<option value="'+p.mitra.id+'">'+p.mitra.nama+'</option>');
                    //     });
                    // }

                },
                error: function(msg){
                    console.log(msg);
                }

            });
        }
    });

</script>
@endsection