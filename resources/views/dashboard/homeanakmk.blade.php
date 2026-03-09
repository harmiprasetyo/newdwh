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
     <div class="p-3  border bg-light text-center text-dark">
       <div id="chart1"></div>

    </div>
    </div>

    <div class="col">
     <div class="p-3  border bg-light text-center text-dark">
         <div id="chart2">

         </div>


    </div>
    </div>




</div>

</div>

<script>
        var options = {
          series: [
          {
            name: 'Jumlah SHK',
            data: [
              {
                x: 'Pusk A',
                y: 1292,
                goals: [
                  {
                    name: '% SHK',
                    value: 900,
                    strokeHeight: 13,
                    strokeWidth:0,
                     strokeLineCap: 'round',
                    strokeColor: '#775DD0'
                  }
                ]
              },
                  {
                x: 'Pusk B',
                y: 2292,
                goals: [
                  {
                    name: '% SHK',
                    value: 1600,
                    strokeHeight: 13,
                    strokeWidth:0,
                     strokeLineCap: 'round',
                    strokeColor: '#775DD0'
                  }
                ]
              },
                   {
                x: 'Pusk C',
                y: 3600,
                goals: [
                  {
                    name: '% SHK',
                    value: 1900,
                    strokeHeight: 13,
                    strokeWidth:0,
                     strokeLineCap: 'round',
                    strokeColor: '#775DD0'
                  }
                ]
              },
                  {
                x: 'Pusk D',
                y: 2100,
                goals: [
                  {
                    name: '% SHK',
                    value: 1400,
                    strokeHeight: 13,
                    strokeWidth:0,
                     strokeLineCap: 'round',
                    strokeColor: '#775DD0'
                  }
                ]
              },
                  {
                x: 'Pusk E',
                y: 1392,
                goals: [
                  {
                    name: '% SHK',
                    value: 900,
                    strokeHeight: 13,
                    strokeWidth:0,
                     strokeLineCap: 'round',
                    strokeColor: '#775DD0'
                  }
                ]
              }



            ]
          }
        ],
          chart: {
          height: 350,
          type: 'bar'
        },
        plotOptions: {
          bar: {
            columnWidth: '60%'
          }
        },
        colors: ['#00E396'],
        dataLabels: {
          enabled: false
        },
        legend: {
          show: true,
          showForSingleSeries: true,
          customLegendItems: ['Jumlah SKH', '% SKH'],
          markers: {
            fillColors: ['#00E396', '#775DD0']
          }
        }
        };

        var chart = new ApexCharts(document.querySelector("#mainchart"), options);
        chart.render();



               var options = {
          series: [
          {
            name: 'Jumlah THS Positif',
            data: [
              {
                x: 'Pusk A',
                y: 1292,
                goals: [
                  {
                    name: '% Capaian',
                    value: 900,
                    strokeHeight: 13,
                    strokeWidth:0,
                     strokeLineCap: 'round',
                    strokeColor: '#775DD0'
                  }
                ]
              },
                  {
                x: 'Pusk A',
                y: 3292,
                goals: [
                  {
                    name: '% Capaian',
                    value: 1900,
                    strokeHeight: 13,
                    strokeWidth:0,
                     strokeLineCap: 'round',
                    strokeColor: '#775DD0'
                  }
                ]
              },
                 {
                x: 'Pusk C',
                y: 4292,
                goals: [
                  {
                    name: '% Capaian',
                    value: 2900,
                    strokeHeight: 13,
                    strokeWidth:0,
                     strokeLineCap: 'round',
                    strokeColor: '#775DD0'
                  }
                ]
              },
                  {
                x: 'Pusk D',
                y: 5192,
                goals: [
                  {
                    name: '% Capaian',
                    value: 5200,
                    strokeHeight: 13,
                    strokeWidth:0,
                     strokeLineCap: 'round',
                    strokeColor: '#775DD0'
                  }
                ]
              }




            ]
          }
        ],
          chart: {
          height: 350,
          type: 'bar'
        },
        plotOptions: {
          bar: {
            columnWidth: '60%'
          }
        },
        colors: ['#00E396'],
        dataLabels: {
          enabled: false
        },
        legend: {
          show: true,
          showForSingleSeries: true,
          customLegendItems: ['Jumlah SKH', '% SKH'],
          markers: {
            fillColors: ['#00E396', '#775DD0']
          }
        }
        };

        var chart = new ApexCharts(document.querySelector("#chart2"), options);
        chart.render();

    </script>
@endsection()
