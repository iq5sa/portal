<?php

namespace App\Exports;

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

class IraqiByStagesStudentReportSheet implements WithHeadings, WithTitle, ShouldAutoSize, WithEvents
{
    private $data;
    private $academic_year;
    private $count;
    private $stages;

    public function __construct($data, $academic_year)
    {
        $this->data = $data;
        $this->academic_year = $academic_year;
        $this->count = 0;
        $this->stages = [
            1 => 'المرحلة الاولى',
            2 => 'المرحلة الثانية',
            3 => 'المرحلة الثالثة',
            4 => 'المرحلة الرابعة',
            5 => 'المرحلة الخامسة',
            6 => 'المرحلة السادسة',
            7 => 'المرحلة السابعة',
        ];
    }


    /**
     * @return array
     */
    public function headings(): array
    {
        $s = [];
        $ss = [];


        array_push($s, 'ت');
        array_push($s, 'القسم');
        array_push($s, '');
        array_push($ss, '');
        array_push($ss, '');
        array_push($ss, '');

        foreach ($this->stages as $key => $stage) {
            array_push($s, $stage);
            array_push($s, '');
            array_push($s, '');
            array_push($ss, 'ذكور');
            array_push($ss, 'اناث');
            array_push($ss, 'المجموع');
        }
        array_push($s, 'المجموع');
        array_push($s, '');
        array_push($s, '');
        array_push($ss, 'ذكور');
        array_push($ss, 'اناث');
        array_push($ss, 'المجموع');


        $headings = [
            [
                'الجامعة',
                '',
                '',
                '',
                '',
                '',
                '',
                'الكلية'
            ],
            [

                'جدول رقم (2) الطلبة العراقيين الموجودين في الدراسات الاولية موزعين  حسب القسم والفرع والمرحلة والجنس للعام الدراسي ' . $this->academic_year->start_year . '-' . $this->academic_year->end_year,
            ],
            $s,
            $ss

        ];
        return $headings;

    }


    /**
     * @return string
     */
    public function title(): string
    {
        return "العراقيين المقبوليين";
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
                $sqn = 1;
                $total_male_stage = 0;
                $total_female_stage = 0;

                foreach ($rows as $cells) {
                    $event->sheet->getDelegate()->setCellValueByColumnAndRow(1, $row_index, $sqn);
                    $event->sheet->getDelegate()->setCellValueByColumnAndRow(2, $row_index, $cells[0]->department_name);

                    $total_male = 0;
                    $total_female = 0;
                    $sum = 0;
                    $level = $cells[0]->level;


                    foreach ($cells as $cell) {

                        if ($level != $cell->level) {
                            $sum = 0;
                        }

                        $column_index = ($cell->level * 3);

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

                        $level = $cell->level;

                    }
                    $total_male_stage += $total_female;
                    $total_female_stage += $total_female;


                    $a = sizeof($this->stages);
                    $event->sheet->getDelegate()->setCellValueByColumnAndRow(($a * 3) + 4, $row_index, $total_male);
                    $event->sheet->getDelegate()->setCellValueByColumnAndRow(($a * 3) + 5, $row_index, $total_female);
                    $event->sheet->getDelegate()->setCellValueByColumnAndRow(($a * 3) + 6, $row_index, $total_male + $total_female);

                    $row_index += 1;
                    $sqn += 1;
                }



                $styleZerosColor = [
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'color' => array('rgb' => 'EEEEEE')
                    ],
                ];

                $highestRow = $event->sheet->getDelegate()->getHighestRow(); // e.g. 10
                $highestColumn = $event->sheet->getDelegate()->getHighestColumn(); // e.g 'F'
                $highestColumnIndex = Coordinate::columnIndexFromString($highestColumn); // e.g. 5

                $total_row = $highestRow + 1;
                $col = 4;

                $event->sheet->getDelegate()->setCellValueByColumnAndRow(1,$total_row,'المجموع');


                for ($i= 0;$i< (sizeof($this->stages) * 3) + 3;$i++){
                    $column_string = Coordinate::stringFromColumnIndex($col);
                    $event->sheet->getDelegate()->setCellValueByColumnAndRow($col,$total_row,'=SUM('.$column_string.'5:'.$column_string.$highestRow.')');
                    $value = $event->sheet->getDelegate()->getCell($column_string.$total_row)->getCalculatedValue();
                    $event->sheet->getDelegate()->setCellValueByColumnAndRow($col,$total_row,$value);
                    $col +=1;
                }

                for ($row = 5; $row <= $highestRow; ++$row) {
                    for ($col = 4; $col <= $highestColumnIndex; ++$col) {
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
                /*$event->sheet->getDelegate()->getStyleByColumnAndRow(1, 3, $highestColumnIndex, 4)->applyFromArray($styleArrayHeader);
                $event->sheet->getDelegate()->getStyleByColumnAndRow(1, 1, 2, 1)->applyFromArray($styleArrayHeader);
                $event->sheet->getDelegate()->getStyleByColumnAndRow(8, 1, 9, 1)->applyFromArray($styleArrayHeader);
                $event->sheet->getDelegate()->getStyleByColumnAndRow(1, $highestRow, $highestColumnIndex, $highestRow)->applyFromArray($styleArrayHeader);*/


                // row 1
                $event->sheet->getDelegate()->mergeCells("A1:B1");
                $event->sheet->getDelegate()->mergeCells("C1:G1");
                $event->sheet->getDelegate()->mergeCells("H1:I1");
                $event->sheet->getDelegate()->mergeCellsByColumnAndRow(10, 1, $highestColumnIndex, 1);
                // row 2
                $event->sheet->getDelegate()->mergeCellsByColumnAndRow(1, 2, $highestColumnIndex, 2);

                $event->sheet->getDelegate()->mergeCells("A3:A4");
                $event->sheet->getDelegate()->mergeCells("B3:C4");


                $column = 4;
                for ($i = 0; $i <= sizeof($this->stages); $i++) {
                    $event->sheet->getDelegate()->mergeCellsByColumnAndRow($column, 3, $column + 2, 3);
                    $column += 3;
                }

                for ($x = 5; $x < $highestRow; $x++) {
                    $event->sheet->getDelegate()->mergeCellsByColumnAndRow(2, $x, 3, $x);
                }

                $event->sheet->getDelegate()->mergeCellsByColumnAndRow(1, $highestRow, 3, $highestRow);




            },
        ];
    }


}
