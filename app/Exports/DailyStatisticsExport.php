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
        $collection->push(['MEECO - Daily Subscription Income Report']);
        $collection->push([$this->dateRange]);
        $collection->push([]);  // Empty row

        // Add summary data
        $collection->push(['SUMMARY STATISTICS']);
        $collection->push(['Total Revenue', 'PHP ' . number_format($this->summary['totalRevenue'], 2)]);
        $collection->push(['Total Subscriptions', $this->summary['totalSubscriptions']]);
        $collection->push(['Average Revenue Per User', 'PHP ' . number_format($this->summary['avgRevenue'], 2)]);
        $collection->push([]);  // Empty row

        // Add daily data
        $collection->push(['DAILY REVENUE BREAKDOWN']);
        $collection->push(['Date', 'Revenue (PHP)', 'Subscriptions Count']);

        // Check if we have at least one day
        if (empty($this->data)) {
            // Add a placeholder row if no data exists
            $collection->push(['No data available', 'PHP 0.00', '0']);
        } else {
            // Debug log the data structure
            \Illuminate\Support\Facades\Log::info('Daily data structure:', [
                'days_count' => count($this->data),
                'first_day' => json_encode(reset($this->data)),
                'structure' => json_encode(array_keys($this->data))
            ]);
            
            // Add each day's data - ensure all values are properly formatted
            foreach ($this->data as $date => $day) {
                // Ensure 'date' field exists
                $displayDate = isset($day['date']) ? $day['date'] : 'Unknown date';
                
                // Ensure revenue is a number and format it consistently
                $revenue = isset($day['total']) ? (float)$day['total'] : 0;
                $formattedRevenue = 'PHP ' . number_format($revenue, 2);
                
                // Ensure count is a number
                $count = isset($day['count']) && is_numeric($day['count']) ? (int)$day['count'] : 0;
                
                // Create complete row with all three columns filled
                $collection->push([
                    $displayDate,             // Column A: Date
                    $formattedRevenue,        // Column B: Revenue
                    $count                    // Column C: Count
                ]);
                
                // Log each row for debugging
                \Illuminate\Support\Facades\Log::info("Added row for {$displayDate}", [
                    'revenue' => $formattedRevenue,
                    'count' => $count
                ]);
            }
        }

        // Add total row at the bottom
        $collection->push([]);  // Empty row
        $collection->push([
            'TOTAL',
            'PHP ' . number_format($this->summary['totalRevenue'], 2),
            $this->summary['totalSubscriptions']
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
        return 'Daily Subscription Data';
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
        
        // Style the date range
        $sheet->getStyle('A2:C2')->getFont()->setItalic(true);
        $sheet->mergeCells('A2:C2');
        $sheet->getStyle('A2:C2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        

        $sheet->mergeCells('A3:C3');
        $sheet->getStyle('A3:C3')->getFont()->setBold(true);
        $sheet->getStyle('A3:C3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // SUMMARY SECTION STYLES
        // Style the summary header
        $sheet->getStyle('A4')->getFont()->setBold(true);
        $sheet->mergeCells('A4');
        $sheet->getStyle('A4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

        $sheet->getStyle('B4')->getFont()->setColor(new Color($valueColor));
        $sheet->getStyle('B4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        
        // Style the summary data
        $sheet->mergeCells('A7:C7');
        $sheet->getStyle('A5:A7')->getFont()->setBold(true);
        $sheet->getStyle('A5:B7')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color($borderColor));
        $sheet->getStyle('A7:C7')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        

        $sheet->getStyle('A8:C8')->getFont()->setBold(true);
        
        // Style for B column values (monetary) - with moderate blue color
        $sheet->getStyle('B5:B7')->getFont()->setColor(new Color($valueColor));
        $sheet->getStyle('B5:B7')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        
        // DAILY DATA SECTION STYLES
        // Style the daily data header
        // $sheet->getStyle('A9:C9')->getFont()->setBold(true);
        // $sheet->mergeCells('A9:C9');
        // $sheet->getStyle('A9:C9')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        
        // Style the daily data column headers
        // $sheet->getStyle('A10:C10')->getFont()->setBold(true);
        $sheet->getStyle('A10:C10')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color($borderColor));
        
        // Set styles for daily data
        $dataLastRow = $sheet->getHighestRow() - 1; // Last row before totals
        if ($dataLastRow >= 9) { // Only if we have data
            $dataRange = 'A9:C' . $dataLastRow;
            $sheet->getStyle($dataRange)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color($borderColor));
            
            // Right-align revenue values and color them blue
            // Make sure to apply color styling to all cells in column B (revenue)
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
        
        // Additional styling - ensure all revenue values are blue
        // Apply blue color to ALL revenue values, summary and details
        $sheet->getStyle('B5:B7')->getFont()->setColor(new Color($valueColor));
        $sheet->getStyle('B9:B'.$dataLastRow)->getFont()->setColor(new Color($valueColor));
        $sheet->getStyle('B'.$totalsRow)->getFont()->setColor(new Color($valueColor));
        
        return [];
    }
}
