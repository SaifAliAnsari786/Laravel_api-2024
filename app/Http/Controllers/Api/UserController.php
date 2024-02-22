<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\Validator;



class UserController extends Controller
{
    public function index($flag)
    {
        $query = User::select('name','email');
        // return response()->json([
        //     'success' => true,
        //     'users' => $users,
        // ]);
        // p($users);
        if ($flag == 1) {
           $query->where('status',1);

        }elseif ($flag == 0){
            // empty
            // $query->where('status',0);
        }
        else{
            return response()->josn([
                'message'=>'invalid parameter passed, it can be either 1 or 0',
                'status'=>0,

            ],400);
        }
        $users = $query->get();
        if (count($users) > 0) {
            //user exist
            $response = [
                'message' => count($users). 'user found',
                'status' => 1,
                'data' => $users
             ];
        }
        else{
            $response = [
                'message' => count($users). 'user found',
                'status' => 0,

            ];
        }
        return response()->json($response,200);
    }

    public function store(Request $request)
    {
        // Validate the incoming data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required|string|min:8',
        ]);


        // Check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Validation passed, create new user
        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User created successfully',
            'user' => $user,
        ], 201);
    }

    public function show($id)
    {
        $user = User::find($id);
        // p($user);

        if(is_null($user)){
            $response = [
                'message' => "User not found",
                'status'  => 0
            ];
            $statusCode = 404; // Not Found
        } else {
            $response = [
                'message' => "User found",
                'status'  => 1,
                'data'    => $user,
            ];
            $statusCode = 200; // OK
        }

        return response()->json($response, $statusCode);
    }


    // public function update(Request $request, $id)
    // {
    //     // Validate the incoming data
    //     $validator = Validator::make($request->all(), [
    //         'name' => 'required|string|max:255',
    //         'email' => 'required|email|unique:users,email,' . $id,
    //         'password' => 'sometimes|string|min:8|confirmed',
    //         'password_confirmation' => 'sometimes|string|min:8',
    //     ]);

    //     // Check if validation fails
    //     if ($validator->fails()) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Validation errors',
    //             'errors' => $validator->errors(),
    //         ], 422);
    //     }

    //     // Find the user
    //     $user = User::findOrFail($id);

    //     // Update user information
    //     $user->name = $request->input('name');
    //     $user->email = $request->input('email');

    //     if ($request->has('password')) {
    //         $user->password = bcrypt($request->input('password'));
    //     }

    //     // Save the changes
    //     $user->save();

    //     return response()->json([
    //         'success' => true,
    //         'message' => 'User updated successfully',
    //         'user' => $user,
    //     ]);
    // }

    public function update(Request $request ,$id)
    {
        $user = User::find($id);
        if(is_null($user)){
            return response()->json([
                'meassage' => 'user is not found yet',
                'status' => 0,
            ],404);

        }else{
            try{
                DB::beginTransaction();
                $user->name = $request->input('name');
                $user->email = $request->input('email');
                $user->contact = $request->input('contact');
                $user->pincode = $request->input('pincode');
                $user->save();
                DB::commit();
                return response()->json([
                    'meassage' => 'user update sucessfully',
                    'status' => 1,
                    'data' => $user,
                ],200);
            }
            catch(Exception $err){
                DB::rollBack();
                return response()->json([
                    'meassage' => 'internal serve error',
                    'status' => 0,
                ],500);
            }
        }
    }

    public function destroy($id)
    {
        $user = User::find($id);
        if(is_null($user)){
            $response = [
                'message' => 'user does not exits',
                'status' => 0,
            ];
            $statusCode = 404;

        }else{
            DB::beginTransaction();
            try{
                $user->delete();
                DB::commit();
                $response = [
                    'message' => 'User has been deleted sucessfully',
                    'status' => 1,
                ];
                $statusCode = 200;

            } catch (\Exception $err){
                DB::rollBack();
                $response = [
                    'message' => 'Internal Server Error',
                    'status' => 0,
                ];
                $statusCode = 500;
            }
        }
        return response()->json($response, $statusCode);
    }
}
