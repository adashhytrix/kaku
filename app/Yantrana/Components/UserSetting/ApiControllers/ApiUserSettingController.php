<?php
/**
 * UserSettingController.php - Controller file
 *
 * This file is part of the UserSetting component.
 *-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\UserSetting\ApiControllers;
use Hash;
use App\Yantrana\Base\BaseController;
use App\Yantrana\Components\UserSetting\Requests\UserBasicSettingAddRequest;
use App\Yantrana\Components\UserSetting\Requests\UserProfileSettingAddRequest;
use App\Yantrana\Components\UserSetting\Requests\UserProfileWizardRequest;
use App\Yantrana\Components\UserSetting\Requests\UserSettingRequest;
use App\Yantrana\Components\UserSetting\UserSettingEngine;
use App\Yantrana\Support\CommonUnsecuredPostRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ApiUserSettingController extends BaseController
{
    /**
     * @var  UserSettingEngine - UserSetting Engine
     */
    protected $userSettingEngine;

    /**
     * Constructor
     *
     * @param  UserSettingEngine  $userSettingEngine - UserSetting Engine
     * @return  void
     *-----------------------------------------------------------------------*/
    public function __construct(UserSettingEngine $userSettingEngine)
    {
        $this->userSettingEngine = $userSettingEngine;
    }

    /**
     * Show user setting view.
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function getUserSettingData($pageType)
    {
        $processReaction = $this->userSettingEngine->prepareUserSettings($pageType);

        return $this->processResponse($processReaction, [], [], true);
    }

    /**
     * Get UserSetting Data.
     *
     * @param  string  $pageType
     * @return json object
     *---------------------------------------------------------------- */
    public function processStoreUserSetting(UserSettingRequest $request, $pageType)
    {
        $processReaction = $this->userSettingEngine->processUserSettingStore($pageType, $request->all());

        return $this->processResponse($processReaction, [], [], true);
    }

    /**
     * Process upload profile image.
     *
     * @param object CommonUnsecuredPostRequest $request
     * @return json object
     *---------------------------------------------------------------- */
    public function uploadProfileImage(Request $request)
    {
        $processReaction = $this->userSettingEngine->processUploadProfileImage($request->all(), 'profile');

        return $this->processResponse($processReaction, [], [], true);
    }
    // public function uploadvideo(Request $request)
    // {
    //     // Validate the request data
    //     $validator = Validator::make($request->all(), [
    //         'video' => 'required|file|mimes:mp4|max:30000', // Max file size: 30MB
    //     ]);

    //     // Check if validation fails
    //     if ($validator->fails()) {
    //         return response()->json(['error' => $validator->errors()->first()], 400);
    //     }

    //     // Get the uploaded video
    //     $video = $request->file('video');
    //     $description = $request->description;

    //     // Generate the new filename based on current date and time
    //     $timestamp = Carbon::now()->format('Ymd_His');
    //     $newFilename = $timestamp . '.' . $video->getClientOriginalExtension();

    //     // Store the uploaded video with the new filename in the storage
    //     $videoPath = $video->storeAs('videos', $newFilename);

    //     // Get the video duration (implement this method accordingly)
    //     $videoDuration = $this->getVideoDuration(storage_path('app/' . $videoPath));

    //     // Assuming getUserID() is a function to get the ID of the currently logged in user
    //     $addedBy = getUserID();

    //     // Store video metadata in the database
    //     $domainName = 'https://api-kaku.jurysoft.in/';
    //     $fullVideoPath = $domainName .'storage/app/' . $videoPath;
    //     DB::table('videos')->insert([
    //         'filename' => $newFilename,
    //         'path' => $fullVideoPath,
    //         'duration' => $videoDuration,
    //         'description'=>$description,
    //         'added_by' => $addedBy,
    //         // 'created_at' => now(),
    //         // 'updated_at' => now()
    //     ]);

    //     // Check the duration of the uploaded video
    //     if ($videoDuration <= 40) {
    //         // Video duration is within 30 seconds, proceed
    //         return response()->json(['message' => 'Video uploaded successfully.']);
    //     } else {
    //         // Video duration is more than 30 seconds, delete the uploaded video
    //         Storage::delete($videoPath);
    //         // Remove video metadata from the database
    //         DB::table('videos')->where('path', $fullVideoPath)->delete();
    //         return response()->json(['error' => 'Uploaded video must be 30 seconds or less.'], 400);
    //     }
    // }

  
    public function uploadVideo(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'video' => 'required|file|mimes:mp4|max:30000', // Max file size: 30MB
            'thumbnail' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Max file size: 2MB
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 400);
        }

        // Get the uploaded video and thumbnail
        $video = $request->file('video');
        $thumbnail = $request->file('thumbnail');
        $description = $request->description;

        // Generate filenames based on current date and time
        $timestamp = Carbon::now()->format('Ymd_His');
        $videoFilename = $timestamp . '.' . $video->getClientOriginalExtension();
        $thumbnailFilename = $timestamp . '.' . $thumbnail->getClientOriginalExtension();

        // Store the uploaded video with the new filename in the storage
        $videoPath = $video->storeAs('videos', $videoFilename);

        // Store the thumbnail in storage
        $thumbnailPath = $thumbnail->storeAs('thumbnails', $thumbnailFilename);

        // Get video duration
        $videoDuration = $this->getVideoDuration(storage_path('app/' . $videoPath));

        // Assuming getUserID() is a function to get the ID of the currently logged in user
        $addedBy = getUserID();

        // Store video metadata in the database and retrieve the inserted record ID
        $domainName = 'https://api-kaku.jurysoft.in/';
        $fullVideoPath = $domainName . 'storage/app/' . $videoPath;
        $fullThumbnailPath = $domainName . 'storage/app/' . $thumbnailPath;

        $videoId = DB::table('videos')->insertGetId([
            'filename' => $videoFilename,
            'path' => $fullVideoPath,
            'thumbnail_path' => $fullThumbnailPath,
            'duration' => $videoDuration,
            'description' => $description,
            'added_by' => $addedBy,
            'tagged_user' => $request->taguser,

            // 'created_at' => now(),
            // 'updated_at' => now()
        ]);

        // Check the duration of the uploaded video
        if ($videoDuration <= 45) {
            // Video duration is within 40 seconds, proceed
            return response()->json([
                'response' => true,
                'message' => 'Video uploaded successfully.',
                'video' => [
                    'id' => $videoId,
                    'filename' => $videoFilename,
                    'path' => $fullVideoPath,
                    'thumbnail_path' => $fullThumbnailPath,
                    'duration' => $videoDuration,
                    'description' => $description,
                ],
            ]);
        } else {
            // Video duration is more than 40 seconds, delete the uploaded video and thumbnail
            Storage::delete([$videoPath, $thumbnailPath]);
            // Remove video metadata from the database
            DB::table('videos')->where('id', $videoId)->delete();
            return response()->json(['error' => 'Uploaded video must be 40 seconds or less.'], 400);
        }
    }
    private function getVideoDuration($filePath)
    {
        // You need to implement logic to get the duration of the video.
        // You can use FFmpeg or other libraries to achieve this.
        // This is a placeholder function.
        return 45; // Replace this with actual code to get video duration
    }
    public function onboardingimage(Request $request){
        $validator = Validator::make($request->all(), [
          
            'thumbnail' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Max file size: 2MB
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 400);
        }

        
        $thumbnail = $request->file('thumbnail');
       
        $timestamp = Carbon::now()->format('Ymd_His');
        
        $thumbnailFilename = $timestamp . '.' . $thumbnail->getClientOriginalExtension();

       $thumbnailPath = $thumbnail->storeAs('onboarding_images', $thumbnailFilename);

        
        $addedBy = getUserID();

       
        $domainName = 'https://api-kaku.jurysoft.in/';
        
        $fullThumbnailPath = $domainName . 'storage/app/' . $thumbnailPath;

        $thumbnailid = DB::table('onboarding_image')->insertGetId([
            'thumbnail_path' => $fullThumbnailPath,
            
            'added_by' => $addedBy,
            'created_at' => now(),
            'updated_at' => now()
        ]);

      
       
            // Video duration is within 40 seconds, proceed
            return response()->json([
                'response' => true,
                'message' => 'Onboarding Images uploaded successfully.',
                'video' => [
                    'id' => $thumbnailid,
                   
                    'thumbnail_path' => $fullThumbnailPath,
                    
                ],
            ]);
    }

    public function getonboardingimage(Request $request)
{
    // Assuming getUserID() is a function to get the ID of the currently logged-in user
    $addedBy = getUserID();

    // Fetch the onboarding images for the user
    $images = DB::table('onboarding_image')->where('added_by', $addedBy)->get();

    // If no images are found, return an appropriate response
    if ($images->isEmpty()) {
        return response()->json([
            'response' => false,
            'message' => 'No onboarding images found.',
            'images' => []
        ], 404);
    }

    // Return the images in the response
    return response()->json([
        'response' => true,
        'message' => 'Onboarding images retrieved successfully.',
        'images' => $images
    ]);
}


    public function bestvideo(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'bestvideo' => 'required|file|mimes:mp4|max:30000', // Max file size: 30MB
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 400);
        }

        // Get the uploaded video and thumbnail
        $video = $request->file('bestvideo');
        $thumbnail = $request->file('thumbnail');
        $description = $request->description;

        // Generate filenames based on current date and time
        $timestamp = Carbon::now()->format('Ymd_His');
        $videoFilename = $timestamp . '.' . $video->getClientOriginalExtension();
        $thumbnailFilename = $timestamp . '.' . $thumbnail->getClientOriginalExtension();

        // Store the uploaded video with the new filename in the storage
        $videoPath = $video->storeAs('best_video', $videoFilename);

        // Store the thumbnail in storage
        $thumbnailPath = $thumbnail->storeAs('thumbnails', $thumbnailFilename);

        // Get video duration
        $videoDuration = $this->getVideoDuration(storage_path('app/' . $videoPath));

        // Assuming getUserID() is a function to get the ID of the currently logged in user
        $addedBy = getUserID();

        // Store video metadata in the database and retrieve the inserted record ID
        $domainName = 'https://api-kaku.jurysoft.in/';
        $fullVideoPath = $domainName . 'storage/app/' . $videoPath;
        $fullThumbnailPath = $domainName . 'storage/app/' . $thumbnailPath;

        $videoId = DB::table('best_video')->insertGetId([
            'filename' => $videoFilename,
            'path' => $fullVideoPath,
            'thumbnail_path' => $fullThumbnailPath,
            'duration' => $videoDuration,
            'description' => $description,
            'added_by' => $addedBy,
            // 'created_at' => now(),
            // 'updated_at' => now()
        ]);

        // Check the duration of the uploaded video
        if ($videoDuration <= 40) {
            // Video duration is within 40 seconds, proceed
            return response()->json([
                'response' => true,
                'message' => 'Video uploaded successfully.',
                'video' => [
                    'id' => $videoId,
                    'filename' => $videoFilename,
                    'path' => $fullVideoPath,
                    'thumbnail_path' => $fullThumbnailPath,
                    'duration' => $videoDuration,
                    'description' => $description,
                ],
            ]);
        } else {
            // Video duration is more than 40 seconds, delete the uploaded video and thumbnail
            Storage::delete([$videoPath, $thumbnailPath]);
            // Remove video metadata from the database
            DB::table('videos')->where('id', $videoId)->delete();
            return response()->json(['error' => 'Uploaded video must be 40 seconds or less.'], 400);
        }
    }

    public function fetchbestvideo($userid)
    {
        $userId = getUserID();
        $video = DB::table('best_video')->where('added_by', $userId)->get();
        return response()->json(
            [
                'Response' => true,
                'video' => $video,
            ],
            200,
        );
    }


    public function deletevideo($id, Request $request)
    {
        // Ensure the video exists before attempting to delete it
        $video = DB::table('videos')->where('id', $id)->first();

        if (!$video) {
            return response()->json(['message' => 'Video not found.'], 404);
        }

        DB::table('videos')->where('id', $id)->delete();
        return response()->json(['message' => 'Video deleted successfully.']);
    }
    public function deletebestvideo($id, Request $request)
    {
        // Ensure the video exists before attempting to delete it
        $video = DB::table('best_video')->where('id', $id)->first();

        if (!$video) {
            return response()->json(['message' => 'Video not found.'], 404);
        }

        DB::table('best_video')->where('id', $id)->delete();
        return response()->json(['message' => 'Video deleted successfully.']);
    }

    // public function getAllVideos(Request $request)
    // {
    //     // Define the number of items per page
    //     $perPage = $request->input('per_page', 10); // Default to 10 if not specified

    //     // Fetch videos with pagination and include resolution
    //     $currentUserId = getUserID(); // Get the ID of the current logged-in user
    //     $userdetail = DB::table('user_profiles')->where('users__id', $currentUserId)->first();
    //     $user = DB::table('users')->where('_id', $currentUserId)->first();

    //     if (!__isEmpty($userdetail->profile_picture)) {
    //         $profileImageFolderPath = getPathByKey('profile_photo', ['{_uid}' => $user->_uid]);
    //         $userImageUrl = getMediaUrl($profileImageFolderPath, $userdetail->profile_picture);
    //     } else {
    //         $userImageUrl = noThumbImageURL();
    //     }
    //     $videos = DB::table('videos')
    //         ->join('users', 'users._id', '=', 'videos.added_by')
    //         ->join('user_profiles', 'user_profiles.users__id', '=', 'videos.added_by')
    //         ->leftJoin('comment', 'comment.video_id', '=', 'videos.id')
    //         ->leftJoin('videolikes', 'videolikes.video_id', '=', 'videos.id') // Using leftJoin to include videos with no likes
    //         ->leftJoin('follows', function ($join) use ($currentUserId) {
    //             $join->on('follows.followed_id', '=', 'videos.added_by')->where('follows.follower_id', '=', $currentUserId);
    //         })
    //         ->leftJoin('intrested', function ($join) use ($currentUserId) {
    //             $join->on('intrested.video_id', '=', 'videos.id')->where('intrested.user_id', '=', $currentUserId);
    //         })
    //         ->leftJoin('save_video', function ($join) use ($currentUserId) {
    //             $join->on('save_video.video_id', '=', 'videos.id')->where('save_video.user_id', '=', $currentUserId);
    //         })
    //         ->select(
    //             'videos.id',
    //             'videos.filename',
    //             'videos.description',
    //             'videos.path',
    //             'videos.added_by',
    //             'users.first_name',
    //             'users.last_name',
                
    //             DB::raw('users._id as user_id'),

    //             DB::raw('COUNT(DISTINCT comment.id) as total_comments'), 
    //             'videos.thumbnail_path',
                
    //             DB::raw('COALESCE(user_profiles.nearby, 0) as nearby'), // Use IFNULL to default to 0 if nearby is null
    //             'user_profiles.nickname',

    //             DB::raw('COALESCE(COUNT(DISTINCT videolikes.user_id), 0) as total_likes'), // Correctly count unique likes by user_id

    //             DB::raw("IF(EXISTS(SELECT 1 FROM videolikes WHERE videolikes.video_id = videos.id AND videolikes.user_id = $currentUserId), 1, 0) as liked_by_current_user"),

    //             DB::raw('IF(follows.id IS NULL, 0, 1) as followed_by_current_user'),
    //             DB::raw('IF(intrested.id IS NULL, 0, 1) as interested_by_current_user'),
    //             DB::raw('IF(save_video.id IS NULL, 0, 1) as saved_by_current_user'),
    //         )
    //         ->groupBy('videos.id', 'videos.filename', 'videos.description', 'videos.path', 'videos.added_by', 'users.username', 'user_profiles.profile_picture', 'user_profiles.nearby') // Group by necessary columns
    //         ->orderBy('videos.id',"desc")
    //         ->paginate($perPage);

    //     // Convert the liked_by_current_user, followed_by_current_user, interested_by_current_user, and saved_by_current_user fields from 0/1 to true/false
    //     $videos->getCollection()->transform(function ($video) {
    //         $video->liked_by_current_user = (bool) $video->liked_by_current_user;
    //         $video->followed_by_current_user = (bool) $video->followed_by_current_user;
    //         $video->interested_by_current_user = (bool) $video->interested_by_current_user;
    //         $video->saved_by_current_user = (bool) $video->saved_by_current_user;
    //         return $video;
    //     });

    //     // Return the paginated result as a JSON response
    //     return response()->json($videos);
    // }
    public function getAllVideos(Request $request)
{
    // Define the number of items per page
    $perPage = $request->input('per_page', 10); // Default to 10 if not specified

    // Get the ID of the current logged-in user
    $currentUserId = getUserID();

    // Get the blocked video IDs
    $blockedUserIds = DB::table('report_video')
        ->where('user_id', $currentUserId)
        ->pluck('video_id')
        ->toArray();

    // Fetch videos and include privacy, follower, follow-back, and like information
    $videos = DB::table('videos')
        ->join('users', 'users._id', '=', 'videos.added_by')
        ->join('user_profiles', 'user_profiles.users__id', '=', 'videos.added_by')
        ->leftJoin('comment', 'comment.video_id', '=', 'videos.id')
        ->leftJoin('videolikes', 'videolikes.video_id', '=', 'videos.id')
        ->leftJoin('follows as current_follow', function ($join) use ($currentUserId) {
            $join->on('current_follow.followed_id', '=', 'videos.added_by')
                 ->where('current_follow.follower_id', '=', $currentUserId);
        })
        ->leftJoin('follows as follow_back', function ($join) use ($currentUserId) {
            $join->on('follow_back.follower_id', '=', 'videos.added_by')
                 ->where('follow_back.followed_id', '=', $currentUserId);
        })
        ->leftJoin('intrested', function ($join) use ($currentUserId) {
            $join->on('intrested.video_id', '=', 'videos.id')
                 ->where('intrested.user_id', '=', $currentUserId);
        })
        ->leftJoin('save_video', function ($join) use ($currentUserId) {
            $join->on('save_video.video_id', '=', 'videos.id')
                 ->where('save_video.user_id', '=', $currentUserId);
        })

        ->leftJoin('follow_requests', function ($join) use ($currentUserId) {
            $join->on('follow_requests.followed_id', '=', 'videos.added_by')
                 ->where('follow_requests.follower_id', '=', $currentUserId);
        })
        ->select(
            'videos.id',
            'videos.filename',
            'videos.view_count',
            'videos.description',
            'videos.path',
            'videos.added_by',
          

            'users.first_name',
            'users.last_name',
            DB::raw('users._id as user_id'),
            DB::raw('users._uid  as _uid'),
          

          


            DB::raw('COUNT(DISTINCT comment.id) as total_comments'),
            'videos.thumbnail_path',
            DB::raw('COALESCE(user_profiles.nearby, 0) as nearby'),
            'user_profiles.nickname',
            DB::raw('COALESCE(COUNT(DISTINCT videolikes.user_id), 0) as total_likes'),
            DB::raw("IF(EXISTS(SELECT 1 FROM videolikes WHERE videolikes.video_id = videos.id AND videolikes.user_id = $currentUserId), 1, 0) as liked_by_current_user"),
            DB::raw('IF(current_follow.id IS NULL, 0, 1) as followed_by_current_user'),
            DB::raw('IF(follow_back.id IS NULL, 0, 1) as followed_back_by_uploader'), // Follow-back check
            DB::raw('IF(intrested.id IS NULL, 0, 1) as interested_by_current_user'),
            DB::raw('IF(save_video.id IS NULL, 0, 1) as saved_by_current_user'),
            DB::raw('IF(follow_requests.id IS NULL, 0, 1) as isRequestSent'),
            'users.privacy_setting', // Include the privacy setting
            'user_profiles.profile_picture as uploader_profile_picture' // Use uploader's profile picture
        )
        ->groupBy('videos.id', 'videos.filename', 'videos.description', 'videos.path', 'videos.added_by', 'users._id', 'users.first_name', 'users.last_name', 'user_profiles.profile_picture', 'user_profiles.nearby', 'user_profiles.nickname', 'users.privacy_setting')
        ->whereNotIn('videos.id', $blockedUserIds)
        ->where(function ($query) use ($currentUserId) {
            // Show public videos or private videos if the user follows the uploader
            $query->where('users.privacy_setting', 'public')
                  ->orWhere(function ($subQuery) use ($currentUserId) {
                      $subQuery->where('users.privacy_setting', 'private')
                               ->whereNotNull('current_follow.id'); // Only show private videos if the user is following
                  });
        })
        ->inRandomOrder()
        ->paginate($perPage);

    // Transform the video data
    $videos->getCollection()->transform(function ($video) use ($currentUserId) {
        $video->liked_by_current_user = (bool) $video->liked_by_current_user;
        $video->followed_by_current_user = (bool) $video->followed_by_current_user;
        $video->followed_back_by_uploader = (bool) $video->followed_back_by_uploader; // Mutual follow (follow-back)
        $video->interested_by_current_user = (bool) $video->interested_by_current_user;
        $video->saved_by_current_user = (bool) $video->saved_by_current_user;
        $video->isRequestSent = (bool) $video->isRequestSent; // Check if follow request was sent by the current user


        // Set the profile picture for the user who added the video dynamically
        // if (!__isEmpty($video->uploader_profile_picture)) {
        //     $profileImageFolderPath = getPathByKey('profile_photo', ['{_uid}' => $video->user_id]);
        //     $video->profile_picture = getMediaUrl($profileImageFolderPath, $video->uploader_profile_picture);
        // } else {
        //     $video->profile_picture = noThumbImageURL();
        // }

        if (!__isEmpty($video->uploader_profile_picture)) {
        $profileImageFolderPath = getPathByKey('profile_photo', ['{_uid}' => $video->_uid ]);
        $video->profile_picture = getMediaUrl($profileImageFolderPath, $video->uploader_profile_picture);
    } else {
        $userImageUrl = noThumbImageURL();
    }


        return $video;
    });

    // Return the paginated result as a JSON response
    return response()->json($videos);
}



