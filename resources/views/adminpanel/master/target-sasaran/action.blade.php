<div class="btn-group">

    <a
        href="{{ route('master.target-sasaran.edit',$row->id) }}"
        class="btn btn-warning btn-sm">

        <i class="fas fa-edit"></i>

    </a>

    <button
        class="btn btn-danger btn-sm btnDelete"
        data-id="{{$row->id}}">

        <i class="fas fa-trash"></i>

    </button>

</div>
