<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Extensions\Auth{
/**
 * App\Extensions\Auth\AvatarUser
 *
 * @method static \Illuminate\Database\Eloquent\Builder|AvatarUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AvatarUser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AvatarUser query()
 */
	class AvatarUser extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Ability
 *
 * @property int $id
 * @property string $name
 * @property string|null $label
 * @property int|null $registry_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Resources\Registry|null $registry
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Role> $roles
 * @property-read int|null $roles_count
 * @method static \Illuminate\Database\Eloquent\Builder|Ability newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Ability newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Ability query()
 * @method static \Illuminate\Database\Eloquent\Builder|Ability whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ability whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ability whereLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ability whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ability whereRegistryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ability whereUpdatedAt($value)
 */
	class Ability extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\CaseRecord
 *
 * @property-read User $creator
 * @property int $id
 * @property int $patient_id
 * @property int $registry_id
 * @property \Illuminate\Database\Eloquent\Casts\AsArrayObject $meta
 * @property \Illuminate\Database\Eloquent\Casts\AsArrayObject $form
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ResourceActionLog> $actionLogs
 * @property-read int|null $action_logs_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Comment> $comments
 * @property-read int|null $comments_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Note> $notes
 * @property-read int|null $notes_count
 * @property-read \App\Models\Resources\Patient $patient
 * @method static \Illuminate\Database\Eloquent\Builder|CaseRecord findByUnhashKey(string $hashed)
 * @method static \Illuminate\Database\Eloquent\Builder|CaseRecord metaSearchTerms($search)
 * @method static \Illuminate\Database\Eloquent\Builder|CaseRecord newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CaseRecord newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CaseRecord onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|CaseRecord query()
 * @method static \Illuminate\Database\Eloquent\Builder|CaseRecord whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CaseRecord whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CaseRecord whereForm($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CaseRecord whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CaseRecord whereMeta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CaseRecord wherePatientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CaseRecord whereRegistryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CaseRecord whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CaseRecord whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CaseRecord withCreatorName()
 * @method static \Illuminate\Database\Eloquent\Builder|CaseRecord withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|CaseRecord withoutTrashed()
 */
	class CaseRecord extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\ChatBot
 *
 * @property-read string $hashed_key
 * @property int $id
 * @property string $name
 * @property string $callback_token
 * @property int $social_provider_id
 * @property int $user_count
 * @property \Illuminate\Database\Eloquent\Casts\AsEncryptedArrayObject $configs
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ResourceActionLog> $actionLogs
 * @property-read int|null $action_logs_count
 * @property-read \App\Models\SocialProvider|null $provider
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder|ChatBot findByUnhashKey(string $hashed)
 * @method static \Illuminate\Database\Eloquent\Builder|ChatBot minUserCountByProviderId($socialProviderId)
 * @method static \Illuminate\Database\Eloquent\Builder|ChatBot newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ChatBot newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ChatBot query()
 * @method static \Illuminate\Database\Eloquent\Builder|ChatBot whereCallbackToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChatBot whereConfigs($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChatBot whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChatBot whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChatBot whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChatBot whereSocialProviderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChatBot whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChatBot whereUserCount($value)
 */
	class ChatBot extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\ChatLog
 *
 * @property int $id
 * @property int|null $user_id
 * @property int $chat_bot_id
 * @property int $mode
 * @property \Illuminate\Database\Eloquent\Casts\AsArrayObject $payload
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ChatLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ChatLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ChatLog query()
 * @method static \Illuminate\Database\Eloquent\Builder|ChatLog whereChatBotId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChatLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChatLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChatLog whereMode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChatLog wherePayload($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChatLog whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChatLog whereUserId($value)
 */
	class ChatLog extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Comment
 *
 * @property-read string $hashed_key
 * @property-read string $body_html
 * @property-read string $commentator_name
 * @property int $id
 * @property string $commentable_type
 * @property int $commentable_id
 * @property string $body
 * @property int $commentator_id
 * @property int|null $parent_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $commentable
 * @property-read \App\Models\User|null $commentator
 * @property-read Comment|null $parent
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Comment> $replies
 * @property-read int|null $replies_count
 * @method static \Illuminate\Database\Eloquent\Builder|Comment findByUnhashKey(string $hashed)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Comment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Comment query()
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereCommentableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereCommentableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereCommentatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment withCommentatorName()
 * @method static \Illuminate\Database\Eloquent\Builder|Comment withCommentatorUsername()
 */
	class Comment extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\DocumentChangeRequest
 *
 * @property-read string $hashed_key
 * @property-read string $change_request_text
 * @property-read string $requester_name
 * @property int $id
 * @property string $changeable_type
 * @property int $changeable_id
 * @property int $authority_ability_id
 * @property \Illuminate\Database\Eloquent\Casts\AsArrayObject $changes
 * @property int $status
 * @property int $requester_id
 * @property \Illuminate\Support\Carbon $submitted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ResourceActionLog> $actionLogs
 * @property-read int|null $action_logs_count
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $changeable
 * @property-read \App\Models\User|null $requester
 * @method static \Illuminate\Database\Eloquent\Builder|DocumentChangeRequest findByUnhashKey(string $hashed)
 * @method static \Illuminate\Database\Eloquent\Builder|DocumentChangeRequest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DocumentChangeRequest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DocumentChangeRequest query()
 * @method static \Illuminate\Database\Eloquent\Builder|DocumentChangeRequest whereAuthorityAbilityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DocumentChangeRequest whereChangeableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DocumentChangeRequest whereChangeableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DocumentChangeRequest whereChanges($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DocumentChangeRequest whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DocumentChangeRequest whereRequesterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DocumentChangeRequest whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DocumentChangeRequest whereSubmittedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DocumentChangeRequest withRequesterName()
 */
	class DocumentChangeRequest extends \Eloquent {}
}

namespace App\Models\DocumentChangeRequests{
/**
 * App\Models\DocumentChangeRequests\AcuteHemodialysisSlotRequest
 *
 * @property int $id
 * @property string $changeable_type
 * @property int $changeable_id
 * @property int $authority_ability_id
 * @property \Illuminate\Database\Eloquent\Casts\AsArrayObject $changes
 * @property int $status
 * @property int $requester_id
 * @property \Illuminate\Support\Carbon $submitted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ResourceActionLog> $actionLogs
 * @property-read int|null $action_logs_count
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $changeable
 * @property-read \App\Models\User|null $requester
 * @method static \Illuminate\Database\Eloquent\Builder|DocumentChangeRequest findByUnhashKey(string $hashed)
 * @method static \Illuminate\Database\Eloquent\Builder|AcuteHemodialysisSlotRequest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AcuteHemodialysisSlotRequest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AcuteHemodialysisSlotRequest query()
 * @method static \Illuminate\Database\Eloquent\Builder|AcuteHemodialysisSlotRequest whereAuthorityAbilityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AcuteHemodialysisSlotRequest whereChangeableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AcuteHemodialysisSlotRequest whereChangeableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AcuteHemodialysisSlotRequest whereChanges($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AcuteHemodialysisSlotRequest whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AcuteHemodialysisSlotRequest whereRequesterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AcuteHemodialysisSlotRequest whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AcuteHemodialysisSlotRequest whereSubmittedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DocumentChangeRequest withRequesterName()
 */
	class AcuteHemodialysisSlotRequest extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\EventBasedNotification
 *
 * @property int $id
 * @property string $name
 * @property string $notification_class_name
 * @property int $registry_id
 * @property int $ability_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Database\Eloquent\Casts\AsArrayObject $locale
 * @property-read \App\Models\Resources\Registry $registry
 * @property-read \App\Models\Subscription|null $subscription
 * @method static \Illuminate\Database\Eloquent\Builder|EventBasedNotification findByUnhashKey(string $hashed)
 * @method static \Illuminate\Database\Eloquent\Builder|EventBasedNotification newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EventBasedNotification newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EventBasedNotification query()
 * @method static \Illuminate\Database\Eloquent\Builder|EventBasedNotification whereAbilityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EventBasedNotification whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EventBasedNotification whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EventBasedNotification whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EventBasedNotification whereNotificationClassName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EventBasedNotification whereRegistryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EventBasedNotification whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EventBasedNotification withRegistryName()
 */
	class EventBasedNotification extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Feedback
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Feedback newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Feedback newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Feedback query()
 */
	class Feedback extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\LoginRecord
 *
 * @property int $id
 * @property int $user_id
 * @property string $provider
 * @property string|null $ip_address
 * @property string $device
 * @property int $type
 * @property string $browser
 * @property string $browser_version
 * @property string $platform
 * @property string $platform_version
 * @property string|null $robot
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|LoginRecord newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LoginRecord newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LoginRecord query()
 * @method static \Illuminate\Database\Eloquent\Builder|LoginRecord whereBrowser($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoginRecord whereBrowserVersion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoginRecord whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoginRecord whereDevice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoginRecord whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoginRecord whereIpAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoginRecord wherePlatform($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoginRecord wherePlatformVersion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoginRecord whereProvider($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoginRecord whereRobot($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoginRecord whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoginRecord whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoginRecord whereUserId($value)
 */
	class LoginRecord extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Note
 *
 * @property-read string $hashed_key
 * @property-read string $place_name
 * @property-read string $attending_name
 * @property-read string $author_name
 * @property-read string $title
 * @property int $id
 * @property int $case_record_id
 * @property int $note_type_id
 * @property int|null $attending_staff_id
 * @property string|null $place_type
 * @property int|null $place_id
 * @property \Illuminate\Database\Eloquent\Casts\AsArrayObject $meta
 * @property \Illuminate\Database\Eloquent\Casts\AsArrayObject $form
 * @property mixed|null $report
 * @property int $status
 * @property \Illuminate\Support\Carbon $date_note
 * @property int $author_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ResourceActionLog> $actionLogs
 * @property-read int|null $action_logs_count
 * @property-read \App\Models\Resources\Person|null $attendingStaff
 * @property-read \App\Models\User|null $author
 * @property-read \App\Models\CaseRecord $caseRecord
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DocumentChangeRequest> $changeRequests
 * @property-read int|null $change_requests_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Comment> $comments
 * @property-read int|null $comments_count
 * @property-read \App\Models\Resources\Patient|null $patient
 * @property-read \App\Models\Subscription|null $subscription
 * @method static \Illuminate\Database\Eloquent\Builder|Note findByUnhashKey(string $hashed)
 * @method static \Illuminate\Database\Eloquent\Builder|Note metaSearchTerms($search)
 * @method static \Illuminate\Database\Eloquent\Builder|Note newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Note newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Note query()
 * @method static \Illuminate\Database\Eloquent\Builder|Note whereAttendingStaffId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Note whereAuthorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Note whereCaseRecordId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Note whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Note whereDateNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Note whereForm($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Note whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Note whereMeta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Note whereNoteTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Note wherePlaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Note wherePlaceType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Note whereReport($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Note whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Note whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Note withAttendingName()
 * @method static \Illuminate\Database\Eloquent\Builder|Note withAuthorName()
 * @method static \Illuminate\Database\Eloquent\Builder|Note withAuthorUsername()
 * @method static \Illuminate\Database\Eloquent\Builder|Note withPlaceName($className)
 */
	class Note extends \Eloquent {}
}

namespace App\Models\Notes{
/**
 * App\Models\Notes\AcuteHemodialysisOrderNote
 *
 * @property-read string $cancel_confirm_text
 * @property-read string $edit_route
 * @property-read string $view_route
 * @property-read string $discussion_route
 * @property-read bool $on_ventilator
 * @property int $id
 * @property int $case_record_id
 * @property int $note_type_id
 * @property int|null $attending_staff_id
 * @property string|null $place_type
 * @property int|null $place_id
 * @property \Illuminate\Database\Eloquent\Casts\AsArrayObject $meta
 * @property \Illuminate\Database\Eloquent\Casts\AsArrayObject $form
 * @property mixed|null $report
 * @property string|null $status
 * @property \Illuminate\Support\Carbon $date_note
 * @property int $author_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ResourceActionLog> $actionLogs
 * @property-read int|null $action_logs_count
 * @property-read \App\Models\Resources\Person|null $attendingStaff
 * @property-read \App\Models\User|null $author
 * @property-read \App\Models\Registries\AcuteHemodialysisCaseRecord $caseRecord
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DocumentChangeRequests\AcuteHemodialysisSlotRequest> $changeRequests
 * @property-read int|null $change_requests_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Comment> $comments
 * @property-read int|null $comments_count
 * @property-read \App\Models\Resources\Patient|null $patient
 * @property-read \App\Models\Subscription|null $subscription
 * @method static \Illuminate\Database\Eloquent\Builder|AcuteHemodialysisOrderNote activeStatuses()
 * @method static \Illuminate\Database\Eloquent\Builder|AcuteHemodialysisOrderNote dialysisDate($dateRef)
 * @method static \Illuminate\Database\Eloquent\Builder|AcuteHemodialysisOrderNote dialysisTypeLike($type)
 * @method static \Illuminate\Database\Eloquent\Builder|Note findByUnhashKey(string $hashed)
 * @method static \Illuminate\Database\Eloquent\Builder|Note metaSearchTerms($search)
 * @method static \Illuminate\Database\Eloquent\Builder|AcuteHemodialysisOrderNote newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AcuteHemodialysisOrderNote newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AcuteHemodialysisOrderNote performedStatuses()
 * @method static \Illuminate\Database\Eloquent\Builder|AcuteHemodialysisOrderNote query()
 * @method static \Illuminate\Database\Eloquent\Builder|AcuteHemodialysisOrderNote slotOccupiedStatuses()
 * @method static \Illuminate\Database\Eloquent\Builder|AcuteHemodialysisOrderNote whereAttendingStaffId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AcuteHemodialysisOrderNote whereAuthorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AcuteHemodialysisOrderNote whereCaseRecordId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AcuteHemodialysisOrderNote whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AcuteHemodialysisOrderNote whereDateNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AcuteHemodialysisOrderNote whereForm($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AcuteHemodialysisOrderNote whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AcuteHemodialysisOrderNote whereMeta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AcuteHemodialysisOrderNote whereNoteTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AcuteHemodialysisOrderNote wherePlaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AcuteHemodialysisOrderNote wherePlaceType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AcuteHemodialysisOrderNote whereReport($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AcuteHemodialysisOrderNote whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AcuteHemodialysisOrderNote whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Note withAttendingName()
 * @method static \Illuminate\Database\Eloquent\Builder|Note withAuthorName()
 * @method static \Illuminate\Database\Eloquent\Builder|Note withAuthorUsername()
 * @method static \Illuminate\Database\Eloquent\Builder|Note withPlaceName($className)
 */
	class AcuteHemodialysisOrderNote extends \Eloquent {}
}

namespace App\Models\Notes{
/**
 * App\Models\Notes\KidneyTransplantAdditionTissueTypingNote
 *
 * @property int $id
 * @property int $case_record_id
 * @property int $note_type_id
 * @property int|null $attending_staff_id
 * @property string|null $place_type
 * @property int|null $place_id
 * @property \Illuminate\Database\Eloquent\Casts\AsArrayObject $meta
 * @property \Illuminate\Database\Eloquent\Casts\AsArrayObject $form
 * @property mixed|null $report
 * @property string|null $status
 * @property \Illuminate\Support\Carbon $date_note
 * @property int $author_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ResourceActionLog> $actionLogs
 * @property-read int|null $action_logs_count
 * @property-read \App\Models\Resources\Person|null $attendingStaff
 * @property-read \App\Models\User|null $author
 * @property-read \App\Models\Registries\KidneyTransplantHLATypingCaseRecord $caseRecord
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DocumentChangeRequest> $changeRequests
 * @property-read int|null $change_requests_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Comment> $comments
 * @property-read int|null $comments_count
 * @property-read \App\Models\Resources\Patient|null $patient
 * @property-read \App\Models\Subscription|null $subscription
 * @method static \Illuminate\Database\Eloquent\Builder|Note findByUnhashKey(string $hashed)
 * @method static \Illuminate\Database\Eloquent\Builder|Note metaSearchTerms($search)
 * @method static \Illuminate\Database\Eloquent\Builder|KidneyTransplantAdditionTissueTypingNote newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|KidneyTransplantAdditionTissueTypingNote newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|KidneyTransplantAdditionTissueTypingNote query()
 * @method static \Illuminate\Database\Eloquent\Builder|KidneyTransplantAdditionTissueTypingNote whereAttendingStaffId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KidneyTransplantAdditionTissueTypingNote whereAuthorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KidneyTransplantAdditionTissueTypingNote whereCaseRecordId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KidneyTransplantAdditionTissueTypingNote whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KidneyTransplantAdditionTissueTypingNote whereDateNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KidneyTransplantAdditionTissueTypingNote whereForm($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KidneyTransplantAdditionTissueTypingNote whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KidneyTransplantAdditionTissueTypingNote whereMeta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KidneyTransplantAdditionTissueTypingNote whereNoteTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KidneyTransplantAdditionTissueTypingNote wherePlaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KidneyTransplantAdditionTissueTypingNote wherePlaceType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KidneyTransplantAdditionTissueTypingNote whereReport($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KidneyTransplantAdditionTissueTypingNote whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KidneyTransplantAdditionTissueTypingNote whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Note withAttendingName()
 * @method static \Illuminate\Database\Eloquent\Builder|Note withAuthorName()
 * @method static \Illuminate\Database\Eloquent\Builder|Note withAuthorUsername()
 * @method static \Illuminate\Database\Eloquent\Builder|Note withPlaceName($className)
 */
	class KidneyTransplantAdditionTissueTypingNote extends \Eloquent {}
}

namespace App\Models\Notes{
/**
 * App\Models\Notes\KidneyTransplantCrossmatchNote
 *
 * @property int $id
 * @property int $case_record_id
 * @property int $note_type_id
 * @property int|null $attending_staff_id
 * @property string|null $place_type
 * @property int|null $place_id
 * @property \Illuminate\Database\Eloquent\Casts\AsArrayObject $meta
 * @property \Illuminate\Database\Eloquent\Casts\AsArrayObject $form
 * @property mixed|null $report
 * @property string|null $status
 * @property \Illuminate\Support\Carbon $date_note
 * @property int $author_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ResourceActionLog> $actionLogs
 * @property-read int|null $action_logs_count
 * @property-read \App\Models\Resources\Person|null $attendingStaff
 * @property-read \App\Models\User|null $author
 * @property-read \App\Models\Registries\KidneyTransplantHLATypingCaseRecord $caseRecord
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DocumentChangeRequest> $changeRequests
 * @property-read int|null $change_requests_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Comment> $comments
 * @property-read int|null $comments_count
 * @property-read \App\Models\Resources\Patient|null $patient
 * @property-read \App\Models\Subscription|null $subscription
 * @method static \Illuminate\Database\Eloquent\Builder|Note findByUnhashKey(string $hashed)
 * @method static \Illuminate\Database\Eloquent\Builder|Note metaSearchTerms($search)
 * @method static \Illuminate\Database\Eloquent\Builder|KidneyTransplantCrossmatchNote newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|KidneyTransplantCrossmatchNote newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|KidneyTransplantCrossmatchNote query()
 * @method static \Illuminate\Database\Eloquent\Builder|KidneyTransplantCrossmatchNote whereAttendingStaffId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KidneyTransplantCrossmatchNote whereAuthorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KidneyTransplantCrossmatchNote whereCaseRecordId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KidneyTransplantCrossmatchNote whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KidneyTransplantCrossmatchNote whereDateNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KidneyTransplantCrossmatchNote whereForm($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KidneyTransplantCrossmatchNote whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KidneyTransplantCrossmatchNote whereMeta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KidneyTransplantCrossmatchNote whereNoteTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KidneyTransplantCrossmatchNote wherePlaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KidneyTransplantCrossmatchNote wherePlaceType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KidneyTransplantCrossmatchNote whereReport($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KidneyTransplantCrossmatchNote whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KidneyTransplantCrossmatchNote whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Note withAttendingName()
 * @method static \Illuminate\Database\Eloquent\Builder|Note withAuthorName()
 * @method static \Illuminate\Database\Eloquent\Builder|Note withAuthorUsername()
 * @method static \Illuminate\Database\Eloquent\Builder|Note withPlaceName($className)
 */
	class KidneyTransplantCrossmatchNote extends \Eloquent {}
}

namespace App\Models\Notes{
/**
 * App\Models\Notes\KidneyTransplantHLATypingNote
 *
 * @property int $id
 * @property int $case_record_id
 * @property int $note_type_id
 * @property int|null $attending_staff_id
 * @property string|null $place_type
 * @property int|null $place_id
 * @property \Illuminate\Database\Eloquent\Casts\AsArrayObject $meta
 * @property \Illuminate\Database\Eloquent\Casts\AsArrayObject $form
 * @property mixed|null $report
 * @property string|null $status
 * @property \Illuminate\Support\Carbon $date_note
 * @property int $author_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ResourceActionLog> $actionLogs
 * @property-read int|null $action_logs_count
 * @property-read \App\Models\Resources\Person|null $attendingStaff
 * @property-read \App\Models\User|null $author
 * @property-read \App\Models\Registries\KidneyTransplantHLATypingCaseRecord $caseRecord
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DocumentChangeRequest> $changeRequests
 * @property-read int|null $change_requests_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Comment> $comments
 * @property-read int|null $comments_count
 * @property-read \App\Models\Resources\Patient|null $patient
 * @property-read \App\Models\Subscription|null $subscription
 * @method static \Illuminate\Database\Eloquent\Builder|Note findByUnhashKey(string $hashed)
 * @method static \Illuminate\Database\Eloquent\Builder|Note metaSearchTerms($search)
 * @method static \Illuminate\Database\Eloquent\Builder|KidneyTransplantHLATypingNote newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|KidneyTransplantHLATypingNote newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|KidneyTransplantHLATypingNote query()
 * @method static \Illuminate\Database\Eloquent\Builder|KidneyTransplantHLATypingNote whereAttendingStaffId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KidneyTransplantHLATypingNote whereAuthorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KidneyTransplantHLATypingNote whereCaseRecordId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KidneyTransplantHLATypingNote whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KidneyTransplantHLATypingNote whereDateNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KidneyTransplantHLATypingNote whereForm($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KidneyTransplantHLATypingNote whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KidneyTransplantHLATypingNote whereMeta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KidneyTransplantHLATypingNote whereNoteTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KidneyTransplantHLATypingNote wherePlaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KidneyTransplantHLATypingNote wherePlaceType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KidneyTransplantHLATypingNote whereReport($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KidneyTransplantHLATypingNote whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KidneyTransplantHLATypingNote whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Note withAttendingName()
 * @method static \Illuminate\Database\Eloquent\Builder|Note withAuthorName()
 * @method static \Illuminate\Database\Eloquent\Builder|Note withAuthorUsername()
 * @method static \Illuminate\Database\Eloquent\Builder|Note withPlaceName($className)
 */
	class KidneyTransplantHLATypingNote extends \Eloquent {}
}

namespace App\Models\Notes{
/**
 * App\Models\Notes\KidneyTransplantHLATypingReportNote
 *
 * @property int $id
 * @property int $case_record_id
 * @property int $note_type_id
 * @property int|null $attending_staff_id
 * @property string|null $place_type
 * @property int|null $place_id
 * @property \Illuminate\Database\Eloquent\Casts\AsArrayObject $meta
 * @property \Illuminate\Database\Eloquent\Casts\AsArrayObject $form
 * @property mixed|null $report
 * @property string|null $status
 * @property \Illuminate\Support\Carbon $date_note
 * @property int $author_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ResourceActionLog> $actionLogs
 * @property-read int|null $action_logs_count
 * @property-read \App\Models\Resources\Person|null $attendingStaff
 * @property-read \App\Models\User|null $author
 * @property-read \App\Models\Registries\KidneyTransplantHLATypingCaseRecord $caseRecord
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DocumentChangeRequest> $changeRequests
 * @property-read int|null $change_requests_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Comment> $comments
 * @property-read int|null $comments_count
 * @property-read \App\Models\Resources\Patient|null $patient
 * @property-read \App\Models\Subscription|null $subscription
 * @method static \Illuminate\Database\Eloquent\Builder|KidneyTransplantHLATypingReportNote filterStatus($status)
 * @method static \Illuminate\Database\Eloquent\Builder|Note findByUnhashKey(string $hashed)
 * @method static \Illuminate\Database\Eloquent\Builder|Note metaSearchTerms($search)
 * @method static \Illuminate\Database\Eloquent\Builder|KidneyTransplantHLATypingReportNote newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|KidneyTransplantHLATypingReportNote newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|KidneyTransplantHLATypingReportNote query()
 * @method static \Illuminate\Database\Eloquent\Builder|KidneyTransplantHLATypingReportNote whereAttendingStaffId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KidneyTransplantHLATypingReportNote whereAuthorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KidneyTransplantHLATypingReportNote whereCaseRecordId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KidneyTransplantHLATypingReportNote whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KidneyTransplantHLATypingReportNote whereDateNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KidneyTransplantHLATypingReportNote whereForm($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KidneyTransplantHLATypingReportNote whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KidneyTransplantHLATypingReportNote whereMeta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KidneyTransplantHLATypingReportNote whereNoteTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KidneyTransplantHLATypingReportNote wherePlaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KidneyTransplantHLATypingReportNote wherePlaceType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KidneyTransplantHLATypingReportNote whereReport($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KidneyTransplantHLATypingReportNote whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KidneyTransplantHLATypingReportNote whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Note withAttendingName()
 * @method static \Illuminate\Database\Eloquent\Builder|Note withAuthorName()
 * @method static \Illuminate\Database\Eloquent\Builder|Note withAuthorUsername()
 * @method static \Illuminate\Database\Eloquent\Builder|Note withPlaceName($className)
 */
	class KidneyTransplantHLATypingReportNote extends \Eloquent {}
}

namespace App\Models\Registries{
/**
 * App\Models\Registries\AcuteHemodialysisCaseRecord
 *
 * @property-read string $hashed_key
 * @property int $id
 * @property int $patient_id
 * @property int $registry_id
 * @property \Illuminate\Database\Eloquent\Casts\AsArrayObject $meta
 * @property \Illuminate\Database\Eloquent\Casts\AsArrayObject $form
 * @property string|null $status
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ResourceActionLog> $actionLogs
 * @property-read int|null $action_logs_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Comment> $comments
 * @property-read int|null $comments_count
 * @property-read \App\Models\Notes\AcuteHemodialysisOrderNote|null $firstPerformedOrder
 * @property-read \App\Models\Notes\AcuteHemodialysisOrderNote|null $lastOrder
 * @property-read \App\Models\Notes\AcuteHemodialysisOrderNote|null $lastPerformedOrder
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Note> $notes
 * @property-read int|null $notes_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Notes\AcuteHemodialysisOrderNote> $orders
 * @property-read int|null $orders_count
 * @property-read \App\Models\Resources\Patient $patient
 * @method static \Illuminate\Database\Eloquent\Builder|AcuteHemodialysisCaseRecord filterStatus($status)
 * @method static \Illuminate\Database\Eloquent\Builder|CaseRecord findByUnhashKey(string $hashed)
 * @method static \Illuminate\Database\Eloquent\Builder|CaseRecord metaSearchTerms($search)
 * @method static \Illuminate\Database\Eloquent\Builder|AcuteHemodialysisCaseRecord newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AcuteHemodialysisCaseRecord newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AcuteHemodialysisCaseRecord onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|AcuteHemodialysisCaseRecord query()
 * @method static \Illuminate\Database\Eloquent\Builder|AcuteHemodialysisCaseRecord whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AcuteHemodialysisCaseRecord whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AcuteHemodialysisCaseRecord whereForm($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AcuteHemodialysisCaseRecord whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AcuteHemodialysisCaseRecord whereMeta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AcuteHemodialysisCaseRecord wherePatientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AcuteHemodialysisCaseRecord whereRegistryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AcuteHemodialysisCaseRecord whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AcuteHemodialysisCaseRecord whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CaseRecord withCreatorName()
 * @method static \Illuminate\Database\Eloquent\Builder|AcuteHemodialysisCaseRecord withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|AcuteHemodialysisCaseRecord withoutTrashed()
 */
	class AcuteHemodialysisCaseRecord extends \Eloquent {}
}

namespace App\Models\Registries{
/**
 * App\Models\Registries\KidneyTransplantAdmissionCaseRecord
 *
 * @property-read string $hashed_key
 * @property int $id
 * @property int $patient_id
 * @property int $registry_id
 * @property \Illuminate\Database\Eloquent\Casts\AsArrayObject $meta
 * @property \Illuminate\Database\Eloquent\Casts\AsArrayObject $form
 * @property string|null $status
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ResourceActionLog> $actionLogs
 * @property-read int|null $action_logs_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Comment> $comments
 * @property-read int|null $comments_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Note> $notes
 * @property-read int|null $notes_count
 * @property-read \App\Models\Resources\Patient $patient
 * @method static \Illuminate\Database\Eloquent\Builder|KidneyTransplantAdmissionCaseRecord filterStatus($status)
 * @method static \Illuminate\Database\Eloquent\Builder|CaseRecord findByUnhashKey(string $hashed)
 * @method static \Illuminate\Database\Eloquent\Builder|KidneyTransplantAdmissionCaseRecord metaSearchTerms($search)
 * @method static \Illuminate\Database\Eloquent\Builder|KidneyTransplantAdmissionCaseRecord newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|KidneyTransplantAdmissionCaseRecord newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|KidneyTransplantAdmissionCaseRecord onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|KidneyTransplantAdmissionCaseRecord query()
 * @method static \Illuminate\Database\Eloquent\Builder|KidneyTransplantAdmissionCaseRecord whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KidneyTransplantAdmissionCaseRecord whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KidneyTransplantAdmissionCaseRecord whereForm($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KidneyTransplantAdmissionCaseRecord whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KidneyTransplantAdmissionCaseRecord whereMeta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KidneyTransplantAdmissionCaseRecord wherePatientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KidneyTransplantAdmissionCaseRecord whereRegistryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KidneyTransplantAdmissionCaseRecord whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KidneyTransplantAdmissionCaseRecord whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CaseRecord withCreatorName()
 * @method static \Illuminate\Database\Eloquent\Builder|KidneyTransplantAdmissionCaseRecord withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|KidneyTransplantAdmissionCaseRecord withoutTrashed()
 */
	class KidneyTransplantAdmissionCaseRecord extends \Eloquent {}
}

namespace App\Models\Registries{
/**
 * App\Models\Registries\KidneyTransplantHLATypingCaseRecord
 *
 * @property int $id
 * @property int $patient_id
 * @property int $registry_id
 * @property \Illuminate\Database\Eloquent\Casts\AsArrayObject $meta
 * @property \Illuminate\Database\Eloquent\Casts\AsArrayObject $form
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ResourceActionLog> $actionLogs
 * @property-read int|null $action_logs_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Comment> $comments
 * @property-read int|null $comments_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Note> $notes
 * @property-read int|null $notes_count
 * @property-read \App\Models\Resources\Patient $patient
 * @method static \Illuminate\Database\Eloquent\Builder|CaseRecord findByUnhashKey(string $hashed)
 * @method static \Illuminate\Database\Eloquent\Builder|CaseRecord metaSearchTerms($search)
 * @method static \Illuminate\Database\Eloquent\Builder|KidneyTransplantHLATypingCaseRecord newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|KidneyTransplantHLATypingCaseRecord newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|KidneyTransplantHLATypingCaseRecord onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|KidneyTransplantHLATypingCaseRecord query()
 * @method static \Illuminate\Database\Eloquent\Builder|KidneyTransplantHLATypingCaseRecord whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KidneyTransplantHLATypingCaseRecord whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KidneyTransplantHLATypingCaseRecord whereForm($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KidneyTransplantHLATypingCaseRecord whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KidneyTransplantHLATypingCaseRecord whereMeta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KidneyTransplantHLATypingCaseRecord wherePatientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KidneyTransplantHLATypingCaseRecord whereRegistryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KidneyTransplantHLATypingCaseRecord whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KidneyTransplantHLATypingCaseRecord whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CaseRecord withCreatorName()
 * @method static \Illuminate\Database\Eloquent\Builder|KidneyTransplantHLATypingCaseRecord withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|KidneyTransplantHLATypingCaseRecord withoutTrashed()
 */
	class KidneyTransplantHLATypingCaseRecord extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\ResourceActionLog
 *
 * @property int $id
 * @property string $loggable_type
 * @property int $loggable_id
 * @property int $action
 * @property \Illuminate\Database\Eloquent\Casts\AsArrayObject|null $payload
 * @property int $actor_id
 * @property \Illuminate\Support\Carbon $performed_at
 * @property-read \App\Models\User|null $actor
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $loggable
 * @method static \Illuminate\Database\Eloquent\Builder|ResourceActionLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ResourceActionLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ResourceActionLog query()
 * @method static \Illuminate\Database\Eloquent\Builder|ResourceActionLog whereAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ResourceActionLog whereActorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ResourceActionLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ResourceActionLog whereLoggableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ResourceActionLog whereLoggableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ResourceActionLog wherePayload($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ResourceActionLog wherePerformedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ResourceActionLog withActorUsername()
 */
	class ResourceActionLog extends \Eloquent {}
}

namespace App\Models\Resources{
/**
 * App\Models\Resources\Admission
 *
 * @property int $id
 * @property string $an
 * @property int $patient_id
 * @property \Illuminate\Database\Eloquent\Casts\AsArrayObject|null $meta
 * @property \Illuminate\Support\Carbon|null $encountered_at
 * @property \Illuminate\Support\Carbon|null $dismissed_at
 * @property int $ward_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Resources\Patient $patient
 * @property-read \App\Models\Resources\Ward $place
 * @method static \Illuminate\Database\Eloquent\Builder|Admission findByHashKey(string $plain)
 * @method static \Illuminate\Database\Eloquent\Builder|Admission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Admission newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Admission query()
 * @method static \Illuminate\Database\Eloquent\Builder|Admission whereAn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admission whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admission whereDismissedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admission whereEncounteredAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admission whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admission whereMeta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admission wherePatientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admission whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admission whereWardId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admission withPlaceName()
 */
	class Admission extends \Eloquent {}
}

namespace App\Models\Resources{
/**
 * App\Models\Resources\Division
 *
 * @property int $id
 * @property string $name
 * @property string $name_en
 * @property string $name_en_short
 * @property string $department
 * @property bool $active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Division newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Division newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Division query()
 * @method static \Illuminate\Database\Eloquent\Builder|Division whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Division whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Division whereDepartment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Division whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Division whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Division whereNameEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Division whereNameEnShort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Division whereUpdatedAt($value)
 */
	class Division extends \Eloquent {}
}

namespace App\Models\Resources{
/**
 * App\Models\Resources\NoteType
 *
 * @property int $id
 * @property string $name
 * @property string $label
 * @property bool $active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|NoteType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|NoteType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|NoteType query()
 * @method static \Illuminate\Database\Eloquent\Builder|NoteType whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NoteType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NoteType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NoteType whereLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NoteType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NoteType whereUpdatedAt($value)
 */
	class NoteType extends \Eloquent {}
}

namespace App\Models\Resources{
/**
 * App\Models\Resources
 *
 * @property-read string $first_name
 * @property-read string $full_name
 * @property int $id
 * @property string $hn
 * @property bool $gender
 * @property \Illuminate\Support\Carbon|null $dob
 * @property \Illuminate\Database\Eloquent\Casts\AsEncryptedArrayObject|null $profile
 * @property bool $alive
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Resources\Admission> $admissions
 * @property-read int|null $admissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Note> $notes
 * @property-read int|null $notes_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Resources\Registry> $registries
 * @property-read int|null $registries_count
 * @method static \Illuminate\Database\Eloquent\Builder|Patient findByHashKey(string $plain)
 * @method static \Illuminate\Database\Eloquent\Builder|Patient newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Patient newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Patient query()
 * @method static \Illuminate\Database\Eloquent\Builder|Patient whereAlive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Patient whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Patient whereDob($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Patient whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Patient whereHn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Patient whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Patient whereProfile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Patient whereUpdatedAt($value)
 */
	class Patient extends \Eloquent {}
}

namespace App\Models\Resources{
/**
 * App\Models\Resources\Person
 *
 * @property-read string $first_name
 * @property int $id
 * @property string $name
 * @property int $division_id
 * @property int $position
 * @property bool $active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Person filter($filters)
 * @method static \Illuminate\Database\Eloquent\Builder|Person newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Person newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Person query()
 * @method static \Illuminate\Database\Eloquent\Builder|Person whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Person whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Person whereDivisionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Person whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Person whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Person wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Person whereUpdatedAt($value)
 */
	class Person extends \Eloquent {}
}

namespace App\Models\Resources{
/**
 * App\Models\Resources\Registry
 *
 * @property int $id
 * @property string $name
 * @property string $route
 * @property int $division_id
 * @property bool $active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ResourceActionLog> $actionLogs
 * @property-read int|null $action_logs_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Resources\Patient> $patients
 * @property-read int|null $patients_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder|Registry newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Registry newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Registry query()
 * @method static \Illuminate\Database\Eloquent\Builder|Registry whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Registry whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Registry whereDivisionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Registry whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Registry whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Registry whereRoute($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Registry whereUpdatedAt($value)
 */
	class Registry extends \Eloquent {}
}

namespace App\Models\Resources{
/**
 * App\Models\Resources\Ward
 *
 * @property int $id
 * @property string $name
 * @property string|null $name_short
 * @property string $name_ref
 * @property int $division_id
 * @property bool $active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Ward newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Ward newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Ward query()
 * @method static \Illuminate\Database\Eloquent\Builder|Ward search($search)
 * @method static \Illuminate\Database\Eloquent\Builder|Ward whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ward whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ward whereDivisionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ward whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ward whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ward whereNameRef($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ward whereNameShort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ward whereUpdatedAt($value)
 */
	class Ward extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Role
 *
 * @property int $id
 * @property string $name
 * @property string|null $label
 * @property int|null $registry_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Ability> $abilities
 * @property-read int|null $abilities_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder|Role newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Role newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Role query()
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereRegistryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereUpdatedAt($value)
 */
	class Role extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\SocialProfile
 *
 * @property int $id
 * @property int $user_id
 * @property int $social_provider_id
 * @property string $profile_id
 * @property \Illuminate\Database\Eloquent\Casts\AsEncryptedArrayObject $profile
 * @property bool $active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\SocialProvider|null $socialProvider
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|SocialProfile activeLoginByProviderId($providerId)
 * @method static \Illuminate\Database\Eloquent\Builder|SocialProfile newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SocialProfile newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SocialProfile query()
 * @method static \Illuminate\Database\Eloquent\Builder|SocialProfile whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SocialProfile whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SocialProfile whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SocialProfile whereProfile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SocialProfile whereProfileId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SocialProfile whereSocialProviderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SocialProfile whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SocialProfile whereUserId($value)
 */
	class SocialProfile extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\SocialProvider
 *
 * @property-read string $hashed_key
 * @property int $id
 * @property int $platform
 * @property string $name
 * @property \Illuminate\Database\Eloquent\Casts\AsEncryptedArrayObject $configs
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ResourceActionLog> $actionLogs
 * @property-read int|null $action_logs_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ChatBot> $chatBots
 * @property-read int|null $chat_bots_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SocialProfile> $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder|SocialProvider findByUnhashKey(string $hashed)
 * @method static \Illuminate\Database\Eloquent\Builder|SocialProvider newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SocialProvider newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SocialProvider query()
 * @method static \Illuminate\Database\Eloquent\Builder|SocialProvider whereConfigs($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SocialProvider whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SocialProvider whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SocialProvider whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SocialProvider wherePlatform($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SocialProvider whereUpdatedAt($value)
 */
	class SocialProvider extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Subscription
 *
 * @property-read string $hashed_key
 * @property int $id
 * @property string $subscribable_type
 * @property int $subscribable_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $subscribable
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $subscribers
 * @property-read int|null $subscribers_count
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription findByUnhashKey(string $hashed)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription query()
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription whereSubscribableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription whereSubscribableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription whereUpdatedAt($value)
 */
	class Subscription extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\SupportTicket
 *
 * @method static \Illuminate\Database\Eloquent\Builder|SupportTicket newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SupportTicket newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SupportTicket query()
 */
	class SupportTicket extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property int $items_per_page
 * @property string $home_page
 * @property Collection $role_names
 * @property Collection $role_labels
 * @property string $hashed_key
 * @property string $first_name
 * @property Collection $abilities
 * @property Collection $abilities_id
 * @property bool $auto_subscribe_to_channel
 * @property bool $mute_notification
 * @property bool $notify_approval_result
 * @property Collection $registry_names
 * @property string $avatar_token
 * @property int $id
 * @property string $name
 * @property string $login
 * @property string $full_name
 * @property string $password
 * @property \Illuminate\Database\Eloquent\Casts\AsArrayObject $profile
 * @property \Illuminate\Database\Eloquent\Casts\AsArrayObject $preferences
 * @property int $division_id
 * @property bool $active
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ResourceActionLog> $actionLogs
 * @property-read int|null $action_logs_count
 * @property-read \App\Models\SocialProfile|null $activeLINEProfile
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ChatBot> $chatBots
 * @property-read int|null $chat_bots_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ChatLog> $chatLogs
 * @property-read int|null $chat_logs_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Resources\Registry> $registries
 * @property-read int|null $registries_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Role> $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SocialProfile> $socialProfiles
 * @property-read int|null $social_profiles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Subscription> $subscriptions
 * @property-read int|null $subscriptions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|User findByUnhashKey(string $hashed)
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDivisionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereFullName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLogin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePreferences($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereProfile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User withActiveChatBots()
 */
	class User extends \Eloquent {}
}