// public function tagvideos(Request $request, $userId)
// {
//     $perPage = $request->input('per_page', 10); // Default to 10 if not specified
//     $currentUserId = getUserID(); // Get the ID of the current logged-in user

//     // Fetch videos where the current user is tagged
//     $videos = DB::table('videos')
//         ->whereRaw('JSON_CONTAINS(tagged_user, ?)', ['"'.$currentUserId.'"'])
//         ->join('users as uploader', 'uploader._id', '=', 'videos.added_by')
//         ->join('user_profiles as uploader_profiles', 'uploader_profiles.users__id', '=', 'videos.added_by')
//         ->leftJoin('comment', 'comment.video_id', '=', 'videos.id')
//         ->leftJoin('videolikes', 'videolikes.video_id', '=', 'videos.id')
//         ->leftJoin('follows', function ($join) use ($userId) {
//             $join->on('follows.followed_id', '=', 'videos.added_by')
//                  ->where('follows.follower_id', '=', $userId);
//         })
//         ->leftJoin('intrested', function ($join) use ($userId) {
//             $join->on('intrested.video_id', '=', 'videos.id')
//                  ->where('intrested.user_id', '=', $userId);
//         })
//         ->leftJoin('save_video', function ($join) use ($userId) {
//             $join->on('save_video.video_id', '=', 'videos.id')
//                  ->where('save_video.user_id', '=', $userId);
//         })
//         ->select(
//             'videos.id',
//             'videos.filename',
//             'videos.view_count',
//             'videos.description',
//             'videos.path',
//             'videos.added_by',
//             'uploader.first_name as uploader_first_name',
//             'uploader.last_name as uploader_last_name',
//             'uploader._uid  as uploader_id',
//             'uploader_profiles.profile_picture as uploader_profile_picture',
//             'uploader_profiles.nickname as uploader_nickname',
//             DB::raw('COUNT(DISTINCT comment.id) as total_comments'),
//             'videos.thumbnail_path',
//             DB::raw('COALESCE(COUNT(DISTINCT videolikes.user_id), 0) as total_likes'),
//             DB::raw("IF(EXISTS(SELECT 1 FROM videolikes WHERE videolikes.video_id = videos.id AND videolikes.user_id = $userId), 1, 0) as liked_by_current_user"),
//             DB::raw('IF(follows.id IS NULL, 0, 1) as followed_by_current_user'),
//             DB::raw('IF(intrested.id IS NULL, 0, 1) as interested_by_current_user'),
//             DB::raw('IF(save_video.id IS NULL, 0, 1) as saved_by_current_user')
//         )
//         ->groupBy('videos.id', 'videos.filename', 'videos.description', 'videos.path', 'videos.added_by', 'uploader._id', 'uploader.first_name', 'uploader.last_name', 'uploader_profiles.profile_picture', 'uploader_profiles.nickname', 'videos.thumbnail_path')
//         ->orderBy('videos.id', 'desc')
//         ->paginate($perPage);

//     // Transform the collection to include uploader's profile picture
//     $videos->getCollection()->transform(function ($video) {
//         // Ensure profile picture is set or use default
//         if (!$video->uploader_profile_picture) {
//             $video->uploader_profile_picture = noThumbImageURL();
//         } else {
//             $video->uploader_profile_picture = getMediaUrl(getPathByKey('profile_photo', ['{_uid}' => $video->uploader_id]), $video->uploader_profile_picture);
//         }

//         return $video;
//     });

//     // Return the paginated result as a JSON response
//     return response()->json($videos);
// }

public function tagvideos(Request $request, $userId)
{
    $perPage = $request->input('per_page', 10); // Default to 10 if not specified
    $currentUserId = getUserID(); // Get the ID of the current logged-in user

    // Fetch videos where the current user is tagged
    $videos = DB::table('videos')
        ->whereRaw('JSON_CONTAINS(tagged_user, ?)', ['"'.$currentUserId.'"'])
        ->join('users as uploader', 'uploader._id', '=', 'videos.added_by')
        ->join('user_profiles as uploader_profiles', 'uploader_profiles.users__id', '=', 'videos.added_by')
        ->leftJoin('comment', 'comment.video_id', '=', 'videos.id')
        ->leftJoin('videolikes', 'videolikes.video_id', '=', 'videos.id')
        ->leftJoin('follows', function ($join) use ($userId) {
            $join->on('follows.followed_id', '=', 'videos.added_by')
                 ->where('follows.follower_id', '=', $userId);
        })
        ->leftJoin('intrested', function ($join) use ($userId) {
            $join->on('intrested.video_id', '=', 'videos.id')
                 ->where('intrested.user_id', '=', $userId);
        })
        ->leftJoin('save_video', function ($join) use ($userId) {
            $join->on('save_video.video_id', '=', 'videos.id')
                 ->where('save_video.user_id', '=', $userId);
        })
        ->select(
            'videos.id',
            'videos.filename',
            'videos.view_count',
            'videos.description',
            'videos.path',
            'videos.added_by',
            'uploader.first_name as uploader_first_name',
            'uploader.last_name as uploader_last_name',
            'uploader._uid as uploader_id',
            'uploader_profiles.profile_picture as uploader_profile_picture',
            'uploader_profiles.nickname as uploader_nickname',
            'videos.tagged_user', // Include the tagged_user field
            DB::raw('COUNT(DISTINCT comment.id) as total_comments'),
            'videos.thumbnail_path',
            DB::raw('COALESCE(COUNT(DISTINCT videolikes.user_id), 0) as total_likes'),
            DB::raw("IF(EXISTS(SELECT 1 FROM videolikes WHERE videolikes.video_id = videos.id AND videolikes.user_id = $userId), 1, 0) as liked_by_current_user"),
            DB::raw('IF(follows.id IS NULL, 0, 1) as followed_by_current_user'),
            DB::raw('IF(intrested.id IS NULL, 0, 1) as interested_by_current_user'),
            DB::raw('IF(save_video.id IS NULL, 0, 1) as saved_by_current_user')
        )
        ->groupBy(
            'videos.id',
            'videos.filename',
            'videos.description',
            'videos.path',
            'videos.added_by',
            'uploader._id',
            'uploader.first_name',
            'uploader.last_name',
            'uploader_profiles.profile_picture',
            'uploader_profiles.nickname',
            'videos.thumbnail_path'
        )
        ->orderBy('videos.id', 'desc')
        ->paginate($perPage);

    // Transform each video to include tagged users
    $videos->getCollection()->transform(function ($video) {
        // Set uploader profile picture or use a default one if it's missing
        if (!$video->uploader_profile_picture) {
            $video->uploader_profile_picture = noThumbImageURL();
        } else {
            $video->uploader_profile_picture = getMediaUrl(getPathByKey('profile_photo', ['{_uid}' => $video->uploader_id]), $video->uploader_profile_picture);
        }

        // Decode the tagged users from JSON format
        $taggedUserIds = json_decode($video->tagged_user);
        $currentUserId = getUserID();

        // Fetch user profiles for all tagged users
        if (!empty($taggedUserIds)) {
            $taggedUsers = DB::table('users')
                ->join('user_profiles', 'user_profiles.users__id', '=', 'users._id')
                ->leftJoin('follows as currentUserFollowsTagged', function ($join) use ($currentUserId) {
                    $join->on('currentUserFollowsTagged.followed_id', '=', 'users._id')
                        ->where('currentUserFollowsTagged.follower_id', '=', $currentUserId);
                })
                ->leftJoin('follows as taggedUserFollowsCurrent', function ($join) use ($currentUserId) {
                    $join->on('taggedUserFollowsCurrent.follower_id', '=', 'users._id')
                        ->where('taggedUserFollowsCurrent.followed_id', '=', $currentUserId);
                })
                ->leftJoin('follow_requests', function ($join) use ($currentUserId) {
                    $join->on('follow_requests.followed_id', '=', 'users._id')
                        ->where('follow_requests.follower_id', '=', $currentUserId);
                })
                ->whereIn('users._id', $taggedUserIds)
                ->select(
                    'users._id',
                    'users._uid',
                    'users.username',
                    'user_profiles.nickname',
                    'user_profiles.profile_picture',
                    DB::raw('IF(follow_requests.id IS NOT NULL, 1, 0) as isRequestSent'),
                    DB::raw('IF(currentUserFollowsTagged.id IS NOT NULL, 1, 0) as followed_by_current_user'),
                    DB::raw('IF(taggedUserFollowsCurrent.id IS NOT NULL, 1, 0) as followback_by_tagged_user')
                )
                ->get();

            // Process profile pictures and cast 1/0 to boolean true/false
            $taggedUsers->transform(function ($user) {
                // Set profile picture URL or default
                if (!$user->profile_picture) {
                    $user->profile_picture = noThumbImageURL(); // Default profile picture
                } else {
                    $user->profile_picture = getMediaUrl(getPathByKey('profile_photo', ['{_uid}' => $user->_uid]), $user->profile_picture);
                }

                // Cast 1/0 to boolean
                $user->isRequestSent = (bool) $user->isRequestSent;
                $user->followed_by_current_user = (bool) $user->followed_by_current_user;
                $user->followback_by_tagged_user = (bool) $user->followback_by_tagged_user;

                return $user;
            });
            $video->tagged_users = $taggedUsers;
        } else {
            $video->tagged_users = [];
        }

        return $video;
    });

    // Return the paginated result as a JSON response
    return response()->json($videos);
}





