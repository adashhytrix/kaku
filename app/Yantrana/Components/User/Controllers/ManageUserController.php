<?php

/**
 * ManageUserController.php - Controller file
 *
 * This file is part of the User component.
 *-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\User\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Yantrana\Base\BaseController;
use App\Yantrana\Components\User\ManageUserEngine;
use App\Yantrana\Components\User\Requests\UserAddRequest;
use App\Yantrana\Components\User\Requests\GenerateFakeUsers;
use App\Yantrana\Components\User\Requests\UserUpdateRequest;
use App\Yantrana\Components\User\Requests\PaypalTransactionRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ManageUserController extends BaseController
{
    /**
     * @var  ManageUserEngine - ManageUser Engine
     */
    protected $manageUserEngine;

    /**
     * Constructor
     *
     * @param  ManageUserEngine  $manageUserEngine - ManageUser Engine
     * @return  void
     *-----------------------------------------------------------------------*/
    public function __construct(ManageUserEngine $manageUserEngine)
    {
        $this->manageUserEngine = $manageUserEngine;
    }

    /**
     * Manage User List.
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function userList($status)
    {
        return $this->loadManageView('user.manage.list');
    }
    public function intrest()
    {
        // dd('okk');
        $intrest=DB::table('intrest')->orderBy('id','Desc')->get();
        return $this->loadManageView('user.intrest.list',compact('intrest'));
    }

    public function intrestadd(){
        return $this->loadManageView('user.intrest.add');

    }

    public function intreststore(Request $request)
{
    // Validate input for both interests and CSV file
    // $request->validate([
    //     'interests' => 'nullable|string',
    //     'csv_file' => 'nullable|file|mimes:csv,txt|max:2048' // Max 2MB CSV file
    // ]);

    $interests = [];

    // Process interests from textarea
    if ($request->filled('interests')) {
        $textInterests = array_map('trim', explode(',', $request->interests));
        $interests = array_merge($interests, $textInterests);
    }

    // Process interests from CSV file if uploaded
    if ($request->hasFile('csv_file')) {
        $file = $request->file('csv_file');
        $fileHandle = fopen($file->getRealPath(), 'r');

        while (($line = fgetcsv($fileHandle)) !== false) {
            // Assuming each line contains one interest
            if (isset($line[0])) {
                $interests[] = trim($line[0]);
            }
        }

        fclose($fileHandle);
    }

    // Remove any duplicate interests
    $interests = array_unique($interests);

    // Prepare the data for bulk insert
    $insertData = array_map(function ($interest) {
        return ['name' => $interest];
    }, $interests);

    // Insert the interests into the database
    if (!empty($insertData)) {
        DB::table('intrest')->insert($insertData);
    }

    // Redirect to the interest list
    return redirect()->route('manage.intrest.list')->with('success', __tr('Interests added successfully'));
}


    public function intrestdelete($id){
        DB::table('intrest')->where('id',$id)->delete();
        return redirect()->back();

    }
    public function languageslist()
    {
        // dd('okk');
        $langauges=DB::table('languages')->orderBy('id','Desc')->get();
        return $this->loadManageView('user.language.list',compact('langauges'));

    }

    public function languagesadd(){
        return $this->loadManageView('user.language.add');

    }

    public function languagestore(Request $request){
        DB::table('languages')->insert([
            'name'=>$request->name,
        ]);
        return redirect()->route('manage.languages.list');

    }
    public function languagedelete($id,Request $request){
        DB::table('languages')->where('id',$id)->delete();
        return redirect()->back();

    }

    public function musiclist(){
        $music=DB::table('musics')->orderBy('id','Desc')->get();

        return $this->loadManageView('user.music.list',compact('music'));

    }
    public function musicadd(){
        return $this->loadManageView('user.music.add');

    }
    public function musicstore(Request $request){
        DB::table('musics')->insert([
            'name'=>$request->name,
        ]);
        return redirect()->route('manage.music.list');

    }
    public function musicdelete($id,Request $request){
        DB::table('musics')->where('id',$id)->delete();
        return redirect()->back();

    }
    public function lifestylelist(){
        $lifestyle=DB::table('lifestyle')->orderBy('id','Desc')->get();

        return $this->loadManageView('user.lifestyle.list',compact('lifestyle'));

    }
    public function lifestyleadd(){
        return $this->loadManageView('user.lifestyle.add');


    }
    public function lifestylstore(Request $request){
        DB::table('lifestyle')->insert([
            'name'=>$request->name,
        ]);
        return redirect()->route('manage.lifestyle.lifestylelist');

    }
    public function lifestyledelete($id,Request $request){
        DB::table('lifestyle')->where('id',$id)->delete();
        return redirect()->back();

    }

    public function religionlist(){
        $Religions=DB::table('Religions')->orderBy('id','Desc')->get();

        return $this->loadManageView('user.religion.list',compact('Religions'));

    }
    public function religionadd(){
        return $this->loadManageView('user.religion.add');


    }

    public function religionstore(Request $request){
        DB::table('Religions')->insert([
            'name'=>$request->name,
        ]);
        return redirect()->route('manage.religion.religionlist');

    }
    public function religiondelete($id,Request $request){
        DB::table('Religions')->where('id',$id)->delete();
        return redirect()->back();

    }
    public function travellist(){
        $travel=DB::table('travel')->orderBy('id','Desc')->get();

        return $this->loadManageView('user.travel.list',compact('travel'));

    }

    public function feedback_list(){
        $postfeedback = DB::table('postfeedback')
        ->join('users', 'users._id', '=', 'postfeedback.user_id') // Assuming 'users.id' is the primary key
        ->leftJoin('user_profiles', 'user_profiles.users__id', '=', 'postfeedback.user_id') // Left join for user profiles in case some users don't have profiles
        ->select('postfeedback.*', 'user_profiles.nickname as user_name','users.email', 'user_profiles.profile_picture') // Adjust the columns as per your need
        ->orderBy('postfeedback.id', 'desc')
        ->get();
       

        return $this->loadManageView('user.feedback.list',compact('postfeedback'));
    }
    public function traveladd(){
        return $this->loadManageView('user.travel.add');


    }
    public function travelstore(Request $request){
        DB::table('travel')->insert([
            'name'=>$request->name,
        ]);
        return redirect()->route('manage.travel.travellist');

    }
    public function traveldelete($id,Request $request){
        DB::table('travel')->where('id',$id)->delete();
        return redirect()->back();

    }
    public function discoverlist(){
        $discover=DB::table('discover')->orderBy('id','Desc')->get();

        return $this->loadManageView('user.discover.list',compact('discover'));

    }
    public function discoveradd(){
        return $this->loadManageView('user.discover.add');


    }
    public function discoverstore(Request $request){
        DB::table('discover')->insert([
            'name'=>$request->name,
        ]);
        return redirect()->route('manage.discover.discoverlist');

    }
    public function discoverdelete($id,Request $request){
        DB::table('discover')->where('id',$id)->delete();
        return redirect()->back();

    }
    public function relationlist(){
        $relationshipgoal=DB::table('relationshipgoal')->orderBy('id','Desc')->get();

        return $this->loadManageView('user.relation.list',compact('relationshipgoal'));

    }
    public function relationadd(){
        return $this->loadManageView('user.relation.add');


    }
    public function relationstore(Request $request){
        DB::table('relationshipgoal')->insert([
            'name'=>$request->name,
            'description'=>$request->description,

        ]);
        return redirect()->route('manage.relation.relationlist');

    }
    public function relationdelete($id,Request $request){
        DB::table('relationshipgoal')->where('id',$id)->delete();
        return redirect()->back();

    }
    

    

    /**
     * Manage User List.
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function userDataTableList($status)
    {
        return $this->manageUserEngine->prepareUsersDataTableList($status);
    }

    /**
     * Load User Photos view.
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function userPhotosView()
    {
        return $this->loadManageView('user.photos.list');
    }

    /**
     * Manage User Photos List.
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function userPhotosList()
    {
        return $this->manageUserEngine->userPhotosDataTableList();
    }

    /**
     * Add new user view.
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function addNewUserView()
    {
        return $this->loadManageView('user.manage.add');
    }

    /**
     * Add new user view.
     *
     * @param object UserAddRequest $request
     * @return json object
     *---------------------------------------------------------------- */
    public function processAddNewUser(UserAddRequest $request)
    {
        $processReaction = $this->manageUserEngine->processAddUser($request->all());

        return $this->responseAction(
            $this->processResponse($processReaction, [], [], true),
            $this->redirectTo('manage.user.view_list', ['status' => 1])
        );
    }

    /**
     * Edit User.
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function editUser($userUid)
    {
        $processReaction = $this->manageUserEngine->prepareUserEditData($userUid);

        return $this->loadManageView('user.manage.edit', $processReaction['data']);
    }

    /**
     * Edit User.
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function processUpdateUser($userUid, UserUpdateRequest $request)
    {
        $processReaction = $this->manageUserEngine->processUserUpdate($userUid, $request->all());

        if ($processReaction['reaction_code'] == 1) {
            return $this->responseAction(
                $this->processResponse($processReaction, [], [], true),
                $this->redirectTo('manage.user.view_list', ['status' => 1])
            );
        }

        return $this->processResponse($processReaction, [], [], true);
    }

    /**
     * Process Soft delete user.
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function processUserSoftDelete($userUid)
    {
        $processReaction = $this->manageUserEngine->processSoftDeleteUser($userUid);

        return $this->responseAction(
            $this->processResponse($processReaction, [], [], true)
        );
    }

    /**
     * Process delete photo of user.
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function processUserPhotoDelete($userUid, $type, $profileOrPhotoUid)
    {
        $processReaction = $this->manageUserEngine->processUserPhotoDelete($userUid, $type, $profileOrPhotoUid);

        return $this->responseAction(
            $this->processResponse($processReaction, [], [], true)
        );
    }

    /**
     * Process Permanent delete user.
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function processUserPermanentDelete($userUid)
    {
        $processReaction = $this->manageUserEngine->processPermanentDeleteUser($userUid);

        return $this->responseAction(
            $this->processResponse($processReaction, [], [], true)
        );
    }

    /**
     * Process Restore user.
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function processRestoreUser($userUid)
    {
        $processReaction = $this->manageUserEngine->processUserRestore($userUid);

        return $this->responseAction(
            $this->processResponse($processReaction, [], [], true)
        );
    }

    /**
     * Process Block user.
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function processUserBlock($userUid)
    {
        $processReaction = $this->manageUserEngine->processBlockUser($userUid);

        return $this->responseAction(
            $this->processResponse($processReaction, [], [], true)
        );
    }

    /**
     * Process Unblock user.
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function processUserUnblock($userUid)
    {
        $processReaction = $this->manageUserEngine->processUnblockUser($userUid);

        return $this->responseAction(
            $this->processResponse($processReaction, [], [], true)
        );
    }

    /**
     * Get User Details.
     *
     * @param  string  $userUid
     * @return json object
     *---------------------------------------------------------------- */
    public function getUserDetails($userUid)
    {
        $processReaction = $this->manageUserEngine->prepareUserDetails($userUid);

        return $this->loadManageView('user.manage.details', $processReaction['data']);
    }

    /**
     * fetch Fake User Generator Options
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function fetchFakeUserOptions()
    {
        $processReaction = $this->manageUserEngine->prepareFakeUserOptions();

        return $this->loadManageView('fake-data-generator.fake-users', $processReaction['data']);
    }

    /**
     * Generate fake users.
     *
     * @param object GenerateFakeUsers $request
     * @return json object
     *---------------------------------------------------------------- */
    public function generateFakeUser(GenerateFakeUsers $request)
    {
        $processReaction = $this->manageUserEngine->processGenerateFakeUser($request->all());

        return $this->responseAction($this->processResponse($processReaction, [], [], true));
    }

    /**
     * Process Verify user profile.
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function processVerifyUserProfile($userUid)
    {
        $processReaction = $this->manageUserEngine->processVerifyUserProfile($userUid);

        return $this->responseAction($this->processResponse($processReaction, [], [], true));
    }

    /**
     * Show user Transaction List.
     *
     *-----------------------------------------------------------------------*/
    public function manageUserTransactionList($userUid)
    {
        return $this->manageUserEngine->getUserTransactionList($userUid);
    }

    /**
     * Add User Allocate Credits
     *
     * @param   object PaypalTransactionRequest  $request
     * @return  json object
     */
    public function addUserAllocateCredits(PaypalTransactionRequest $request)
    {
        $request->validate(['allocate_credits' => 'required']);
        return $this->manageUserEngine->allocatedCreditsForUser($request->all());
    }

    /**
     * Process To Login Admin as a User
     *
     * @param   [type]  $userId  [$userId description]
     *
     * @return  [type]           [return description]
     */
    public function processToLoginAdminAsFakeUser($userId)
    {
        if (Auth::loginUsingId($userId)) {
            return redirect()->route('user.login');
        }
    }

