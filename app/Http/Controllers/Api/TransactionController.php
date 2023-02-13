<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTransactionRequest;
use App\Models\Transaction;
use App\Models\Account;

use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * Display a listing of all the transaction history of particular account.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $id = $request->account_id;
        $sender_account_history = Transaction::where(
                                    function ($q) use($id) {$q->where('sender_account_id', $id)->orWhere('receiver_account_id', $id);
                                })->where('is_active', 1)->get();
        return response()->json([
            'status'=> true,
            'transactions'=>$sender_account_history,
        ], 200);
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
     * Store a new transaction in table and also update balance.
     *
     * @param  \Illuminate\Http\Request\StoreTransactionRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTransactionRequest $request)
    {
        $sender_account = Account::where('id', $request->sender_account_id)->where('is_active', 1)->get();
        if (count($sender_account)<=0) {
            return response()->json([
                'status' => false,
                'msg' => 'Sender Account does not Exist: '.$request->sender_account_id,
            ], 422);
        }
        $sender_account_balance = Account::where('id', $request->sender_account_id)->where('balance', '>=', $request->amount)->get();
        if (count($sender_account_balance)<=0) {
            return response()->json([
                'status' => false,
                'msg' => 'Sender does not enough funds to transfer',
            ], 422);
        }
        
        $receiver_account = Account::where('id', $request->receiver_account_id)->get();
        if (count($receiver_account)<=0) {
            return response()->json([
                'status' => false,
                'msg' => 'Receiver Account does not Exist: '.$request->receiver_account_id,
            ], 422);
        }

        if ($request->sender_account_id == $request->receiver_account_id) {
            return response()->json([
                'status' => false,
                'msg' => 'Sender & Receiver Account numbers cannot be same',
            ], 422);
        }

        $transaction = Transaction::create(["sender_account_id"=> $request->sender_account_id, 
                                            "receiver_account_id"=> $request->receiver_account_id, 
                                            "amount" => $request->amount, "is_active" => 1]);
        $account = Account::where(["id"=> $request->sender_account_id])->update(["balance" => ($sender_account_balance[0]['balance'] - $request->amount)]);

        return response()->json([
            'status'=> true,
            'transaction'=> $transaction
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function show(Transaction $transaction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function edit(Transaction $transaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Transaction $transaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transaction $transaction)
    {
        //
    }
}
