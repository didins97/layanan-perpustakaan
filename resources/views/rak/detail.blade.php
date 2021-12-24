<div class="card">
    <div class="card-header border-0">
      <div class="row align-items-center">
        <div class="col">
            <small>Nama Rak</small>
            <br>
            <span class="h2 font-weight-bold mb-0">{{$rak->nm_rak}} Buku</span>
        </div>
        <div class="col text-right">
            <small>Daya Tampung</small>
            <br>
            <span class="h2 font-weight-bold mb-0">{{$rak->kapasitas}} Buku</span>
        </div>
      </div>
    </div>
    <div class="table-responsive">
      <!-- Projects table -->
      <table class="table align-items-center table-flush">
        <thead class="thead-light">
          <tr>
            <th scope="col">Nama Buku</th>
            <th scope="col">Jumlah</th>
          </tr>
        </thead>
        <tbody>
            @foreach ($rak->buku as $item) 
          <tr>
            <th scope="row">
              {{$item->judul}}
            </th>
            <td>
                {{$item->jumlah_buku}}
            </td>
          </tr>
            @endforeach
        </tbody>
      </table>
    </div>
</div>