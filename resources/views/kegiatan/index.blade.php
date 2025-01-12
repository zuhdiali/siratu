@extends('layouts.app')

@section('content')

<div class="container">
  <div class="page-inner">
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4" >
      <div>
        <h3 class="fw-bold mb-3">Manajemen Kegiatan</h3>
        <h6 class="op-7 mb-2">Daftar kegiatan </h6>
      </div>
      <div class="ms-md-auto py-2 py-md-0">
        <a href="{{route('kegiatan.create')}}" class="btn btn-primary btn-round">Tambah kegiatan</a>
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
                  <p class="card-category">Jumlah Kegiatan Tahun Ini</p>
                  <h4 class="card-title">{{$kegiatanTahunIni}}</h4>
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
            <h4 class="card-title">Daftar Kegiatan</h4>
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
                    <th>Nama Kegiatan</th>
                    <th>Tim</th>
                    <th>PJK</th>
                    <th>Tanggal Mulai</th>
                    <th>Tanggal Selesai</th>
                    <th>Jumlah Mitra</th>
                    <th>Honor (Pengawasan)</th>
                    <th>Honor (Pencacahan)</th>
                    <th>Detil Kegiatan</th>
                  </tr>
                </thead>
                <tfoot>
                  <tr>
                    <th>Aksi</th>
                    <th>Nama Kegiatan</th>
                    <th>Tim</th>
                    <th>PJK</th>
                    <th>Tanggal Mulai</th>
                    <th>Tanggal Selesai</th>
                    <th>Jumlah Mitra</th>
                    <th>Honor (Pengawasan)</th>
                    <th>Honor (Pencacahan)</th>
                    <th>Detil Kegiatan</th>
                  </tr>
                </tfoot>
                <tbody>
                  @foreach($kegiatans as $kegiatan)
                    <!-- Modal -->
                    @if((Auth::user()->role == 'Admin')||($kegiatan->id_pjk == Auth::user()->id))
                    <div class="modal fade" id="{{'exampleModal'.$kegiatan->id}}" tabindex="-1" aria-labelledby="{{'exampleModalLabel'.$kegiatan->id}}" aria-hidden="true">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h1 class="modal-title fs-5" id="{{'exampleModalLabel'.$kegiatan->id}}">Yakin Menghapus Kegiatan?</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <div class="modal-body">
                            Kegiatan <strong>{{$kegiatan->nama}}</strong> akan ditandai sebagai kegiatan tidak aktif!
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batalkan</button>
                            <form action="{{url('kegiatan/destroy/'.$kegiatan->id)}}">
                            <button type="submit" class="btn btn-danger hapus-kegiatan" >Hapus Kegiatan</button>
                            </form>
                          </div>
                        </div>
                      </div>
                    </div>
                    @endif
                    <tr>
                      <td>
                        @if((Auth::user()->role == 'Admin')||($kegiatan->id_pjk  == Auth::user()->id))
                        <div class="form-button-action">
                          <form action="{{url('kegiatan/edit', $kegiatan->id)}}">
                            <button
                              type="submit"
                              data-bs-toggle="tooltip"
                              title=""
                              class="btn btn-link btn-primary btn-lg"
                              data-original-title="Edit Kegiatan"
                            >
                            <i class="fa fa-edit"></i>
                          </form>
                          </button>

                          <button
                            type="button"
                            title="Hapus"
                            class="btn btn-link btn-danger"
                            data-bs-toggle="modal" 
                            data-bs-target="{{'#exampleModal'.$kegiatan->id}}"
                            data-original-title="Hapus"
                          >
                            <i class="fa fa-times"></i>
                          </button>
                        </div>
                        @endif
                      </td>
                      <th scope="row">{{(strlen($kegiatan->nama)>90 ? substr($kegiatan->nama, 0, 90) . '...' : $kegiatan->nama)}}</th>
                      <td>{{$kegiatan->tim}}</td>
                      <td>{{$kegiatan->pjk->nama}}</td>
                      <td>{{Carbon\Carbon::parse($kegiatan->tgl_mulai)->locale('id')->translatedFormat('d M Y') }}</td>
                      <td>{{Carbon\Carbon::parse($kegiatan->tgl_selesai)->locale('id')->translatedFormat('d M Y') }}</td>
                      <td>{{$kegiatan->mitra->count()}}</td>
                      <td>{{number_format($kegiatan->honor_pengawasan, 0, ",", ".")}} / {{$kegiatan->satuan_honor_pengawasan}}</td>
                      <td>{{number_format($kegiatan->honor_pencacahan, 0, ",", ".")}} / {{$kegiatan->satuan_honor_pencacahan}}</td>
                      <td>
                        <a href="{{url('kegiatan/show', $kegiatan->id)}}" class="btn btn-primary btn-border btn-round">Detil Kegiatan</a>
                      </td>
                      {{-- <td>
                        @if($kegiatan->flag == null)
                        <span class="badge bg-success">Aktif</span>
                        @else
                        <span class="badge bg-danger">Tidak Aktif</span>
                        @endif
                      </td> --}}
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
