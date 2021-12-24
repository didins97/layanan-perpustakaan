@extends('layouts.master')
@section('content')
    <div class="row">
        <div class="col-md-12 mt-4">
            <div class="card">
                <div class="card-header border-0">
                  <div class="row align-items-center">
                    <div class="col">
                      <h3 class="text-left">{{ $title }}</h3>
                    </div>
                    <div class="col text-right">
                      <a href="{{ route('riwayat.index') }}" class="btn btn-primary">Tampilkan sedikit</a>
                    </div>
                  </div>
                  <br>
                <div class="row">
                  <form action="{{ route('riwayat.filter') }}" method="GET">
                      <div class="input-group ml-3">
                          <input type="date" class="form-control" name="start_date">
                          <input type="date" class="form-control" name="end_date">
                          <button class="btn btn-primary" type="submit">Filter</button>
                      </div>
                  </form>
              </div>
                </div>
                <div class="table-responsive">
                  <!-- Projects table -->
                  <table class="table align-items-center table-flush">
                    <thead class="thead-light">
                      <tr>
                        <th scope="col">Nama </th>
                        <th scope="col">Nim</th>
                        <th scope="col">Buku</th>
                        <th scope="col">Tanggal Pinjam</th>
                        <th scope="col">Tanggal Kembali</th>
                        <th scope="col">Status</th>
                      </tr>
                    </thead>
                    <tbody>
                        @foreach ($transaksi as $item)
                            
                        <tr>
                            <th scope="row">
                              {{ $item->anggota->nama }}
                          </th>
                          <td>
                              {{ $item->anggota->nim }}
                          </td>
                          <td>
                              {{ $item->buku->judul }}
                          </td>
                          <td>
                              {{ $item->tgl_pinjam }}
                          </td>
                          <td>
                              {{ $item->tgl_kembali }}
                          </td>
                          <td>
                              @if ($item->status == 'kembali')
                                  <span class="badge badge-dot mr-4">
                                      <i class="bg-success"></i>
                                  {{ $item->status }}
                                  </span>
                              @else 
                                  <span class="badge badge-dot mr-4">
                                      <i class="bg-danger"></i>
                                      {{ $item->status }}
                                  </span>
                              @endif
                              
                          </td>
                      </tr>
                      @endforeach
                  </tbody>
                  </table>
              </div>
        </div>
    </div>
@endsection