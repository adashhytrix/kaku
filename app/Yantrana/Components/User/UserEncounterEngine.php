<?php
/**
 * UserEncounterEngine.php - Main component file
 *
 * This file is part of the UserEncounter User component.
 *-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\User;
use Illuminate\Support\Facades\DB;
use App\Yantrana\Components\UserSetting\Models\UserSpecificationModel;
use Carbon\Carbon;
use App\Yantrana\Base\BaseEngine;
use App\Yantrana\Support\CommonTrait;
use App\Yantrana\Components\User\Repositories\UserRepository;
use App\Yantrana\Components\User\Repositories\UserEncounterRepository;
use App\Yantrana\Components\UserSetting\Repositories\UserSettingRepository;

class UserEncounterEngine extends BaseEngine
{
    /**
     * @var  UserEncounterRepository - UserEncounter Repository
     */
    protected $userEncounterRepository;

    /**
     * @var UserRepository - User Repository
     */
    protected $userRepository;

    /**
     * @var UserSettingRepository - UserSetting Repository
     */
    protected $userSettingRepository;

    /**
     * @var CommonTrait - Common Trait
     */
    use CommonTrait;

    /**
     * Constructor
     *
     * @param  UserEncounterRepository  $userEncounterRepository - UserEncounter Repository
     * @return  void
     *-----------------------------------------------------------------------*/
    public function __construct(UserEncounterRepository $userEncounterRepository, UserRepository $userRepository, UserSettingRepository $userSettingRepository)
    {
        $this->userEncounterRepository = $userEncounterRepository;
        $this->userRepository = $userRepository;
        $this->userSettingRepository = $userSettingRepository;
    }

    /**
     * Prepare User Encounter List Data.
     *
     *
     *---------------------------------------------------------------- */
//     public function getEncounterUserData()
//     {
//         $inputData = [
//             'looking_for' => getUserSettings('looking_for'),
//             'min_age' => getUserSettings('min_age'),
//             'max_age' => getUserSettings('max_age'),
//         ];
    
//         // Check if looking for is given in string
//         if (!\__isEmpty($inputData['looking_for'])) {
//             if (\is_string($inputData['looking_for']) && $inputData['looking_for'] == 'all') {
//                 $inputData['looking_for'] = [1, 2, 3];
//             } else {
//                 $inputData['looking_for'] = [$inputData['looking_for']];
//             }
//         } else {
//             $inputData['looking_for'] = [];
//         }

        
    
//         // Delete old encounter User
//         $this->userEncounterRepository->deleteOldEncounterUser();
//         // Fetch all user like dislike data
//         $getLikeDislikeData = $this->userRepository->fetchAllUserLikeDislike();
//         // Pluck to_users_id in array
//         $toUserIds = $getLikeDislikeData->pluck('to_users__id')->toArray();
//         // Fetch encounter user data
//         $userEncounterData = $this->userEncounterRepository->fetchEncounterUser();
//         // Collect encounter user ids
//         $encounterUserIds = $userEncounterData->pluck('to_users__id')->toArray();
//         // All blocked user list
//         $blockUserCollection = $this->userRepository->fetchAllBlockUser();
//         // Blocked user ids
//         $blockUserIds = $blockUserCollection->pluck('to_users__id')->toArray();
//         // Blocked me user list
//         $allBlockMeUser = $this->userRepository->fetchAllBlockMeUser();
//         // Blocked me user ids
//         $blockMeUserIds = $allBlockMeUser->pluck('by_users__id')->toArray();
//         // Can admin show in featured user
//         $adminIds = [];
//         // Check condition is false
//         if (!getStoreSettings('include_exclude_admin')) {
//             // Array merge of unique users ids
//             $adminIds = $this->userRepository->fetchAdminIds();
//         }
    
//         // Array merge of unique users ids
//         $ignoreUserIds = array_unique(array_merge($toUserIds, $encounterUserIds, $blockUserIds, $blockMeUserIds, [getUserID()], $adminIds));
    
