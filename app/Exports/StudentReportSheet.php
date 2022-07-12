<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class StudentReportSheet implements FromCollection, WithHeadings, WithMapping, WithTitle, ShouldAutoSize, WithEvents
{
    private $data;
    private $number;

    public function __construct($data)
    {
        $this->data = $data;
        $this->number = 1;
    }


    /**
     * @return Collection
     */
    public function collection()
    {
        return $this->data;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        $headings = [
            [
                'تقرير الطلبة',
            ],
            [
                'جميع الحقوق محفوظة / جامعة البيان / تكنولوجيا المعلومات 2019©',
            ],
            [
                'التسلسل',
                'الرقم الجامعي',
                'الاسم',
                'الجنس',
                'الكلية',
                'القسم',
                'المرحلة',
                'نوع الدراسة',
                'الحالة',
            ]
        ];
        return $headings;

    }

    /**
     * @param mixed $row
     *
     * @return array
     */
    public function map($row): array
    {
        $data = [

            [
                $this->number,
                $row->student_id,
                $row->full_name,
                $this->gender($row->gender),
                $row->college_name,
                $row->department_name,
                $row->level,
                $row->shift,
                $row->academic_status_name,

            ],
        ];
        $this->number ++;
        return $data;
    }

    function gender($gender){
        if ($gender == 0){
            return "ذكر";
        } else if ($gender == 1){
            return "انثى";
        }else {
            return "";
        }
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return "Worksheet";
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getDelegate()->setRightToLeft(true);
                $event->sheet->getDelegate()->getStyle("A2:P2")->getFont()->setSize(14);

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
                $event->sheet->getDelegate()->getStyle('A1:M1')->applyFromArray($styleArrayHeader);
                $event->sheet->getDelegate()->getStyle('A2:M2')->applyFromArray($styleArrayHeader2);
                $event->sheet->getDelegate()->getStyle('A3:M3')->applyFromArray($styleArrayHeader3);
                $event->sheet->getDelegate()->getRowDimension('1')->setRowHeight(30);
                $event->sheet->getDelegate()->getRowDimension('2')->setRowHeight(16);
                $event->sheet->getDelegate()->getRowDimension('3')->setRowHeight(20);
                $event->sheet->getDelegate()->getRowDimension('4')->setRowHeight(20);
                $event->sheet->getDelegate()->mergeCells("A1:M1");
                $event->sheet->getDelegate()->mergeCells("A2:M2");

            },
        ];
    }
}
