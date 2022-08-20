<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


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
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Role[] $roles
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
 * App\Models\ChatBot
 *
 * @property-read string $hashed_key
 * @property int $id
 * @property string $name
 * @property string $callback_token
 * @property int $social_provider_id
 * @property int $user_count
 * @property mixed $configs
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ResourceActionLog[] $actionLogs
 * @property-read int|null $action_logs_count
 * @property-read \App\Models\SocialProvider|null $provider
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $users
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
 * @property mixed $payload
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
 * @property-read \Illuminate\Database\Eloquent\Collection|Comment[] $replies
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
 * @property mixed $changes
 * @property int $status
 * @property int $requester_id
 * @property \Illuminate\Support\Carbon $submitted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ResourceActionLog[] $actionLogs
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
 * @property mixed $changes
 * @property int $status
 * @property int $requester_id
 * @property \Illuminate\Support\Carbon $submitted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ResourceActionLog[] $actionLogs
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
 * @property mixed $locale
 * @property-read \App\Models\Resources\Registry|null $registry
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
 * App\Models\ResourceActionLog
 *
 * @property int $id
 * @property string $loggable_type
 * @property int $loggable_id
 * @property int $action
 * @property mixed|null $payload
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
 * App\Models\Resources\Division
 *
 * @property int $id
 * @property string $name
 * @property string $name_en
 * @property string $name_en_short
 * @property string $department
 * @property int $active
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
 * @property int $active
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
 * @property int $gender
 * @property \Illuminate\Support\Carbon|null $dob
 * @property mixed|null $profile
 * @property int $alive
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Resources\Admission[] $admissions
 * @property-read int|null $admissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Note[] $notes
 * @property-read int|null $notes_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Resources\Registry[] $registries
 * @property-read int|null $registries_count
 * @method static \Illuminate\Database\Eloquent\Builder|Patient findByHashedKey(string $plain)
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
 * @property int $active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
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
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Ability[] $abilities
 * @property-read int|null $abilities_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $users
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
 * @property mixed $profile
 * @property int $active
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
 * @property mixed $configs
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ResourceActionLog[] $actionLogs
 * @property-read int|null $action_logs_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ChatBot[] $chatBots
 * @property-read int|null $chat_bots_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\SocialProfile[] $users
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
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $subscribers
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
 * @property int $id
 * @property string $name
 * @property string $login
 * @property string $full_name
 * @property string $password
 * @property mixed $profile
 * @property mixed $preferences
 * @property int $division_id
 * @property int $active
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ResourceActionLog[] $actionLogs
 * @property-read int|null $action_logs_count
 * @property-read \App\Models\SocialProfile|null $activeLINEProfile
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ChatBot[] $chatBots
 * @property-read int|null $chat_bots_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ChatLog[] $chatLogs
 * @property-read int|null $chat_logs_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Resources\Registry[] $registries
 * @property-read int|null $registries_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Role[] $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\SocialProfile[] $socialProfiles
 * @property-read int|null $social_profiles_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Subscription[] $subscriptions
 * @property-read int|null $subscriptions_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Sanctum\PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory(...$parameters)
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

