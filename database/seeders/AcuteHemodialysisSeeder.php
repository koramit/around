<?php

namespace Database\Seeders;

use App\Actions\Procedures\AcuteHemodialysis\AcuteHemodialysisAction;
use App\Actions\Procedures\AcuteHemodialysis\CaseRecordStoreAction;
use App\Actions\Procedures\AcuteHemodialysis\OrderStoreAction;
use App\Actions\Procedures\AcuteHemodialysis\SlotRequestUpdateAction;
use App\Managers\Resources\AdmissionManager;
use App\Models\CaseRecord;
use App\Models\DocumentChangeRequests\AcuteHemodialysisSlotRequest;
use App\Models\Note;
use App\Models\Notes\AcuteHemodialysisOrderNote;
use App\Models\Resources\Admission;
use App\Models\Resources\Patient;
use App\Models\Resources\Person;
use App\Models\Resources\Registry;
use App\Models\Role;
use App\Models\User;
use Exception;
use Illuminate\Database\Seeder;

class AcuteHemodialysisSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     *
     * @throws Exception
     */
    public function run(): void
    {
        $authority = Role::query()->where('name', 'authority')->first();
        $participant = Role::query()->where('name', 'participant')->first();
        $nurse = Role::query()->where('name', 'acute_hemodialysis_nurse')->first();
        $inCharge = Role::query()->where('name', 'acute_hemodialysis_nurse_manager')->first();
        $fellow = Role::query()->where('name', 'acute_hemodialysis_fellow')->first();
        $staff = Role::query()->where('name', 'acute_hemodialysis_staff')->first();
        $manager = Role::query()->where('name', 'acute_hemodialysis_manager')->first();
        User::factory()->create(['login' => 'nurse.ahd'])->roles()->attach([$participant->id, $nurse->id]); // participant, nurse;
        User::factory()->create(['login' => 'in-charge.ahd'])->roles()->attach([$authority->id, $participant->id, $nurse->id, $inCharge->id]); // authority, participant, nurse, manager;
        User::factory()->create(['login' => 'fellow1.ahd'])->roles()->attach([$participant->id, $fellow->id]); // participant, fellow;
        User::factory()->create(['login' => 'fellow2.ahd'])->roles()->attach([$participant->id, $fellow->id]); // participant, fellow;
        User::factory()->create(['login' => 'fellow3.ahd'])->roles()->attach([$participant->id, $fellow->id]); // participant, fellow;
        User::factory()->create(['login' => 'fellow4.ahd'])->roles()->attach([$participant->id, $fellow->id]); // participant, fellow;
        User::factory()->create(['login' => 'staff.ahd'])->roles()->attach([$participant->id, $fellow->id, $staff->id]); // participant, fellow, staff;
        User::factory()->create(['login' => 'manager.ahd'])->roles()->attach([$authority->id, $participant->id, $manager->id]); // authority, participant, manager;
        $users = User::query()->where('id', '>', 1)->pluck('id');
        Registry::query()->where('name', 'acute_hd')->first()->users()->attach($users);

        /** admissions */
        $anRun = env('SEED_AN_START');
        $count = 0;
        do {
            $admission = (new AdmissionManager())->manage($anRun);
            $anRun++;
            if (! $admission['found']) {
                continue;
            }
            $count++;
        } while ($count !== 100);

        $users = User::query()->whereIn('login', ['fellow1.ahd', 'fellow2.ahd', 'fellow3.ahd', 'fellow4.ahd'])->get();
        $staff = Person::query()
            ->select(['name'])
            ->where('division_id', 6)
            ->where('position', 8)
            ->get();

        /** create cases */
        CaseRecord::query()->truncate();
        Note::query()->truncate();
        $dateNote = now()->tz(7);
        $countAn = 1;
        for ($day = 0; $day <= 3; $day++) { // next 3 days
            if ($dateNote->is((new AcuteHemodialysisAction())->getUnitDayOff())) {
                $dateNote->addDay();

                continue;
            }
            for ($i = 1; $i <= 11; $i++) { // in unit, 11 HD cases
                $admission = Admission::query()->find($countAn);
                $this->genOrder($admission, $dateNote->format('Y-m-d'), 'HD 4 hrs.', 'ไตเทียม (Hemodialysis Unit)', $users->random(), $staff->random());
                $countAn++;
            }
            for ($i = 1; $i <= 2; $i++) { // in unit, 2 HD+TPE cases
                $admission = Admission::query()->find($countAn);
                $this->genOrder($admission, $dateNote->format('Y-m-d'), 'HD+TPE 6 hrs.', 'ไตเทียม (Hemodialysis Unit)', $users->random(), $staff->random());
                $countAn++;
            }
            // out unit, HD+TPE case
            $admission = Admission::query()->find($countAn);
            $this->genOrder($admission, $dateNote->format('Y-m-d'), 'HD+TPE 6 hrs.', $admission->place->name, $users->random(), $staff->random());
            $countAn++;
            // out unit, HD case
            $admission = Admission::query()->find($countAn);
            $this->genOrder($admission, $dateNote->format('Y-m-d'), 'HD 4 hrs.', $admission->place->name, $users->random(), $staff->random());
            $countAn++;
            for ($i = 1; $i <= 3; $i++) { // out unit, 3 SLEDD cases
                $admission = Admission::query()->find($countAn);
                $this->genOrder($admission, $dateNote->format('Y-m-d'), 'SLEDD', $admission->place->name, $users->random(), $staff->random());
                $countAn++;
            }
            $dateNote->addDay();
        }

        // approve today request
        $inCharge = User::query()->where('login', 'in-charge.ahd')->first();
        AcuteHemodialysisSlotRequest::query()
            ->each(function ($r) use ($inCharge) {
                (new SlotRequestUpdateAction())($r->hashed_key, ['approve' => true], $inCharge);
            });

        // fake patient name
        Patient::query()
            ->each(function ($p) {
                $title = fake()->title($p->gender);
                $firstName = fake()->firstName($p->gender);
                $lastName = fake()->lastName();
                $p->profile['title'] = $title;
                $p->profile['first_name'] = $firstName;
                $p->profile['last_name'] = $lastName;
                $p->profile['patient_name'] = implode(' ', [$title, $firstName, $lastName]);
                $p->save();
            });

        // update meta
        AcuteHemodialysisOrderNote::query()
            ->with(['caseRecord' => fn ($q) => $q->with('patient')])
            ->each(function ($n) {
                $c = $n->caseRecord;
                $p = $c->patient;
                $c->meta['name'] = $p->first_name;
                $c->meta['title'] = $c->genTitle();
                $c->save();
                $n->meta['name'] = $p->first_name;
                $n->meta['title'] = $n->genTitle();
                $n->save();
            });

        // fake staff
        Person::query()
            ->where('division_id', 6)
            ->where('position', 8)
            ->each(function ($s) {
                $s->name = 'Prof. '.fake()->firstName().' '.fake()->lastName();
                $s->save();
            });
    }

    /**
     * @throws Exception
     */
    protected function genOrder(Admission $admission, string $dateNote, string $dialysisType, string $dialysisAt, User $user, Person $staff): void
    {
        $data['hn'] = $admission->patient->hn;
        if (! $admission->dismissed_at) {
            $data['an'] = $admission->an;
        }
        $case = (new CaseRecordStoreAction())($data, $user);
        $data['dialysis_type'] = $dialysisType;
        $data['attending_staff'] = $staff->name;
        $data['patient_type'] = 'Acute';
        $data['dialysis_at'] = $dialysisAt;
        $data['case_record_hashed_key'] = $case['key'];
        $data['date_note'] = $dateNote;
        (new OrderStoreAction())($data, $user);
    }
}
