<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use App\Models\Account;

use Illuminate\Http\Request;

use Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created account and balance in database.
     *
     * @param  \Illuminate\Http\Request\StoreUserRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUserRequest $request)
    {
        if ($request->user_id<=0) {
            $user = User::factory()->create(["name"=> $request->name]);
            $user_id = $user->id;
        } else {
            $user_id = $request->user_id ?? 0;
            $user = User::find($user_id);
            if ( !$user) {
                return response()->json([
                    'status' => false,
                    'msg' => 'User ID does not Exist: '.$user_id,
                ], 422);
            }
        }

        $account = Account::create(["user_id"=> $user_id, "balance" => $request->balance, "is_active" => 1]);
        $token = $user->createToken('bankingapp')->accessToken;
        
        return response()->json([
            'status' => true,
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'account_id' => $account->id,
            'balance' => $account->balance,
            'token' => $token,
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }
}