//         $randomUser = [];
//         // Fetch random users
//         $randomUser = $this->userEncounterRepository->fetchRandomUser($ignoreUserIds, $inputData);
    
//         $randomUserData = [];

//         // Check is not empty
//         if (!__isEmpty($randomUser)) {
//             $userImageUrl = '';
//             // Check is not empty
//             if (!__isEmpty($randomUser->profile_picture)) {
//                 $profileImageFolderPath = getPathByKey('profile_photo', ['{_uid}' => $randomUser->_uid]);
//                 $userImageUrl = getMediaUrl($profileImageFolderPath, $randomUser->profile_picture);
//             } else {
//                 $userImageUrl = noThumbImageURL();
//             }
//             $userCoverUrl = '';
//             // Check is not empty
//             if (!__isEmpty($randomUser->cover_picture)) {
//                 $coverPath = getPathByKey('cover_photo', ['{_uid}' => $randomUser->_uid]);
//                 $userCoverUrl = getMediaUrl($coverPath, $randomUser->cover_picture);
//             } else {
//                 $userCoverUrl = noThumbCoverImageURL();
//             }
    
//             $userAge = isset($randomUser->dob) ? Carbon::parse($randomUser->dob)->age : null;
//             $gender = isset($randomUser->gender) ? configItem('user_settings.gender', $randomUser->gender) : null;
            
//             $extrastat = DB::table('user_specifications')
//             ->where('users__id', $randomUser->_id)
//             ->get();

//         $userSpecifications = [];

//         foreach ($extrastat as $spec) {
//             if (!empty($spec->specification_value)) {
//                 try {
//                     $value = json_decode($spec->specification_value, true); // Decode JSON as associative array
//                     if ($value === null && json_last_error() !== JSON_ERROR_NONE) {
//                         throw new \Exception('Error decoding JSON: ' . json_last_error_msg());
//                     }
//                     $userSpecifications[$spec->specification_key] = $value;
//                 } catch (\Exception $e) {
//                     \Log::error('Error decoding specification_value for key ' . $spec->specification_key . ': ' . $e->getMessage());
//                     // Handle the error as needed
//                 }
//             }
//         }
//     // dd($userSpecifications);
//             // Check if current user follows the random user
//             $followerId = getUserID();
//             $followedId = $randomUser->_id;
//             $isFollowing = DB::table('follows')
//                 ->where('follower_id', $followerId)
//                 ->where('followed_id', $followedId)
//                 ->exists();

//     $bestvideo=DB::table('best_video')->select('path')->where('added_by',$randomUser->_id)->get();
//     $onboardingimages=DB::table('onboarding_image')->select('thumbnail_path')->where('added_by',$randomUser->_id)->get();

//    $privacysetting=DB::table('users')->select('privacy_setting')->where('_id',$randomUser->_id)->get();
// //    dd($privacysetting);
//             // Random user data
//             // dd($randomUser->nickname);
//             $randomUserData = [
//                 '_id' => $randomUser->_id,
//                 '_uid' => $randomUser->_uid,
//                 'username' => $randomUser->username,
//                 'userFullName' => $randomUser->userFullName,
//                 'stats' => $randomUser->status,
//                 'userImageUrl' => $userImageUrl,
//                 'userCoverUrl' => $userCoverUrl,
//                 'gender' => $gender,
//                 'dob' => $randomUser->dob,
//                 'nearby' => $randomUser->nearby,
//                 'nickname'=>$randomUser->nickname,

//                 'userAge' => $userAge,
//                 'countryName' => $randomUser->countryName,
//                 'userOnlineStatus' => $this->getUserOnlineStatus($randomUser->userAuthorityUpdatedAt),
//                 'isPremiumUser' => isPremiumUser($randomUser->_id),
//                 'userspec' => $userSpecifications,
//                 'best_video' => $bestvideo,
//                 'onboarding_images' => $onboardingimages,

