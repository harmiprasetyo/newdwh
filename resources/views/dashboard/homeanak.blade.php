@extends('layouts.mainchild')
@section('container')

<div class="container px-4">

    <div class="row gx-5 mt-4">
<div class="col">
     <div class="p-3  border bg-light text-center text-dark">
        <div id="mainchart">
    </div>

    </div>
    </div>






</div>

    <div class="row gx-5 mt-4">
<div class="col">
     <div class="p-3  border bg-danger text-center text-dark">
        <b>AKB</b>
          <h1 class="display4">{{ $akb }}</h1>


    </div>
    </div>

    <div class="col">
     <div class="p-3  border bg-warning text-center text-dark">
         <b>AKN</b>
         <h1 class="display4">{{ $akn }}</h1>


    </div>
    </div>



    <div class="col">
     <div class="p-3  border bg-info text-center text-dark">
         <b>ASB</b>
           <h1 class="display4">{{ $asb }}</h1>


    </div>
    </div>



</div>

</div>

<script>
     var options = {
          series: [{
            name: "Kumulatif",
            data: [10, 41, 45, 51, 58, 62, 69, 91, 148]
        },{
            name: "Kelahiran baru",
            data: [5, 2, 6, 7, 1, 3, 5, 8, 7]
        }
    ],
          chart: {
          height: 350,
          type: 'line',
          zoom: {
            enabled: false
          }
        },
        dataLabels: {
          enabled: false
        },
        stroke: {
          curve: 'straight'
        },
        title: {
          text: 'Tren Kelahiran Hidup',
          align: 'left'
        },
        grid: {
          row: {
            colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
            opacity: 0.5
          },
        },
        xaxis: {
          categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep'],
        }
        };

        var chart = new ApexCharts(document.querySelector("#mainchart"), options);
        chart.render();
    </script>
@endsection()