//     public function userdownload()
// {
//     // Fetch all users' data
//     $users = DB::table('users')->get();

//     // Define the headers for the CSV file
//     $headers = [
//         "Content-type" => "text/csv",
//         "Content-Disposition" => "attachment; filename=users_data.csv",
//         "Pragma" => "no-cache",
//         "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
//         "Expires" => "0"
//     ];

//     // Define the callback function to write the CSV
//     $callback = function() use ($users) {
//         $file = fopen('php://output', 'w');

//         // Insert the first row with column names
//         fputcsv($file, ['ID', 'Name', 'Email', 'Created At']);

//         // Insert each user's data
//         foreach ($users as $user) {
           
//             fputcsv($file, [
//                 $user->_id,
//                 $user->first_name,
//                 $user->email,
//                 $user->created_at,
//             ]);
//         }

//         fclose($file);
//     };

//     // Return the response with headers
//     return response()->stream($callback, 200, $headers);
// }


public function userdownload()
{
    // Define the CSV file name
    $fileName = 'users_data.csv';

    // Fetch all users' data
    $tasks = DB::table('users')
    ->join('user_profiles','user_profiles.users__id','=','users._id')
    ->get();

    

    // Define headers for the CSV file
    $headers = [
        "Content-type"        => "text/csv",
        "Content-Disposition" => "attachment; filename=\"$fileName\"",
        "Pragma"              => "no-cache",
        "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
        "Expires"             => "0"
    ];

    // Define the columns for the CSV file
    $columns = ['ID', 'First Name', 'Email','username','dob'];

    // Callback function to stream the CSV data
    $callback = function() use ($tasks, $columns) {
        // Open file pointer connected to the output stream
        $file = fopen('php://output', 'w');
        
        // Insert the first row with column names
        fputcsv($file, $columns);

        // Insert each user's data
        foreach ($tasks as $task) {
            $row = [
                'ID'         => $task->_id, // Assuming the correct column is `id`
                'First Name' => $task->first_name,
                'Email'      => $task->email,
                'username'      => $task->nickname,
                // 'gender'      => $task->gender,
                'dob'      => $task->dob,



                // 'Created At' => $task->created_at,
            ];

            // Write the row to the CSV file
            fputcsv($file, $row);
        }

        // Close the file pointer
        fclose($file);
    };

    // Return the CSV file as a streamed response
    return response()->stream($callback, 200, $headers);
}
}
