@extends('layouts.main')
@section('container')

<div class="container px-4">

    <div class="row gx-5 mt-4">
<div class="col">
     <div class="p-3  border bg-light text-center text-dark">
        <div id="chartline">
    </div>

    </div>
    </div>

</div>

<div class="row gx-5 mt-4">
<div class="col">
     <div class="p-3  border bg-light text-center text-dark">
        <div id="chartline2">
    </div>

    </div>
    </div>
    <div class="col">
      <div class="p-3  border bg-light text-center text-dark">
        <div id="chartbar">
    </div>


    </div>
    </div>
</div>
</div>


<script>

     var options = {
          series: [{
          name: 'Jumlah Skrining',
          type: 'column',
          data: [440, 505, 414, 671, 227, 413, 201]
        }, {
          name: 'Presentase',
          type: 'line',
          data: [8, 8.2, 9, 8.9, 8, 9.5, 7]
        }],
          chart: {
          height: 450,
          type: 'line',
        },
        stroke: {
          width: [0, 4]
        },
        title: {
          text: 'Tren Skrining Pre Eklamsia'
        },
        dataLabels: {
          enabled: true,
          enabledOnSeries: [1]
        },
        labels: ['Jan 26', 'Feb 26', 'Mar 26', 'Apr 26', 'May 26', 'Jun 26', 'Jul 26'],
        yaxis: [{
          title: {
            text: 'Jumlah Skrining',
          },

        }, {
          opposite: true,
          title: {
            text: 'Presentase (%)'
          }
        }]
        };

        var chart = new ApexCharts(document.querySelector("#chartline"), options);
        chart.render();






        var options = {
          series: [{
          name: 'Jumlah Skrining',
          type: 'column',
          data: [100, 105, 214, 171, 221]
        }, {
          name: 'Presentase',
          type: 'line',
          data: [8, 8.7, 7.8, 9, 8.3]
        }],
          chart: {
          height: 450,
          type: 'line',
        },
        stroke: {
          width: [0, 4]
        },
        title: {
          text: 'Analisis Kasus Pre-eklamsia Per Faskes'
        },
        dataLabels: {
          enabled: true,
          enabledOnSeries: [1]
        },
        labels: ['Pusk A', 'Pusk B', 'Pusk C', 'Pusk D', 'Pusk E'],
        yaxis: [{
          title: {
            text: 'Jumlah Pasien',
          },

        }, {
          opposite: true,
          title: {
            text: 'Presentase (%)'
          }
        }]
        };

        var chart = new ApexCharts(document.querySelector("#chartline2"), options);
        chart.render();





         var options = {
          series: [{
          name: 'Jumlah Skrining',
          type: 'column',
          data: [100, 105, 214, 171, 221]
        }, {
          name: 'Presentase',
          type: 'line',
          data: [8, 8.7, 7.8, 9, 8.3]
        }],
          chart: {
          height: 450,
          type: 'line',
        },
        stroke: {
          width: [0, 4]
        },
        title: {
          text: 'Tata Laksana Kasus Pre-eklamsia Per Faskes'
        },
        dataLabels: {
          enabled: true,
          enabledOnSeries: [1]
        },
        labels: ['Pusk A', 'Pusk B', 'Pusk C', 'Pusk D', 'Pusk E'],
        yaxis: [{
          title: {
            text: 'Jumlah Pasien',
          },

        }, {
          opposite: true,
          title: {
            text: 'Presentase (%)'
          }
        }]
        };

        var chart = new ApexCharts(document.querySelector("#chartbar"), options);
        chart.render();

    </script>
@endsection()