//                 'privacysetting' => $privacysetting,


//                 'isFollowing' => $isFollowing ? true : false,
//             ];
//         }
    
//         return $this->engineReaction(1, [
//             'encounterAvailability' => getFeatureSettings('user_encounter'),
//             'randomUserData' => $randomUserData,
//             'loggedInUserIsPremium' => isPremiumUser(getUserID()),
//         ]);
//     }

public function getEncounterUserData()
{
    $inputData = [
        'looking_for' => getUserSettings('looking_for'),
        'min_age' => getUserSettings('min_age'),
        'max_age' => getUserSettings('max_age'),
    ];

    if (!__isEmpty($inputData['looking_for'])) {
        if (is_string($inputData['looking_for']) && $inputData['looking_for'] == 'all') {
            $inputData['looking_for'] = [1, 2, 3];
        } else {
            $inputData['looking_for'] = [$inputData['looking_for']];
        }
    } else {
        $inputData['looking_for'] = [];
    }

    $this->userEncounterRepository->deleteOldEncounterUser();
    $getLikeDislikeData = $this->userRepository->fetchAllUserLikeDislike();
    $toUserIds = $getLikeDislikeData->pluck('to_users__id')->toArray();
    $userEncounterData = $this->userEncounterRepository->fetchEncounterUser();
    $encounterUserIds = $userEncounterData->pluck('to_users__id')->toArray();
    $blockUserCollection = $this->userRepository->fetchAllBlockUser();
    $blockUserIds = $blockUserCollection->pluck('to_users__id')->toArray();
    $allBlockMeUser = $this->userRepository->fetchAllBlockMeUser();
    $blockMeUserIds = $allBlockMeUser->pluck('by_users__id')->toArray();
    $adminIds = [];

    if (!getStoreSettings('include_exclude_admin')) {
        $adminIds = $this->userRepository->fetchAdminIds();
    }

    $ignoreUserIds = array_unique(array_merge($toUserIds, $encounterUserIds, $blockUserIds, $blockMeUserIds, [getUserID()], $adminIds));
    $randomUsers = $this->userEncounterRepository->fetchRandomUser($ignoreUserIds, $inputData);
   
    $randomUserData = [];

    // Fetch the logged-in user's interests
    $userId = getUserID();
    $loggedInUserInterests = DB::table('user_specifications')
        ->where('users__id', $userId)
        ->where('specification_key', 'intrest')
        ->value('specification_value');

        if ($loggedInUserInterests) {
            $loggedInUserInterests = json_decode($loggedInUserInterests, true);
            // If json_decode fails or returns something that isn't an array, set to empty array
            if (!is_array($loggedInUserInterests)) {
                $loggedInUserInterests = [];
            }
        } else {
            $loggedInUserInterests = [];
        }
    $loggedInUserNearby = DB::table('user_profiles')
    ->where('users__id', $userId)
    ->value('nearby');

   
    foreach ($randomUsers as $randomUser) {
        $randomUserInterests = json_decode($randomUser->specification_values, true);
       
        // Ensure it's an array, defaulting to an empty array if decoding fails
        if (!is_array($randomUserInterests)) {
            $randomUserInterests = [];
        }
    
        // Check if there are any matching interests
        if (!empty(array_intersect($loggedInUserInterests, $randomUserInterests))) {

            $userImageUrl = !__isEmpty($randomUser->profile_picture)
                ? getMediaUrl(getPathByKey('profile_photo', ['{_uid}' => $randomUser->_uid]), $randomUser->profile_picture)
                : noThumbImageURL();

            $userCoverUrl = !__isEmpty($randomUser->cover_picture)
                ? getMediaUrl(getPathByKey('cover_photo', ['{_uid}' => $randomUser->_uid]), $randomUser->cover_picture)
                : noThumbCoverImageURL();

            $userAge = isset($randomUser->dob) ? Carbon::parse($randomUser->dob)->age : null;
            $gender = isset($randomUser->gender) ? configItem('user_settings.gender', $randomUser->gender) : null;

            $extrastat = DB::table('user_specifications')->where('users__id', $randomUser->_id)->get();
            $userSpecifications = [];

            foreach ($extrastat as $spec) {
                if (!empty($spec->specification_value)) {
                    try {
                        $value = json_decode($spec->specification_value, true);
                        if ($value === null && json_last_error() !== JSON_ERROR_NONE) {
                            throw new \Exception('Error decoding JSON: ' . json_last_error_msg());
                        }
                        $userSpecifications[$spec->specification_key] = $value;
                    } catch (\Exception $e) {
                        \Log::error('Error decoding specification_value for key ' . $spec->specification_key . ': ' . $e->getMessage());
                    }
                }
            }

            $isFollowing = DB::table('follows')
                ->where('follower_id', getUserID())
                ->where('followed_id', $randomUser->_id)
                ->exists();
                $isFollowBack = DB::table('follows')
                ->where('follower_id', $randomUser->_id)
                ->where('followed_id', getUserID())
                ->exists();
                $followerId = getUserID(); 

                $isRequestSent = DB::table('follow_requests')
        ->where('follower_id', $followerId)
        ->where('followed_id', $randomUser->_id)
        ->exists();

            $bestVideo = DB::table('best_video')->select('path')->where('added_by', $randomUser->_id)->get();
            $onboardingImages = DB::table('onboarding_image')->select('thumbnail_path')->where('added_by', $randomUser->_id)->get();
            $privacySetting = DB::table('users')->select('privacy_setting')->where('_id', $randomUser->_id)->get();

            $randomUserData[] = [
                '_id' => $randomUser->_id,
                '_uid' => $randomUser->_uid,
                'username' => $randomUser->username,
                'userFullName' => $randomUser->userFullName,
                'status' => $randomUser->status,
                'userImageUrl' => $userImageUrl,
                'userCoverUrl' => $userCoverUrl,
                'gender' => $gender,
                'dob' => $randomUser->dob,
                'nearby' => $randomUser->nearby,
                'nickname' => $randomUser->nickname,
                'userAge' => $userAge,
                'countryName' => $randomUser->countryName,
                'userOnlineStatus' => $this->getUserOnlineStatus($randomUser->userAuthorityUpdatedAt),
               
                'isPremiumUser' => isPremiumUser($randomUser->_id),
                'userspec' => $userSpecifications,
                'best_video' => $bestVideo,
                'onboarding_images' => $onboardingImages,
                'privacysetting' => $privacySetting,
                'isRequestSent' => $isRequestSent ? true : false, 
                'isFollowing' => $isFollowing ? true : false,
                'isFollowBack' => $isFollowBack ? true : false,
                'Specification_value' => json_decode($randomUser->specification_values, true),
            ];
        }else{
            $userImageUrl = !__isEmpty($randomUser->profile_picture)
                ? getMediaUrl(getPathByKey('profile_photo', ['{_uid}' => $randomUser->_uid]), $randomUser->profile_picture)
                : noThumbImageURL();

            $userCoverUrl = !__isEmpty($randomUser->cover_picture)
                ? getMediaUrl(getPathByKey('cover_photo', ['{_uid}' => $randomUser->_uid]), $randomUser->cover_picture)
                : noThumbCoverImageURL();

            $userAge = isset($randomUser->dob) ? Carbon::parse($randomUser->dob)->age : null;
            $gender = isset($randomUser->gender) ? configItem('user_settings.gender', $randomUser->gender) : null;

            $extrastat = DB::table('user_specifications')->where('users__id', $randomUser->_id)->get();
            $userSpecifications = [];

            foreach ($extrastat as $spec) {
                if (!empty($spec->specification_value)) {
                    try {
                        $value = json_decode($spec->specification_value, true);
                        if ($value === null && json_last_error() !== JSON_ERROR_NONE) {
                            throw new \Exception('Error decoding JSON: ' . json_last_error_msg());
                        }
                        $userSpecifications[$spec->specification_key] = $value;
                    } catch (\Exception $e) {
                        \Log::error('Error decoding specification_value for key ' . $spec->specification_key . ': ' . $e->getMessage());
                    }
                }
            }

            $isFollowing = DB::table('follows')
                ->where('follower_id', getUserID())
                ->where('followed_id', $randomUser->_id)
                ->exists();
                
                $isFollowBack = DB::table('follows')
                ->where('follower_id', $randomUser->_id)
                ->where('followed_id', getUserID())
                ->exists();

            $bestVideo = DB::table('best_video')->select('path')->where('added_by', $randomUser->_id)->get();
            $onboardingImages = DB::table('onboarding_image')->select('thumbnail_path')->where('added_by', $randomUser->_id)->get();
            $privacySetting = DB::table('users')->select('privacy_setting')->where('_id', $randomUser->_id)->get();

            $followerId = getUserID(); 

            $isRequestSent = DB::table('follow_requests')
            ->where('follower_id', $followerId)
            ->where('followed_id', $randomUser->_id)
            ->exists();
            $randomUserData[] = [
                '_id' => $randomUser->_id,
                '_uid' => $randomUser->_uid,
                'username' => $randomUser->username,
                'userFullName' => $randomUser->userFullName,
                'status' => $randomUser->status,
                'userImageUrl' => $userImageUrl,
                'userCoverUrl' => $userCoverUrl,
                'gender' => $gender,
                'dob' => $randomUser->dob,
                'nearby' => $randomUser->nearby,
                'nickname' => $randomUser->nickname,
                'userAge' => $userAge,
                'countryName' => $randomUser->countryName,
                'userOnlineStatus' => $this->getUserOnlineStatus($randomUser->userAuthorityUpdatedAt),
                'isPremiumUser' => isPremiumUser($randomUser->_id),
                'userspec' => $userSpecifications,
                'best_video' => $bestVideo,
                'onboarding_images' => $onboardingImages,
                'privacysetting' => $privacySetting,
                'isFollowing' => $isFollowing ? true : false,
                'isFollowBack' => $isFollowBack ? true : false,
                'isRequestSent' => $isRequestSent ? true : false, 
                'Specification_value' => json_decode($randomUser->specification_values, true),
            ];
        }
    }

    return $this->engineReaction(1, [
        'encounterAvailability' => getFeatureSettings('user_encounter'),
        'randomUserData' => $randomUserData,
        'loggedInUserIsPremium' => isPremiumUser(getUserID()),
    ]);
}



    /**
     * Process Skip Encounter User.
     *
     * @param  array  $inputData
     *
     *-----------------------------------------------------------------------*/
    public function processSkipEncounterUser($toUserUid)
    {
        // Delete old encounter User
        $this->userEncounterRepository->deleteOldEncounterUser();
    
        // Fetch User by toUserUid
        $user = $this->userRepository->fetch($toUserUid);
    
        // Check if user exists
        if (__isEmpty($user)) {
            return $this->engineReaction(2, null, __tr('User does not exists.'));
        }
    
        // Store encounter User Data
        $storeData = [
            'status' => 1,
            'to_users__id' => $user->_id,
            'by_users__id' => getUserID(),
        ];
    
        // Store encounter user
        if ($this->userEncounterRepository->storeEncounterUser($storeData)) {
            // Fetch next random user data
            $nextUserData = $this->getEncounterUserData();
            return $this->engineReaction(1, $nextUserData['data'], __tr('Skipped user successfully.'));
        }
    
        return $this->engineReaction(2, null, __tr('Something went wrong.'));
    }
}
