<h1>Data dari API Eksternal</h1>
<?php  ?>

<TABLE>
    <header>
        <tr>
            <th>Fulll Url</th>
            <th>Patient Id</th>
            <th>Patient NIK</th>



        </tr>
    </header>
    <body>
@foreach($dt as $val)
<tr>
    <td>{{ $val['fullUrl'] }}</td>
    <td>{{ $val['id'] }}</td>
    <td>{{ $val['nik'] }}</td>












</tr>

@endforeach
    </body>

