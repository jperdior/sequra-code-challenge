# Sequra challenge

![unittests workflow](https://github.com/jperdior/sequra-challenge/actions/workflows/unittests.yml/badge.svg)

Originally this was a coding challenge for a job interview that I did with my "old" DDD template, and of course the time limit related to the nature of a code challenge.

The pair programming was not very successful, but as I kept learning and deepening my knowledge in DDD, CQRS and Domain Events, I decided to refactor the code and improve it at the same time I practiced with the new concepts I was learning.

[For context here's the original requirements of the challenge.](Challenge.md)


## Requirements

- Docker
- Docker-compose
- Make
- Available ports 8080, 15672, 5672 ,3306


## My approach

As this is a code challenge for an specific functionality the "bounded context" corresponds with the folder SequraChallenge, but in a real world scenario depending on the business ubiquitous language, the bounding context would be "disbursements" or maybe it would be part of a wider context named "orderProcessing".

I organized the code in modules, where each module contains an Aggregate Root, and the corresponding logic, repositories, events, etc related to that Aggregate Root.

I also created a shared bounded context and a shared module, where I put the common logic, like the base classes for the entities, value objects, repositories etc.

### Modules

#### Merchants

For our disbursement bounded context we only need the disbursement frequency, the minimum monthly fee and the liveOn date, so in this module I modeled the aggregate root Merchant with those properties ignoring the rest of the provided in the challenge. It only contains a MerchantFinder domain service to find a merchant by id.

#### Purchases

As I had experiences in the past with the "order" keyword in sql, I decided to use the word "purchase" instead, as it is more generic, and it is not a reserved word in sql. As I was provided with a csv file with purchases to process, in this module there's no aggregate root and I only created a PurchaseCreated domain event and in the presentation layer a symfony command that reads the csv file and dispatches the event for each purchase. In a real world scenario the purchase created events would be dispatched by an ecommerce system or something similar.

#### Disbursements

This module tracks the data related with the disbursements such as the total amount to disburse and the total fees. It handles 2 events: 
- PurchaseCreated: creates or searches for the corresponding disbursement, then, knowing the disbursement and the merchant relevant data, dispatches the command CreateDisbursementLine.
- DisbursementLineCreated: updates the disbursement with the new line and recalculates the total amount and fees. As there a high concurrency I implemented a pessimistic lock in this use case so the disbursement is locked until the amount and fees are updated. Also as TODO, fees and amounts from lines should be queried to make sure the calculation, as rabbitmq doesn't guarantee the non duplication of messages, or maybe in the future there's a feature request to edit the lines manually.

#### DisbursementLines

This module contains the use case that creates a disbursement line based on the purchase and the disbursement. To handle duplicity of purchases in the queue system, there's a disbursement line finder by purchase id domain service, if a line exists for a purchase the use case finishes without doing anything, otherwise the disbursement line is created and the event DisbursementLineCreated is dispatched.

#### MerchantMonthlyFees

This module tracks the monthly fees for each merchant, it handles the event DisbursementAmountAndFeeIncreased and updates the monthly fee for the corresponding merchant.

## Running the project

To have the containers up and running:

- Clone the repository
- Run `make start`

The fixtures takes a while to run, wait for `[OK] Cache for the "dev" environment (debug=true) was successfully cleared.` to appear in the console.

To run unit tests:

- Run `make tests`

To run acceptance tests:

- Run `make behat`

To run the consumer and feed the queue:

- Run `make run`

To stop the containers:

- Run `make stop`

To manually enqueue orders:

- Run `make enqueue-orders`

To manually consume orders with just one worker:

- Run `make consume-orders`

To open the rabbitmq management console: (only works in MacOs)

- Run `make open-rabbitmq-manager`