<div
class="offcanvas offcanvas-end"
tabindex="-1"
id="offcanvasForm"
style="width:700px">

<div class="offcanvas-header">

<h5>

Wilayah Kerja Posyandu

</h5>

<button
type="button"
class="btn-close"
data-bs-dismiss="offcanvas">
</button>

</div>

<div class="offcanvas-body">

<form
id="formData">

@csrf
<input
type="hidden"
id="id"
name="id">
<div class="mb-3">

<label>

Posyandu

</label>

<select
    name="kodePosyandu"
    id="kodePosyandu"
    class="form-select">

    <option value="">Pilih Posyandu</option>

    @foreach($posyandu as $item)

        <option value="{{ $item->kodePosyandu }}">
            {{ $item->namaPosyandu }}
        </option>

    @endforeach

</select>

</div>

<div class="mb-3">

    <label class="form-label">

        RW

    </label>

    <input
        id="rw"
        name="rw"
        class="form-control">

    <small class="text-muted">
        Tekan Enter setelah mengetik setiap RW.
    </small>

</div>
<button
class="btn btn-primary">

Simpan

</button>

</form>

</div>

</div>

<script>
    const tagifyRW = new Tagify(document.querySelector('#rw'),{

    duplicates:false,

    maxTags:30,

    pattern:/^[0-9]{1,3}$/,

    dropdown:{
        enabled:0
    }

});




$('#kodePosyandu').select2({

    dropdownParent: $('#offcanvasForm'),

    width:'100%',

    placeholder:'Pilih Posyandu'

});


    </script>
