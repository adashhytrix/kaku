<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::get('/base-data', function() {
//         __dd('Test');
//     }
// );

/*
|--------------------------------------------------------------------------
| Lw-Dating (Mobile App) Api Routesf
|--------------------------------------------------------------------------
*/
Route::group([
    'namespace' => '\App\Yantrana\Components',
], function () {
    /*
    User Components Public Section Related Routes
    ----------------------------------------------------------------------- */
    Route::group(['middleware' => 'guest'], function () {
        Route::group([
            'namespace' => 'User\ApiControllers',
            'prefix' => 'user',
        ], function () {
           
            // login process
            Route::post('/login-process', [
                'as' => 'api.user.login.process',
                'uses' => 'ApiUserController@loginProcess',
                
            ]);

            // logout
            Route::post('/logout', [
                'as' => 'api.user.logout',
                'uses' => 'ApiUserController@logout',
            ]);
            Route::get('/terms', [
                'as' => 'api.user.terms',
                'uses' => 'ApiUserController@terms',
            ]);
            Route::get('/privacy', [
                'as' => 'api.user.privacy',
                'uses' => 'ApiUserController@privacy',
            ]);
            Route::get('/about', [
                'as' => 'api.user.about',
                'uses' => 'ApiUserController@about',
            ]);

            // User Registration prepare data
            Route::get('/prepare-sign-up', [
                'as' => 'api.user.sign_up.prepare',
                'uses' => 'ApiUserController@prepareSignUp',
            ]);
            Route::post('/sign-up-otp', [
                'as' => 'api.user.verifysignup',
                'uses' => 'ApiUserController@verifysignup',
            ]);
            Route::get('/Discover', [
                'as' => 'api.user.sign_up.Discover',
                'uses' => 'ApiUserController@discover',
            ]);
            Route::get('/faith', [
                'as' => 'api.user.sign_up.faith',
                'uses' => 'ApiUserController@faith',
            ]);
            Route::get('/wonderlust', [
                'as' => 'api.user.sign_up.wonderlust',
                'uses' => 'ApiUserController@wonderlust',
            ]);
            Route::get('/relationship-goal', [
                'as' => 'api.user.sign_up.relationship',
                'uses' => 'ApiUserController@relationship',
            ]);
            Route::get('/travel', [
                'as' => 'api.user.sign_up.travel',
                'uses' => 'ApiUserController@travel',
            ]);
            Route::get('/religion', [
                'as' => 'api.user.sign_up.religion',
                'uses' => 'ApiUserController@religion',
            ]);
            Route::get('/lifestyle', [
                'as' => 'api.user.sign_up.lifestyle',
                'uses' => 'ApiUserController@lifestyle',
            ]);
            Route::get('/musics', [
                'as' => 'api.user.sign_up.musics',
                'uses' => 'ApiUserController@musics',
            ]);
            Route::get('/languages', [
                'as' => 'api.user.sign_up.languages',
                'uses' => 'ApiUserController@languages',
            ]);
            Route::get('/intrest', [
                'as' => 'api.user.sign_up.intrest',
                'uses' => 'ApiUserController@intrest',
            ]);

            // User Registration
            Route::post('/process-sign-up', [
                'as' => 'api.user.sign_up.process',
                'uses' => 'ApiUserController@processSignUp',
            ]);

            // send activation mail
            Route::post('/process-resend-activation-mail', [
                'as' => 'api.user.resend.activation_mail',
                'uses' => 'ApiUserController@resendActivationMail',
            ]);

            // send activation mail
            Route::post('/request-new-password', [
                'as' => 'api.user.request.new_password',
                'uses' => 'ApiUserController@requestNewPassword',
            ]);

            // forgot password resend otp
            Route::post('/{userEmail}/forgot-password-resend-otp', [
                'as' => 'api.user.write.forgot_passowrd.resend_otp_process',
                'uses' => 'ApiUserController@forgotPasswordResendOtp',
            ]);

            // send activation mail
            Route::post('/process-reset-password', [
                'as' => 'api.user.reset.password',
                'uses' => 'ApiUserController@resetPassword',
            ]);

            Route::post('/send-otp', [
                'as' => 'api.user.send_otp',
                'uses' => 'ApiUserController@sendotp',
            ]);
            // verify otp
            Route::post('/{type}/verify-otp', [
                'as' => 'api.user.verify_otp',
                'uses' => 'ApiUserController@verifyOtp',
            ]);

        });

        /*
            User Social Access Components Public Section Related Routes
            ----------------------------------------------------------------------- */
            Route::group([
                'namespace' => 'User\Controllers',
                'prefix' => 'user/social-login',
            ], function () {
                // social user login callback
                Route::post('/response/{provider}', [
                    'as' => 'api.social.user.login.callback',
                    'uses' => 'SocialAccessController@handleApiProviderCallback',
                ]);
            });
    });

    /*
    After Authentication Accessible Routes
    -------------------------------------------------------------------------- */
   
    Route::group([
        'middleware' => 'api.authenticate',
    ], function () {
        //call base data request
        Route::get('/base-data', [
            'as' => 'base_data',
            'uses' => '__Igniter@baseData',
        ]);

        /*
        Messenger Component Related Routes
        ----------------------------------------------------------------------- */
        Route::group([
            'namespace' => 'Messenger\ApiControllers',
            'prefix' => 'messenger'
        ], function () {
            // Fetch user conversation list
            Route::get('/get-user-conversations', [
                'as' => 'api.user.read.user_conversations_list',
                'uses' => 'ApiMessengerController@getUserConversationList',
            ]);

            Route::get('/chat-list/search', [
                'as' => 'api.user.read.searchChatList',
                'uses' => 'ApiMessengerController@searchChatList',
            ]);

            // Get individual conversation
            Route::get('/{specificUserId}/single-conversation', [
                'as' => 'api.user.read.user_single_conversation',
                'uses' => 'ApiMessengerController@getUserSingleConversation',
            ]);

            // Get user messages
            Route::get('/{userId}/get-user-messages', [
                'as' => 'api.user.read.user_messages',
                'uses' => 'ApiMessengerController@getUserMessages',
            ]);

            // Get Stickers
            Route::get('/fetch-stickers', [
                'as' => 'api.user.read.get_stickers',
                'uses' => 'ApiMessengerController@getStickers',
            ]);

            // Buy Sticker
            Route::post('/buy-sticker', [
                'as' => 'api.user.write.buy_stickers',
                'uses' => 'ApiMessengerController@buySticker',
            ]);

            // Send message
            Route::post('/{userId}/send-message', [
                'as' => 'api.user.write.send_message',
                'uses' => 'ApiMessengerController@sendMessage',
            ]);

            // Accept / Decline Message request
            Route::post('/{userId}/process-accept-decline-message-request', [
                'as' => 'api.user.write.accept_decline_message_request',
                'uses' => 'ApiMessengerController@acceptDeclineMessageRequest',
            ]);
            // Delete Single Chat
            Route::post('/{chatId}/{userId}/delete-message', [
                'as' => 'api.user.write.delete_message',
                'uses' => 'ApiMessengerController@deleteMessage',
            ]);

            // Delete all chat conversation
            Route::post('/{userId}/delete-all-messages', [
                'as' => 'api.user.write.delete_all_messages',
                'uses' => 'ApiMessengerController@deleteAllMessages',
            ]);
            //  current work 4/26/2024
            // Get Call Token Data
            Route::post('/{userUId}/{type}/call-initialize', [
                'as' => 'api.user.write.caller.call_initialize',
                'uses' => 'ApiMessengerController@callerCallInitialize',
            ]);

            // Get Call Token Data
            Route::post('/join-call', [
                'as' => 'api.user.write.receiver.join_call',
                'uses' => 'ApiMessengerController@receiverJoinCallRequest',
            ]);

            // Caller Call Reject
            Route::get('/{receiverUserUid}/caller-reject-call', [
                'as' => 'api.user.write.caller.reject_call',
                'uses' => 'ApiMessengerController@callerRejectCall',
            ]);
            // Receiver Call Reject
            Route::get('/{callerUserUid}/receiver-reject-call', [
                'as' => 'api.user.write.receiver.reject_call',
                'uses' => 'ApiMessengerController@receiverRejectCall',
            ]);
            // Caller call errors
            Route::get('/{receiverUserUid}/caller-errors', [
                'as' => 'api.user.write.caller.error',
                'uses' => 'ApiMessengerController@callerCallErrors',
            ]);

            // Receiver call errors
            Route::get('/{callerUserUid}/receiver-errors', [
                'as' => 'api.user.write.receiver.error',
                'uses' => 'ApiMessengerController@receiverCallErrors',
            ]);

            // Receiver call accept
            Route::post('/{receiverUserUid}/receiver-call-accept', [
                'as' => 'api.user.write.receiver.call_accept',
                'uses' => 'ApiMessengerController@receiverCallAccept',
            ]);
            // Receiver call errors
            Route::get('/{callerUserUid}/receiver-busy-call', [
                'as' => 'api.user.write.receiver.call_busy',
                'uses' => 'ApiMessengerController@receiverCallBusy',
            ]);
        });

        /*
        Home Component Related Routes
        ----------------------------------------------------------------------- */
        Route::group([
            'namespace' => 'Home\ApiControllers',
        ], function () {
            // Home page for logged in user
            Route::get('/home', [
                'as' => 'api.user.read.home_page_data',
                'uses' => 'ApiHomeController@getEncounterData',
            ]);

            Route::get('/encounter-data', [
                'as' => 'api.user.read.encounter_data',
                'uses' => 'ApiHomeController@getEncounterData',
            ]);

            // Home page for logged in user
            Route::get('/random-user', [
                'as' => 'api.user.read.random_users',
                'uses' => 'ApiHomeController@getRandomUsers',
            ]);
        });

        /*
        Filter Components Public Section Related Routes
        ----------------------------------------------------------------------- */
        Route::group([
            'namespace' => 'Filter\ApiControllers',
        ], function () {
            // Show Find Matches View
            Route::get('/find-matches-data', [
                'as' => 'api.user.find_matches.read.support_data',
                'uses' => 'ApiFilterController@getFindMatchSupportData',
            ]);

            // Show Find Matches View
            Route::post('/find-matches', [
                'as' => 'api.user.read.find_matches',
                'uses' => 'ApiFilterController@getFindMatches',
            ]);
        });

        /*
        User Setting related routes
        ----------------------------------------------------------------------- */
        Route::group([
            'namespace' => 'UserSetting\ApiControllers',
        ], function () {
            // View settings
            Route::get('/{pageType}/get-setting-data', [
                'as' => 'api.user.read.setting',
                'uses' => 'ApiUserSettingController@getUserSettingData',
            ]);

            // Process Configuration Data
            Route::post('/{pageType}/user-setting-store', [
                'as' => 'api.user.write.setting',
                'uses' => 'ApiUserSettingController@processStoreUserSetting',
            ]);

            // upload User Profile Image
            Route::post('/upload-profile-image', [
                'as' => 'api.user.upload_profile_image',
                'uses' => 'ApiUserSettingController@uploadProfileImage',
            ]);

            Route::get('/getFollowRequests', [
                'as' => 'api.user.getFollowRequests',
                'uses' => 'ApiUserSettingController@getFollowRequests',
            ]);

            Route::post('/cancel-follow-request/{followedId}', [
                'as' => 'api.user.cancelFollowRequest',
                'uses' => 'ApiUserSettingController@cancelFollowRequest',
            ]);


            Route::post('/remove-complete-profile', [
                'as' => 'api.user.removecompleteprofile',
                'uses' => 'ApiUserSettingController@removecompleteprofile',
            ]);
            
          
            Route::post('/follow/{followeeId}', [
                'as' => 'api.user.follow',
                'uses' => 'ApiUserSettingController@follow',
            ]);
            // accept follow request 
            Route::post('/acceptFollowRequest/{followerId}', [
                'as' => 'api.user.acceptFollowRequest',
                'uses' => 'ApiUserSettingController@acceptFollowRequest',
            ]);
            Route::post('/rejectFollowRequest/{followerId}', [
                'as' => 'api.user.rejectFollowRequest',
                'uses' => 'ApiUserSettingController@rejectFollowRequest',
            ]);

            // private account 
            Route::post('/private_account', [
                'as' => 'api.user.private_account',
                'uses' => 'ApiUserSettingController@private_account',
            ]);
            Route::post('/preference', [
                'as' => 'api.user.preference',
                'uses' => 'ApiUserSettingController@preference',
            ]);
            Route::get('/getpreference', [
                'as' => 'api.user.getpreference',
                'uses' => 'ApiUserSettingController@getpreference',
            ]);
            Route::get('/gettaguser', [
                'as' => 'api.user.gettaguser',
                'uses' => 'ApiUserSettingController@gettaguser',
            ]);
            Route::get('/tag-videos/{userId}', [
                'as' => 'api.user.tagvideos',
                'uses' => 'ApiUserSettingController@tagvideos',
            ]);
            Route::post('/taguser/{videoId}', [
                'as' => 'api.user.taguser',
                'uses' => 'ApiUserSettingController@taguser',
            ]);
            // get suggeested user 
            Route::get('/suggested-users', [
                'as' => 'api.user.suggested_users',
                'uses' => 'ApiUserSettingController@getSuggestedUsers',
            ]);
            Route::get('/suggested-users-by-intrest', [
                'as' => 'api.user.suggested_users_by_intrest',
                'uses' => 'ApiUserSettingController@getSuggestedUsersbyintrest',
            ]);
            Route::post('/delete-suggested-users/{id}', [
                'as' => 'api.user.delete_suggested_users',
                'uses' => 'ApiUserSettingController@deleteSuggestedUsers',
            ]);
            Route::post('/delete-suggested-users-by-intrest/{id}', [
                'as' => 'api.user.delete_suggested_users_by_intrest',
                'uses' => 'ApiUserSettingController@deleteSuggestedUsersbyintrest',
            ]);

            Route::post('/get_user_details/{userid}', [
                'as' => 'api.user.get_user_details',
                'uses' => 'ApiUserSettingController@get_user_details',
            ]);




            Route::post('/unfollow/{followeeId}', [
                'as' => 'api.user.unfollow',
                'uses' => 'ApiUserSettingController@unfollow',
            ]);
            Route::post('/views/{videoId}', [
                'as' => 'api.user.videoviews',
                'uses' => 'ApiUserSettingController@videoviews',
            ]);
            Route::post('/countFollowersandfollowing/{userid}', [
                'as' => 'api.user.countFollowers',
                'uses' => 'ApiUserSettingController@countFollowers',
            ]);
            Route::post('/countFollowing/{userid}', [
                'as' => 'api.user.countfollowing',
                'uses' => 'ApiUserSettingController@countFollowing',
            ]);

            Route::post('/upload-video', [
                'as' => 'api.user.upload_video',
                'uses' => 'ApiUserSettingController@uploadvideo',
            ]);

            // intrested api 
            Route::post('/intrested-video/{videoid}', [
                'as' => 'api.user.intrested_video',
                'uses' => 'ApiUserSettingController@intrestedvideo',
            ]);
            Route::post('/not-intrested-video/{videoid}', [
                'as' => 'api.user.not_intrested_video',
                'uses' => 'ApiUserSettingController@notintrestedvideo',
            ]);
             // get intrested and non intrested video 
             Route::get('/get-intrested', [
                'as' => 'api.user.getintrested',
                'uses' => 'ApiUserSettingController@getintrested',
            ]);
            Route::post('/best-video', [
                'as' => 'api.user.best_video',
                'uses' => 'ApiUserSettingController@bestvideo',
            ]);

            Route::post('/onboarding-image', [
                'as' => 'api.user.onboarding_image',
                'uses' => 'ApiUserSettingController@onboardingimage',
            ]);
            Route::get('/get-onboarding-image', [
                'as' => 'api.user.get_onboarding_image',
                'uses' => 'ApiUserSettingController@getonboardingimage',
            ]);

            Route::post('/delete-video/{videoid}', [
                'as' => 'api.user.delete_video',
                'uses' => 'ApiUserSettingController@deletevideo',
            ]);
            Route::post('/delete-bestvideo/{videoid}', [
                'as' => 'api.user.delete_bestvideo',
                'uses' => 'ApiUserSettingController@deletebestvideo',
            ]);
            Route::post('/like-video/{videoid}/{to_user_id}', [
                'as' => 'api.user.like_video',
                'uses' => 'ApiUserSettingController@likevideo',
            ]);
            Route::post('/comment-video/{video_id}/{to_user_id}', [
                'as' => 'api.user.comment_video',
                'uses' => 'ApiUserSettingController@commentvideo',
            ]);
            Route::post('/reply-comment/{comment_id}/{video_id}/{to_user_id}', [
                'as' => 'api.user.reply_comment',
                'uses' => 'ApiUserSettingController@replyComment',
            ]);
            // fetch reply 
            Route::get('/fetch-reply/{comment_id}', [
                'as' => 'api.user.reply_comment',
                'uses' => 'ApiUserSettingController@fetch_replies',
            ]);
           
            // comment reaction 
            Route::post('/react-comment/{comment_id}', [
                'as' => 'api.user.react_comment',
                'uses' => 'ApiUserSettingController@reactComment',
            ]);
            // fetch reaction 
            Route::get('/comment-reactions/{comment_id}', [
                'as' => 'api.user.comment_reactions',
                'uses' => 'ApiUserSettingController@getCommentReactions',
            ]);
            
            
            Route::post('/save-video/{videoid}', [
                'as' => 'api.user.save_video',
                'uses' => 'ApiUserSettingController@savevideo',
            ]);

            // report video 
            Route::post('/report-video/{videoid}', [
                'as' => 'api.user.report_video',
                'uses' => 'ApiUserSettingController@reportvideo',
            ]);

            // report user 
            Route::post('/report-user/{reporteduser}', [
                'as' => 'api.user.reportuser',
                'uses' => 'ApiUserSettingController@reportuser',
            ]);

            // get report user data 
            Route::get('/get-report-user', [
                'as' => 'api.user.getreportuser',
                'uses' => 'ApiUserSettingController@getreportuser',
            ]);

            // contact support according to figma 
            Route::get('/contact-support', [
                'as' => 'api.user.contact_support',
                'uses' => 'ApiUserSettingController@contact_support',
            ]);

            // deactivate account
            Route::post('/deactivate-account', [
                'as' => 'api.user.deactivate_account',
                'uses' => 'ApiUserSettingController@deactivateaccount',
            ]); 
            // change username 
            Route::post('/change-username', [
                'as' => 'api.user.change_username',
                'uses' => 'ApiUserSettingController@changeusername',
            ]); 
            // visibolity of user 
            Route::post('/visibility', [
                'as' => 'api.user.visibility',
                'uses' => 'ApiUserSettingController@visibility',
            ]);
            // get visibility setting 
            Route::get('/get-visibility', [
                'as' => 'api.user.get_visibility',
                'uses' => 'ApiUserSettingController@getvisibility',
            ]);

            Route::get('/get-report-video', [
                'as' => 'api.user.get_report_video',
                'uses' => 'ApiUserSettingController@getreportvideo',
            ]);




            Route::get('/get-save-video', [
                'as' => 'api.user.getsavevideo',
                'uses' => 'ApiUserSettingController@getsavevideo',
            ]);
            // rate the app 
            Route::post('/rate-app', [
                'as' => 'api.user.rate_app',
                'uses' => 'ApiUserSettingController@rateApp',
            ]);

            Route::post('/recent-search/{userId}', [
                'as' => 'api.user.recentsearch',
                'uses' => 'ApiUserSettingController@recentsearch',
            ]);

            Route::get('/fetch-recent-search', [
                'as' => 'api.user.fetchrecentsearch',
                'uses' => 'ApiUserSettingController@fetchrecentsearch',
            ]);



            Route::get('/fetch-video', [
                'as' => 'api.user.fetch_video',
                'uses' => 'ApiUserSettingController@getAllVideos',
            ]);
            Route::get('/fetch-user-video/{userid}', [
                'as' => 'api.user.fetchuservideo',
                'uses' => 'ApiUserSettingController@fetchuservideo',
            ]);
            Route::get('/fetch-user-video-by-id/{videoid}', [
                'as' => 'api.user.fetchvideobyid',
                'uses' => 'ApiUserSettingController@fetchvideoid',
            ]);
            Route::get('/fetch-comment/{videoid}', [
                'as' => 'api.user.fetch_comment',
                'uses' => 'ApiUserSettingController@fetch_comment',
            ]);
            // get notification
           
            Route::get('/getnotification', [
                'as' => 'api.user.getNotifications',
                'uses' => 'ApiUserSettingController@getNotifications',
            ]);
        


            Route::get('/fetch-bestvideo/{userid}', [
                'as' => 'api.user.fetch_bestvideo',
                'uses' => 'ApiUserSettingController@fetchbestvideo',
            ]);

            // upload User Cover Image
            Route::post('/upload-cover-image', [
                'as' => 'api.user.upload_cover_image',
                'uses' => 'ApiUserSettingController@uploadCoverImage',
            ]);

            // Home page for logged in user
            Route::post('/upload-photos', [
                'as' => 'api.user.upload_photos',
                'uses' => 'ApiUserSettingController@uploadPhotos',
            ]);

            // block user 
                // Home page for logged in user
                Route::post('/block-user/{blocked_user}', [
                    'as' => 'api.user.block_user',
                    'uses' => 'ApiUserSettingController@blockuser',
                ]);
                // get block user data 
                Route::get('/get-block-user', [
                    'as' => 'api.user.get_block_user',
                    'uses' => 'ApiUserSettingController@getblockuser',
                ]);
                // get following 
                Route::get('/getFollowing/{user_Id}', [
                    'as' => 'api.user.getFollowing',
                    'uses' => 'ApiUserSettingController@getFollowing',
                ]);
                
                Route::get('/getFollowers/{user_Id}', [
                    'as' => 'api.user.getFollowers',
                    'uses' => 'ApiUserSettingController@getFollowers',
                ]);


            // Home page for logged in user
            Route::get('/uploaded-photos', [
                'as' => 'api.user.read.photos',
                'uses' => 'ApiUserSettingController@getUserPhotos',
            ]);

            // feedback get api 
            Route::get('/get-feedback', [
                'as' => 'api.user.getfeedback',
                'uses' => 'ApiUserSettingController@getfeedback',
            ]);
            Route::post('/post-feedback', [
                'as' => 'api.user.postfeedback',
                'uses' => 'ApiUserSettingController@postfeedback',
            ]);

            // Process location / maps data
            Route::post('/process-location-data', [
                'as' => 'api.user.write.location_data',
                'uses' => 'ApiUserSettingController@processLocationData',
            ]);

            // Process basic settings
            Route::post('/update-basic-settings', [
                'as' => 'api.user.write.basic_setting',
                'uses' => 'ApiUserSettingController@updateUserBasicSetting',
            ]);

            // Process User Profile
            // this is the main update 
            Route::post('/update-profile-settings', [
                'as' => 'api.user.write.profile_setting',
                'uses' => 'ApiUserSettingController@updateStoreUserSetting',
            ]);
            Route::get('/profile-update-data', [
                'as' => 'api.user.write.profile-update-data',
                'uses' => 'ApiUserSettingController@profileupdatedata',
            ]);

            // Process basic settings
            Route::post('/process-update-profile-wizard', [
                'as' => 'api.user.write.update_profile_wizard',
                'uses' => 'ApiUserSettingController@profileUpdateWizard',
            ]);

            // Process location / maps data
            Route::post('/search-static-cities', [
                'as' => 'api.user.read.search_static_cities',
                'uses' => 'ApiUserSettingController@searchStaticCities',
            ]);

            // Process location / maps data
            Route::post('/store-city', [
                'as' => 'api.user.write.store_city',
                'uses' => 'ApiUserSettingController@processStoreCity',
            ]);

            // delete photo
            Route::post('/{photoUid}/delete-photos', [
                'as' => 'api.user.upload_photos.write.delete',
                'uses' => 'ApiUserSettingController@deleteUserPhotos',
            ]);
        });

        // User Encounter related routes
        Route::group([
            'namespace' => 'User\ApiControllers',
            'prefix' => 'encounters',
        ], function () {
            
            // User Like Dislike route
            Route::post('/{toUserUid}/{like}/user-encounter-like-dislike', [
                'as' => 'api.user.write.encounter.like_dislike',
                'uses' => 'ApiUserEncounterController@userEncounterLikeDislike',
            ]);

            // Skip Encounter User
            Route::post('/{toUserUid}/skip-encounter-user', [
                'as' => 'api.user.write.encounter.skip_user',
                'uses' => 'ApiUserEncounterController@skipEncounterUser',
            ]);
        });

        // User Encounter related routes
        Route::group([
            'namespace' => 'User\ApiControllers',
            'prefix' => 'profile',
        ], function () {
            //User Profile
            Route::get('/read-profile-details', [
                'as' => 'api.user.read.wizard_profile_data',
                'uses' => 'ApiUserController@readWizardProfileData',
            ]);

            //User Profile
            Route::get('/{username}/read-profile-details', [
                'as' => 'api.user.read.profile',
                'uses' => 'ApiUserController@readProfile',
            ]);

            //prepare user profile data
            Route::get('/prepare-profile-update', [
                'as' => 'api.user.read.profile_update_data',
                'uses' => 'ApiUserController@prepareProfileUpdate',
            ]);
          
            Route::get('/remove-complete-profile', [
                'as' => 'api.user.read.removecompleteprofile',
                'uses' => 'ApiUserController@removecompleteprofile',
            ]);
            Route::post('/process-profile-update', [
                'as' => 'api.user.read.profile_update_dataa',
                'uses' => 'ApiUserController@processProfileUpdate',
            ]);

            // get relationship 
            Route::get('/relationship-goal', [
                'as' => 'api.user.read.relationshipgoal',
                'uses' => 'ApiUserController@relationshipgoal',
            ]);

            // Update email
            Route::post('/update-email-process', [
                'as' => 'api.user.write.change_email',
                'uses' => 'ApiUserController@changeEmail',
            ]);

            Route::post('/change-password-process', [
                'as' => 'api.user.write.change_password',
                'uses' => 'ApiUserController@processChangePassword',
            ]);
        });

        /*
        Credit wallet User Components Public Section Related Routes
        ----------------------------------------------------------------------- */
        Route::group([
            'namespace' => 'User\ApiControllers',
            'prefix' => 'credit-wallet',
        ], function () {
            // Public User Wallet transaction list
            Route::get('/transaction-list', [
                'as' => 'api.user.credit_wallet.read.wallet_transaction_list',
                'uses' => 'ApiCreditWalletController@getTransactionList',
            ]);

            Route::get('/wallet-info-data', [
                'as' => 'api.user.credit_wallet.read.wallet_info',
                'uses' => 'ApiCreditWalletController@getCreditWalletInfo',
            ]);

            // User Credit-wallet View
            Route::get('/credit-wallet-data', [
                'as' => 'api.user.credit_wallet.read.wallet_data',
                'uses' => 'ApiCreditWalletController@getCreditWalletData',
            ]);

            // paypal transaction complete
            Route::post('/{packageUid}/paypal-checkout', [
                'as' => 'api.user.credit_wallet.write.paypal_transaction_complete',
                'uses' => 'ApiCreditWalletController@processApiPaypalCheckout',
            ]);

            // razorpay checkout
            Route::post('/razorpay-checkout', [
                'as' => 'api.user.credit_wallet.write.razorpay.checkout',
                'uses' => 'ApiCreditWalletController@razorpayCheckout',
            ]);

            //payment process
            Route::post('/payment-process', [
                'as' => 'api.user.credit_wallet.write.payment_process',
                'uses' => 'ApiCreditWalletController@paymentProcess',
            ]);

            // create stripe payment intent
            Route::post('/create-stripe-payment-intent', [
                'as' => 'api.user.credit_wallet.stripe.write.create_payment_intent',
                'uses' => 'ApiCreditWalletController@createStripePaymentIntent',
            ]);

            // retrieve stripe payment intent
            Route::post('/retrieve-stripe-payment-intent', [
                'as' => 'api.user.credit_wallet.stripe.write.retrieve_payment_intent',
                'uses' => 'ApiCreditWalletController@retrieveStripePaymentIntent',
            ]);

            // retrieve stripe payment intent
            Route::post('/store-stripe-payment', [
                'as' => 'api.user.credit_wallet.write.stripe.store_payment',
                'uses' => 'ApiCreditWalletController@storeStripePayment',
            ]);

            Route::post('/process-in-app-purchase/google', [
                'as' => 'api.user.credit_wallet.write.in_app_process_google',
                'uses' => 'ApiCreditWalletController@googleInAppPurchase',
            ]);
        });

        /*
        Manage Premium Plan User Components Public Section Related Routes
        ----------------------------------------------------------------------- */
        Route::group([
            'namespace' => 'User\ApiControllers',
            'prefix' => 'premium-plan',
        ], function () {
            // Api User Premium Plan Data
            Route::get('/premium-plan-data', [
                'as' => 'api.user.read.premium_plan_data',
                'uses' => 'ApiPremiumPlanController@getPremiumPlanData',
            ]);

            // buy premium plans
            Route::post('/buy-plans', [
                'as' => 'api.user.premium_plan.write.buy_premium_plan',
                'uses' => 'ApiPremiumPlanController@buyPremiumPlans',
            ]);

            // User Premium Plan Buy Successfully
            // Route::get('/success', [
            //     'as' => 'user.premium_plan.read.success_view',
            //     'uses' => 'PremiumPlanController@getPremiumPlanSuccessView',
            // ]);
        });

        /*
        User Component Related Routes
        ----------------------------------------------------------------------- */
        Route::group([
            'namespace' => 'User\ApiControllers',
        ], function () {

            Route::post('/get-user-auth-info', [
                'as' => 'api.user.read.auth_info',
                'uses' => 'ApiUserController@fetchUserAuthInfo',
            ]);

            // Get who liked me users
            Route::get('/who-liked-me', [
                'as' => 'api.user.read.who_liked_me_users',
                'uses' => 'ApiUserController@getWhoLikedMeData',
            ]);

            // Get mutual likes users
            Route::get('/mutual-likes', [
                'as' => 'api.user.read.mutual_like_users',
                'uses' => 'ApiUserController@getMutualLikeData',
            ]);

            // Get User My like view
            Route::get('/my-likes', [
                'as' => 'api.user.read.my_liked_users',
                'uses' => 'ApiUserController@getMyLikeData',
            ]);

            // Get User My Dislike view
            Route::get('/disliked', [
                'as' => 'api.user.read.my_disliked_users',
                'uses' => 'ApiUserController@getMyDislikedData',
            ]);

            // Get profile visitors users
            Route::get('/visitors', [
                'as' => 'api.user.read.profile_visitors_users',
                'uses' => 'ApiUserController@getProfileVisitorData',
            ]);

            // block user list
            Route::get('/blocked-users-list', [
                'as' => 'api.user.read.block_user_list',
                'uses' => 'ApiUserController@blockUserList',
            ]);

            // post un-block user
            Route::post('/{userUid}/unblock-user-data', [
                'as' => 'api.user.write.unblock_user',
                'uses' => 'ApiUserController@processUnblockUser',
            ]);

            // block user list
            Route::get('/get-booster-info', [
                'as' => 'api.user.read.booster_data',
                'uses' => 'ApiUserController@getBoosterInfo',
            ]);

            // Permanent delete account
            Route::post('/delete-account', [
                'as' => 'api.user.write.delete_account',
                'uses' => 'ApiUserController@deleteAccount',
            ]);



            // post un-block user
            Route::post('/boost-profile', [
                'as' => 'api.user.write.boost_profile',
                'uses' => 'ApiUserController@processBoostProfile',
            ]);

            // post User send gift
            Route::post('/block-user', [
                'as' => 'api.user.write.block_user',
                'uses' => 'ApiUserController@blockUser',
            ]);

            // post report user
            Route::post('/{reportUserUid}/report-user', [
                'as' => 'api.user.write.report_user',
                'uses' => 'ApiUserController@reportUser',
            ]);

            // post User send gift
            Route::post('/{sendUserUId}/send-gift', [
                'as' => 'api.user.write.send_gift',
                'uses' => 'ApiUserController@userSendGift',
            ]);

            // User Like Dislike route
            Route::post('/{toUserUid}/{like}/user-like-dislike', [
                'as' => 'api.user.write.like_dislike',
                'uses' => 'ApiUserController@userLikeDislike',
            ]);

            // featured user list
            Route::get('/get-featured-user-data', [
                'as' => 'api.user.featured_user.read.support_data',
                'uses' => 'ApiUserController@getFeaturedUsers',
            ]);

            // process contact form
            Route::post('/contact', [
                'as' => 'api.user.contact.process',
                'uses' => 'ApiUserController@contactProcess',
            ]);
        });

        // User Notification related routes
        Route::group([
            'namespace' => 'Notification\ApiControllers',
            'prefix' => 'notifications',
        ], function () {
            // Get mutual likes users
            Route::get('/notification-list', [
                'as' => 'api.user.notification.read.list',
                'uses' => 'ApiNotificationController@getNotificationList',
            ]);

            // Get mutual likes users
            Route::get('/notification-data', [
                'as' => 'api.user.notification.read.data',
                'uses' => 'ApiNotificationController@getNotificationData',
            ]);

            // Post Read All Notification
            Route::post('/read-all-notification', [
                'as' => 'api.user.notification.write.read_all_notification',
                'uses' => 'ApiNotificationController@readAllNotification',
            ]);
        });
    });
});
