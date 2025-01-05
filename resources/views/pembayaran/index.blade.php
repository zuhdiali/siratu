@extends('layouts.app')

@section('content')

<div class="container">
  <div class="page-inner">
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4" >
      <div>
        <h3 class="fw-bold mb-3">Manajemen Pembayaran</h3>
        <h6 class="op-7 mb-2">Daftar pembayaran </h6>
      </div>
      <div class="ms-md-auto py-2 py-md-0">
        <a href="{{url('pembayaran/create/organik')}}" class="btn btn-primary btn-round">Tambah Pembayaran Organik</a>
        <a href="{{url('pembayaran/create/mitra')}}" class="btn btn-primary btn-round">Tambah Pembayaran Mitra</a>
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
                  <p class="card-category">Jumlah Pembayaran</p>
                  <h4 class="card-title">{{count($pembayarans)}}</h4>
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
            <h4 class="card-title">Daftar Pembayaran</h4>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table
                id="multi-filter-select"
                class="display table table-striped table-hover"
              >
                <thead>
                  <tr>
                    <th style="width: 10%">Aksi</th>
                    <th>Kegiatan</th>
                    <th>Tipe Pembayaran</th>
                    <th>Mitra/Pegawai Yang Dibayar</th>
                    <th>Nominal</th>
                    <th>Bukti Pembayaran</th>
                    
                  </tr>
                </thead>
                <tfoot>
                  <tr>
                    <th>Aksi</th>
                    <th>Kegiatan</th>
                    <th>Tipe Pembayaran</th>
                    <th>Mitra/Pegawai Yang Dibayar</th>
                    <th>Nominal</th>
                    <th>Bukti Pembayaran</th>
                  </tr>
                </tfoot>
                <tbody>
                  @foreach($pembayarans as $pembayaran)
                    <!-- Modal -->
                    <div class="modal fade" id="{{'exampleModal'.$pembayaran->id}}" tabindex="-1" aria-labelledby="{{'exampleModalLabel'.$pembayaran->id}}" aria-hidden="true">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h1 class="modal-title fs-5" id="{{'exampleModalLabel'.$pembayaran->id}}">Yakin Menghapus Pembayaran?</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <div class="modal-body">
                            Pembayaran <strong>{{$pembayaran->kegiatan->nama}}</strong> akan dihapus!
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batalkan</button>
                            <form action="{{url('pembayaran/destroy/'.$pembayaran->bukti_pembayaran->tipe_pembayaran.'/'.$pembayaran->id)}}">
                            <button type="submit" class="btn btn-danger hapus-pembayaran" >Hapus Pembayaran</button>
                            </form>
                          </div>
                        </div>
                      </div>
                    </div>
                    <tr>
                      <td>
                        <div class="form-button-action">
                          <form action="{{url('pembayaran/edit', $pembayaran->bukti_pembayaran_id)}}" method="GET">
                            <button
                              type="submit"
                              data-bs-toggle="tooltip"
                              title=""
                              class="btn btn-link btn-primary btn-lg"
                              data-original-title="Edit Pembayaran"
                            >
                            <i class="fa fa-edit"></i>
                          </form>
                          </button>

                          <button
                            type="button"
                            title="Hapus"
                            class="btn btn-link btn-danger"
                            data-bs-toggle="modal" 
                            data-bs-target="{{'#exampleModal'.$pembayaran->id}}"
                            data-original-title="Hapus"
                          >
                            <i class="fa fa-times"></i>
                          </button>
                        </div>
                      </td>
                      <th scope="row">{{$pembayaran->kegiatan->nama}}</th>
                      <td>{{$pembayaran->bukti_pembayaran->tipe_pembayaran}}</td>
                      @if ($pembayaran->mitra)
                        <td>{{$pembayaran->mitra->nama}}</td>
                        <td>Rp {{number_format($pembayaran->honor, 0, ",", ".")}}</td>
                      @else
                        <td>{{$pembayaran->pegawai->nama}}</td>
                        <td>Rp {{number_format($pembayaran->translok, 0, ",", ".")}}</td>
                      @endif
                      
                      <td>
                          <a href="{{url('pembayaran/lihat-bukti/'.$pembayaran->bukti_pembayaran->id)}}" class="btn btn-link btn-primary" target="_blank">Lihat Bukti Bayar</a>
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
</div>
@endsection
