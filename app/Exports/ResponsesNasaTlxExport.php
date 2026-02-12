<?php

namespace App\Exports;

use App\Models\NasaTlxScore;
use App\Models\Survey;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithTitle;

class ResponsesNasaTlxExport implements WithMultipleSheets
{
    use Exportable;

    protected $survey_ids;

    public function __construct($survey_ids)
    {
        $this->survey_ids = is_array($survey_ids) ? $survey_ids : [$survey_ids];
    }

    public function sheets(): array
    {
        $sheets = [];

        foreach ($this->survey_ids as $survey_id) {
            $survey = Survey::find($survey_id);
            if ($survey) {
                $sheets[] = new NasaTlxResponseSheet($survey_id, $survey->title);
            }
        }

        // If multiple surveys, add a summary sheet
        if (count($this->survey_ids) > 1) {
            $sheets[] = new NasaTlxSummarySheet($this->survey_ids);
        }

        return $sheets;
    }
}

class NasaTlxResponseSheet implements FromCollection, ShouldAutoSize, WithHeadings, WithStyles, WithEvents, WithTitle
{
    use Exportable;

    protected $survey_id;
    protected $survey_title;

    public function __construct($survey_id, $survey_title)
    {
        $this->survey_id = $survey_id;
        $this->survey_title = $survey_title;
    }

    public function title(): string
    {
        $cleanTitle = preg_replace('/[^A-Za-z0-9\-_]/', '_', $this->survey_title);
        return substr($cleanTitle, 0, 31);
    }

    public function collection()
    {
        $scores = NasaTlxScore::where('survey_id', $this->survey_id)
            ->with('surveyResponse')
            ->get();

        return $scores->map(function ($score) {
            return [
                'Full Name' => $score->surveyResponse->first_name . ' ' . $score->surveyResponse->surname,
                'Email' => $score->surveyResponse->email,
                'Birth Date' => $score->surveyResponse->birth_date,
                'Gender' => $score->surveyResponse->gender,
                'Profession' => $score->surveyResponse->profession,
                'Educational Background' => $score->surveyResponse->educational_background,
                'Mental Demand' => $score->mental_demand,
                'Physical Demand' => $score->physical_demand,
                'Temporal Demand' => $score->temporal_demand,
                'Performance' => $score->performance,
                'Effort' => $score->effort,
                'Frustration' => $score->frustration,
                'Final Score' => $score->final_score,
                'Created At' => $score->created_at,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Full Name',
            'Email',
            'Birth Date',
            'Gender',
            'Profession',
            'Educational Background',
            'Mental Demand',
            'Physical Demand',
            'Temporal Demand',
            'Performance',
            'Effort',
            'Frustration',
            'Final Score',
            'Created At',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true], 'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'D3D3D3']]],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getDelegate()->getStyle('A1:N1')->getFont()->setBold(true);
            },
        ];
    }
}

class NasaTlxSummarySheet implements FromCollection, ShouldAutoSize, WithHeadings, WithStyles, WithTitle
{
    use Exportable;

    protected $survey_ids;

    public function __construct($survey_ids)
    {
        $this->survey_ids = is_array($survey_ids) ? $survey_ids : [$survey_ids];
    }

    public function title(): string
    {
        return 'Summary';
    }

    public function collection()
    {
        $summaries = [];

        foreach ($this->survey_ids as $survey_id) {
            $survey = Survey::find($survey_id);
            $scores = NasaTlxScore::where('survey_id', $survey_id)->get();

            if ($scores->count() > 0) {
                $avgMentalDemand = $scores->avg('mental_demand');
                $avgPhysicalDemand = $scores->avg('physical_demand');
                $avgTemporalDemand = $scores->avg('temporal_demand');
                $avgPerformance = $scores->avg('performance');
                $avgEffort = $scores->avg('effort');
                $avgFrustration = $scores->avg('frustration');
                $avgFinalScore = $scores->avg('final_score');

                $summaries[] = [
                    'Survey Title' => $survey->title,
                    'Total Respondents' => $scores->count(),
                    'Avg Mental Demand' => round($avgMentalDemand, 2),
                    'Avg Physical Demand' => round($avgPhysicalDemand, 2),
                    'Avg Temporal Demand' => round($avgTemporalDemand, 2),
                    'Avg Performance' => round($avgPerformance, 2),
                    'Avg Effort' => round($avgEffort, 2),
                    'Avg Frustration' => round($avgFrustration, 2),
                    'Avg Final Score' => round($avgFinalScore, 2),
                ];
            }
        }

        return collect($summaries);
    }

    public function headings(): array
    {
        return [
            'Survey Title',
            'Total Respondents',
            'Avg Mental Demand',
            'Avg Physical Demand',
            'Avg Temporal Demand',
            'Avg Performance',
            'Avg Effort',
            'Avg Frustration',
            'Avg Final Score',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true], 'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'D3D3D3']]],
        ];
    }
}
