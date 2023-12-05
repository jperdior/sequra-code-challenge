# Sequra challenge

## Arquitectural and technical decisions

- Framework: I decided to use symfony as it's the framework I am more familiar with ,and it comes with out of the box dependency injection, ORM, message broker integration, etc.
- Database: MySQL
- Message broker: RabbitMQ
- I used DDD + CQRS to structure the code

## Requirements

- Docker
- Docker-compose
- Make
- Available ports 8080, 15672, 5672 ,3306

## Results

| Year | Number of Disbursements | Amount Disbursed to Merchants | Amount of Order Fees | Number of Monthly Fees Charged (From Minimum Monthly Fee) | Amount of Monthly Fee Charged (From Minimum Monthly Fee) |
|------|-------------------------|-------------------------------|----------------------|-----------------------------------------------------------|----------------------------------------------------------|
| 2022 | 1547                    | 37496446.37 €                 | 338817.97 €          | 20                                                        | 381.03 €                                                 |
| 2023 | 10363                   | 187914787.11 €                | 1703367.25 €         | 116                                                       | 1939.97 €                                                 |


## My approach

At the beginning I thought the before 8:00 am requirement was irrelevant, but then I realized that it was the contrary.
Instead of approaching the matter as a daily batch operation
I decided to handle the orders in "real time", for that I enqueue the orders not processed yet ordered from oldest to newer, so the system will process old orders
,and it's ready to work with new orders as soon as they are created. This also achieves O(1) time complexity for the calculation of disbursements instead of being a daily
batch operation that would have O(n) time complexity. 

I set up supervisord to run 30 instances of the consumer and feed the queue, I calculated that the application processes around 20k orders per minute. Exists a race condition
when several orders that belong to the same disbursement are processed in parallel, as the disbursement is created when the first order is processed, and the rest of the orders
provoke a unique constraint violation. I decided to handle this situation by catching the exception and symfony messenger retries the message up to 3 times, so it works fine.
In any case I set up a failure queue to handle the messages that fail after 3 retries.

There's also another race condition calculating the amount and fee amount of each disbursement as several orders can be processed in parallel and that is causing some miscalculations as the total
amount of the disbursement doesn't match the sum of the amounts of the orders. I tried to solve this but I am out of time so I leave it as it is.

All the logic to calculate the disbursements is in the DisbursementCalculatorService in src/SequraChallenge/Domain/Service/DisbursementCalculatorService.php

To identify easily each orders and fee per order in each disbursement I created an entity DisbursementLine that has a relation one on 
one with Order and one to many with Disbursement. This way I can easily identify the orders and fees that are part of each disbursement.

## Improvements

- I think fees should be managed in Database, so they can be easily modified without having to deploy a new version of the application. In this challenge I handled it with constants.
- As indicated in the previous section, there's some problem with race conditions that I would like to solve.
- I am using the last stable version of symfony 6 and there seems to be some deprecation warnings that I left unresolved but doesn't affect the functionality.
- I tested mostly the happy paths, I would have liked to test more edge cases.

## Running the project

To have the containers up and running:

- Clone the repository
- Run `make start`

The fixtures takes a while to run, white for `[OK] Cache for the "dev" environment (debug=true) was successfully cleared.` to appear in the console.

To run the tests:

- Run `make tests`

To run start the consumer and feed the queue:

- Run `make run`

To stop the containers:

- Run `make stop`

To manually enqueue orders:

- Run `make enqueue-orders`

To manually consume orders with just one worker:

- Run `make consume-orders`

To open the rabbitmq management console: (only works in MacOs)

- Run `make open-rabbitmq-manager`