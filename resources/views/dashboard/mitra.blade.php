@extends('layouts.app')

@section('content')

<div class="container">
  <div class="page-inner">
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4" >
      <div>
        <h3 class="fw-bold mb-3">Dashboard Mitra</h3>
        <h6 class="op-7 mb-2">Rekap kegiatan dan honor mitra BPS Kabupaten Simeulue</h6>
      </div>
      <div class="ms-md-auto py-2 py-md-0">
        {{-- <a href="{{route('mitra.create')}}" class="btn btn-primary btn-round">Tambah mitra</a> --}}
      </div>
    </div>
    <div class="row">
      <div class="col-sm-6 col-md-3">
        <div class="card card-stats card-round">
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
                  <p class="card-category">Jumlah Mitra Aktif</p>
                  <h4 class="card-title">{{$mitraAktif}}</h4>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-sm-6 col-md-3">
      </div>
      <div class="col-sm-6 col-md-3">
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h4 class="card-title">Daftar Mitra</h4>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table
                id="multi-filter-select"
                class="display table table-striped table-hover"
              >
                <thead>
                  <tr>
                    {{-- <th style="width: 10%">Aksi</th> --}}
                    <th>Nama</th>
                    <th>Kec. Asal</th>
                    <th>Jumlah Kegiatan</th>
                    <th>Honor</th>
                  </tr>
                </thead>
                <tfoot>
                  <tr>
                    {{-- <th>Aksi</th> --}}
                    <th>Nama</th>
                    <th>Kec. Asal</th>
                    <th>Jumlah Kegiatan</th>
                    <th>Honor</th>
                  </tr>
                </tfoot>
                <tbody>
                  @foreach($mitras as $mitra)
                    <tr>
                      <th scope="row">{{$mitra->nama}}</th>
                      <td>{{$mitra->kec_asal}}</td>
                      <td>{{$mitra->kegiatan->count()}}</td>
                      <td>Rp {{number_format($mitra->honor, 0, ",", ".")}}</td>
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
</div>
@endsection
