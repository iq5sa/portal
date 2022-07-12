<?php

namespace App\Exports;

use App\JobRequest;
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

class JobsPerTypeSheet implements FromCollection, WithHeadings, WithMapping, WithTitle, ShouldAutoSize, WithEvents
{
    private $job_category_id;
    private $job_types;
    private $certificate;
    private $job_type_id;
    private $job_type_title;
    private $job_type_speciality;
    private $print_certificate;
    private $filter;
    private $number;

    /**
     * JobsPerTypeSheet constructor.
     * @param $job_category_id
     * @param $job_types
     * @param $certificate
     * @param $job_type_id
     * @param $job_type_title
     * @param $job_type_speciality
     * @param $print_certificate
     * @param $filter
     * @param $number
     */
    public function __construct($job_category_id, $job_types, $certificate, $job_type_id, $job_type_title, $job_type_speciality, $print_certificate, $filter)
    {
        $this->job_category_id = $job_category_id;
        $this->job_types = $job_types;
        $this->certificate = $certificate;
        $this->job_type_id = $job_type_id;
        $this->job_type_title = $job_type_title;
        $this->job_type_speciality = $job_type_speciality;
        $this->print_certificate = $print_certificate;
        $this->filter = $filter;
        $this->number = 0;
    }


    /**
     * @return Collection
     */
    public function collection()
    {
        if ($this->filter == true) {
            if ($this->job_types != null && $this->certificate == null) {
                return JobRequest::all()
                    ->where('job_category_id', '=', $this->job_category_id)
                    ->where('job_types_id', '=', $this->job_types);
            } elseif ($this->job_types == null && $this->certificate != null) {
                return JobRequest::all()
                    ->where('job_category_id', '=', $this->job_category_id)
                    ->where('certificate', '=', $this->certificate);
            } elseif ($this->job_types != null && $this->certificate != null) {
                return JobRequest::all()
                    ->where('job_category_id', '=', $this->job_category_id)
                    ->where('job_types_id', '=', $this->job_types)
                    ->where('certificate', '=', $this->certificate);
            } else {
                return JobRequest::all()
                    ->where('job_category_id', '=', $this->job_category_id);
            }
        }
        return JobRequest::all()
            ->where('job_category_id', '=', $this->job_category_id)
            ->where('job_types_id', '=', $this->job_type_id);
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        if (!$this->filter) {
            return [
                [
                    'تقرير المتقدميين للتعيين على وظائف جامعة اليبان الجامعة حسب نظام التقديم الالكتروني',
                ],
                [
                    'جميع الحقوق محفوظة /جامعة اليبان / وحدة تكنولوجيا المعلومات 2019©',
                ],
                [
                    "العنوان الوظيفي",
                    "",
                    $this->job_type_speciality . ' / ' . $this->job_type_title,
                ],
                [
                  "الشهادة",
                  "",
                    $this->print_certificate
                ],
                [
                    'التسلسل',
                    'رقم الاستمارة',
                    'الاسم الرباعي',
                    'أسم الام الثلاثي',
                    'تأريخ الميلاد',
                    'الجنس',
                    'العنوان',
                    'الشهادة',
                    'الاختصاص العام',
                    'الاختصاص الدقيق',
                    'تأريخ التخرج',
                    'بلد الدراسة',
                    'أسم الجامعة',
                    'رقم الهاتف',
                    'البريد الالكتروني',
                    'تأريخ التقديم'
                ]
            ];
        }

        return [
            [
                'تقرير المتقدميين للتعيين على وظائف جامعة اليبان حسب نظام التقديم الالكتروني',
            ],
            [
                'جميع الحقوق محفوظة / جامعة اليبان / وحدة تكنولوجيا المعلومات 2019©',
            ],
            [
                "العنوان الوظيفي",
                "",
                $this->job_type_speciality . ' / ' . $this->job_type_title,
            ],
            [
                "الشهادة",
                "",
                $this->print_certificate
            ],
            [
                'التسلسل',
                'رقم الاستمارة',
                'الاسم الرباعي',
                'أسم الام الثلاثي',
                'تأريخ الميلاد',
                'الجنس',
                'العنوان',
                'الشهادة',
                'الاختصاص العام',
                'الاختصاص الدقيق',
                'تأريخ التخرج',
                'بلد الدراسة',
                'أسم الجامعة',
                'رقم الهاتف',
                'البريد الالكتروني',
                'تأريخ التقديم'
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

        $certificate = null;
        switch ($row->certificate) {
            case 1 :
                $certificate = "بكالوريوس";
                break;
            case 2 :
                $certificate = "ماجستير";;
                break;
            case 3 :
                $certificate = "دكتوراه";
                break;
        }
        $this->number += 1;
        return [

            [
                $this->number,
                $row->form_id,
                $row->firstName . " " . $row->middleName . " " . $row->lastName . ' ' . $row->surname,
                $row->mother_firstName . " " . $row->mother_middleName . " " . $row->mother_lastName,
                $row->dateOfBirth,
                $row->gender,
                $row->city . " " . $row->country,
                $certificate,
                $row->specialityGeneral,
                $row->specialitySpacial,
                $row->graduateYear,
                $row->countryOfStudy,
                $row->universityOrCollege,
                $row->phone,
                $row->email,
                $row->created_at
            ],
        ];
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
                $event->sheet->getDelegate()->getStyle('A1:P1')->applyFromArray($styleArrayHeader);
                $event->sheet->getDelegate()->getStyle('A2:P2')->applyFromArray($styleArrayHeader2);
                $event->sheet->getDelegate()->getStyle('A3:P3')->applyFromArray($styleArrayHeader3);
                $event->sheet->getDelegate()->getStyle('A4:P4')->applyFromArray($styleArrayHeader3);
                $event->sheet->getDelegate()->getStyle('A5:P5')->applyFromArray($styleArrayHeader3);
                $event->sheet->getDelegate()->getRowDimension('1')->setRowHeight(30);
                $event->sheet->getDelegate()->getRowDimension('2')->setRowHeight(16);
                $event->sheet->getDelegate()->getRowDimension('3')->setRowHeight(20);
                $event->sheet->getDelegate()->getRowDimension('4')->setRowHeight(20);
                $event->sheet->getDelegate()->mergeCells("A1:P1");
                $event->sheet->getDelegate()->mergeCells("A2:P2");
                $event->sheet->getDelegate()->mergeCells("A3:B3");
                $event->sheet->getDelegate()->mergeCells("C3:P3");
                $event->sheet->getDelegate()->mergeCells("A4:B4");
                $event->sheet->getDelegate()->mergeCells("C4:P4");

            },
        ];
    }
}
