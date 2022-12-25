<?php

namespace App\Actions\Procedures\AcuteHemodialysis;

use App\Extensions\Auth\AvatarUser;
use App\Models\Registries\AcuteHemodialysisCaseRecord;
use App\Models\Resources\Registry;
use App\Models\User;
use OpenSpout\Common\Exception\InvalidArgumentException;
use OpenSpout\Common\Exception\IOException;
use OpenSpout\Common\Exception\UnsupportedTypeException;
use OpenSpout\Writer\Exception\WriterNotOpenedException;
use Rap2hpoutre\FastExcel\FastExcel;

class CaseRecordExportAction extends AcuteHemodialysisAction
{
    /**
     * @throws IOException
     * @throws WriterNotOpenedException
     * @throws UnsupportedTypeException
     * @throws InvalidArgumentException
     */
    public function __invoke(array $filters, User|AvatarUser $user)
    {
        if (($link = $this->shouldLinkAvatar()) !== false) {
            return $link;
        }

        $cases = AcuteHemodialysisCaseRecord::query()
            ->select(['id', 'patient_id', 'status', 'meta'])
            ->with([
                'patient:id,profile,hn',
                'orders' =>
                    fn ($query) => $query->select(['id', 'case_record_id', 'author_id', 'status', 'meta', 'date_note'])
                        ->withAuthorName()
                        ->slotoccupiedStatuses()
                        ->orderByDesc('date_note'),
            ])->filterStatus($filters['scope'] ?? null)
            ->get()
            ->transform(function ($case) {
                $lastPerformedOrder = $case->orders
                    ->filter(fn ($o) => collect(['started', 'finished'])->contains($o->status))->first();
                $firstPerformedOrder = $case->orders
                    ->filter(fn ($o) => collect(['started', 'finished'])->contains($o->status))->last();

                return [
                    'hn' => $case->patient->hn,
                    'patient_name' => $case->patient->full_name,
                    'status' => $case->status,
                    'an' => $case->meta['an'],
                    'first_md' => $this->getFirstName(
                        ($firstPerformedOrder?->author_name)
                    ),
                    'first_dialysis_date' => $firstPerformedOrder?->date_note?->format('Y-m-d'),
                    'last_md' => $this->getFirstName(
                        ($lastPerformedOrder?->author_name)
                    ),
                    'last_dialysis_date' => $lastPerformedOrder?->date_note?->format('Y-m-d'),
                ];
            });

        $registry = Registry::query()->find($this->REGISTRY_ID);
        $registry->actionLogs()->create([
            'actor_id' => $user->id,
            'action' => 'export',
            'payload' => [
                'report' => 'case_records',
                'config' => $filters,
            ],
        ]);

        return (new FastExcel($cases))->download('acute-hd-case-records.xlsx');
    }
}
