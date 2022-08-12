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
use App\Models\Resources\Admission;
use App\Models\Resources\Patient;
use App\Models\Resources\Person;
use App\Models\User;
use Exception;
use Illuminate\Database\Seeder;

class AcuteHemodialysisSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     *
     * @throws Exception
     */
    public function run(): void
    {
        User::factory()->create(['login' => 'nurse1.ahd'])->roles()->attach([3, 4]); // participant, nurse;
        User::factory()->create(['login' => 'nurse2.ahd'])->roles()->attach([3, 4]); // participant, nurse;
        User::factory()->create(['login' => 'manager1.ahd'])->roles()->attach([2, 3, 4, 5]); // authority, participant, nurse, manager;
        User::factory()->create(['login' => 'manager2.ahd'])->roles()->attach([2, 3, 4, 5]); // authority, participant, nurse, manager;
        User::factory()->create(['login' => 'fellow1.ahd'])->roles()->attach([3, 6]); // participant, fellow;
        User::factory()->create(['login' => 'fellow2.ahd'])->roles()->attach([3, 6]); // participant, fellow;
        User::factory()->create(['login' => 'fellow3.ahd'])->roles()->attach([3, 6]); // participant, fellow;
        User::factory()->create(['login' => 'fellow4.ahd'])->roles()->attach([3, 6]); // participant, fellow;
        User::factory()->create(['login' => 'staff1.ahd'])->roles()->attach([2, 3, 6, 7]); // authority, participant, fellow, staff;
        User::factory()->create(['login' => 'staff2.ahd'])->roles()->attach([2, 3, 6, 7]); // authority, participant, fellow, staff;

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
        $managers = User::query()->whereIn('login', ['manager.ahd1', 'manager2.ahd'])->get();
        AcuteHemodialysisSlotRequest::query()
            ->each(function ($r) use ($managers) {
                (new SlotRequestUpdateAction())($r->hashed_key, ['approve' => true], $managers->random());
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
        Note::query()
            ->with(['caseRecord' => fn ($q) => $q->with('patient')])
            ->each(function ($n) {
                $c = $n->caseRecord;
                $p = $c->patient;
                $c->meta['name'] = $p->first_name;
                $c->save();
                $n->meta['name'] = $p->first_name;
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
        $data['case_record_hashed_key'] = $case->hashed_key;
        $data['date_note'] = $dateNote;
        (new OrderStoreAction())($data, $user);
    }
}