public function getNotifications()
{
    $userId = getUserID();

    $notifications = DB::table('noti')
        ->where('noti.to_user_id', $userId)
        // ->whereIn('noti.type', ['comment', 'like','reply']) 
        ->leftJoin('videos', 'videos.id', '=', 'noti.video_id')
        ->join('users', 'users._id', '=', 'noti.user_id')
        ->join('user_profiles', 'user_profiles.users__id', '=', 'noti.user_id')
        ->leftJoin('comment', 'comment.video_id', '=', 'videos.id')
        ->leftJoin('videolikes', 'videolikes.video_id', '=', 'videos.id')
        ->leftJoin('follows', function ($join) use ($userId) {
            $join->on('follows.followed_id', '=', 'videos.added_by')
                 ->where('follows.follower_id', '=', $userId);
        })
        ->leftJoin('intrested', function ($join) use ($userId) {
            $join->on('intrested.video_id', '=', 'videos.id')
                 ->where('intrested.user_id', '=', $userId);
        })
        ->leftJoin('save_video', function ($join) use ($userId) {
            $join->on('save_video.video_id', '=', 'videos.id')
                 ->where('save_video.user_id', '=', $userId);
        })
        ->leftJoin('follow_requests', function ($join) use ($userId) {
            $join->on('follow_requests.followed_id', '=', 'videos.added_by')
                 ->where('follow_requests.follower_id', '=', $userId);
        })
        ->select(
            'noti.*',
            'videos.id',
            'videos.filename',
            'videos.view_count',
            'videos.description',
            'videos.path',
            'videos.added_by',
            'users.first_name',
            'users.last_name',
            DB::raw('users._id as user_id'),
            DB::raw('COUNT(DISTINCT comment.id) as total_comments'),
            'videos.thumbnail_path',
            DB::raw('COALESCE(user_profiles.nearby, 0) as nearby'),
            'user_profiles.nickname',
            DB::raw('COALESCE(COUNT(DISTINCT videolikes.user_id), 0) as total_likes'),
            DB::raw("IF(EXISTS(SELECT 1 FROM videolikes WHERE videolikes.video_id = videos.id AND videolikes.user_id = $userId), 1, 0) as liked_by_current_user"),
            DB::raw('IF(follows.id IS NULL, 0, 1) as followed_by_current_user'),
            DB::raw('IF(intrested.id IS NULL, 0, 1) as interested_by_current_user'),
            DB::raw('IF(save_video.id IS NULL, 0, 1) as saved_by_current_user'),
            DB::raw('IF(follow_requests.id IS NULL, 0, 1) as isRequestSent'),
            'user_profiles.profile_picture'
        )
        ->groupBy(
            'noti.id', 
            'videos.id',
            'videos.filename',
            'videos.description',
            'videos.path',
            'videos.added_by',
            'users._id',
            'users.first_name',
            'users.last_name',
            'user_profiles.profile_picture',
            'user_profiles.nearby',
            'user_profiles.nickname'
        )
        ->orderBy('videos.id', "desc")
        ->get();

    foreach ($notifications as $notification) {
        $notification->user_images = []; // Initialize an array to store user images

        // Retrieve all user profile records
        $userDetails = DB::table('user_profiles')
            ->where('users__id', $notification->user_id)
            ->get(); // Fetch all matching records

        // Retrieve all user records
        $users = DB::table('users')
            ->where('_id', $notification->user_id)
            ->get(); // Fetch all matching records

        // Iterate over each userDetails record
        foreach ($userDetails as $userDetail) {
            foreach ($users as $userRecord) {
                // Check if profile picture exists for each user detail record
                if (!__isEmpty($userDetail->profile_picture)) {
                    $profileImageFolderPath = getPathByKey('profile_photo', ['{_uid}' => $userRecord->_uid]);
                    $userImageUrl = getMediaUrl($profileImageFolderPath, $userDetail->profile_picture);
                } else {
                    $userImageUrl = noThumbImageURL();
                }

                // Add each user image URL to the user_images array
                $notification->user_images = $userImageUrl;
            }
        }
    }

    // Transform notifications to add flags and additional information
    $notifications->transform(function ($notification) {
        $notification->liked_by_current_user = (bool) $notification->liked_by_current_user;
        $notification->followed_by_current_user = (bool) $notification->followed_by_current_user;
        $notification->interested_by_current_user = (bool) $notification->interested_by_current_user;
        $notification->saved_by_current_user = (bool) $notification->saved_by_current_user;
        $notification->isRequestSent  = (bool) $notification->isRequestSent;

        // Profile pictures are already stored in the user_images array
        return $notification;
    });

    return response()->json([
        'response' => true,
        'notifications' => $notifications,
    ]);
}


// public function getAllVideos(Request $request)
// {
//     // Define the number of items per page
//     $perPage = $request->input('per_page', 10); // Default to 10 if not specified

//     // Get the ID of the current logged-in user
//     $currentUserId = getUserID();

//     // Get the blocked video IDs
//     $blockedUserIds = DB::table('report_video')
//         ->where('user_id', $currentUserId)
//         ->pluck('video_id')
//         ->toArray();

//     // Fetch videos and include privacy, follower, follow-back, and like information
//     $videos = DB::table('videos')
//         ->join('users', 'users._id', '=', 'videos.added_by')
//         ->join('user_profiles', 'user_profiles.users__id', '=', 'videos.added_by')
//         ->leftJoin('comment', 'comment.video_id', '=', 'videos.id')
//         ->leftJoin('videolikes', 'videolikes.video_id', '=', 'videos.id')
//         ->leftJoin('follows as current_follow', function ($join) use ($currentUserId) {
//             $join->on('current_follow.followed_id', '=', 'videos.added_by')
//                  ->where('current_follow.follower_id', '=', $currentUserId);
//         })
//         ->leftJoin('follows as follow_back', function ($join) use ($currentUserId) {
//             $join->on('follow_back.follower_id', '=', 'videos.added_by')
//                  ->where('follow_back.followed_id', '=', $currentUserId);
//         })
//         ->leftJoin('intrested', function ($join) use ($currentUserId) {
//             $join->on('intrested.video_id', '=', 'videos.id')
//                  ->where('intrested.user_id', '=', $currentUserId);
//         })
//         ->leftJoin('save_video', function ($join) use ($currentUserId) {
//             $join->on('save_video.video_id', '=', 'videos.id')
//                  ->where('save_video.user_id', '=', $currentUserId);
//         })
//         ->select(
//             'videos.id',
//             'videos.filename',
//             'videos.view_count',
//             'videos.description',
//             'videos.path',
//             'videos.added_by',
//             'users.first_name',
//             'users.last_name',
//             DB::raw('users._id as user_id'),
//             DB::raw('users._uid  as _uid'),

//             DB::raw('COUNT(DISTINCT comment.id) as total_comments'),
//             'videos.thumbnail_path',
//             DB::raw('COALESCE(user_profiles.nearby, 0) as nearby'),
//             'user_profiles.nickname',
//             DB::raw('COALESCE(COUNT(DISTINCT videolikes.user_id), 0) as total_likes'),
//             DB::raw("IF(EXISTS(SELECT 1 FROM videolikes WHERE videolikes.video_id = videos.id AND videolikes.user_id = $currentUserId), 1, 0) as liked_by_current_user"),
//             DB::raw('IF(current_follow.id IS NULL, 0, 1) as followed_by_current_user'),
//             DB::raw('IF(follow_back.id IS NULL, 0, 1) as followed_back_by_uploader'), // Follow-back check
//             DB::raw('IF(intrested.id IS NULL, 0, 1) as interested_by_current_user'),
//             DB::raw('IF(save_video.id IS NULL, 0, 1) as saved_by_current_user'),
//             'users.privacy_setting', // Include the privacy setting
//             'user_profiles.profile_picture as uploader_profile_picture' // Use uploader's profile picture
//         )
//         ->groupBy('videos.id', 'videos.filename', 'videos.description', 'videos.path', 'videos.added_by', 'users._id', 'users.first_name', 'users.last_name', 'user_profiles.profile_picture', 'user_profiles.nearby', 'user_profiles.nickname', 'users.privacy_setting')
//         ->whereNotIn('videos.id', $blockedUserIds)
//         ->where(function ($query) use ($currentUserId) {
//             // Show public videos or private videos if the user follows the uploader
//             $query->where('users.privacy_setting', 'public')
//                   ->orWhere(function ($subQuery) use ($currentUserId) {
//                       $subQuery->where('users.privacy_setting', 'private')
//                                ->whereNotNull('current_follow.id'); // Only show private videos if the user is following
//                   });
//         })
//         ->inRandomOrder()
//         ->paginate($perPage);

//     // Transform the video data
//     $videos->getCollection()->transform(function ($video) use ($currentUserId) {
//         $video->liked_by_current_user = (bool) $video->liked_by_current_user;
//         $video->followed_by_current_user = (bool) $video->followed_by_current_user;
//         $video->followed_back_by_uploader = (bool) $video->followed_back_by_uploader; // Mutual follow (follow-back)
//         $video->interested_by_current_user = (bool) $video->interested_by_current_user;
//         $video->saved_by_current_user = (bool) $video->saved_by_current_user;

//         // Set the profile picture for the user who added the video dynamically
//         // if (!__isEmpty($video->uploader_profile_picture)) {
//         //     $profileImageFolderPath = getPathByKey('profile_photo', ['{_uid}' => $video->user_id]);
//         //     $video->profile_picture = getMediaUrl($profileImageFolderPath, $video->uploader_profile_picture);
//         // } else {
//         //     $video->profile_picture = noThumbImageURL();
//         // }

//         if (!__isEmpty($video->uploader_profile_picture)) {
//         $profileImageFolderPath = getPathByKey('profile_photo', ['{_uid}' => $video->_uid ]);
//         $video->profile_picture = getMediaUrl($profileImageFolderPath, $video->uploader_profile_picture);
//     } else {
//         $userImageUrl = noThumbImageURL();
//     }


//         return $video;
//     });

