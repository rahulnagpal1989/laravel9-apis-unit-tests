<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

use Tests\TestCase;

use App\Models\User;

class UserTest extends TestCase
{
    protected $token = '';

    public function setup(): void
    {
        parent::setup();

        $this->seed();
        $user = User::find(1);
        $this->token = $user->createToken('bankingapp')->accessToken;
    }

    /**
     * A create account feature test create account.
     *
     * @return void
     */
    public function test_the_create_account_response()
    {
        $response = $this->postJson('/api/create-account', [
            "name"=> "Test user1",
            "user_id"=> 0,
            "balance"=> 102
        ]);
        
        $response->assertStatus(201)->assertJson([
            'status' => true,
        ]);
    }

    /**
     * A create account feature test create account of same exisiting user.
     *
     * @return void
     */
    public function test_the_create_account_of_same_user_response()
    {
        $response = $this->postJson('/api/create-account', [
            "name"=> "Test user1",
            "user_id"=> 1,
            "balance"=> 102
        ]);
        
        $response->assertStatus(201)->assertJson([
            'status' => true,
        ]);
    }

    /**
     * A transfer amount feature test transfer amount with passing token.
     *
     * @return void
     */
    public function test_the_transfer_amount_with_token_response()
    {
        $response = $this->postJson('/api/transaction', [
                "sender_account_id"=> 1,
                "receiver_account_id"=> 2,
                "amount"=> 30
            ], ['Authorization' => 'Bearer '.$this->token]);

        $response->assertStatus(201)->assertJson([
            'status' => true,
        ]);
    }

    /**
     * A transfer amount feature test transfer amount without passing token.
     *
     * @return void
     */
    public function test_the_transfer_amount_without_token_response()
    {
        $response = $this->postJson('/api/transaction', [
                "sender_account_id"=> 1,
                "receiver_account_id"=> 2,
                "amount"=> 30
            ]);

        $response->assertStatus(422)->assertJson([
            'status' => false,
        ]);
    }

    /**
     * A transfer amount feature test transfer amount in same account.
     *
     * @return void
     */
    public function test_the_transfer_amount_in_same_account_response()
    {
        $response = $this->postJson('/api/transaction', [
            "sender_account_id"=> 1,
            "receiver_account_id"=> 1,
            "amount"=> 30
        ], ['Authorization' => 'Bearer '.$this->token]);

        $response->assertStatus(422)->assertJson([
            'status' => false,
        ]);
    }

    /**
     * A transfer amount feature test transfer amount in wrong sender id case.
     *
     * @return void
     */
    public function test_the_transfer_amount_in_case_wrong_sender_id_response()
    {
        $response = $this->postJson('/api/transaction', [
            "sender_account_id"=> 1000000000000,
            "receiver_account_id"=> 1,
            "amount"=> 30
        ], ['Authorization' => 'Bearer '.$this->token]);

        $response->assertStatus(422)->assertJson([
            'status' => false,
        ]);
    }

    /**
     * A transfer amount feature test transfer amount in wrong receiver id case.
     *
     * @return void
     */
    public function test_the_transfer_amount_in_case_wrong_receiver_id_response()
    {
        $response = $this->postJson('/api/transaction', [
            "sender_account_id"=> 1,
            "receiver_account_id"=> 1000000000000,
            "amount"=> 30
        ], ['Authorization' => 'Bearer '.$this->token]);

        $response->assertStatus(422)->assertJson([
            'status' => false,
        ]);
    }

    /**
     * A transfer amount feature test transfer amount in case sender has less bank balance than transfer anount.
     *
     * @return void
     */
    public function test_the_transfer_amount_in_case_sender_has_less_balance_in_account_to_transfer_response()
    {
        $response = $this->postJson('/api/transaction', [
            "sender_account_id"=> 1,
            "receiver_account_id"=> 2,
            "amount"=> 30000000000000
        ], ['Authorization' => 'Bearer '.$this->token]);

        $response->assertStatus(422)->assertJson([
            'status' => false,
        ]);
    }

    /**
     * A account balance feature test balance of specific bank account with passing token.
     *
     * @return void
     */
    public function test_the_balance_of_specific_bank_account_with_token_response()
    {
        $response = $this->get('/api/account/1', ['Authorization' => 'Bearer '.$this->token]);

        $response->assertStatus(200)->assertJson([
            'status' => true,
        ]);
    }

    /**
     * A account balance feature test balance of specific bank account without passing token.
     *
     * @return void
     */
    public function test_the_balance_of_specific_bank_account_without_token_response()
    {
        $response = $this->get('/api/account/1');

        $response->assertStatus(422)->assertJson([
            'status' => false,
        ]);
    }

    /**
     * A transaction history feature test transaction history of particular bank account with passing token.
     *
     * @return void
     */
    public function test_the_transaction_history_of_specific_bank_account_with_token_response()
    {
        $response = $this->get('/api/transaction?account_id=1', ['Authorization' => 'Bearer '.$this->token]);

        $response->assertStatus(200)->assertJson([
            'status' => true,
        ]);
    }

    /**
     * A transaction history feature test transaction history of particular bank account without passing token.
     *
     * @return void
     */
    public function test_the_transaction_history_of_specific_bank_account_without_token_response()
    {
        $response = $this->get('/api/transaction?account_id=1');

        $response->assertStatus(422)->assertJson([
            'status' => false,
        ]);
    }
}
