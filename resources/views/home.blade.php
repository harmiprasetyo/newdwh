


@extends('layouts.main')






@section('container')
<div class="container px-4">
  <div class="row gx-5">
    <div class="col">
     <div class="p-3 border bg-info text-center text-light">
        <h1 class="display4">{{ $sasaranbumil }}</h1>
        <p>Total Sasaran Ibu Hamil</p>

    </div>
    </div>
    <div class="col">
      <div class="p-3 border bg-warning text-center text-light"> <h1 class="display4">{{ $sasaranbulin }}</h1>
        <p>Total Sasaran Ibu Bersalin</p></div>
    </div>

    <div class="col">
      <div class="p-3 border bg-success text-center text-light"> <h1 class="display4">{{ $jmlfaskes }}</h1>
        <p>Total Faskes Terdaftar</p></div>
    </div>

  </div>


  <div class="row gx-5 mt-4">
    <div class="col">
     <div class="p-4 border bg-light text-center text-dark">
        <div class="card">

            <div class="card-body">
          <div id="chart">
          </div>



            </div>
        </div>


    </div>


    </div>
    <div class="col">
      <div class="p-2 border bg-light text-center text-dark">
        <div id="chart2"></div>


      </div>
    </div>



  </div>

  <div class="row gx-5 mt-4">



    <div class="col">
     <div class="p-4 border bg-light text-center text-dark">
        <div class="card">

            <div class="card-body">
          <div id="barchart">
          </div>



            </div>
        </div>


    </div>


    </div>




  </div>


</div>





<script>
            var options = {
          series: [{
          name: 'Puskesmas A',
          data: [800,600]
        }, {
          name: 'Puskesmas B',
          data: [530, 320]
        }, {
          name: 'Puskesmas C',
          data: [1200, 800]
        }, {
          name: 'Puskesmas D',
          data: [900, 600]
        }, {
          name: 'Puskesmas E',
          data: [1000, 800]
        }],
          chart: {
          type: 'bar',
          height: 300,
          stacked: true,
          stackType: '100%'
        },
        plotOptions: {
          bar: {
            horizontal: true,
          },
        },
        stroke: {
          width: 1,
          colors: ['#fff']
        },
        title: {
          text: 'Distribusi Sasaran Per Faskes'
        },
        xaxis: {
          categories: ['Sasaran Bumil', 'Sasaran Bulin'],
        },
        tooltip: {
          y: {
            formatter: function (val) {
              return val + "K"
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

        var chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();



               var options = {
          series: [15,44, 55, 41, 17, 15],
          chart: {
          width: 530,

          type: 'donut',
        },
        plotOptions: {
          pie: {
            startAngle: -90,
            endAngle: 270
          }
        },
        dataLabels: {
          enabled: true
        },
        fill: {
          type: 'gradient',
        },
        labels: ["<20 th","21 th s/d 25 th","26 th s/d 30 th","31 th s/d 35 th","36 th s/d 40 th",">45 th"],
        title: {
          text: 'Profil Usia Ibu Hamil'
        },
        responsive: [{
          breakpoint: 480,
          options: {
            chart: {
              width: 200
            },
            legend: {
              position: 'bottom'
            }
          }
        }]
        };

        var chart = new ApexCharts(document.querySelector("#chart2"), options);
        chart.render();





                var options = {
          series: [{
          name: 'Sasaran Bumil',
          data: [3200,1230]
        },{
          name: 'Sasaran Bulin',
          data: [2500,620]
        }],
          chart: {
          height: 550,
          type: 'bar',
        },
        plotOptions: {
          bar: {
            borderRadius: 1,
            dataLabels: {
              position: 'top', // top, center, bottom
            },
          }
        },
        dataLabels: {
          enabled: true,
          formatter: function (val) {
            return val;
          },
          offsetY: -20,
          style: {
            fontSize: '12px',
            colors: ["#304758"]
          }
        },

        xaxis: {
          categories: ["Puskesmas", "RSUD"],
          position: 'top',
          axisBorder: {
            show: false
          },
          axisTicks: {
            show: false
          },
          crosshairs: {
            fill: {
              type: 'gradient',
              gradient: {
                colorFrom: '#D8E3F0',
                colorTo: '#BED1E6',
                stops: [0, 100],
                opacityFrom: 0.4,
                opacityTo: 0.5,
              }
            }
          },
          tooltip: {
            enabled: true,
          }
        },
        yaxis: {
          axisBorder: {
            show: false
          },
          axisTicks: {
            show: false,
          },
          labels: {
            show: false,
            formatter: function (val) {
              return val;
            }
          }

        },
        title: {
          text: 'Distribusi Sasaran Berdasarkan tipe Fasyankes',
          floating: false,
          offsetY: 10,
          align: 'center',
          style: {
            color: '#444'
          }
        }
        };

        var chart = new ApexCharts(document.querySelector("#barchart"), options);
        chart.render();

    </script>



@endsection()
