<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Color;

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
        $collection->push(['MEECO - Monthly Subscription Income Report']);
        $collection->push(['Annual Report for ' . $this->year]);
        $collection->push([]);  // Empty row

        // Add summary data
        $collection->push(['SUMMARY STATISTICS']);
        $collection->push(['Annual Revenue', 'PHP ' . number_format($this->summary['annualRevenue'], 2)]);
        $collection->push(['Annual Subscriptions', $this->summary['annualSubscriptions']]);
        $collection->push(['Average Monthly Revenue', 'PHP ' . number_format($this->summary['avgMonthlyRevenue'], 2)]);
        $collection->push([]);  // Empty row

        // Add monthly data
        $collection->push(['MONTHLY REVENUE BREAKDOWN']);
        $collection->push(['Month', 'Revenue (PHP)', 'Subscriptions Count']);

        // Full month names
        $monthNames = [
            1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April', 
            5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
            9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
        ];

        // Initialize array to store monthly data
        $formattedMonthlyData = [];
        
        // Find the data for each month
        foreach ($monthNames as $monthNum => $monthName) {
            $revenue = 0;
            $count = 0;
            
            // Try to find the data for this month
            foreach ($this->monthlyData as $month) {
                if (array_search($month['label'], ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']) + 1 == $monthNum) {
                    $revenue = isset($month['total']) ? (float)$month['total'] : 0;
                    $count = isset($month['count']) ? (int)$month['count'] : 0;
                    break;
                }
            }
            
            $formattedMonthlyData[] = [
                'month' => $monthName,
                'revenue' => $revenue,
                'count' => $count
            ];
        }

        // Add each month's data
        foreach ($formattedMonthlyData as $month) {
            $collection->push([
                $month['month'],
                'PHP ' . number_format($month['revenue'], 2),
                $month['count']
            ]);
        }

        // Add total row at the bottom
        $collection->push([]);  // Empty row
        $collection->push([
            'TOTAL',
            'PHP ' . number_format($this->summary['annualRevenue'], 2),
            $this->summary['annualSubscriptions']
        ]);

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
        // Define a moderate blue color for values
        $valueColor = '3B82F6';  // Moderate blue
        $borderColor = 'E5E7EB';  // Light gray border
        
        // HEADER STYLES
        // Style the title
        $sheet->getStyle('A1:C1')->getFont()->setBold(true)->setSize(14);
        $sheet->mergeCells('A1:C1');
        $sheet->getStyle('A1:C1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        // Style the year
        $sheet->getStyle('A2:C2')->getFont()->setItalic(true);
        $sheet->mergeCells('A2:C2');
        $sheet->getStyle('A2:C2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        

        $sheet->mergeCells('A3:C3');
        $sheet->getStyle('A3:A3')->getFont()->setBold(true);
        $sheet->getStyle('A3:C3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // SUMMARY SECTION STYLES
        // Style the summary header
        $sheet->getStyle('A4')->getFont()->setBold(true);
        $sheet->getStyle('A4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        $sheet->getStyle('B4')->getFont()->setColor(new Color($valueColor));
        

        $sheet->mergeCells('A7:C7');
        $sheet->getStyle('A7:C7')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        // Style the summary data
        $sheet->getStyle('A5:A7')->getFont()->setBold(true);
        $sheet->getStyle('A5:B7')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color($borderColor));
        
        // Style for B column values (monetary) - with moderate blue color
        $sheet->getStyle('B4:B6')->getFont()->setColor(new Color($valueColor));
        $sheet->getStyle('B4:B6')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        
        $sheet->getStyle('A8:C8')->getFont()->setBold(true);
        // MONTHLY DATA SECTION STYLES
        // Style the monthly data header
        // $sheet->getStyle('A9:C9')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        
        // Style the monthly data column headers
        // $sheet->getStyle('A10:C10')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color($borderColor));
        
        // Set styles for monthly data
        $dataLastRow = $sheet->getHighestRow() - 1; // Last row before totals
        if ($dataLastRow >= 9) { // Only if we have data
            $dataRange = 'A9:C' . $dataLastRow;
            $sheet->getStyle($dataRange)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color($borderColor));
            
            // Right-align revenue values and color them blue
            $sheet->getStyle('B9:B'.$dataLastRow)->getFont()->setColor(new Color($valueColor));
            $sheet->getStyle('B9:B'.$dataLastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            
            // Center-align subscription counts
            $sheet->getStyle('C9:C'.$dataLastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        }
        
        // Style the totals row
        $totalsRow = $sheet->getHighestRow();
        $sheet->getStyle('A'.$totalsRow.':C'.$totalsRow)->getFont()->setBold(true);
        $sheet->getStyle('A'.$totalsRow.':C'.$totalsRow)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color($borderColor));
        $sheet->getStyle('B'.$totalsRow)->getFont()->setColor(new Color($valueColor));
        $sheet->getStyle('B'.$totalsRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle('C'.$totalsRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        // Additional styling for the whole document
        $sheet->getStyle('A1:C'.$sheet->getHighestRow())->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        
        return [];
    }
}