//     // Return the paginated result as a JSON response
//     return response()->json($videos);
// }
public function fetchuservideo($userid, Request $request)
{
    $perPage = $request->input('per_page', 10); // Default to 10 if not specified
    $currentUserId = getUserID(); // Get the ID of the current logged-in user

    // Fetch videos with pagination and include resolution
    $videos = DB::table('videos')
        ->where('videos.added_by', $userid)
        ->join('users', 'users._id', '=', 'videos.added_by')
        ->join('user_profiles', 'user_profiles.users__id', '=', 'videos.added_by')
        ->leftJoin('comment', 'comment.video_id', '=', 'videos.id')
        ->leftJoin('videolikes', 'videolikes.video_id', '=', 'videos.id') // Using leftJoin to include videos with no likes
        ->leftJoin('follows', function ($join) use ($currentUserId) {
            $join->on('follows.followed_id', '=', 'videos.added_by')
                 ->where('follows.follower_id', '=', $currentUserId);
        })
        ->leftJoin('intrested', function ($join) use ($currentUserId) {
            $join->on('intrested.video_id', '=', 'videos.id')
                 ->where('intrested.user_id', '=', $currentUserId);
        })
        ->leftJoin('save_video', function ($join) use ($currentUserId) {
            $join->on('save_video.video_id', '=', 'videos.id')
                 ->where('save_video.user_id', '=', $currentUserId);
        })
        ->leftJoin('follow_requests', function ($join) use ($currentUserId) {
            $join->on('follow_requests.followed_id', '=', 'videos.added_by')
                 ->where('follow_requests.follower_id', '=', $currentUserId);
        })
        
        ->select(
            'videos.id',
            'videos.filename',
            'videos.view_count',
            DB::raw('users._uid  as _uid'),
            'videos.description',
            'videos.path',
            'videos.added_by',
            'users.first_name',
            'users.last_name',
            'user_profiles.profile_picture as uploader_profile_picture', // Uploader's profile picture
            DB::raw('users._id as user_id'),
            DB::raw('COUNT(DISTINCT comment.id) as total_comments'),
            'videos.thumbnail_path',
            DB::raw('COALESCE(user_profiles.nearby, 0) as nearby'),
            'user_profiles.nickname',
            DB::raw('COALESCE(COUNT(DISTINCT videolikes.user_id), 0) as total_likes'),
            DB::raw("IF(EXISTS(SELECT 1 FROM videolikes WHERE videolikes.video_id = videos.id AND videolikes.user_id = $currentUserId), 1, 0) as liked_by_current_user"),
            DB::raw('IF(follows.id IS NULL, 0, 1) as followed_by_current_user'),
            DB::raw('IF(intrested.id IS NULL, 0, 1) as interested_by_current_user'),
            DB::raw('IF(save_video.id IS NULL, 0, 1) as saved_by_current_user'),
            DB::raw('IF(follow_requests.id IS NULL, 0, 1) as isRequestSent'),
            // Follow-back status for each video creator
            DB::raw("IF(EXISTS(SELECT 1 FROM follows WHERE follows.follower_id = $userid AND follows.followed_id = $currentUserId), 1, 0) as followed_back")
        )
        ->groupBy(
            'videos.id',
            'videos.filename',
            'videos.description',
            'videos.path',
            'videos.added_by',
            'users.first_name',
            'users.last_name',
            'user_profiles.profile_picture',
            'user_profiles.nearby',
            'user_profiles.nickname'
        )
        ->orderBy('videos.id', 'desc')
        ->paginate($perPage);

    // Convert the liked_by_current_user, followed_by_current_user, interested_by_current_user, saved_by_current_user, and followed_back fields from 0/1 to true/false
    $videos->getCollection()->transform(function ($video) {
        $video->liked_by_current_user = (bool) $video->liked_by_current_user;
        $video->followed_by_current_user = (bool) $video->followed_by_current_user;
        $video->interested_by_current_user = (bool) $video->interested_by_current_user;
        $video->saved_by_current_user = (bool) $video->saved_by_current_user;
        $video->followed_back = (bool) $video->followed_back;
        $video->isRequestSent = (bool) $video->isRequestSent;

        // Profile picture handling logic
        if (!empty($video->uploader_profile_picture)) {
            $profileImageFolderPath = getPathByKey('profile_photo', ['{_uid}' => $video->_uid]);
            $video->profile_picture = getMediaUrl($profileImageFolderPath, $video->uploader_profile_picture);
        } else {
            $video->profile_picture = noThumbImageURL(); // Default image
        }

        return $video;
    });

    // Return the paginated result as a JSON response
    return response()->json($videos);
}



    public function fetchvideoid($videoid, Request $request)
    {
       
        $perPage = $request->input('per_page', 10); // Default to 10 if not specified
        $currentUserId = getUserID(); // Get the ID of the current logged-in user
        $videos = DB::table('videos')
        ->where('videos.id', $videoid)
        ->get();
        
        // Fetch videos with pagination and include resolution
        $videos = DB::table('videos')
            ->where('videos.id', $videoid)
            ->join('users', 'users._id', '=', 'videos.added_by')
            ->join('user_profiles', 'user_profiles.users__id', '=', 'videos.added_by')
            ->leftJoin('comment', 'comment.video_id', '=', 'videos.id')
            ->leftJoin('videolikes', 'videolikes.video_id', '=', 'videos.id') // Using leftJoin to include videos with no likes
            ->leftJoin('follows', function ($join) use ($currentUserId) {
                $join->on('follows.followed_id', '=', 'videos.added_by')->where('follows.follower_id', '=', $currentUserId);
            })
            ->leftJoin('intrested', function ($join) use ($currentUserId) {
                $join->on('intrested.video_id', '=', 'videos.id')->where('intrested.user_id', '=', $currentUserId);
            })
            ->leftJoin('save_video', function ($join) use ($currentUserId) {
                $join->on('save_video.video_id', '=', 'videos.id')->where('save_video.user_id', '=', $currentUserId);
            })
            ->select(
                'videos.id',
                'videos.filename',
                'videos.view_count',

                'videos.description',
                'videos.path',
                'videos.added_by',
                'users.first_name',
                'users.last_name',
                DB::raw('users._id as user_id'),
                DB::raw('COUNT(DISTINCT comment.id) as total_comments'), 
                'videos.thumbnail_path',
                DB::raw("CONCAT('https://api-kaku.jurysoft.in/public/media-storage/users/', user_profiles.profile_picture) as profile_picture"),
                DB::raw('COALESCE(user_profiles.nearby, 0) as nearby'), // Use IFNULL to default to 0 if nearby is null
                'user_profiles.nickname',
                DB::raw('COALESCE(COUNT(DISTINCT videolikes.user_id), 0) as total_likes'), // Correctly count unique likes by user_id
                DB::raw("IF(EXISTS(SELECT 1 FROM videolikes WHERE videolikes.video_id = videos.id AND videolikes.user_id = $currentUserId), 1, 0) as liked_by_current_user"),
                DB::raw('IF(follows.id IS NULL, 0, 1) as followed_by_current_user'),
                DB::raw('IF(intrested.id IS NULL, 0, 1) as interested_by_current_user'),
                DB::raw('IF(save_video.id IS NULL, 0, 1) as saved_by_current_user')
            )
            ->groupBy('videos.id', 'videos.filename', 'videos.description', 'videos.path', 'videos.added_by', 'users.first_name', 'users.last_name', 'user_profiles.profile_picture', 'user_profiles.nearby', 'user_profiles.nickname') // Group by necessary columns
            ->orderBy('videos.id', 'desc')
            ->paginate($perPage);
    
        // Convert the liked_by_current_user, followed_by_current_user, interested_by_current_user, and saved_by_current_user fields from 0/1 to true/false
        $videos->getCollection()->transform(function ($video) {
            $video->liked_by_current_user = (bool) $video->liked_by_current_user;
            $video->followed_by_current_user = (bool) $video->followed_by_current_user;
            $video->interested_by_current_user = (bool) $video->interested_by_current_user;
            $video->saved_by_current_user = (bool) $video->saved_by_current_user;
            return $video;
        });
    
        // Return the paginated result as a JSON response
        return response()->json($videos);
    }

    public function blockuser(Request $request,$blocked_user){
        $userId = getUserID();
        $block = DB::table('blocked_user')->insert([
            'blocking_user' => $userId,
            'blocked_user' => $blocked_user,
           

            'created_at' => now(),
            'updated_at' => now(),
        ]);
        return response()->json([
            'status' => 'success',

            'Message' => 'User Blocked Successfully Successfully',
        ]);
    }

    public function getblockuser(Request $request){
        $userId = getUserID();
        // $data = DB::table('blocked_user')->where('blocking_user',$userId)->select('blocked_user')->get();
        $perPage = $request->input('per_page', 10); 
        
    $userdetail = DB::table('user_profiles')->where('users__id', $userId)->first();
    $user = DB::table('users')->where('_id', $userId)->first();

    if (!__isEmpty($userdetail->profile_picture)) {
        $profileImageFolderPath = getPathByKey('profile_photo', ['{_uid}' => $user->_uid]);
        $userImageUrl = getMediaUrl($profileImageFolderPath, $userdetail->profile_picture);
    } 
        $data = DB::table('blocked_user')->where('blocking_user',$userId)
        ->join('users', 'users._id', '=', 'blocked_user.blocked_user')
        ->join('user_profiles', 'user_profiles.users__id', '=', 'users._id')
    
        ->select(
            'users._id as user_id',
            'users._uid as user_uid',
            'users.first_name',
            'users.last_name',
            'user_profiles.nickname',
            'user_profiles.profile_picture',
            DB::raw('COALESCE(user_profiles.nearby, 0) as nearby')
        )
        ->paginate($perPage);
        // ->get();
        return response()->json([
            'status' => 'success',

            'data' => $data,
        ]);




    }
    

    public function getsavevideo(Request $request)
{
    $userId = getUserID();
    $perPage = $request->input('per_page', 10);

    // Query to fetch saved videos for the current user
    $videos = DB::table('save_video')
        ->where('save_video.user_id', $userId)
        ->join('videos', 'videos.id', '=', 'save_video.video_id')
        ->join('users', 'users._id', '=', 'videos.added_by')
        ->join('user_profiles', 'user_profiles.users__id', '=', 'users._id')
        ->leftJoin('videolikes', 'videolikes.video_id', '=', 'videos.id')
        ->leftJoin('comment', 'comment.video_id', '=', 'videos.id')
        ->leftJoin('follows', function ($join) use ($userId) {
            $join->on('follows.followed_id', '=', 'videos.added_by')
                 ->where('follows.follower_id', '=', $userId);
        })
        ->leftJoin('intrested', function ($join) use ($userId) {
            $join->on('intrested.video_id', '=', 'videos.id')
                 ->where('intrested.user_id', '=', $userId);
        })
        ->leftJoin('follow_requests', function ($join) use ($userId) {
            $join->on('follow_requests.followed_id', '=', 'videos.added_by')
                 ->where('follow_requests.follower_id', '=', $userId);
        })
        ->leftJoin('follows as follows_back', function ($join) use ($userId) {
            // Check if the uploader (videos.added_by) follows the current user (userId)
            $join->on('follows_back.follower_id', '=', 'videos.added_by')
                 ->where('follows_back.followed_id', '=', $userId);
        })
        ->select(
            'videos.*',
            'videos.added_by as user_id',
            'users.username',
            DB::raw("CONCAT('https://api-kaku.jurysoft.in/public/media-storage/users/', user_profiles.profile_picture) as profile_picture"), 
            'user_profiles.nearby',
            'user_profiles.nickname',
            DB::raw('SUM(videolikes.count) as total_likes'),
            DB::raw('GROUP_CONCAT(videolikes.user_id) as liked_by_users'),
            DB::raw('COUNT(comment.id) as total_comments'),
            DB::raw("IF(EXISTS(SELECT 1 FROM videolikes WHERE videolikes.video_id = videos.id AND videolikes.user_id = $userId), 1, 0) as liked_by_current_user"),
            DB::raw('IF(follows.id IS NULL, 0, 1) as followed_by_current_user'),
            DB::raw('IF(intrested.id IS NULL, 0, 1) as interested_by_current_user'),
            DB::raw('IF(save_video.id IS NULL, 0, 1) as saved_by_current_user'),
            DB::raw('IF(follow_requests.id IS NULL, 0, 1) as isRequestSent'),
            DB::raw('IF(follows_back.id IS NULL, 0, 1) as followed_back_by_uploader'),
            'users.privacy_setting',
        )
        ->groupBy('videos.id', 'users.username', 'user_profiles.profile_picture', 'user_profiles.nearby')
        ->paginate($perPage);

    // Transform the video collection to boolean values
    $videos->getCollection()->transform(function ($video) {
        $video->liked_by_current_user = (bool) $video->liked_by_current_user;
        $video->followed_by_current_user = (bool) $video->followed_by_current_user;
        $video->interested_by_current_user = (bool) $video->interested_by_current_user;
        $video->saved_by_current_user = (bool) $video->saved_by_current_user;
        $video->isRequestSent = (bool) $video->isRequestSent;
        $video->followed_back_by_uploader = (bool) $video->followed_back_by_uploader;
        return $video;
    });

    // Return the paginated result as a JSON response
    return response()->json([
        'status' => 'success',
        'data' => $videos,
    ]);
}

    public function reportvideo($videoid, Request $request)
    {
        $userId = getUserID();
        $report = DB::table('report_video')->insert([
            'user_id' => $userId,
            'video_id' => $videoid,
            'reason' => $request->reason,

            'created_at' => now(),
            'updated_at' => now(),
        ]);
        return response()->json([
            'status' => 'success',

            'Message' => 'video Report Successfully',
        ]);
    }

    public function reportuser($reporteduser,Request $request)
    {
        $userId = getUserID();
        $report = DB::table('report_user')->insert([
            'reporting_user' => $userId,
            'reported_user' => $reporteduser,
            'reason' => $request->reason,

            'created_at' => now(),
            'updated_at' => now(),
        ]);
        return response()->json([
            'status' => 'success',

            'Message' => 'User Report Successfully',
        ]);

    }
   
    public function getreportuser(Request $request){
        $userId = getUserID();

        $report = DB::table('report_user')->where('reporting_user',$userId)->select('reported_user')->get();
        return response()->json([
            'status' => 'success',

            'data' => $report,
        ]);

    }
    public function fetch_comment($id)
    {
        $comment = DB::table('comment')
            ->where('video_id', $id)
            ->join('user_profiles', 'user_profiles.users__id', '=', 'comment.user_id')
            ->select('comment.id', 'comment.video_id', 'comment.user_id', 'comment.comment', 'comment.created_at', 'comment.updated_at', 'user_profiles.nickname')

            ->get();

            foreach($comment as $comments){
            $replycount=DB::table('comment_reply')->where('parent_id',$comments->id)->count();
            $comments->replycount=$replycount;
            }
            foreach($comment as $comments){
            $userdetail = DB::table('user_profiles')->where('users__id', $comments->user_id)->first();
            $user = DB::table('users')->where('_id', $comments->user_id)->first();
    
    if($userdetail){

 
            // dd($userdetail);
            if (!__isEmpty($userdetail->profile_picture)) {
                $profileImageFolderPath = getPathByKey('profile_photo', ['{_uid}' => $user->_uid]);
                $userImageUrl = getMediaUrl($profileImageFolderPath, $userdetail->profile_picture);
            } else {
                $userImageUrl = noThumbImageURL();
            }

            $comments->nickname = $userdetail->nickname;
            $comments->userImageUrl = $userImageUrl;

        }else{
            $comments->nickname = null;
            $comments->userImageUrl = null;
        }

        }
    
    
          
        return response()->json([
            'status' => 'success',

            'data' => $comment,
            // 'reply'=>$commentsWithReplies
        ]);
    }
    public function fetch_replies($comment_id)
{
    // Fetch replies for the given comment ID
    $replies = DB::table('comment_reply')
        ->where('parent_id', $comment_id)
        ->get();

    // Check if replies exist
    if ($replies->isEmpty()) {
        return response()->json([
            'status' => 'error',
            'message' => 'No replies found for this comment',
        ], 404);
    }

    // Add user details to each reply
    foreach ($replies as $reply) {
        // Fetch user profile details
        $userdetail = DB::table('user_profiles')->where('users__id', $reply->user_id)->first();
        $userImageUrl = null; // Initialize with null in case there's no profile picture

        // If user details exist, fetch the profile picture
        if ($userdetail) {
            $user = DB::table('users')->where('_id', $reply->user_id)->first();

            // Set user profile picture URL
            if (!__isEmpty($userdetail->profile_picture)) {
                $profileImageFolderPath = getPathByKey('profile_photo', ['{_uid}' => $user->_uid]);
                $userImageUrl = getMediaUrl($profileImageFolderPath, $userdetail->profile_picture);
            } else {
                $userImageUrl = noThumbImageURL();
            }

            // Attach user details to the reply
            $reply->nickname = $userdetail->nickname;
            $reply->userImageUrl = $userImageUrl;
        } else {
            // If no user details, set default values
            $reply->nickname = null;
            $reply->userImageUrl = null;
        }
    }

    return response()->json([
        'status' => 'success',
        'data' => $replies,
    ]);
}

    


  

    public function likevideo(Request $request, $videoId,$to_user_id)
    {
        // Assuming getUserID() is a function to get the ID of the currently logged in user
        $userId = getUserID();

        // Check if the user already liked the video
        $like = DB::table('videolikes')->where('video_id', $videoId)->where('user_id', $userId)->first();

        if ($like) {
            // User has already liked the video, so unlike it
            DB::table('videolikes')->where('video_id', $videoId)->where('user_id', $userId)->delete();

            return response()->json([
                'response' => false,

                'message' => 'Video unliked successfully.',
            ]);
        } else {
            // Insert a new like record
            DB::table('videolikes')->insert([
                'video_id' => $videoId,
                'user_id' => $userId,
                

                'count' => 1, // Assuming 'count' column is used to indicate a like, set it to 1

                // 'created_at' => now(),
                // 'updated_at' => now()
            ]);
            $stat = DB::table('user_profiles')->where('users__id', $userId)->first();
            // dd($stat);
            DB::table('noti')->insert([
                'user_id' => $userId,
                'to_user_id' => $to_user_id,
                'video_id' => $videoId,
                'type' => 'like',
                'message' => $stat->nickname . ' liked your video.',
                'profile' => $stat->profile_picture,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json([
                'response' => true,
                'message' => 'Video liked successfully.',
            ]);
        }
    }
    // save video
    public function savevideo($videoid, Request $request)
    {
        $userId = getUserID();
        $savedVideo = DB::table('save_video')->where('video_id', $videoid)->where('user_id', $userId)->first();

        if ($savedVideo) {
        $savedVideo = DB::table('save_video')->where('video_id', $videoid)->where('user_id', $userId)->delete();
        return response()->json([
                'response' => true,
                'message' => 'Video Unsaved successfully.',
            ]);

        } else {
            DB::table('save_video')->insert([
                'video_id' => $videoid,
                'user_id' => $userId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json([
                'response' => true,
                'message' => 'Video Saved successfully.',
            ]);
        }
    }

    // public function follow($followedId)
    // {
    //     $followerId = getUserID();

    //     // Check if already following
    //     $existingFollow = DB::table('follows')->where('follower_id', $followerId)->where('followed_id', $followedId)->first();

    //     if ($existingFollow) {
    //         return response()->json(['message' => 'You are already following this user.'], 400);
    //     }

    //     // Check privacy setting of the followed user
    //     $followedUser = DB::table('users')->where('_id', $followedId)->first();

    //     if ($followedUser->privacy_setting == 'private') {
    //         // Check if a follow request already exists
    //         $existingRequest = DB::table('follow_requests')->where('follower_id', $followerId)->where('followed_id', $followedId)->first();

    //         if ($existingRequest) {
    //             return response()->json(['message' => 'You have already sent a follow request to this user.'], 400);
    //         }

    //         // Create follow request
    //         DB::table('follow_requests')->insert([
    //             'follower_id' => $followerId,
    //             'followed_id' => $followedId,
    //             'created_at' => Carbon::now(),
    //             'updated_at' => Carbon::now(),
    //         ]);

    //         return response()->json(['message' => 'Follow request sent.']);
    //     }

    //     // Create follow relationship for public accounts
    //     DB::table('follows')->insert([
    //         'follower_id' => $followerId,
    //         'followed_id' => $followedId,
    //         'created_at' => Carbon::now(),
    //         'updated_at' => Carbon::now(),
    //     ]);

    //     return response()->json(['message' => 'Successfully followed the user.']);
    // }
    public function follow($followedId)
 {
    $followerId = getUserID();

    // Check if already following
    $existingFollow = DB::table('follows')
        ->where('follower_id', $followerId)
        ->where('followed_id', $followedId)
        ->first();

    if ($existingFollow) {
        return response()->json(['message' => 'You are already following this user.'], 400);
    }

    // Check if a follow request already exists
    $existingRequest = DB::table('follow_requests')
        ->where('follower_id', $followerId)
        ->where('followed_id', $followedId)
        ->first();

    if ($existingRequest) {
        return response()->json(['message' => 'You have already sent a follow request to this user.'], 400);
    }

    // Create follow request (for both private and public users)
    DB::table('follow_requests')->insert([
        'follower_id' => $followerId,
        'followed_id' => $followedId,
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
    ]);

    return response()->json(['message' => 'Follow request sent.']);
 }

 public function cancelFollowRequest($followedId)
{
    $followerId = getUserID();

    // Check if follow request exists
    $existingRequest = DB::table('follow_requests')
        ->where('follower_id', $followerId)
        ->where('followed_id', $followedId)
        ->first();

    // If no follow request exists, return an error response
    if (!$existingRequest) {
        return response()->json(['message' => 'No follow request to cancel.'], 400);
    }

    // Delete the follow request
    DB::table('follow_requests')
        ->where('follower_id', $followerId)
        ->where('followed_id', $followedId)
        ->delete();

    return response()->json(['message' => 'Follow request canceled.']);
}



    // suggested user 
//     public function getSuggestedUsers()
// {
//     $currentUserId = getUserID();

//     // Get the list of users the current user is already following
//     $followedUserIds = DB::table('follows')
//         ->where('follower_id', $currentUserId)
//         ->pluck('followed_id');

//     // Get the list of users the current user has sent a follow request to
//     $requestedUserIds = DB::table('follow_requests')
//         ->where('follower_id', $currentUserId)
//         ->pluck('followed_id');

//         $blockedUserIds = DB::table('blocked_user')
//         ->where('blocking_user', $currentUserId)
//         ->pluck('blocked_user')
//         ->toArray();

//         $reportuser = DB::table('report_user')
//         ->where('reporting_user', $currentUserId)
//         ->pluck('reported_user')
//         ->toArray();
//     // Combine the followed and requested IDs into one collection
//     $excludedUserIds = $followedUserIds->merge($requestedUserIds)->toArray();

//     // Get suggested users who are not followed and have not received a follow request
//     $suggestedUsers = DB::table('users')
//         ->whereNotIn('_id', $excludedUserIds)
//         ->whereNotIn('_id', $blockedUserIds)
//         ->whereNotIn('_id', $reportuser)

//         ->where('_id', '!=', $currentUserId) // Exclude the current user from suggestions
//         // ->join('')
//         ->select('_id') // Add any fields you want to include
//         ->get();

//         foreach ($suggestedUsers as $suggestedUser) {
//             // Fetch user profile details
//             $userdetail = DB::table('user_profiles')->where('users__id', $suggestedUser->_id)->first();
//             $userImageUrl = null; // Initialize with null in case there's no profile picture
    
//             // If user details exist, fetch the profile picture
//             if ($userdetail) {
//                 $user = DB::table('users')->where('_id', $suggestedUser->_id)->first();
    
//                 // Set user profile picture URL
//                 if (!__isEmpty($userdetail->profile_picture)) {
//                     $profileImageFolderPath = getPathByKey('profile_photo', ['{_uid}' => $user->_uid]);
//                     $userImageUrl = getMediaUrl($profileImageFolderPath, $userdetail->profile_picture);
//                 } else {
//                     $userImageUrl = noThumbImageURL();
//                 }
    
//                 // Attach user details to the reply
//                 $suggestedUser->nickname = $userdetail->nickname;
//                 $suggestedUser->userImageUrl = $userImageUrl;
//             } else {
//                 // If no user details, set default values
//                 $suggestedUser->nickname = null;
//                 $suggestedUser->userImageUrl = null;
//             }
//         }

//     return response()->json([
//         'status' => 'success',
//         'data' => $suggestedUsers,
//     ]);
// }
// public function getSuggestedUsers()
// {
//     $currentUserId = getUserID();

//     // Get the list of users the current user is already following
//     $followedUserIds = DB::table('follows')
//         ->where('follower_id', $currentUserId)
//         ->pluck('followed_id');

//     // Get the list of users the current user has sent a follow request to
//     $requestedUserIds = DB::table('follow_requests')
//         ->where('follower_id', $currentUserId)
//         ->pluck('followed_id');

//     // Get blocked and reported user IDs
//     $blockedUserIds = DB::table('blocked_user')
//         ->where('blocking_user', $currentUserId)
//         ->pluck('blocked_user')
//         ->toArray();

//     $reportuser = DB::table('report_user')
//         ->where('reporting_user', $currentUserId)
//         ->pluck('reported_user')
//         ->toArray();

//     // Get the list of users that have been removed/hidden by the current user
//     $removedUserIds = DB::table('removed_users')
//         ->where('user_id', $currentUserId)
//         ->pluck('removed_user_id')
//         ->toArray();

//     // Combine the followed, requested, blocked, reported, and removed user IDs
//     $excludedUserIds = array_merge(
//         $followedUserIds->toArray(),
//         $requestedUserIds->toArray(),
//         $blockedUserIds,
//         $reportuser,
//         $removedUserIds
//     );

//     // Get suggested users who are not excluded
//     $suggestedUsers = DB::table('users')
//         ->whereNotIn('_id', $excludedUserIds)
//         ->where('_id', '!=', $currentUserId) // Exclude the current user
//         ->select('_id')
//         ->get();

//     foreach ($suggestedUsers as $suggestedUser) {
//         $userdetail = DB::table('user_profiles')->where('users__id', $suggestedUser->_id)->first();
//         $userImageUrl = null;

//         if ($userdetail) {
//             $user = DB::table('users')->where('_id', $suggestedUser->_id)->first();
//             $profileImageFolderPath = getPathByKey('profile_photo', ['{_uid}' => $user->_uid]);

//             $userImageUrl = !__isEmpty($userdetail->profile_picture)
//                 ? getMediaUrl($profileImageFolderPath, $userdetail->profile_picture)
//                 : noThumbImageURL();

//             $suggestedUser->nickname = $userdetail->nickname;
//             $suggestedUser->userImageUrl = $userImageUrl;
//         } else {
//             $suggestedUser->nickname = null;
//             $suggestedUser->userImageUrl = null;
//         }
//     }

//     return response()->json([
//         'status' => 'success',
//         'data' => $suggestedUsers,
//     ]);
// }

public function getSuggestedUsers()
{
    $currentUserId = getUserID(); // The logged-in user's ID

    // Get the list of users the current user is already following
    $followedUserIds = DB::table('follows')
        ->where('follower_id', $currentUserId)
        ->pluck('followed_id')
        ->toArray();

    // Get blocked and reported user IDs
    $blockedUserIds = DB::table('blocked_user')
        ->where('blocking_user', $currentUserId)
        ->pluck('blocked_user')
        ->toArray();

    $reportUserIds = DB::table('report_user')
        ->where('reporting_user', $currentUserId)
        ->pluck('reported_user')
        ->toArray();

    // Get the list of users that have been removed/hidden by the current user
    $removedUserIds = DB::table('removed_users')
        ->where('user_id', $currentUserId)
        ->pluck('removed_user_id')
        ->toArray();

    // Combine the excluded user IDs
    $excludedUserIds = array_merge(
        $followedUserIds,
        $blockedUserIds,
        $reportUserIds,
        $removedUserIds
    );

    // Get users followed by the users the current user follows (mutual follows)
    $mutualFollowedUserIds = DB::table('follows')
        ->whereIn('follower_id', $followedUserIds) // Find users followed by the people you follow
        ->whereNotIn('followed_id', $excludedUserIds) // Exclude users you're already interacting with
        ->where('followed_id', '!=', $currentUserId) // Exclude the current user
        ->pluck('followed_id')
        ->toArray();

    // Get suggested users who are mutual follows
    $suggestedUsers = DB::table('users')
        ->whereIn('_id', $mutualFollowedUserIds)
        ->select('_id')
        ->get();

    foreach ($suggestedUsers as $suggestedUser) {
        $userDetail = DB::table('user_profiles')->where('users__id', $suggestedUser->_id)->first();
        $userImageUrl = null;

        // Check if follow request is already sent by the current user
        $isRequestSent = DB::table('follow_requests')
            ->where('follower_id', $currentUserId)
            ->where('followed_id', $suggestedUser->_id)
            ->exists(); // returns true if request exists, false otherwise

        if ($userDetail) {
            $user = DB::table('users')->where('_id', $suggestedUser->_id)->first();
            $profileImageFolderPath = getPathByKey('profile_photo', ['{_uid}' => $user->_uid]);

            $userImageUrl = !__isEmpty($userDetail->profile_picture)
                ? getMediaUrl($profileImageFolderPath, $userDetail->profile_picture)
                : noThumbImageURL();

            $suggestedUser->nickname = $userDetail->nickname;
            $suggestedUser->userImageUrl = $userImageUrl;
        } else {
            $suggestedUser->nickname = null;
            $suggestedUser->userImageUrl = null;
        }

        // Add the isRequestSent field to the suggested user
        $suggestedUser->isRequestSent = $isRequestSent;
    }

    return response()->json([
        'status' => 'success',
        'data' => $suggestedUsers,
    ]);
}


// public function getSuggestedUsersbyintrest()
// {
//     $currentUserId = getUserID();

//     if (is_null($currentUserId)) {
//         // If no user is logged in, return an empty response
//         return response()->json([
//             'status' => 'error',
//             'message' => 'No user is logged in.',
//             'data' => []
//         ]);
//     }

//     // Get the list of users the current user is already following
//     $followedUserIds = DB::table('follows')
//         ->where('follower_id', $currentUserId)
//         ->pluck('followed_id')
//         ->toArray();

//     // Get blocked, reported, and removed user IDs
//     $blockedUserIds = DB::table('blocked_user')
//         ->where('blocking_user', $currentUserId)
//         ->pluck('blocked_user')
//         ->toArray();

//     $reportUserIds = DB::table('report_user')
//         ->where('reporting_user', $currentUserId)
//         ->pluck('reported_user')
//         ->toArray();

//     $removedUserIds = DB::table('removed_users_by_intrest')
//         ->where('user_id', $currentUserId)
//         ->pluck('removed_user_id')
//         ->toArray();

//     // Combine exclusion list
//     $excludedUserIds = array_merge(
//         $followedUserIds,
//         $blockedUserIds,
//         $reportUserIds,
//         $removedUserIds
//     );

//     // Get the current user's interests
//     $currentUserInterests = DB::table('user_specifications')
//         ->where('users__id', $currentUserId)
//         ->where('specification_key', 'intrest')
//         ->pluck('specification_value')
//         ->first();
        
//     // Decode the interests from JSON-like format
//     if ($currentUserInterests) {
        
//         $currentUserInterests = json_decode($currentUserInterests);
//     } else {
//         $currentUserInterests = [];
//     }
//     if (!is_array($currentUserInterests)) {
//         $currentUserInterests = [];
//     }

//     // Build the query for suggested users
//     $suggestedUsersQuery = DB::table('users')
//         ->leftJoin('user_specifications', 'users._id', '=', 'user_specifications.users__id')
//         ->whereNotIn('users._id', $excludedUserIds)
//         ->where('users._id', '!=', $currentUserId);

//     // Add interest matching condition
//     if (!empty($currentUserInterests)) {
//         $suggestedUsersQuery->whereIn('users._id', function($subQuery) use ($currentUserInterests) {
//             $subQuery->select('users__id')
//                      ->from('user_specifications')
//                      ->where('specification_key', 'intrest')
//                      ->where(function ($q) use ($currentUserInterests) {
//                          foreach ($currentUserInterests as $interest) {
//                              $q->orWhere('specification_value', 'LIKE', "%$interest%");
//                          }
//                      })
//                      ->groupBy('users__id');
//         });
//     } else {
//         // If currentUserInterests is empty, don't match any users
//         $suggestedUsersQuery->whereRaw('1 = 0');
//     }

//     // Execute the query
//     $suggestedUsers = $suggestedUsersQuery->select('users._id')->groupBy('users._id')->get();

//     // Get user details for suggested users
//     foreach ($suggestedUsers as $suggestedUser) {
//         $userDetail = DB::table('user_profiles')->where('users__id', $suggestedUser->_id)->first();
//         $userImageUrl = null;

//         if ($userDetail) {
//             $user = DB::table('users')->where('_id', $suggestedUser->_id)->first();
//             $profileImageFolderPath = getPathByKey('profile_photo', ['{_uid}' => $user->_uid]);

//             $userImageUrl = !__isEmpty($userDetail->profile_picture)
//                 ? getMediaUrl($profileImageFolderPath, $userDetail->profile_picture)
//                 : noThumbImageURL();

//             $suggestedUser->nickname = $userDetail->nickname;
//             $suggestedUser->userImageUrl = $userImageUrl;
//         } else {
//             $suggestedUser->nickname = null;
//             $suggestedUser->userImageUrl = null;
//         }
//     }

//     return response()->json([
//         'status' => 'success',
//         'data' => $suggestedUsers,
//     ]);
// }


public function getsuggestedUsersbyintrest()
{
    $currentUserId = getUserID();

    if (is_null($currentUserId)) {
        // If no user is logged in, return an empty response
        return response()->json([
            'status' => 'error',
            'message' => 'No user is logged in.',
            'data' => []
        ]);
    }

    // Get the list of users the current user is already following
    $followedUserIds = DB::table('follows')
        ->where('follower_id', $currentUserId)
        ->pluck('followed_id')
        ->toArray();

    // Get blocked, reported, and removed user IDs
    $blockedUserIds = DB::table('blocked_user')
        ->where('blocking_user', $currentUserId)
        ->pluck('blocked_user')
        ->toArray();

    $reportUserIds = DB::table('report_user')
        ->where('reporting_user', $currentUserId)
        ->pluck('reported_user')
        ->toArray();

    $removedUserIds = DB::table('removed_users_by_intrest')
        ->where('user_id', $currentUserId)
        ->pluck('removed_user_id')
        ->toArray();

    // Combine exclusion list
    $excludedUserIds = array_merge(
        $followedUserIds,
        $blockedUserIds,
        $reportUserIds,
        $removedUserIds
    );

    // Get the current user's interests
    $currentUserInterests = DB::table('user_specifications')
        ->where('users__id', $currentUserId)
        ->where('specification_key', 'intrest')
        ->pluck('specification_value')
        ->first();

    // Decode the interests from JSON-like format
    if ($currentUserInterests) {
        $currentUserInterests = json_decode($currentUserInterests);
    } else {
        $currentUserInterests = [];
    }

    if (!is_array($currentUserInterests)) {
        $currentUserInterests = [];
    }

    // Build the query for suggested users
    $suggestedUsersQuery = DB::table('users')
        ->leftJoin('user_specifications', 'users._id', '=', 'user_specifications.users__id')
        ->whereNotIn('users._id', $excludedUserIds)
        ->where('users._id', '!=', $currentUserId);

    // Add interest matching condition
    if (!empty($currentUserInterests)) {
        $suggestedUsersQuery->whereIn('users._id', function($subQuery) use ($currentUserInterests) {
            $subQuery->select('users__id')
                ->from('user_specifications')
                ->where('specification_key', 'intrest')
                ->where(function ($q) use ($currentUserInterests) {
                    foreach ($currentUserInterests as $interest) {
                        $q->orWhere('specification_value', 'LIKE', "%$interest%");
                    }
                })
                ->groupBy('users__id');
        });
    } else {
        // If currentUserInterests is empty, don't match any users
        $suggestedUsersQuery->whereRaw('1 = 0');
    }

    // Execute the query
    $suggestedUsers = $suggestedUsersQuery->select('users._id')->groupBy('users._id')->get();

    // Get user details for suggested users
    foreach ($suggestedUsers as $suggestedUser) {
        $userDetail = DB::table('user_profiles')->where('users__id', $suggestedUser->_id)->first();
        $userImageUrl = null;

        // Check if follow request is already sent by the current user
        $isRequestSent = DB::table('follow_requests')
            ->where('follower_id', $currentUserId)
            ->where('followed_id', $suggestedUser->_id)
            ->exists(); // returns true if request exists, false otherwise

        if ($userDetail) {
            $user = DB::table('users')->where('_id', $suggestedUser->_id)->first();
            $profileImageFolderPath = getPathByKey('profile_photo', ['{_uid}' => $user->_uid]);

            $userImageUrl = !__isEmpty($userDetail->profile_picture)
                ? getMediaUrl($profileImageFolderPath, $userDetail->profile_picture)
                : noThumbImageURL();

            $suggestedUser->nickname = $userDetail->nickname;
            $suggestedUser->userImageUrl = $userImageUrl;
        } else {
            $suggestedUser->nickname = null;
            $suggestedUser->userImageUrl = null;
        }

        // Add the isRequestSent field to the suggested user
        $suggestedUser->isRequestSent = $isRequestSent;
    }

    return response()->json([
        'status' => 'success',
        'data' => $suggestedUsers,
    ]);
}


public function deleteSuggestedUsersbyintrest(Request $request, $id)
{
    $currentUserId = getUserID();
    $removedUserId = $id;

    // Check if the user exists in the 'users' table
    $userExists = DB::table('users')->where('_id', $removedUserId)->exists();

    if (!$userExists) {
        return response()->json([
            'status' => 'error',
            'message' => 'User not found',
        ], 404);
    }

    // Check if the record already exists in the 'removed_users' table
    $alreadyRemoved = DB::table('removed_users_by_intrest')
        ->where('user_id', $currentUserId)
        ->where('removed_user_id', $removedUserId)
        ->exists();

    if ($alreadyRemoved) {
        return response()->json([
            'status' => 'error',
            'message' => 'User already removed from suggestions',
        ], 400);
    }

    // Insert the user into the 'removed_users' table if not already removed
    DB::table('removed_users_by_intrest')->insert([
        'user_id' => $currentUserId,
        'removed_user_id' => $removedUserId,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return response()->json([
        'status' => 'success',
        'message' => 'User removed from suggestions by intrest',
    ]);
}



public function deleteSuggestedUsers(Request $request, $id)
{
    $currentUserId = getUserID();
    $removedUserId = $id;

    // Check if the user exists in the 'users' table
    $userExists = DB::table('users')->where('_id', $removedUserId)->exists();

    if (!$userExists) {
        return response()->json([
            'status' => 'error',
            'message' => 'User not found',
        ], 404);
    }

    // Check if the record already exists in the 'removed_users' table
    $alreadyRemoved = DB::table('removed_users')
        ->where('user_id', $currentUserId)
        ->where('removed_user_id', $removedUserId)
        ->exists();

    if ($alreadyRemoved) {
        return response()->json([
            'status' => 'error',
            'message' => 'User already removed from suggestions',
        ], 400);
    }

    // Insert the user into the 'removed_users' table if not already removed
    DB::table('removed_users')->insert([
        'user_id' => $currentUserId,
        'removed_user_id' => $removedUserId,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return response()->json([
        'status' => 'success',
        'message' => 'User removed from suggestions',
    ]);
}



    public function getFollowers($userId, Request $request)
{
    $perPage = $request->input('per_page', 10); // Default to 10 if not specified

    $userdetail = DB::table('user_profiles')->where('users__id', $userId)->first();
    $user = DB::table('users')->where('_id', $userId)->first();

    if (!__isEmpty($userdetail->profile_picture)) {
        $profileImageFolderPath = getPathByKey('profile_photo', ['{_uid}' => $user->_uid]);
        $userImageUrl = getMediaUrl($profileImageFolderPath, $userdetail->profile_picture);
    } 

    $userCoverUrl = '';
    // Check if not empty
    if (!__isEmpty($userdetail->cover_picture)) {
        $coverPath = getPathByKey('cover_photo', ['{_uid}' => $user->_uid]);
        $userCoverUrl = getMediaUrl($coverPath, $userdetail->cover_picture);
    } 

    // Fetch followers with pagination
    $followers = DB::table('follows')
        ->join('users', 'users._id', '=', 'follows.follower_id')
        ->join('user_profiles', 'user_profiles.users__id', '=', 'users._id')
        ->where('follows.followed_id', $userId)
        ->select(
            'users._id as user_id',
            'users._uid as user_uid',
            'users.first_name',
            'users.last_name',
            'user_profiles.nickname',
            'user_profiles.profile_picture',
            DB::raw('COALESCE(user_profiles.nearby, 0) as nearby')
        )
        ->paginate($perPage);

    // Post-process the followers to generate the correct profile picture URL
    $followers->getCollection()->transform(function ($follower) {
        if (!__isEmpty($follower->profile_picture)) {
            $profileImageFolderPath = getPathByKey('profile_photo', ['{_uid}' => $follower->user_uid]);
            $follower->profile_picture = getMediaUrl($profileImageFolderPath, $follower->profile_picture);
        } 
        return $follower;
    });

    return response()->json($followers);
}


    public function getFollowing($userId, Request $request)
{
    $perPage = $request->input('per_page', 10); // Default to 10 if not specified
    $userdetail = DB::table('user_profiles')->where('users__id', $userId)->first();
    $user = DB::table('users')->where('_id', $userId)->first();

    
    if (!__isEmpty($userdetail->profile_picture)) {
        $profileImageFolderPath = getPathByKey('profile_photo', ['{_uid}' => $user->_uid]);
        $userImageUrl = getMediaUrl($profileImageFolderPath, $userdetail->profile_picture);
    } 

    $userCoverUrl = '';
    // Check if not empty
    if (!__isEmpty($userdetail->cover_picture)) {
        $coverPath = getPathByKey('cover_photo', ['{_uid}' => $user->_uid]);
        $userCoverUrl = getMediaUrl($coverPath, $userdetail->cover_picture);
    } 
    // Fetch following with pagination
    $following = DB::table('follows')
        ->join('users', 'users._id', '=', 'follows.followed_id')
        ->join('user_profiles', 'user_profiles.users__id', '=', 'users._id')
        ->where('follows.follower_id', $userId)
        ->select(
          'users._id as user_id',
            'users._uid as user_uid',
            'users.first_name',
            'users.last_name',
            'user_profiles.nickname',
            'user_profiles.profile_picture',
            DB::raw('COALESCE(user_profiles.nearby, 0) as nearby') // Default to 0 if nearby is null
        )
        ->paginate($perPage);
        
        $following->getCollection()->transform(function ($follower) {
            if (!__isEmpty($follower->profile_picture)) {
                $profileImageFolderPath = getPathByKey('profile_photo', ['{_uid}' => $follower->user_uid]);
                $follower->profile_picture = getMediaUrl($profileImageFolderPath, $follower->profile_picture);
            } 
            return $follower;
        });

    return response()->json($following);
}

// public function getFollowRequests()
// {
//     $userId = getUserID();

//     // Fetch follow requests and include the profile picture
//     $followRequests = DB::table('follow_requests')
//         ->join('users', 'users._id', '=', 'follow_requests.follower_id')
//         ->join('user_profiles', 'user_profiles.users__id', '=', 'follow_requests.follower_id')
//         ->select('follow_requests.id', 'users._id as follower_id', 'users.email', 'user_profiles.nickname', 'user_profiles.profile_picture', 'users._uid')
//         ->where('follow_requests.followed_id', $userId)
//         ->get();

//     // Add profile picture URL for each follow request
//     $followRequests->transform(function ($request) {
//         if (!empty($request->profile_picture)) {
//             $profileImageFolderPath = getPathByKey('profile_photo', ['{_uid}' => $request->_uid]);
//             $request->profile_picture = getMediaUrl($profileImageFolderPath, $request->profile_picture);
//         } else {
//             $request->profile_picture = noThumbImageURL();
//         }

//         return $request;
//     });

//     return response()->json($followRequests);
// }

public function getFollowRequests()
{
    $userId = getUserID(); // Current logged-in user ID

    // Fetch follow requests along with the user's profile picture and follow status
    $followRequests = DB::table('follow_requests')
        ->join('users', 'users._id', '=', 'follow_requests.follower_id')
        ->join('user_profiles', 'user_profiles.users__id', '=', 'follow_requests.follower_id')
        ->select(
            'follow_requests.id',
            'users._id as follower_id',
            'users.email',
            'user_profiles.nickname',
            'user_profiles.profile_picture',
            'users._uid'
        )
        ->where('follow_requests.followed_id', $userId) // Follow requests where current user is the one being followed
        ->get();

    // Transform each request and add profile picture URL, follow status, and request sent status
    $followRequests->transform(function ($request) use ($userId) {
        // Add profile picture URL
        if (!empty($request->profile_picture)) {
            $profileImageFolderPath = getPathByKey('profile_photo', ['{_uid}' => $request->_uid]);
            $request->profile_picture = getMediaUrl($profileImageFolderPath, $request->profile_picture);
        } else {
            $request->profile_picture = noThumbImageURL();
        }

        // Check if the current user is already following this user
        $isFollowing = DB::table('follows')
            ->where('follower_id', $userId)  // Current user as the follower
            ->where('followed_id', $request->follower_id)  // The user who sent the follow request
            ->exists();

        // Add the follow status
        $request->followed_by_current_user = $isFollowing;

        // Check if the current user has sent a follow request to this user
        $requestSent = DB::table('follow_requests')
            ->where('follower_id', $userId)  // Current user as the follower
            ->where('followed_id', $request->follower_id)  // The user who sent the request
            ->exists();

        // Add the request sent status
        $request->isRequestSent = $requestSent;
        $request->followed_back_by_uploader = $requestSent;


        return $request;
    });

    return response()->json($followRequests);
}


    // accept follower
    public function acceptFollowRequest($followerId)
    {
        $followedId = getUserID();

        // Check if follow request exists
        $followRequest = DB::table('follow_requests')->where('follower_id', $followerId)->where('followed_id', $followedId)->first();

        if (!$followRequest) {
            return response()->json(['message' => 'No follow request found.'], 400);
        }

        // Create follow relationship
        DB::table('follows')->insert([
            'follower_id' => $followerId,
            'followed_id' => $followedId,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // Delete follow request
        DB::table('follow_requests')
            ->where('id', $followRequest->id)
            ->delete();

        return response()->json(['message' => 'Follow request accepted.']);
    }
    public function rejectFollowRequest($followerId)
    {
        $followedId = getUserID();

        // Check if follow request exists
        $followRequest = DB::table('follow_requests')->where('follower_id', $followerId)->where('followed_id', $followedId)->first();

        if (!$followRequest) {
            return response()->json(['message' => 'No follow request found.'], 400);
        }

        // Delete follow request
        DB::table('follow_requests')
            ->where('id', $followRequest->id)
            ->delete();

        return response()->json(['message' => 'Follow request rejected.']);
    }

    public function unfollow($followedId)
    {
        $followerId = getUserID();

        // Check if already following
        $existingFollow = DB::table('follows')->where('follower_id', $followerId)->where('followed_id', $followedId);

        if (!$existingFollow) {
            return response()->json(['message' => 'You are not following this user.'], 400);
        }

        // Delete follow relationship
        $existingFollow->delete();

        return response()->json(['message' => 'Successfully unfollowed the user.']);
    }
//     public function countFollowers($userId)
// {
//     $currentUserId = getUserID();

//     // Count of followers
//     $followersCount = DB::table('follows')
//         ->where('followed_id', $userId)
//         ->count();

//     // Count of users that the user is following
//     $followingCount = DB::table('follows')
//         ->where('follower_id', $userId)
//         ->count();

//     // Count of videos uploaded by the user
//     $reelCount = DB::table('videos')
//         ->where('added_by', $userId)
//         ->count();

//     // Get user profile details
//     $userDetail = DB::table('user_profiles')
//         ->where('users__id', $userId)
//         ->first();
    
//     $user = DB::table('users')
//         ->where('_id', $userId)
//         ->first();


//         $followerId = getUserID();  // The logged-in user's ID
//         $followedId = $userId;   

//         $isRequestSent = DB::table('follow_requests')
//         ->where('follower_id', $followerId)
//         ->where('followed_id', $followedId)
//         ->exists();

//     // Check if the current user is following the user
//     $isFollowing = DB::table('follows')
//         ->where('follower_id', $currentUserId)
//         ->where('followed_id', $userId)
//         ->exists();

//     // Check if the user follows back the current user
//     $isFollowedBack = DB::table('follows')
//         ->where('follower_id', $userId)
//         ->where('followed_id', $currentUserId)
//         ->exists();

//     // Generate URLs for profile and cover images
//     $userImageUrl = !__isEmpty($userDetail->profile_picture)
//         ? getMediaUrl(getPathByKey('profile_photo', ['{_uid}' => $user->_uid]), $userDetail->profile_picture)
//         : noThumbImageURL();

//     $userCoverUrl = !__isEmpty($userDetail->cover_picture)
//         ? getMediaUrl(getPathByKey('cover_photo', ['{_uid}' => $user->_uid]), $userDetail->cover_picture)
//         : noThumbCoverImageURL();

//     // Return JSON response
//     return response()->json([
//         'followers_count' => $followersCount,
//         'following_count' => $followingCount,
//         'reel_count' => $reelCount,
//         'nickname' => $userDetail ? $userDetail->nickname : null,
//         'privacy_setting' => $user ? $user->privacy_setting : null,
//         'is_following' => $isFollowing,
//         'is_followed_back' => $isFollowedBack, // Indicates if the user follows back the current user
//         'userImageUrl' => $userImageUrl,
//         'userCoverUrl' => $userCoverUrl,
        
//     ]);
// }

public function countFollowers($userId)
{
    $currentUserId = getUserID();

    // Count of followers
    $followersCount = DB::table('follows')
        ->where('followed_id', $userId)
        ->count();

    // Count of users that the user is following
    $followingCount = DB::table('follows')
        ->where('follower_id', $userId)
        ->count();

    // Count of videos uploaded by the user
    $reelCount = DB::table('videos')
        ->where('added_by', $userId)
        ->count();

    // Get user profile details
    $userDetail = DB::table('user_profiles')
        ->where('users__id', $userId)
        ->first();
    
    $user = DB::table('users')
        ->where('_id', $userId)
        ->first();

    // Check if a follow request has been sent by the logged-in user
    $isRequestSent = DB::table('follow_requests')
        ->where('follower_id', $currentUserId)
        ->where('followed_id', $userId)
        ->exists();

    // Check if the current user is following the user
    $isFollowing = DB::table('follows')
        ->where('follower_id', $currentUserId)
        ->where('followed_id', $userId)
        ->exists();

    // Check if the user follows back the current user
    $isFollowedBack = DB::table('follows')
        ->where('follower_id', $userId)
        ->where('followed_id', $currentUserId)
        ->exists();

    // Generate URLs for profile and cover images
    $userImageUrl = !__isEmpty($userDetail->profile_picture)
        ? getMediaUrl(getPathByKey('profile_photo', ['{_uid}' => $user->_uid]), $userDetail->profile_picture)
        : noThumbImageURL();

    $userCoverUrl = !__isEmpty($userDetail->cover_picture)
        ? getMediaUrl(getPathByKey('cover_photo', ['{_uid}' => $user->_uid]), $userDetail->cover_picture)
        : noThumbCoverImageURL();

    // Return JSON response
    return response()->json([
        'followers_count' => $followersCount,
        'following_count' => $followingCount,
        'reel_count' => $reelCount,
        'nickname' => $userDetail ? $userDetail->nickname : null,
        'privacy_setting' => $user ? $user->privacy_setting : null,
        'is_following' => $isFollowing,
        'is_followed_back' => $isFollowedBack, // Indicates if the user follows back the current user
        'is_request_sent' => $isRequestSent, // Add the follow request status
        'userImageUrl' => $userImageUrl,
        'userCoverUrl' => $userCoverUrl,
    ]);
}


public function removecompleteprofile()
{
    // Fetch the user ID (use your getUserID() method)
    $userId = getUserID();

    // Fetch user profile
    $userProfile = DB::table('user_profiles')->where('users__id', $userId)->first();

    // Check if user profile exists
    if (__isEmpty($userProfile)) {
        return response()->json([
            'status' => false,
            'message' => 'User profile not found.',
        ], 404);
    }

    // Toggle the profile completion status
    $newStatus = !$userProfile->is_profile_complete;

    // Update the new status
    DB::table('user_profiles')
        ->where('users__id', $userId)
        ->update(['is_profile_complete' => $newStatus]);

    // Prepare the response message based on the new status
    $message = $newStatus 
        ? 'Profile completion section was removed.' 
        : 'Profile completion section was shown successfully.';

    return response()->json([
        'status' => true,
        'is_profile_complete' => $newStatus,
        'message' => $message,
    ]);
}




    // Function to count the number of users a person is following and list their IDs
    public function countFollowing($userId)
    {
        $following = DB::table('follows')
            ->where('follower_id', $userId)
            ->get(['followed_id']);
        $followingCount = $following->count();
        $followeeIds = $following->pluck('followed_id');

        return response()->json([
            'following_count' => $followingCount,
            'followed_ids' => $followeeIds,
        ]);
    }

    public function commentvideo(Request $request, $video_id,$to_user_id)
    {
        // dd($request->all());
        $userId = getUserID();

        $comment = $request->comment;
        DB::table('comment')->insert([
            'video_id' => $video_id,
            'user_id' => $userId,
            
            'comment' => $comment,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        $stat = DB::table('user_profiles')->where('users__id', $userId)->first();

        DB::table('noti')->insert([
            'user_id' => $userId,
            'to_user_id' => $to_user_id,

            'video_id' => $video_id,
            'type' => 'comment',
            'message' => $stat->nickname . ' Commented your video.',
            'profile' => $stat->profile_picture,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'response' => true,
            'message' => 'Video Comment Added successfully.',
        ]);
    }
    public function replyComment(Request $request, $comment_id, $video_id, $to_user_id)
{
    $userId = getUserID();

    $reply = $request->reply;
    DB::table('comment_reply')->insert([
        'video_id' => $video_id,
        'user_id' => $userId,
        'reply' => $reply,
        'parent_id' => $comment_id,  // Link to the parent comment
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
    ]);

    $stat = DB::table('user_profiles')->where('users__id', $userId)->first();

    DB::table('noti')->insert([
        'user_id' => $userId,
        'to_user_id' => $to_user_id,
        'video_id' => $video_id,
        'type' => 'reply',
        'message' => $stat->nickname . ' replied to your comment.',
        'profile' => $stat->profile_picture,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return response()->json([
        'response' => true,
        'message' => 'Reply added successfully.',
    ]);
}

// comment reaction 
public function reactComment(Request $request, $comment_id)
{
    $userId = getUserID();
    $reactionType = $request->reaction_type;

    // Check if the user has already reacted to this comment
    $existingReaction = DB::table('reactions')
        ->where('comment_id', $comment_id)
        ->where('user_id', $userId)
        ->first();

    if ($existingReaction) {
        // Update the existing reaction
        DB::table('reactions')
            ->where('id', $existingReaction->id)
            ->update([
                'reaction_type' => $reactionType,
                'updated_at' => Carbon::now(),
            ]);
    } else {
        // Insert a new reaction
        DB::table('reactions')->insert([
            'comment_id' => $comment_id,
            'user_id' => $userId,
            'reaction_type' => $reactionType,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }

    return response()->json([
        'response' => true,
        'message' => 'Reaction added successfully.',
    ]);
}

// public function gettaguser(Request $request) {
//     $userId = getUserID();
//     $followIds = DB::table('follows')->where('follower_id', $userId)->pluck('followed_id')->toArray();

//     // Fetch user details and their profiles
//     $userDetails = DB::table('user_profiles')
//                      ->whereIn('users__id', $followIds)
//                      ->get();
                     

//     $users = DB::table('users')
//                ->whereIn('_id', $followIds)
//                ->get()
//                ->keyBy('_id');

//     $response = [];

//     foreach ($userDetails as $userDetail) {
//         $user = $users->get($userDetail->users__id);

//         if (!__isEmpty($userDetail->profile_picture)) {
//             $profileImageFolderPath = getPathByKey('profile_photo', ['{_uid}' => $user->_uid]);
//             $userImageUrl = getMediaUrl($profileImageFolderPath, $userDetail->profile_picture);
//         } else {
//             $userImageUrl = noThumbImageURL();
//         }

//         $response[] = [
//             'id' => $userDetail->users__id,
//             'nickname' => $userDetail->nickname,
//             'profile_picture' => $userImageUrl
//         ];
//     }

//     return response()->json($response);
// }

public function gettaguser(Request $request) {
    $userId = getUserID();

    // Fetch user IDs of users you follow
    $followedIds = DB::table('follows')
                     ->where('follower_id', $userId)
                     ->pluck('followed_id')
                     ->toArray();

    // Fetch user IDs of users who follow you
    $followerIds = DB::table('follows')
                     ->where('followed_id', $userId)
                     ->pluck('follower_id')
                     ->toArray();

    // Combine both followed and follower IDs
    $allRelatedIds = array_unique(array_merge($followedIds, $followerIds));

    // Fetch user details and their profiles for all related users
    $userDetails = DB::table('user_profiles')
                     ->whereIn('users__id', $allRelatedIds)
                     ->get();

    // Fetch user data for all related users
    $users = DB::table('users')
               ->whereIn('_id', $allRelatedIds)
               ->get()
               ->keyBy('_id');

    $response = [];

    foreach ($userDetails as $userDetail) {
        // Fetch the corresponding user record
        $user = $users->get($userDetail->users__id);

        // Check and set profile picture URL
        if (!__isEmpty($userDetail->profile_picture)) {
            $profileImageFolderPath = getPathByKey('profile_photo', ['{_uid}' => $user->_uid]);
            $userImageUrl = getMediaUrl($profileImageFolderPath, $userDetail->profile_picture);
        } else {
            $userImageUrl = noThumbImageURL();
        }

        // Append user details to the response
        $response[] = [
            'id' => $userDetail->users__id,
            'nickname' => $userDetail->nickname,
            'profile_picture' => $userImageUrl
        ];
    }

    return response()->json($response);
}


public function taguser($videoId, Request $request){
    $userId = getUserID();

    $tag=DB::table('taguser')->insert([
     'user_id'=>$userId,
     'tagged_user'=>$request->taguser,
     'video_id'=>$videoId,

    ]);
    return response()->json([
        'response' => true,
        'reactions' => 'User Tagged Successfully',
    ]);
}
public function getCommentReactions($comment_id)
{
    // Fetch the reactions for the given comment_id
    $reactions = DB::table('reactions')
        ->where('comment_id', $comment_id)
        ->get();

    return response()->json([
        'response' => true,
        'reactions' => $reactions,
    ]);
}




    public function private_account(Request $request)
    {
        // Assuming getUserID() is a helper function to get the authenticated user's ID
        $userId = getUserID();

        // Get the current privacy setting
        $currentSetting = DB::table('users')->where('_id', $userId)->value('privacy_setting');

        // Determine the new value for privacy_setting
        $newSetting = $currentSetting === 'private' ? 'public' : 'private';

        // Update the user profile's privacy setting in the users table
        DB::table('users')
            ->where('_id', $userId)
            ->update(['privacy_setting' => $newSetting]);

        return response()->json([
            'response' => 'success',
            'message' => 'Privacy setting updated successfully',
            'new_setting' => $newSetting,
        ]);
    }

    public function preference(Request $request){
    $userId = getUserID();
     DB::table('user_profiles')->where('users__id',$userId)->update([
       'nearby'=>$request->nearby,
       'age'=>$request->age,

     ]);
     return response()->json([
        'response' => true,
        'Message' => 'Setting Updated',
    ]);

    }
    public function getpreference(Request $request){
        $userId = getUserID();
       $preference=  DB::table('user_profiles')->where('users__id',$userId)->select('nearby','age')->get();
         return response()->json([
            'response' => true,
            'data' => $preference,
        ]);
    
        }



    public function intrestedvideo($videoid, Request $request)
    {
        // Validate the video ID
        if (!$videoid || !is_numeric($videoid)) {
            return response()->json(
                [
                    'response' => false,
                    'message' => 'Invalid video ID',
                ],
                400,
            );
        }

        $userId = getUserID();
        DB::table('intrested')->insert([
            'user_id' => $userId,
            'video_id' => $videoid,
        ]);

        return response()->json([
            'response' => true,
            'message' => 'Video added to interested',
        ]);
    }

    public function notintrestedvideo($videoid, Request $request)
    {
        // Validate the video ID
        if (!$videoid || !is_numeric($videoid)) {
            return response()->json(
                [
                    'response' => false,
                    'message' => 'Invalid video ID',
                ],
                400,
            );
        }

        $userId = getUserID();
        DB::table('not_intrested')->insert([
            'user_id' => $userId,
            'video_id' => $videoid,
        ]);

        return response()->json([
            'response' => true,
            'message' => 'Video added to not interested',
        ]);
    }

    public function getintrested(Request $request)
    {
        $userId = getUserID();

        $perPage = $request->input('per_page', 10); // Default to 10 if not specified

        $videos = DB::table('intrested')
            ->where('user_id', $userId)
            ->join('videos', 'intrested.video_id', '=', 'videos.id')
            ->join('users', 'users._id', '=', 'videos.added_by')
            ->join('user_profiles', 'user_profiles.users__id', '=', 'videos.added_by')
            ->leftJoin('comment', 'comment.video_id', '=', 'videos.id')
            ->leftJoin('videolikes', 'videolikes.video_id', '=', 'videos.id')
            ->leftJoin('follows', function ($join) use ($userId) {
                $join->on('follows.followed_id', '=', 'videos.added_by')->where('follows.follower_id', '=', $userId);
            })
            ->where('intrested.user_id', $userId)
                
            ->select('videos.id', 'videos.filename', 'videos.view_count', 'videos.description', 'videos.path', 'videos.added_by', 'users.first_name', 'users.last_name', DB::raw('users._id as user_id'), DB::raw('COUNT(comment.id) as total_comments'), 'videos.thumbnail_path', DB::raw("CONCAT('https://api-kaku.jurysoft.in/public/media-storage/users/', user_profiles.profile_picture) as profile_picture"), DB::raw('COALESCE(user_profiles.nearby, 0) as nearby'), 'user_profiles.nickname', DB::raw('COALESCE(SUM(videolikes.count), 0) as total_likes'), DB::raw("IF(EXISTS(SELECT 1 FROM videolikes WHERE videolikes.video_id = videos.id AND videolikes.user_id = $userId), 1, 0) as liked_by_current_user"), DB::raw('IF(follows.id IS NULL, 0, 1) as followed_by_current_user'))
            ->groupBy('videos.id', 'videos.filename', 'videos.description', 'videos.path', 'videos.added_by', 'users.first_name', 'users.last_name', 'user_profiles.profile_picture', 'user_profiles.nearby', 'user_profiles.nickname')
            ->paginate($perPage);

        $videos->getCollection()->transform(function ($video) {
            $video->liked_by_current_user = (bool) $video->liked_by_current_user;
            $video->followed_by_current_user = (bool) $video->followed_by_current_user;
            return $video;
        });

        return response()->json($videos);
    }

//     public function get_user_details($userid, Request $request)
//     {
//         // Fetch user details with the specified user ID
//         $user = DB::table('users')
//             ->join('user_profiles', 'user_profiles.users__id', '=', 'users._id')
//             ->where('users._id', $userid) // Specify the table name for the `_id` column
//             ->select('users.*', 'user_profiles.*') // Select columns from both tables
//             ->first();
//             // dd($user->dob);
//             $userAge = isset($randomUser->dob) ? Carbon::parse($user->dob)->age : null;
//             $gender = isset($randomUser->gender) ? configItem('user_settings.gender', $user->gender) : null;
            
//             $extrastat = DB::table('user_specifications')
//             ->where('users__id', $user->_id)
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
//         $followerId = getUserID();
//             $followedId = $user->_id;
//             $isFollowing = DB::table('follows')
//                 ->where('follower_id', $followerId)
//                 ->where('followed_id', $followedId)
//                 ->exists();

//     $bestvideo=DB::table('best_video')->select('path')->where('added_by',$user->_id)->get();
//    $privacysetting=DB::table('users')->select('privacy_setting')->where('_id',$user->_id)->get();
//    $userImageUrl = '';
//    // Check is not empty
//    if (!__isEmpty($user->profile_picture)) {
//        $profileImageFolderPath = getPathByKey('profile_photo', ['{_uid}' => $user->_uid]);
//        $userImageUrl = getMediaUrl($profileImageFolderPath, $user->profile_picture);
//    } else {
//        $userImageUrl = noThumbImageURL();
//    }
//    $userCoverUrl = '';
//    // Check is not empty
//    if (!__isEmpty($user->cover_picture)) {
//        $coverPath = getPathByKey('cover_photo', ['{_uid}' => $user->_uid]);
//        $userCoverUrl = getMediaUrl($coverPath, $user->cover_picture);
//    } else {
//        $userCoverUrl = noThumbCoverImageURL();
//    }
//             $randomUserData = [
//                 '_id' => $user->_id,
//                 '_uid' => $user->_uid,
//                 'username' => $user->username,
//                 // 'userFullName' => $user->userFullName,
//                 'stats' => $user->status,
//                 'userImageUrl' => $userImageUrl,
//                 'userCoverUrl' => $userCoverUrl,
//                 'gender' => $gender,
//                 'dob' => $user->dob,
//                 'nearby' => $user->nearby,

//                 'userAge' => $userAge,
//                 // 'countryName' => $user->countryName,
//                 // 'userOnlineStatus' => $this->getUserOnlineStatus($user->userAuthorityUpdatedAt),
//                 'isPremiumUser' => isPremiumUser($user->_id),
//                 'userspec' => $userSpecifications,
//                 'best_video' => $bestvideo,
//                 'privacysetting' => $privacysetting,


//                 'isFollowing' => $isFollowing ? true : false,
//             ];

//         return response()->json([
//             'response' => true,
//             'data' => $randomUserData,

//         ]);
//     }

public function get_user_details($userid, Request $request)
{
    // Fetch user details with the specified user ID
    $user = DB::table('users')
        ->join('user_profiles', 'user_profiles.users__id', '=', 'users._id')
        ->where('users._id', $userid) // Specify the table name for the `_id` column
        ->select('users.*', 'user_profiles.*') // Select columns from both tables
        ->first();

    // Calculate user age and gender
    $userAge = isset($user->dob) ? Carbon::parse($user->dob)->age : null;
    $gender = isset($user->gender) ? configItem('user_settings.gender', $user->gender) : null;

    // Fetch extra specifications for the user
    $extrastat = DB::table('user_specifications')
        ->where('users__id', $user->_id)
        ->get();

    $userSpecifications = [];

    foreach ($extrastat as $spec) {
        if (!empty($spec->specification_value)) {
            try {
                $value = json_decode($spec->specification_value, true); // Decode JSON as associative array
                if ($value === null && json_last_error() !== JSON_ERROR_NONE) {
                    throw new \Exception('Error decoding JSON: ' . json_last_error_msg());
                }
                $userSpecifications[$spec->specification_key] = $value;
            } catch (\Exception $e) {
                \Log::error('Error decoding specification_value for key ' . $spec->specification_key . ': ' . $e->getMessage());
                // Handle the error as needed
            }
        }
    }

    // Get follower and followed IDs
    $followerId = getUserID();  // The logged-in user's ID
    $followedId = $user->_id;   // The user being viewed

    // Check if the logged-in user is already following this user
    $isFollowing = DB::table('follows')
        ->where('follower_id', $followerId)
        ->where('followed_id', $followedId)
        ->exists();

    // Check if a follow request has been sent by the logged-in user
    $isRequestSent = DB::table('follow_requests')
        ->where('follower_id', $followerId)
        ->where('followed_id', $followedId)
        ->exists();

    // Fetch the best video for the user
    $bestvideo = DB::table('best_video')->select('path')->where('added_by', $user->_id)->get();
   
    // Fetch the privacy setting for the user
    $privacysetting = DB::table('users')->select('privacy_setting')->where('_id', $user->_id)->get();

    // Set up user image URLs
    $userImageUrl = '';
    if (!__isEmpty($user->profile_picture)) {
        $profileImageFolderPath = getPathByKey('profile_photo', ['{_uid}' => $user->_uid]);
        $userImageUrl = getMediaUrl($profileImageFolderPath, $user->profile_picture);
    } else {
        $userImageUrl = noThumbImageURL();
    }

    // Set up cover image URLs
    $userCoverUrl = '';
    if (!__isEmpty($user->cover_picture)) {
        $coverPath = getPathByKey('cover_photo', ['{_uid}' => $user->_uid]);
        $userCoverUrl = getMediaUrl($coverPath, $user->cover_picture);
    } else {
        $userCoverUrl = noThumbCoverImageURL();
    }

    // Prepare the randomUserData array
    $randomUserData = [
        '_id' => $user->_id,
        '_uid' => $user->_uid,
        'username' => $user->username,
        'stats' => $user->status,
        'userImageUrl' => $userImageUrl,
        'userCoverUrl' => $userCoverUrl,
        'gender' => $gender,
        'dob' => $user->dob,
        'nearby' => $user->nearby,
        'userAge' => $userAge,
        'isPremiumUser' => isPremiumUser($user->_id),
        'userspec' => $userSpecifications,
        'best_video' => $bestvideo,
        'privacysetting' => $privacysetting,
        'isFollowing' => $isFollowing ? true : false,
        'isRequestSent' => $isRequestSent ? true : false,  // Add isRequestSent parameter
        'isvisiblperset' =>  $user->visibility,
        'profilecompletebar'=>$user->is_profile_complete

       
    ];

    // Return the response with the user details
    return response()->json([
        'response' => true,
        'data' => $randomUserData,
    ]);
}


    /**
     * Process upload cover image.
     *
     * @param object CommonUnsecuredPostRequest $request
     * @return json object
     *---------------------------------------------------------------- */
    public function uploadCoverImage(CommonUnsecuredPostRequest $request)
    {
        $processReaction = $this->userSettingEngine->processUploadCoverImage($request->all(), 'cover_image');

        return $this->processResponse($processReaction, [], [], true);
    }

    /**
     * Upload multiple photos
     *
     * @param object CommonUnsecuredPostRequest $request
     * @return json object
     *---------------------------------------------------------------- */
    public function uploadPhotos(CommonUnsecuredPostRequest $request)
    {
        $processReaction = $this->userSettingEngine->processUploadPhotos($request->all());

        return $this->processResponse($processReaction, [], [], true);
    }

    /**
     * prepare user photos.
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function getUserPhotos()
    {
        $processReaction = $this->userSettingEngine->prepareUserPhotosSettings();

        return $this->processResponse($processReaction, [], [], true);
    }

    /**
     * Get UserSetting Data.
     *
     * @param  string  $pageType
     * @return json object
     *---------------------------------------------------------------- */
    public function updateStoreUserSetting(Request $request)
    {
        $processReaction = $this->userSettingEngine->processStoreUserProfileSetting($request->all());

        return $this->processResponse($processReaction, [], [], true);
    }
    public function profileupdatedata(Request $request)
    {
        $userid = getUserID();
        $data = DB::table('user_specifications')->where('users__id', $userid)->get();

        return response()->json([
            'data' => $data,
        ]);
    }

    /**
     * Process store user basic settings.
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function processLocationData(CommonUnsecuredPostRequest $request)
    {
        $processReaction = $this->userSettingEngine->processStoreLocationData($request->all());

        return $this->responseAction($this->processResponse($processReaction, [], [], true));
    }

    /**
     * Process store user basic settings.
     *
     * @return json object
     *---------------------------------------------------------------- */
    // public function updateUserBasicSetting(UserBasicSettingAddRequest $request)
    public function updateUserBasicSetting(Request $request)
    {
        $processReaction = $this->userSettingEngine->processStoreUserBasicSettings($request->all());

        return $this->processResponse($processReaction, [], [], true);
    }

    /**
     * Process profile Update Wizard.
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function profileUpdateWizard(UserProfileWizardRequest $request)
    {
        $processReaction = $this->userSettingEngine->processStoreProfileWizard($request->all());

        return $this->processResponse($processReaction, [], [], true);
    }

    /**
     * Upload multiple photos
     *
     * @param object CommonUnsecuredPostRequest $request
     * @return json object
     *---------------------------------------------------------------- */
    public function deleteUserPhotos($photoUid)
    {
        $processReaction = $this->userSettingEngine->processDeleteUserPhotos($photoUid);

        return $this->processResponse($processReaction, [], [], true);
    }

    /**
     * Search Cities
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function searchStaticCities(CommonUnsecuredPostRequest $request)
    {
        $processReaction = $this->userSettingEngine->searchStaticCities($request->get('search_query'));

        return $this->responseAction($this->processResponse($processReaction, [], [], true));
    }

    /**
     * Process store user city
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function processStoreCity(CommonUnsecuredPostRequest $request)
    {
        $processReaction = $this->userSettingEngine->processStoreCity($request->get('selected_city_id'));

        return $this->responseAction($this->processResponse($processReaction, [], [], true));
    }
    public function getfeedback()
    {
        $getfeedback = DB::table('feedback')->orderBy('id', 'Desc')->get()->toArray();

        $structuredData = [];

        foreach ($getfeedback as $item) {
            $structuredData[] = [
                'id' => $item->id,
                'name' => $item->name,
            ];
        }

        $response = [
            'getfeedback' => $structuredData,
        ];

        return $response;
    }
    public function getreportvideo()
    {
        $report_video_get = DB::table('report_video_get')->orderBy('id', 'Desc')->get()->toArray();

        $structuredData = [];

        foreach ($report_video_get as $item) {
            $structuredData[] = [
                'id' => $item->id,
                'name' => $item->name,
            ];
        }

        $response = [
            'report_video_get' => $report_video_get,
        ];

        return $response;
    }
    public function rateApp(Request $request)
    {
        // Validate the request
        $request->validate([
            'rating' => 'required|integer|min:1|max:5', // Ratings should be between 1 and 5
        ]);

        $currentUserId = getUserID(); // Get the ID of the current logged-in user

        // Check if the user has already rated the app
        $existingRating = DB::table('app_ratings')->where('user_id', $currentUserId)->first();

        if ($existingRating) {
            // Update the existing rating
            DB::table('app_ratings')
                ->where('user_id', $currentUserId)
                ->update([
                    'rating' => $request->rating,
                ]);
        } else {
            // Create a new rating
            DB::table('app_ratings')->insert([
                'user_id' => $currentUserId,
                'rating' => $request->rating,
            ]);
        }

        return response()->json(
            [
                'reponse' => true,
                'message' => 'Rating submitted successfully',
            ],
            200,
        );
    }
    public function postfeedback(Request $request)
    {
        // dd($request->all());
        $currentUserId = getUserID(); 
        DB::table('postfeedback')->insert([
            'impressed' => $request->impressed,
            'comment' => $request->comment,
            'user_id'=>$currentUserId
        ]);

        return response()->json([
            'response' => true,
            'message' => 'Feedback Added Successfully',
        ]);
    }

    public function visibility(Request $request)
    {
        $currentUserId = getUserID();
        DB::table('users')
            ->where('_id', $currentUserId)
            ->update([
                'visibility' => $request->visibility,
            ]);
        return response()->json([
            'response' => true,
            'message' => 'Visibility Setting Changed',
        ]);
    }
    public function getvisibility()
    {
        $visibility = DB::table('visibility')->orderBy('id', 'Desc')->get()->toArray();

        $structuredData = [];

        foreach ($visibility as $item) {
            $structuredData[] = [
                'id' => $item->id,
                'name' => $item->visibility,
            ];
        }

        $response = [
            'visibility' => $visibility,
        ];

        return $response;
    }
    public function contact_support(){
        
        $contact = DB::table('contact_support') ->get();
        $userSpecifications = [];

        foreach ($contact as $spec) {
            if (!empty($spec->contact_value)) {
                try {
                    $value = $spec->contact_value; // Decode JSON as associative array
                    if ($value === null && json_last_error() !== JSON_ERROR_NONE) {
                        throw new \Exception('Error decoding JSON: ' . json_last_error_msg());
                    }
                    $userSpecifications[$spec->contact_key] = $value;
                } catch (\Exception $e) {
                    \Log::error('Error decoding specification_value for key ' . $spec->contact_key . ': ' . $e->getMessage());
                    // Handle the error as needed
                }
            }
        }
        return response()->json([
            'response' => true,
            'Data' => $userSpecifications,
        ]);
    }

    // deactivate account 
    public function deactivateaccount(Request $request){
        if (isAdmin()) {
           return response()->json([
            'Message'=>'Admin Account Did not Deactivate'
        ]);
        }

        $user = getUserID();

        // Check if user exists
        if (__isEmpty($user)) {
            return response()->json([
            'Message'=>'user does not exist'
        ]);
        }

         $detail=DB::table('users')->where('_id',$user)->first();
         
        if (!Hash::check($request->password, $detail->password)) {
            return response()->json([
            'Message'=>'current Password is Incorrect'
        ]);
        }
        $detail=DB::table('users')->where('_id',$user)->update([
            'status'=>2
        ]);

      

    return response()->json([
        'response'=>true,
        'Message' => 'User account was deactivated'
    ]);

        
    }

    public function changeusername(Request $request){
        $user = getUserID();
        $detail=DB::table('user_profiles')->where('users__id',$user)->update([
            'nickname'=>$request->nickname
        ]);
        return response()->json([
        'response'=>true,
        'Message' => 'Username Changed Successfully'
    ]);

    }

    public function recentsearch($userId) {
        $user = getUserID();
    
        // Check if the record already exists
        $exists = DB::table('recent_searches')
            ->where('user_id', $user)
            ->where('searched_user_id', $userId)
            ->exists();
    
        if ($exists) {
            return response()->json([
                'response' => false,
                'Message' => 'Record already exists'
            ]);
        }
    
        // If not exists, insert the new record
        DB::table('recent_searches')->insert([
            'user_id' => $user,
            'searched_user_id' => $userId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    
        return response()->json([
            'response' => true,
            'Message' => 'Success'
        ]);
    }
    

    public function fetchRecentSearch()
    {
        $userId = getUserID();
    
        // Fetch recent searches where the current user's ID is in the user_id column
        $recentSearches = DB::table('recent_searches')
            ->where('recent_searches.user_id', $userId)
            ->join('users', 'users._id', '=', 'recent_searches.searched_user_id')
            ->join('user_profiles', 'user_profiles.users__id', '=', 'users._id')
            ->select(
                'users._id as user_id',
                'users._uid as uid',
                'users.username',
                'user_profiles.nickname',
                'user_profiles.profile_picture',
                'recent_searches.created_at'
            )
            ->get();
    
        // Process the results to add the profile image URL
        $recentSearches->transform(function ($search) {
            if (!empty($search->profile_picture)) {
                $profileImageFolderPath = getPathByKey('profile_photo', ['{_uid}' => $search->uid]);
                $search->profile_picture_url = getMediaUrl($profileImageFolderPath, $search->profile_picture);
            } else {
                $search->profile_picture_url = noThumbImageURL();
            }
    
            return $search;
        });
    
        // Return the result as a JSON response
        return response()->json([
            'response' => true,
            'Data' => $recentSearches,
        ]);
    }

    public function videoviews($videoId, Request $request)
{
    $userId = getUserID();

    // Check if the user has already viewed the video
    $viewRecord = DB::table('videos_views')
        ->where('user_id', $userId)
        ->where('video_id', $videoId)
        ->first();

    if (!$viewRecord) {
        // User has not viewed this video yet, insert a new record
        DB::table('videos_views')->insert([
            'user_id' => $userId,
            'video_id' => $videoId,
            'viewed_at' => now(),
        ]);

        // Increment the view count for the video
        DB::table('videos')
            ->where('id', $videoId)
            ->increment('view_count');
    }

    return response()->json(['message' => 'View recorded']);
}


}
