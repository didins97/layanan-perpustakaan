@extends('layouts.master')
@section('content')
    <div class="row ">
        <div class="col-md-6 mt-4 mb-2">
            <a class="btn btn-secondary btn-rounded" data-toggle="modal" data-target="#tambahRak"> Tambah Rak</a>
        </div>
        <div class="col-md-6 mt-4 mb-3 d-flex justify-content-end">
              <!-- Search form -->
              <form  action="{{ route('rak.search') }}" method="get" class="navbar-search navbar-search-light form-inline mr-sm-3 " id="navbar-search-main">

                <input type="text" placeholder="masukkan pencarian" id="search" class="form-control bg-white @error('q') is-invalid @enderror" name="q" autocomplete="off" autofocus>
               <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
    
            </form>
        </div>
        <div class="col-md-12">
            <div class="card">
                <!-- Card header -->
                <div class="card-header border-0">
                    <h3 class="my-3 text-center">{{ $title }}</h3>
                    <div class="success" data-flash="{{ session()->get('success') }}"></div>
                    <div class="hapus" data-flash="{{ session()->get('success') }}"></div>
                </div>
                <!-- Light table -->
                <div class="table">
                    <table class="table align-items-center table-flush">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col" class="sort" data-sort="Nama Rak">Nama Rak</th>
                                <th scope="col" class="sort" data-sort="Daya Tampung">Daya Tampung</th>
                                <th scope="col" class="sort" data-sort="Daya Tampung">Jumlah Buku</th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody class="list">
                            @foreach ($rak as $item)

                            <tr id="baris">
                                <th scope="row">
                                    <div class="media align-items-center">
                                        <div class="media-body">
                                            <span class="name mb-0 text-sm">{{ $item->nm_rak }}</span>
                                        </div>
                                    </div>
                                </th>
                                <td>
                                    <span class="badge badge-dot mr-4">
                                        <i class="bg-warning"></i>
                                        <span class="status">{{ $item->kapasitas }}</span> Buku
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-dot mr-4">
                                        <i class="bg-warning"></i>
                                        <span class="status">{{ $item->buku->sum('jumlah_buku') }}</span> Buku
                                    </span>
                                </td>
                                <td class="text-right">
                                    <div class="dropdown">
                                        <a class="btn btn-sm btn-icon-only text-light" href="#" role="button"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                            <button class="dropdown-item btn-detail" data-target="#detailRak" data-toggle="modal" data-id="{{$item->id}}">Detail</button>

                                            <button class="dropdown-item btn-edit" data-toggle="modal" data-target="#editRak" data-id="{{$item->id}}">Edit</button>
                        
                                            <form action="{{ route('rak-buku.destroy', $item->id) }}" method="post"
                                                id="delete{{ $item->id }}">
                                                @csrf
                                                @method('delete')
                                                <button class="dropdown-item" type="button"
                                                    onclick="deleteRak({{ $item->id}})">Hapus</button>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>

                        @endforeach
                        </tbody>
                    </table>
                </div>
                 <!-- Card footer -->
                 <div class="card-footer py-4">
                    <nav aria-label="...">

                        {{-- pagination --}}
                        @if ($rak->lastPage() != 1)
                            <ul class="pagination justify-content-end mb-0">
                                <li class="page-item">
                                    <a class="page-link" href="{{ $rak->previousPageUrl() }}" tabindex="-1">
                                        <i class="fas fa-angle-left"></i>
                                        <span class="sr-only">Previous</span>
                                    </a>
                                </li>
                                @for ($i = 1; $i <= $rak->lastPage(); $i++)
                                    <li class="page-item {{ $i == $rak->currentPage() ? 'active' : '' }}">
                                        <a class="page-link" href="{{ $rak->url($i) }}">{{ $i }}</a>
                                    </li>
                                @endfor
                                <li class="page-item">
                                    <a class="page-link" href="{{ $rak->nextPageUrl() }}">
                                        <i class="fas fa-angle-right"></i>
                                        <span class="sr-only">Next</span>
                                    </a>
                                </li>
                            </ul>
                        @endif

                        @if (count($rak) == 0)
                            <div class="text-center"> Tidak ada data!</div>
                        @endif

                    </nav>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('modal')

     {{-- Modal Tambah Buku --}}
     <div class="modal fade" id="tambahRak" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog  mt-5">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Tambah Rak</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <form action="{{ route('rak-buku.store') }}" method="post">
                        @csrf
                        @method('post')
                        <div class="form-group">
                            <label for="">Nama Rak</label>
                            <input type="text" name="nm_rak" value="" class="form-control">
                            @error('nm_rak')
                                <span class="text-danger"></span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="">Daya Tampung / Kapasitas</label>
                            <input type="number" min="0" name="kapasitas" value="" class="form-control">
                            @error('kapasitas')
                                <span class="text-danger"></span>
                            @enderror
                        </div>
                        <div class="float-right">
                            <button type="submit" class="btn btn-primary ">simpan</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>



    {{-- Modal Detail Buku  --}}
    <div class="modal fade" id="detailRak" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content  mt-5">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Detail Buku</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    ...
                </div>
            </div>
        </div>
     </div>

        {{-- Modal Edit Buku  --}}
    <div class="modal fade" id="editRak" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog  mt-5">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Rak</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    ...
                </div>
            </div>
        </div>
     </div>



@endsection


@push('script')
    <script>

        // delete buku
        function deleteRak(id) {
            
            Swal.fire({
                title: 'PERINGATAN!',
                text: "Yakin ingin menghapus Rak?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes',
                cancelButtonText: 'Cancle',
            }).then((result) => {
                if (result.value) {
                    $('#delete' + id).submit();
                }
            })
        }

        $(document).ready(function(){

            //detail buku
            $('.btn-detail').on('click',function(){
                let id = $(this).data('id');
                $.ajax({
                    url:`/rak-buku/${id}`,
                    method:'GET',
                    success:function(data){
                        $('#detailRak').find('.modal-body').html(data);
                        $('#detailRak').show();
                    }
                })
            })

             //edit buku
             $('.btn-edit').on('click',function(){
                 console.log('ok');
                let id = $(this).data('id');
                
                $.ajax({
                    url:`/rak-buku/${id}/edit`,
                    method:'GET',
                    success:function(data){
                        $('#editRak').find('.modal-body').html(data);
                        $('#editRak').show();
                    }
                })
            })

            $('#search').on('keyup', function() {
                // console.log($(this).val());
                $value = $(this).val();
                $.ajax({
                        type : 'get',
                        url : '/rak-search',
                        data:{'search':$value},
                        success:function(data){     
                            $('.list').html(data);
                        }
                });
            })
        })
            
    </script>
@endpush
