<?php

namespace App\Models;

use App\Models\Resources\Patient;
use App\Models\Resources\Person;
use App\Traits\PKHashable;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

/**
 * App\Models\Note
 *
 * @property-read string $hashed_key
 * @property-read string $place_name
 * @property-read string $attending_name
 * @property-read string $author_name
 * @property-read string $title
 */
class Note extends Model
{
    use HasFactory, PKHashable;

    protected $casts = [
        'form' => AsArrayObject::class,
        'meta' => AsArrayObject::class,
        'date_note' => 'date',
    ];

    protected string $defaultChangeRequestClass = '\App\Models\DocumentChangeRequest';

    protected string $caseRecordClass = '\App\Models\CaseRecord';

    public function patient(): HasOneThrough
    {
        return $this->hasOneThrough(
            Patient::class, // target
            CaseRecord::class, // via
            'id', // selected key on the via table...
            'id', // selected key on the target table...
            'case_record_id', // link key this table => via table...
            'patient_id' // link key via table => target table...
        );
    }

    public function caseRecord(): BelongsTo
    {
        return $this->belongsTo($this->caseRecordClass);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function attendingStaff(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }

    public function changeRequests(): MorphMany
    {
        return $this->morphMany($this->defaultChangeRequestClass, 'changeable');
    }

    public function actionLogs(): MorphMany
    {
        return $this->morphMany(ResourceActionLog::class, 'loggable');
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function subscription(): MorphOne
    {
        return $this->morphOne(Subscription::class, 'subscribable');
    }

    protected function title(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->meta['title'] ?? 'placeholder',
        );
    }

    /** @alias $log_list */
    protected function logList(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->actionLogs()
                ->withActorUsername()
                ->oldest('performed_at')
                ->get()
                ->transform(fn ($log) => [
                    'request_id' => $log->payload['request_id'] ?? null,
                    'action' => $log->action,
                    'actor' => $log->actor_username,
                    'at' => $log->performed_at->format('M j H:i'),
                ])
        );
    }

    /** @alias $request_list */
    protected function requestList(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->changeRequests()
                ->with([
                    'requester:id,name',
                    'actionLogs' => fn ($q) => $q->with('actor:id,name')->latest('performed_at'),
                ])->oldest('submitted_at')
                ->get()
                ->transform(fn ($request) => [
                    'requester' => $request->requester->name,
                    'request' => $request->change_request_text,
                    'status' => $request->status,
                    'actor' => $request->actionLogs->first()->actor->name,
                    'at' => $request->actionLogs->first()->performed_at->format('M j H:i'),
                    'id' => $request->id,
                ])
        );
    }

    protected function logs(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->log_list
                ->transform(function ($log) {
                    $data = [
                        'action' => $log['action'],
                        'actor' => $log['actor'],
                        'at' => $log['at'],
                    ];
                    if (! $log['request_id']) {
                        return $data;
                    }
                    $key = $this->request_list->search(fn ($r) => $r['id'] === $log['request_id']);
                    if ($key === false) {
                        return $data;
                    }

                    return $data + [
                        'request' => [
                            'requester' => $this->request_list[$key]['requester'],
                            'request' => $this->request_list[$key]['request'],
                            'status' => $this->request_list[$key]['status'],
                            'actor' => $this->request_list[$key]['actor'],
                            'at' => $this->request_list[$key]['at'],
                        ],
                    ];
                })
        );
    }

    public function scopeWithAuthorUsername($query)
    {
        $query->addSelect([
            'author_username' => User::select('name')
                ->whereColumn('id', 'notes.author_id')
                ->limit(1)
                ->latest(),
        ]);
    }

    public function scopeWithAuthorName($query)
    {
        $query->addSelect([
            'author_name' => User::select('full_name')
                ->whereColumn('id', 'notes.author_id')
                ->limit(1)
                ->latest(),
        ]);
    }

    public function scopeWithPlaceName($query, $className)
    {
        $query->addSelect([
            'place_name' => $className::select('name')
                ->whereColumn('id', 'notes.place_id')
                ->limit(1)
                ->latest(),
        ]);
    }

    public function scopeWithAttendingName($query)
    {
        $query->addSelect([
            'attending_name' => Person::select('name')
                ->whereColumn('id', 'notes.attending_staff_id')
                ->limit(1)
                ->latest(),
        ]);
    }

    public function scopeMetaSearchTerms($query, $search)
    {
        $iLike = config('database.iLike');
        $query->when($search ?? null, function ($query, $search) use ($iLike) {
            $query->where('meta->name', $iLike, $search.'%')
                ->orWhere('meta->hn', $iLike, $search.'%');
        });
    }

    public function genTitle(?string $dateNote = null): string
    {
        return 'placeholder';
    }
}
