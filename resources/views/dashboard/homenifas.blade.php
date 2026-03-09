@extends('layouts.main');
@section('container');
<div class="container px-4">

    <div class="row gx-5 mt-4">
<div class="col">
     <div class="p-3  border bg-light text-center text-dark">
        <div id="chartline1">
    </div>

    </div>
    </div>


    <div class="col">
     <div class="p-3  border bg-light text-center text-dark">
        <div id="chartline2">
    </div>

    </div>
    </div>



</div>

<div class="row gx-5 mt-4">
<div class="col">
     <div class="p-3  border bg-light text-center text-dark">
        <div id="chartline3">
    </div>

    </div>
    </div>
    <div class="col">
      <div class="p-3  border bg-light text-center text-dark">
        <div id="chartline4">
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
          text: 'Total Temuan Kasus anemia Per Faskes'
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

        var chart = new ApexCharts(document.querySelector("#chartline1"), options);
        chart.render();





        var options = {
          series: [{
          name: 'Jumlah Skrining',
          type: 'column',
          data: [100, 80, 114, 111, 121]
        }],
          chart: {
          height: 350,
          type: 'line',
        },
        stroke: {
          width: [0, 4]
        },
        title: {
          text: 'Sebaran Kasus HDK Per Faskes'
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

        var chart = new ApexCharts(document.querySelector("#chartline3"), options);
        chart.render();



         var options = {
          series: [{
          name: 'Jumlah Skrining',
          type: 'column',
          data: [150, 80, 214, 101, 21]
        }],
          chart: {
          height: 350,
          type: 'line',
        },
        stroke: {
          width: [0, 4]
        },
        title: {
          text: 'Tata Laksana Kasus HDK'
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

        var chart = new ApexCharts(document.querySelector("#chartline4"), options);
        chart.render();



                var options = {
          series: [{
          name: 'Anemia Ringan',
          data: [44, 55, 41, 37, 22, 43, 21]
        }, {
          name: 'Anemia Berat',
          data: [53, 32, 33, 52, 13, 43, 32]
        }],
          chart: {
          type: 'bar',
          height: 350,
          stacked: true,
        },
        plotOptions: {
          bar: {
            horizontal: true,
            dataLabels: {
              total: {
                enabled: true,
                offsetX: 0,
                style: {
                  fontSize: '13px',
                  fontWeight: 900
                }
              }
            }
          },
        },
        stroke: {
          width: 1,
          colors: ['#fff']
        },
        title: {
          text: 'Kategori Kasus Anemia Berdasarkan Faskes'
        },
        xaxis: {
          categories: ['Puskesmas A', 'Puskesmas B', 'Puskesmas C', 'Puskesmas D', 'Puskesmas E'],
          labels: {
            formatter: function (val) {
              return val
            }
          }
        },
        yaxis: {
          title: {
            text: undefined
          },
        },
        tooltip: {
          y: {
            formatter: function (val) {
              return val
            }
          }
        },
        fill: {
          opacity: 1
        },
        legend: {
          position: 'top',
          horizontalAlign: 'left',
          offsetX: 40
        }
        };

        var chart = new ApexCharts(document.querySelector("#chartline2"), options);
        chart.render();

    </script>
@endsection();
