<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\Admin\UserProfileResource;

class UsersController extends Controller
{
    // Register a new user
    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,NULL,id,deleted_at,NULL',
            'password' => 'required|confirmed',
            'phone_number' => 'required',
            'address' => 'required',         
        ]);

        try{
            $user = User::create($validatedData);

            return response()->json([
                'message' => 'User created successfully.',
                'data' => $user
            ], 201);
        }catch(\Exception $e){
            return response()->json([
                'message' => 'User creation failed.',
                'error' => $e->getMessage()
            ], 500);
        }
        
    }

    // Login a user
    public function login(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        $token = Auth::attempt($validatedData);
        if (!$token) {
            return response()->json([
                'message' => 'Incorrect login credentials.'
            ], 401);
        }

        return response()->json([
            'message' => 'User login successful.',
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 60
        ], 200);
    }

    // Get the authenticated user's profile
    public function profile()
    {
        $user = Auth::user();
        return response()->json([
            'message' => 'User profile fetched successfully.',
            'data' => new UserProfileResource($user),
        ], 200);
    }

    // Edit the authenticated user's profile
    public function editProfile(Request $request)
    {
        $user = Auth::user();
        $validatedData = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id . ',id,deleted_at,NULL',
            'phone_number' => 'required',
            'address' => 'required',
        ]);

        try{
            $user->update([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'address' => $validatedData['address'],
                'phone_number' => $validatedData['phone_number']
            ]);

            return response()->json([
                'message' => 'User profile updated successfully.'
            ], 200);
        }catch(\Exception $e){
            return response()->json([
                'message' => 'User profile update failed.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Logout the authenticated user
    public function logout()
    {
        try{
            Auth::logout();
            return response()->json([
                'message' => 'User logout successfully'
            ], 200);
        }
        catch(\Exception $e){
            return response()->json([
                'message' => 'User logout failed.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    //Refresh the authenticated user's token
    public function refresh()
    {
        return response()->json([
            'access_token' => Auth::refresh(),
            'token_type' => 'bearer',  
            'expires_in' => auth()->factory()->getTTL() * 60
        ], 200);
    }

    // Change the authenticated user's password
    public function changePassword(Request $request)
    {
        $validatedData = $request->validate([
            'old_password' => 'required',
            'new_password' => 'required'
        ]);

        $user = Auth::user();

        $passwordCheck = Hash::check($validatedData['old_password'], $user->password);
        if (!$passwordCheck) {
            return response()->json([
                'message' => 'Incorrect Password!'
            ], 401);
        }
        try{
            $user->password = Hash::make($validatedData['new_password']);
            $user->save();
            return response()->json([
                'message' => 'Password changed successfully.'
            ], 200);
        }catch(\Exception $e){
            return response()->json([
                'message' => 'Password change failed.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Get all users
    public function getAllUsers(){
        return response()->json([
            'message' => 'Admins details fetched successfully.',
            'data' => UserProfileResource::collection(User::all()),
        ], 200);
    }

    // Get a specific user's details
    public function getOtherUserDetails($id){
        $selectedUser = User::find($id);
        if(!$selectedUser){
            return response()->json([
                'message' => 'User not found.'
            ], 404);
        }
        return response()->json([
            'message' => 'User details fetched successfully.',
            'data' => new UserProfileResource($selectedUser),
        ], 200);
    }

    // Edit a specific user's details
    public function editOtherUserDetails(Request $request, $id){
        $selectedUser = User::find($id);
        if(!$selectedUser){
            return response()->json([
                'message' => 'User not found.'
            ], 404);
        }
        $validatedData = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $selectedUser->id . ',id,deleted_at,NULL',
            'phone_number' => 'required',
            'address' => 'required',
        ]);

        try{
            $selectedUser->update([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'address' => $validatedData['address'],
                'phone_number' => $validatedData['phone_number'],
            ]);
            return response()->json([
                'message' => 'User updated successfully.'
            ], 200);
        }catch(\Exception $e){
            return response()->json([
                'message' => 'User update failed.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    // Edit a specific user's password
    public function editOtherUserPassword(Request $request, $id){
        $selectedUser = User::find($id);
        if(!$selectedUser){
            return response()->json([
                'message' => 'User not found.'
            ], 404);
        }
        $validatedData = $request->validate([
            'password' => 'required|confirmed'
        ]);
        try{
            $selectedUser->password = Hash::make($validatedData['password']);
            $selectedUser->save();
            return response()->json([
                'message' => 'User password updated successfully.'
            ], 200);
        }catch(\Exception $e){
            return response()->json([
                'message' => 'User password update failed.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Delete a specific user
    public function deleteUser($id){
        $selectedUser = User::find($id);
        if(!$selectedUser){
            return response()->json([
                'message' => 'User not found.'
            ], 404);
        }
        try{
            $selectedUser->delete();
            return response()->json([
                'message' => 'User deleted successfully.'
            ], 200);
        }
        catch(\Exception $e){
            return response()->json([
                'message' => 'User deletion failed.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
