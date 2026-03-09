@extends('layouts.mainchild')
@section('container')

<div class="container px-4">



    <div class="row gx-5 mt-4">
<div class="col">
     <div class="p-3  border bg-light text-center text-dark">
        <div id="chart1"></div>



    </div>
    </div>

    <div class="col">
     <div class="p-3  border bg-light text-center text-dark">
        <div id="chart2"></div>



    </div>
    </div>







</div>

</div>

<script>
         var options = {
          series: [{
          name: 'Jumlah Kunjungan',
          type: 'column',
          data: [100, 105, 214, 171, 221]
        }],
          chart: {
          height: 350,
          type: 'line',
        },
        stroke: {
          width: [0, 4]
        },
        title: {
          text: 'Jumlah Kunjungan KN'
        },
        dataLabels: {
          enabled: true,
          enabledOnSeries: [1]
        },
        labels: ['Pusk A', 'Pusk B', 'Pusk C', 'Pusk D', 'Pusk E'],
        yaxis: [{
          title: {
            text: 'Jumlah Kunjungan',
          },

        }, {
          opposite: true,
          title: {
            text: 'Presentase (%)'
          }
        }]
        };

        var chart = new ApexCharts(document.querySelector("#chart1"), options);
        chart.render();




         var options = {
          series: [{
          name: 'Jumlah Kunjungan',
          type: 'column',
          data: [50, 60, 150, 171, 120]
        }],
          chart: {
          height: 350,
          type: 'line',
        },
        stroke: {
          width: [0, 4]
        },
        title: {
          text: 'Jumlah Kunjungan IMD'
        },
        dataLabels: {
          enabled: true,
          enabledOnSeries: [1]
        },
        labels: ['Pusk A', 'Pusk B', 'Pusk C', 'Pusk D', 'Pusk E'],
        yaxis: [{
          title: {
            text: 'Jumlah Kunjungan',
          },

        }, {
          opposite: true,
          title: {
            text: 'Presentase (%)'
          }
        }]
        };

        var chart = new ApexCharts(document.querySelector("#chart2"), options);
        chart.render();
    </script>
@endsection()
