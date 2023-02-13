# Financial Institution API
In this project we have created internal API for a financial institution using PHP and Laravel.

### Brief

While modern banks have evolved to serve a plethora of functions, at their core, banks must provide certain basic features. We have build the basic HTTP API for one of those banks! It could ultimately be consumed by multiple frontends (web, iOS, Android etc).

### Tasks

- Implemented using:
  - Language: **PHP**
  - Framework: **Laravel**

- Setup guide:
  Under the laravel directory first you need to run the migration and seed dummy data by using this below command:
  ```
  php artisan migrate:fresh --seed
  ```
  After that run this below command to create passport token client.
  ```
  php artisan passport:client --personal --name=bankingapp
  ```
  Now copy the Client ID i.e. generated and paste it under the .env file like below:
  ```
  PASSPORT_PERSONAL_ACCESS_CLIENT_ID="<PUT CLIENT ID HERE>"
  PASSPORT_PERSONAL_ACCESS_CLIENT_SECRET="this-is-my-secret-key-for-banking-app"
  ```

- There should be API routes that allow them to:
  - Create a new bank account for a customer, with an initial deposit amount. A single customer may have multiple bank accounts.
      #### For creating a new bank account of a new user
      **[POST]: http://127.0.0.1:8000/api/create-account**
      ```
      {
          "name": "Test user",
          "balance": 102
      }
      ```
      #### For creating a new bank account of an exisiting user
      **[POST]: (http://127.0.0.1:8000/api/create-account)**
      ```
      {
          "name": "Test user",
          "user_id": 1,
          "balance": 102
      }
      ```
      #### Headers that need to be set while sending parameters
      ```
      {
          Accept:application/json
          Content-Type:application/json
      }
      ```
      This above API gives you user's id, name, email, account_id, balance and token.
      The account_id and token is used in further APIs.
      <br/><br/><br/>
  
  - Transfer amounts between any two accounts, including those owned by different customers.
      
      **[POST]: (http://127.0.0.1:8000/api/transaction)**
      ```
      {
          "sender_account_id": 1,
          "receiver_account_id": 2,
          "amount": 5
      }
      ```
      #### Headers that need to be set while sending parameters
      ```
      {
          Authorization: Bearer <token>
          Accept:application/json
          Content-Type:application/json
      }
      ```
      This above API is used to send money from one bank account to another and for sender/receiver_account_id you already get it from create-account API.
      <br/><br/><br/>
      
  - Retrieve balance for a given account.

      **[GET]: http://127.0.0.1:8000/api/account/1)**
      #### Headers that need to be set while sending parameters
      ```
      {
          Authorization: Bearer <token>
          Accept:application/json
          Content-Type:application/json
      }
      ```
      
      
  - Retrieve transfer history for a given account.

      **[GET]: (http://127.0.0.1:8000/api/transaction?account_id=2)**
      #### Headers that need to be set while sending parameters
      ```
      {
          Authorization: Bearer <token>
          Accept:application/json
          Content-Type:application/json
      }
      ```
      This above API give you balance details of particular bank account that you are passing as a parameter in account_id.
      <br/><br/>
  
- Unit test cases
  ```
      # For test unit test case just run this command:
      php artisan test
  ```
  