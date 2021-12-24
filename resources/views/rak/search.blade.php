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
            <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-ellipsis-v"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                <button class="dropdown-item btn-detail" data-target="#detailRak" data-toggle="modal">Detail</button>

                <button class="dropdown-item btn-edit" data-toggle="modal" data-target="#editRak"
                    data-id="{{$item->id}}">Edit</button>

                <form action="{{ route('rak-buku.destroy', $item->id) }}" method="post" id="delete{{ $item->id }}">
                    @csrf
                    @method('delete')
                    <button class="dropdown-item" type="button" onclick="deleteRak({{ $item->id}})">Hapus</button>
                </form>
            </div>
        </div>
    </td>
</tr>

@endforeach
