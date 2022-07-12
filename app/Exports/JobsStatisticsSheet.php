<?php

namespace App\Exports;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeWriting;
use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class JobsStatisticsSheet implements FromArray, WithHeadings, WithMapping, WithTitle, ShouldAutoSize, WithEvents
{
    private $number;


    public function __construct()
    {
        $this->number = 0;
    }

    /**
     * @return array
     */
    public function array(): array
    {
        $sta1 = DB::select('select COUNT(jr.id) as number, cc.sp as spec,cc.jtid from job_requests as jr right JOIN (select jc.title,jt.speciality as sp ,jt.id as jtid from job_categories as jc INNER JOIN job_type_job_categories jtc on jc.id = jtc.job_category_id INNER JOIN job_types as jt on jt.id = jtc.job_type_id WHERE jc.active = 1 and jt.hide = 0) as cc on jr.job_types_id = cc.jtid GROUP BY cc.jtid');
        return $sta1;
    }

    /**
     * @return Builder
     */
    public function query()
    {

    }


    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            [
                'تقرير المتقدميين للتعيين على وظائف  جامعة اليبان حسب نظام التقديم الالكتروني',
            ],
            [
                'جميع الحقوق محفوظة / جامعة اليبان / وحدة تكنولوجيا المعلومات 2019©',
            ],
            [
              "أحصائية أعداد المتقدمين حسب اختصاص المتقدم"
            ],
            [
                'التسلسل',
                'العنوان',
                'العدد',
            ]
        ];
    }

    /**
     * @param mixed $row
     *
     * @return array
     */
    public function map($row): array
    {

        $this->number += 1;
        return [

            [
                $this->number,
                $row->spec,
                $row->number
            ],
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return "أحصائيات";
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            BeforeWriting::class=> function(BeforeWriting $event){
                $event->getDelegate()->setIncludeCharts(true);
            },
            AfterSheet::class => function (AfterSheet $event) {

                $event->sheet->getDelegate()->setRightToLeft(true);
                $styleArray = [
                    'font' => [
                        'bold' => true,
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_RIGHT,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                ];
                $styleArrayHeader = [
                    'font' => [
                        'bold' => true,
                        'size' => 18,
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_RIGHT,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THICK,
                        ],
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'color' => array('rgb' => 'CCCCCC')

                    ],
                ];
                $styleArrayHeader2 = [
                    'font' => [
                        'bold' => true,
                        'size' => 11,
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_RIGHT,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'color' => array('rgb' => 'CCCCCC')

                    ],
                ];
                $styleArrayHeader3 = [
                    'font' => [
                        'bold' => true,
                        'size' => 14,
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_RIGHT,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'color' => array('rgb' => '80DFFF')

                    ],
                ];
                $range = $event->sheet->getDelegate()->calculateWorksheetDataDimension();
                $event->sheet->getDelegate()->getStyle($range)->applyFromArray($styleArray);
                $event->sheet->getDelegate()->getStyle('A1:P1')->applyFromArray($styleArrayHeader);
                $event->sheet->getDelegate()->getStyle('A2:P2')->applyFromArray($styleArrayHeader2);
                $event->sheet->getDelegate()->getStyle('A3:P3')->applyFromArray($styleArrayHeader3);
                $event->sheet->getDelegate()->getStyle('A4:P4')->applyFromArray($styleArrayHeader3);
                $event->sheet->getDelegate()->getRowDimension('1')->setRowHeight(30);
                $event->sheet->getDelegate()->getRowDimension('2')->setRowHeight(16);
                $event->sheet->getDelegate()->getRowDimension('3')->setRowHeight(20);
                $event->sheet->getDelegate()->getRowDimension('4')->setRowHeight(20);
                $event->sheet->getDelegate()->mergeCells("A1:P1");
                $event->sheet->getDelegate()->mergeCells("A2:P2");
                $event->sheet->getDelegate()->mergeCells("A3:P3");
            },
        ];
    }

    /**
     * @return Chart|Chart[]
     */
    /*public function charts()
    {
        $label      = [new DataSeriesValues('String', 'Graph!$A$3', null, 1)];
        $categories = [new DataSeriesValues('String', 'Graph!$B$5:$B$22', null, 18)];
        $values     = [new DataSeriesValues('Number', 'Graph!$C$5:$C$22', null, 18)];

        $series = new DataSeries(DataSeries::TYPE_PIECHART, DataSeries::GROUPING_STANDARD,
            range(0, count($values) - 1), $label, $categories, $values);
        $plot   = new PlotArea(null, [$series]);

        $legend = new Legend();
        $chart  = new Chart('chart name', new Title('chart title'), $legend, $plot);

        $chart->setTopLeftPosition('D1');
        $chart->setBottomRightPosition('P22');

        return $chart;
    }*/
}
