<?php
 
namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use App\Models\Log;

class ProfileController extends Controller
{
    /**
     * Update the user's profile.
     *
     * @param  Request  $request
     * @return Response
     */
    public function update(Request $request)
    {
        // $request->user() returns an instance of the authenticated user...
    }

    public function getLoggedInUser(Request $request) {
        return json_encode(['userData' => Auth::user()]);
    }
    
    public function getAllUser(Request $request) {
        //get all orders
        $list = DB::table('users')
            ->select('id', 'name', 'nickname', 'role', 'email')
            ->get();

        if (count($list) > 0) {
            $jsonFile = json_encode($list);
    
            return $jsonFile;
        
        } else {
            return false;
        }
    }

    public function saveUserData(Request $request) {
        // validate

        $newData = $request->newData;

        $name     = $newData['name'];
        $nickname = $newData['nickname'];
        $email    = $newData['email'];
        $newPw    = $newData['password'];

        $hashed = Hash::make($newPw, [
            'rounds' => 12,
        ]);

        $userID = Auth::user()->id;
        if (isset($newData['id'])) {
            $userID = isset($newData['id']);
        }

        DB::table('users')
            ->where('id', $userID)
            ->update([
                'name'     => $name,
                'nickname' => $nickname,
                'email'    => $email,
                'password' => $hashed
            ]);
    
        Log::create("user", "User data updated: " . $name);
    }

    public function addNewUser(Request $request) {
        $newUser = $request->newUser;

        $name     = $newUser['name'];
        $nickname = $newUser['nickname'];
        $email    = $newUser['email'];
        $newPw    = $newUser['password'];

        $hashed = Hash::make($newPw, [
            'rounds' => 12,
        ]);

        DB::table('users')
            ->insert([
                'name'     => $name,
                'nickname' => $nickname,
                'email'    => $email,
                'password' => $hashed
            ]);
    
        Log::create("user", "New user added: " . $name);
    }

    public function deleteUser(Request $request) {
        $id = $request->userID;

        DB::table('users')
            ->where('id', $id)
            ->delete();
    
        Log::create("user", "User deleted: " . $id);
    }
}