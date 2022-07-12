<?php

namespace App\Exports;

use App\Town;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeSheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ByTownStudentReportSheet implements WithHeadings, WithTitle, WithEvents
{
    private $data;
    private $academic_year;
    private $count;
    private $stages;

    public function __construct($data, $academic_year)
    {
        $this->data = $data;
        $this->academic_year = $academic_year;
    }


    /**
     * @return array
     */
    public function headings(): array
    {

        $headings = [
            [
                'الجامعة',
                '',
                'الكلية'
            ],
            [

                'جدول رقم (3) الطلبة المقبولون (المسجلون والمباشرون فعلاً) بحسب محافظة السكن والجنس للعام الدراسي ' . $this->academic_year->start_year . '-' . $this->academic_year->end_year,
            ],
            [
                'محافظة السكن',
                'الطلبة المقبولون صف اول'
            ],
            [
                '',
                'ذكور',
                'اناث',
                'المجموع',
            ],


        ];
        return $headings;

    }


    /**
     * @return string
     */
    public function title(): string
    {
        return "عراقيين محافظات";
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {


        return [
            BeforeSheet::class => function (BeforeSheet $event) {

                $rows = $this->data;
                $row_index = 5; // first row
                $total_male = 0;
                $total_female = 0;

                foreach ($rows as $cells) {
                    $event->sheet->getDelegate()->setCellValueByColumnAndRow(1, $row_index, $cells[0]->t_name);


                    $sum = 0;
                    $town_id = $cells[0]->town_id;


                    if ($cells[0]->number != null){
                        foreach ($cells as $cell) {

                            if ($town_id != $cell->town_id) {
                                $sum = 0;
                            }

                            $column_index = 1;

                            // print sum
                            if ($cell->gender == 0) {
                                $event->sheet->getDelegate()->setCellValueByColumnAndRow($column_index + 1, $row_index, $cell->number);
                                $sum += $cell->number;
                                $event->sheet->getDelegate()->setCellValueByColumnAndRow($column_index + 3, $row_index, $sum);
                                $total_male += $cell->number;
                            } elseif ($cell->gender == 1) {
                                $event->sheet->getDelegate()->setCellValueByColumnAndRow($column_index + 2, $row_index, $cell->number);
                                $sum += $cell->number;
                                $event->sheet->getDelegate()->setCellValueByColumnAndRow($column_index + 3, $row_index, $sum);
                                $total_female += $cell->number;

                            }

                            $town_id = $cell->town_id;

                        }
                    }

                    $row_index += 1;
                }

                $event->sheet->getDelegate()->setCellValueByColumnAndRow($column_index, $row_index, 'الاجمالي');
                $event->sheet->getDelegate()->setCellValueByColumnAndRow($column_index + 1, $row_index, $total_male);
                $event->sheet->getDelegate()->setCellValueByColumnAndRow($column_index + 2, $row_index, $total_female);
                $event->sheet->getDelegate()->setCellValueByColumnAndRow($column_index + 3, $row_index, $total_male + $total_female);


                $styleZerosColor = [
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'color' => array('rgb' => 'EEEEEE')
                    ],
                ];

                $highestRow = $event->sheet->getDelegate()->getHighestRow(); // e.g. 10
                $highestColumn = $event->sheet->getDelegate()->getHighestColumn(); // e.g 'F'
                $highestColumnIndex = Coordinate::columnIndexFromString($highestColumn); // e.g. 5
                for ($row = 5; $row <= $highestRow; ++$row) {
                    for ($col = 2; $col <= $highestColumnIndex; ++$col) {
                        $value = $event->sheet->getDelegate()->getCellByColumnAndRow($col, $row)->getValue();
                        if ($value == "") {
                            $event->sheet->getDelegate()->setCellValueByColumnAndRow($col, $row, 0);
                            $event->sheet->getDelegate()->getStyleByColumnAndRow($col, $row)->applyFromArray($styleZerosColor);
                        }
                    }
                }
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
                        'size' => 12,
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
                        'color' => array('rgb' => '84e184')

                    ],
                ];

                $highestRow = $event->sheet->getDelegate()->getHighestRow(); // e.g. 10
                $highestColumn = $event->sheet->getDelegate()->getHighestColumn(); // e.g 'F'
                $highestColumnIndex = Coordinate::columnIndexFromString($highestColumn); // e.g. 5

                $range = $event->sheet->getDelegate()->calculateWorksheetDataDimension();
                $event->sheet->getDelegate()->getStyle($range)->applyFromArray($styleArray);

                $event->sheet->getDelegate()->getStyle($range)
                    ->getAlignment()->setWrapText(false);
                $event->sheet->getDelegate()->getColumnDimension('A')->setWidth(50);
                for ($i = 2; $i<= $highestColumnIndex;$i++){
                    $event->sheet->getDelegate()->getColumnDimensionByColumn($i)->setWidth(20);
                }

                $event->sheet->getDelegate()->getStyleByColumnAndRow(1, 3, $highestColumnIndex, 4)->applyFromArray($styleArrayHeader);
                $event->sheet->getDelegate()->getStyleByColumnAndRow(1, 1, 1, 1)->applyFromArray($styleArrayHeader);
                $event->sheet->getDelegate()->getStyleByColumnAndRow(3, 1, 3, 1)->applyFromArray($styleArrayHeader);

                // row 2
                $event->sheet->getDelegate()->mergeCellsByColumnAndRow(1, 2, $highestColumnIndex, 2);
                // row 3

                $event->sheet->getDelegate()->mergeCells("A3:A4");
                $event->sheet->getDelegate()->mergeCells("B3:D3");
                $event->sheet->getDelegate()->getStyleByColumnAndRow(1, $highestRow, 1, $highestRow)->applyFromArray($styleArrayHeader);

            },
        ];
    }


}
