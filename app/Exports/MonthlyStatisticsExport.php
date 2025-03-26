<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class MonthlyStatisticsExport implements FromCollection, WithHeadings, WithTitle, WithStyles, ShouldAutoSize
{
    protected $monthlyData;
    protected $summary;
    protected $year;

    public function __construct(array $monthlyData, array $summary, int $year)
    {
        $this->monthlyData = $monthlyData;
        $this->summary = $summary;
        $this->year = $year;
    }

    /**
     * @return Collection
     */
    public function collection()
    {
        $collection = new Collection();

        // Add title row
        $collection->push(['Monthly Subscription Income Report - ' . $this->year]);
        $collection->push(['January - December ' . $this->year]);
        $collection->push([]);  // Empty row

        // Add summary data
        $collection->push(['Summary Statistics']);
        $collection->push(['Annual Revenue', 'PHP ' . number_format($this->summary['annualRevenue'], 2)]);
        $collection->push(['Annual Subscriptions', $this->summary['annualSubscriptions']]);
        $collection->push(['Average Monthly Revenue', 'PHP ' . number_format($this->summary['avgMonthlyRevenue'], 2)]);
        $collection->push([]);  // Empty row

        // Add monthly data
        $collection->push(['Monthly Revenue Breakdown']);
        $collection->push(['Month', 'Revenue (PHP)', 'Subscriptions']);

        // Full month names
        $monthNames = [
            1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April', 
            5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
            9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
        ];

        // Add each month's data
        foreach ($monthNames as $monthNum => $monthName) {
            $total = 0;
            $count = 0;
            
            // Find the data for this month if it exists
            foreach ($this->monthlyData as $month) {
                if (array_search($month['label'], ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']) + 1 == $monthNum) {
                    $total = $month['total'];
                    $count = $month['count'] ?? 0;
                    break;
                }
            }
            
            $collection->push([
                $monthName,
                'PHP ' . number_format($total, 2),
                $count
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
        return 'Monthly Subscription Data';
    }

    public function styles(Worksheet $sheet)
    {
        // Style the title
        $sheet->getStyle('A1:C1')->getFont()->setBold(true)->setSize(16);
        $sheet->mergeCells('A1:C1');
        
        // Style the year range
        $sheet->getStyle('A2:C2')->getFont()->setItalic(true);
        $sheet->mergeCells('A2:C2');
        
        // Style the summary header
        $sheet->getStyle('A4:C4')->getFont()->setBold(true);
        $sheet->mergeCells('A4:C4');
        
        // Style the summary data
        $sheet->getStyle('A5:B7')->getFont()->setSize(11);
        
        // Style the monthly data header
        $sheet->getStyle('A9:C9')->getFont()->setBold(true);
        $sheet->mergeCells('A9:C9');
        
        // Style the monthly data column headers
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
