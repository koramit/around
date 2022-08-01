<?php

namespace App\Actions\Procedures\AcuteHemodialysis;

use App\Casts\AcuteHemodialysisOrderStatus;
use App\Models\Notes\AcuteHemodialysisOrderNote;
use App\Models\Resources\Admission;
use App\Models\User;
use Hashids\Hashids;
use Illuminate\Database\Eloquent\Collection;

class OrderExportAction
{
    public function __invoke(?string $dateNote, User $user): array
    {
        // $data = (new App\Actions\Procedures\AcuteHemodialysis\OrderExportAction)('2022-08-01', User::first())
        if (config('auth.guards.web.provider') === 'avatar') {
            return []; // call api
        }

        if (config('database.default') === 'sqlite') {
            $dateNote = $dateNote.' 00:00:00';
        }

        $slotOccupiedStatuses = (new AcuteHemodialysisOrderStatus)->getSlotOccupiedStatusCodes();

        $ans = AcuteHemodialysisOrderNote::query()
            ->where('date_note', $dateNote)
            ->whereIn('status', $slotOccupiedStatuses)
            ->select('meta')
            ->get()
            ->transform(function ($o) {
                if ($o->meta['an'] ?? null) {
                    return app(Hashids::class)->encode($o->meta['an']);
                } else {
                    return null;
                }
            })->filter(fn ($an) => $an)
            ->values();

        $admissions = Admission::query()
            ->withPlaceName()
            ->whereIn('an', $ans)
            ->get()
            ->transform(fn (Admission $a) => ['an' => $a->an, 'ward' => $a->place_name]);


        $orders = AcuteHemodialysisOrderNote::query()
            ->with('patient')
            ->withPlaceName('App\Models\Resources\Ward')
            ->withAuthorName()
            ->where('date_note', $dateNote)
            ->whereIn('status', $slotOccupiedStatuses)
            ->get()
            ->transform(fn (AcuteHemodialysisOrderNote $order) => $this->getHdSleddRow($order, $admissions));

        return [$ans, $admissions, $orders];
    }

    private function getHdSleddRow(AcuteHemodialysisOrderNote $order, Collection $admissions): array
    {
        $form = $order->form;
        $meta = $order->meta;

        $data = [];
        $data['name'] = $order->patient->first_name;
        $data['last_name'] = $order->patient->profile['last_name'];
        $data['hn'] = $meta['hn'];
        $data['an'] = $meta['an'];
        $data['ward'] = null;
        if ($data['an']) {
            $index = $admissions->search(fn ($a) => $a['an'] == $data['an']);
            if ($index !== false) {
                $data['ward'] = $admissions[$index]['ward'];
            }
        }
        $data['dialysis_at'] = $order->place_name;
        $data['dialysis_type'] = $meta['dialysis_type'];

        // +hf pre/post
        // +hf uf
        // hf sled duration



        return $data;

    }
}
