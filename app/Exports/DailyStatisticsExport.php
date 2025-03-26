<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class DailyStatisticsExport implements FromCollection, WithHeadings, WithTitle, WithStyles, ShouldAutoSize
{
    protected $data;
    protected $summary;
    protected $dateRange;

    public function __construct(array $data, array $summary, string $dateRange)
    {
        $this->data = $data;
        $this->summary = $summary;
        $this->dateRange = $dateRange;
    }

    /**
     * @return Collection
     */
    public function collection()
    {
        $collection = new Collection();

        // Add title row
        $collection->push(['Daily Subscription Income Report']);
        $collection->push([$this->dateRange]);
        $collection->push([]);  // Empty row

        // Add summary data
        $collection->push(['Summary Statistics']);
        $collection->push(['Total Revenue', 'PHP ' . number_format($this->summary['totalRevenue'], 2)]);
        $collection->push(['Total Subscriptions', $this->summary['totalSubscriptions']]);
        $collection->push(['Average Revenue Per User', 'PHP ' . number_format($this->summary['avgRevenue'], 2)]);
        $collection->push([]);  // Empty row

        // Add daily data
        $collection->push(['Daily Revenue Breakdown']);
        $collection->push(['Date', 'Revenue (PHP)', 'Subscriptions']);

        // Add each day's data
        foreach ($this->data as $date => $day) {
            $collection->push([
                $day['date'],
                'PHP ' . number_format($day['total'], 2),
                $day['count']
            ]);
        }

        return $collection;
    }

    public function headings(): array
    {
        // This is not used since we're building our own structure
        // but the interface requires it
        return [];
    }

    public function title(): string
    {
        return 'Daily Subscription Data';
    }

    public function styles(Worksheet $sheet)
    {
        // Style the title
        $sheet->getStyle('A1:C1')->getFont()->setBold(true)->setSize(16);
        $sheet->mergeCells('A1:C1');
        
        // Style the date range
        $sheet->getStyle('A2:C2')->getFont()->setItalic(true);
        $sheet->mergeCells('A2:C2');
        
        // Style the summary header
        $sheet->getStyle('A4:C4')->getFont()->setBold(true);
        $sheet->mergeCells('A4:C4');
        
        // Style the summary data
        $sheet->getStyle('A5:B7')->getFont()->setSize(11);
        
        // Style the daily data header
        $sheet->getStyle('A9:C9')->getFont()->setBold(true);
        $sheet->mergeCells('A9:C9');
        
        // Style the daily data column headers
        $sheet->getStyle('A10:C10')->getFont()->setBold(true);
        $sheet->getStyle('A10:C10')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('E0E0E0');
        
        // Center align all headers
        $sheet->getStyle('A1:C1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A2:C2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A4:C4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
        $sheet->getStyle('A9:C9')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
        $sheet->getStyle('A10:C10')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        // Right align all revenue values
        $revenue_cells = 'B11:B' . ($sheet->getHighestRow());
        $sheet->getStyle($revenue_cells)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
        
        // Center align all subscription counts
        $count_cells = 'C11:C' . ($sheet->getHighestRow());
        $sheet->getStyle($count_cells)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        return [
            // Additional styles could be added here
        ];
    }
}
