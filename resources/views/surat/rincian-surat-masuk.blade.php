@extends('layouts.app')

@section('content')

<div class="container">
  <div class="page-inner">
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4" >
      <div>
        <h3 class="fw-bold mb-3">{{$surat->nama}}</h3>
        {{-- <h6 class="op-7 mb-2">Rincian surat </h6> --}}
      </div>
      <div class="ms-md-auto py-2 py-md-0">
				{{-- <a href="{{url('surat/edit', $surat->id)}}" class="btn btn-primary btn-round">Edit Kegiatan</a>
        <a href="{{url('surat/edit-terlibat', $surat->id)}}" class="btn btn-primary btn-round">Edit Pegawai & Mitra</a> --}}
      </div>
    </div>

		<div class="row">
			<div class="card">
				<div class="card-header">
					<div class="card-title">Rincian Surat Masuk</div>
					{{-- <div class="card-category">Card Category</div> --}}
				</div>
				<div class="card-body">
					<table class=" table table-hover table-responsive">
						<tbody>
							<tr>
								<td width="40%">
									<p>Dinas Pemberi Surat</p>
								</td>
								<td>
									<p>: {{$surat->dinas_surat_masuk}} </p>
								</td>
							</tr>
                            <tr>
                                <td>
                                <p>Nomor Surat</p>
                                </td>
                                <td>
                                <p>: {{$surat->no_surat_masuk}} </p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                <p>Tanggal Surat Diterima</p>
                                </td>
                                <td>
                                <p>: {{\Carbon\Carbon::parse($surat->created_at)->translatedFormat('d F Y')}} </p>
                                </td>
                            </tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>

    <div class="row">
      <div class="col-sm-6 col-md-3">
        {{-- <div class="card card-stats card-round">
          <div class="card-body">
            <div class="row align-items-center">
              <div class="col-icon">
                <div
                  class="icon-big text-center icon-primary bubble-shadow-small"
                >
                  <i class="fas fa-users"></i>
                </div>
              </div>
              <div class="col col-stats ms-3 ms-sm-0">
                <div class="numbers">
                  <p class="card-category">Jumlah Kegiatan Tahun Ini</p>
                  <h4 class="card-title">{{$suratTahunIni}}</h4>
                </div>
              </div>
            </div>
          </div>
        </div> --}}
      </div>
      <div class="col-sm-6 col-md-3">
      </div>
      <div class="col-sm-6 col-md-3">
      </div>
    </div>
    <div class="row">
      <div class="col">
        <div class="card">
          <div class="card-header">
            <h4 class="card-title">Foto Surat Masuk</h4>
          </div>
          <div class="card-body">
            <div class="row">
                @foreach($surat->foto_surat_masuk as $foto)
                <div class="col py-5">
                    <img src="{{asset('uploads/surat/'.$foto->filename)}}" alt="foto" class="img-fluid">
                </div>
                @endforeach
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
