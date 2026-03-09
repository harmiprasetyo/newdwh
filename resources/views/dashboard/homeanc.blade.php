@extends('layouts.main');
@section('container');
<div class="container px-4">
<div class="row gx-5">
<div class="col">
     <div class="p-3  border bg-light text-center text-light">
        <div id="chartline">
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

<div class="row gx-5 mt-4">
<div class="col">
     <div class="p-3 xl-4 border bg-light text-center text-dark">
        <div id="chartcol">
    </div>

    </div>
    </div>

</div>
</div>


<script>
     var options = {
          series: [
          {
            type: 'rangeArea',
            name: 'K1 USG',

            data: [
              {
                x: 'Jan',
                y: [100, 120]
              },
              {
                x: 'Feb',
                y: [80, 100]
              },
              {
                x: 'Mar',
                y: [120, 200]
              },
              {
                x: 'Apr',
                y: [150, 340]
              },
              {
                x: 'May',
                y: [260, 390]
              },
              {
                x: 'Jun',
                y: [120, 540]
              },
              {
                x: 'Jul',
                y: [110, 230]
              },
              {
                x: 'Aug',
                y: [210, 360]
              }
            ]
          },



          {
            type: 'line',
            name: 'K1 UGS',
            data: [
              {
                x: 'Jan',
                y: 120
              },
              {
                x: 'Feb',
                y: 100
              },
              {
                x: 'Mar',
                y: 200
              },
              {
                x: 'Apr',
                y: 340
              },
              {
                x: 'May',
                y: 390
              },
              {
                x: 'Jun',
                y: 540
              },
              {
                x: 'Jul',
                y: 210
              },
              {
                x: 'Aug',
                y: 360
              }
            ]
          },
          {
            type: 'line',
            name: 'Standar 6x',
            data: [
              {
                x: 'Jan',
                y: 100
              },
              {
                x: 'Feb',
                y: 80
              },
              {
                x: 'Mar',
                y: 120
              },
              {
                x: 'Apr',
                y: 150
              },
              {
                x: 'May',
                y: 260
              },
              {
                x: 'Jun',
                y: 120
              },
              {
                x: 'Jul',
                y: 110
              },
              {
                x: 'Aug',
                y: 210
              }
            ]
          },
            {
            type: 'line',
            name: 'K1',
            data: [
              {
                x: 'Jan',
                y: 120
              },
              {
                x: 'Feb',
                y: 100
              },
              {
                x: 'Mar',
                y: 200
              },
              {
                x: 'Apr',
                y: 340
              },
              {
                x: 'May',
                y: 390
              },
              {
                x: 'Jun',
                y: 540
              },
              {
                x: 'Jul',
                y: 230
              },
              {
                x: 'Aug',
                y: 360
              }
            ]
          }

        ],
          chart: {
          height: 450,
          width:500,
          type: 'rangeArea',
          animations: {
            speed: 500
          }
        },
        colors: ['#d4526e', '#33b2df', '#d422ff', '#3366ff'],
        dataLabels: {
          enabled: false
        },
        fill: {
          opacity: [0.24, 0.24, 1, 1]
        },
        forecastDataPoints: {
          count: 2
        },
        stroke: {
          curve: 'straight',
          width: [0, 0, 2, 2]
        },
        legend: {
          show: true,
          customLegendItems: ['K1 USG', 'K1', 'Standar 6x'],
          inverseOrder: true
        },
        title: {
          text: 'Tren Kunjungan ANC dan Skrining USG'
        },
        markers: {
          hover: {
            sizeOffset: 5
          }
        }
        };

        var chart = new ApexCharts(document.querySelector("#chartline"), options);
        chart.render();




               var options = {
          series: [{
          data: [400, 430, 448, 470, 540]
        }],
          chart: {
          type: 'bar',
          height: 450
        },
        plotOptions: {
          bar: {
            borderRadius: 4,
            borderRadiusApplication: 'end',
            horizontal: true,
          }
        },
        dataLabels: {
          enabled: false
        },
        xaxis: {
          categories: ['Puskesmas A', 'Pukesmas B', 'Puskesmas C', 'Puskesmas D', 'Puskesmas E'
          ],
        },

         title: {
          text: 'Capaian ANC 6x dan 12 T Per Fasilitas Kesehatan'
        }
        };

        var chart = new ApexCharts(document.querySelector("#chartbar"), options);
        chart.render();





        var options = {
          series: [{
          data: [41, 52, 30]
        }],
          chart: {
          height: 350,
          type: 'bar',
          events: {
            click: function(chart, w, e) {
              // console.log(chart, w, e)
            }
          }
        },

        plotOptions: {
          bar: {
            columnWidth: '45%',
            distributed: true,
          }
        },
        dataLabels: {
          enabled: false,
          formatter: function (val) {
            return val + "%";
          }
        },
        legend: {
          show: false
        },
        xaxis: {
          categories: [
            'ANC 6x',
            'K1 USG',
            'Layanan 12T'
          ],
          labels: {
            style: {

              fontSize: '12px'
            },
           
          }
        }
        };

        var chart = new ApexCharts(document.querySelector("#chartcol"), options);
        chart.render();

    </script>
@endsection();
