<?php

namespace App\Charts;

use ArielMejiaDev\LarapexCharts\LarapexChart;

class TestChart
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
        $chart = (new LarapexChart)->setTitle('Posts')
                   ->setDataset([150, 120])
                   ->setLabels(['Published', 'No Published']);

    }

    public function build()
    {

        return $this->chart->pieChart()
            ->setTitle('Top 3 scorers of the team.')
            ->setSubtitle('Season 2021.')
            ->addData([40, 50, 30])
            ->setLabels(['Player 7', 'Player 10', 'Player 9']);
    }
}
