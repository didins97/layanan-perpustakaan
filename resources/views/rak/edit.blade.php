<form action="{{ route('rak-buku.update',$rak->id) }}" method="post">
    @csrf
    @method('put')
    <div class="form-group">
        <label for="">Nama Rak</label>
        <input type="text" name="nm_rak" value="{{$rak->nm_rak}}" class="form-control">
        @error('nm_rak')
        <span class="text-danger"></span>
        @enderror
    </div>
    <div class="form-group">
        <label for="">Daya Tampung / Kapasitas</label>
        <input type="number" min="0" name="kapasitas" value="{{$rak->kapasitas}}" class="form-control">
        @error('kapasitas')
        <span class="text-danger"></span>
        @enderror
    </div>
    <div class="float-right">
        <button type="reset" class="btn btn-danger">reset</button>
        <button type="submit" class="btn btn-primary">update</button>
    </div>
</form>

{{-- <script>
 function readURL() {
            var input = this;
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $(input).prev().attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        $(function () {
            $(".uploads").change(readURL)
            $("#f").submit(function(){
                return false
            })
        })

</script> --}}
