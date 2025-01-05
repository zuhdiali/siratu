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
			<div class="card">
				<div class="card-header">
					<div class="card-title">Rincian Kegiatan {{$kegiatan->nama}}</div>
					{{-- <div class="card-category">Card Category</div> --}}
				</div>
				<div class="card-body">
					<table class=" table table-hover table-responsive">
						<tbody>
							<tr>
								<td width="40%">
									<p>Honor <strong>Pengawasan</strong></p>
								</td>
								<td>
									<p>: Rp {{number_format($kegiatan->honor_pengawasan, 0, ",", ".")}} / {{$kegiatan->satuan_honor_pengawasan}}</p>
								</td>
							</tr>
							<tr>
								<td>
									<p>Honor <strong>Pencacahan</strong></p>
								</td>
								<td>
									<p>: Rp {{number_format($kegiatan->honor_pencacahan, 0, ",", ".")}} / {{$kegiatan->satuan_honor_pencacahan}}</p>
								</td>
							</tr>
						</tbody>
					</table>

                                        
                    <form action="{{url('kegiatan/estimasi-honor/'. $kegiatan->id)}}" method="POST">
                        @csrf <!-- {{ csrf_field() }} -->
                            <div class="row">
                                <div class="col">
                                    <div class="card">
                                        <div class="card-header">
                                        <h4 class="card-title">Estimasi Honor Mitra</h4>
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
                                                <th>Estimasi Honor Yang Didapat Dari Kegiatan Ini</th>
                                                </tr>
                                            </thead>
                                            
                                            <tbody>
                                                @foreach($kegiatan->mitra as $mitra)
                                                <tr>
                                                    <td>{{$mitra->nama}}<input type="hidden" name="id_mitra[{{$mitra->id}}]" value="{{$mitra->id}}" /> </td>
                                                    <td><input type="number" name="estimasi_honor[{{$mitra->id}}]" class="form-control" value="{{$mitra->pivot->estimasi_honor}}"/></td>
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
                        <div class="card-action">
                            <button type="submit" class="btn btn-success">Perbarui Estimasi Honor</button>
                        </div>
                    </form>
			</div>
		</div>

    </div>
</div>
@endsection

@section('script')
<script src="{{asset('select2/js/select2.full.min.js')}}"></script>

@endsection