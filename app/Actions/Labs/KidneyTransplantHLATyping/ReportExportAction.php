<?php

namespace App\Actions\Labs\KidneyTransplantHLATyping;

use App\Casts\KidneyTransplantHLATypingReportStatus;
use App\Models\Notes\KidneyTransplantAdditionTissueTypingNote;
use App\Models\Notes\KidneyTransplantHLATypingNote;
use App\Models\Notes\KidneyTransplantHLATypingReportNote;
use App\Traits\FirstNameAware;
use Rap2hpoutre\FastExcel\FastExcel;
use Rap2hpoutre\FastExcel\SheetCollection;

class ReportExportAction
{
    use FirstNameAware;

    public function __invoke()
    {
        $addition = [];
        KidneyTransplantAdditionTissueTypingNote::query()
            ->with('patient')
            // ->withAuthorName()
            // ->whereIn('status', [2,4]) // published, edited
            ->orderByDesc('date_note')
            ->each(function ($report) use (&$addition) {
                $addition[] = [
                    // 'id' => $report->id,
                    'hn' => $report->patient->hn,
                    'patient' => $report->patient->full_name,
                    // 'request' => $report->request,
                    'date_serum' => $report->date_note->format('M j Y'),
                    // 'date_addition_tissue_typing' => $report->form["date_addition_tissue_typing"],
                    'dqa1' => $report->form["tissue_typing_dqa1"],
                    'dqa2' => $report->form["tissue_typing_dqa2"],
                    'dpa1' => $report->form["tissue_typing_dpa1"],
                    'dpa2' => $report->form["tissue_typing_dpa2"],
                    'dpb1' => $report->form["tissue_typing_dpb1"],
                    'dpb2' => $report->form["tissue_typing_dpb2"],
                    'mica1' => $report->form["tissue_typing_mica1"],
                    'mica2' => $report->form["tissue_typing_mica2"],
                    // 'status' => $report->status,
                    // 'author' => $this->getFirstName($report->author_name),
                ];
            });

        $hla = [];
        KidneyTransplantHLATypingNote::query()
            ->with('patient')
            ->orderByDesc('date_note')
            ->each(function ($report) use (&$hla) {
                $hla[] = [
                    // 'id' => $report->id,
                    'hn' => $report->patient->hn,
                    'patient' => $report->patient->full_name,
                    // 'request' => $report->request,
                    'date_serum' => $report->date_note->format('M j Y'),
                    // 'date_hla_typing' => $report->form["date_hla_typing"],
                    "abo" => "B",
                    "rh" => "positive",
                    "class_i_a1" => $report->form["hla_typing_class_i_a1"],
                    "class_i_a2" => $report->form["hla_typing_class_i_a2"],
                    "class_i_b1" => $report->form["hla_typing_class_i_b1"],
                    "class_i_b2" => $report->form["hla_typing_class_i_b2"],
                    "class_i_c1" => $report->form["hla_typing_class_i_c1"],
                    "class_i_c2" => $report->form["hla_typing_class_i_c2"],
                    "class_i_bw4" => $report->form["hla_typing_class_i_bw4"],
                    "class_i_bw6" => $report->form["hla_typing_class_i_bw6"],
                    "class_ii_drb11" => $report->form["hla_typing_class_ii_drb11"],
                    "class_ii_drb12" => $report->form["hla_typing_class_ii_drb12"],
                    "class_ii_drb31" => $report->form["hla_typing_class_ii_drb31"],
                    "class_ii_drb32" => $report->form["hla_typing_class_ii_drb32"],
                    "class_ii_drb41" => $report->form["hla_typing_class_ii_drb41"],
                    "class_ii_drb42" => $report->form["hla_typing_class_ii_drb42"],
                    "class_ii_drb51" => $report->form["hla_typing_class_ii_drb51"],
                    "class_ii_drb52" => $report->form["hla_typing_class_ii_drb52"],
                    "class_ii_dqb11" => $report->form["hla_typing_class_ii_dqb11"],
                    "class_ii_dqb12" => $report->form["hla_typing_class_ii_dqb12"],
                    "mismatch" => $report->form["hla_typing_mismatch"],
                    // 'status' => $report->status,
                    // 'author' => $this->getFirstName($report->author_name),
                ];
            });



        $sheets = new SheetCollection([
            'Addition Tissue Typing' => $addition,
            'HLA Typing' => $hla,
        ]);

        return (new FastExcel($sheets))->export(storage_path('app/hla_report_export.xlsx'));
    }
}
