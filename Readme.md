# Sequra challenge

Originally this was a coding challenge for a job interview that I did with my "old" DDD template, and of course the time limit related to the nature of a code challenge.

The pair programming was not very successful, but as I kept learning and deepening my knowledge in DDD, CQRS and Domain Events, I decided to refactor the code and improve it at the same time I practiced with the new concepts I was learning.

[For context here's the original requirements of the challenge.](Challenge.md)


## Requirements

- Docker
- Docker-compose
- Make
- Available ports 8080, 15672, 5672 ,3306


## My approach

TODO new approach

## Running the project

To have the containers up and running:

- Clone the repository
- Run `make start`

The fixtures takes a while to run, wait for `[OK] Cache for the "dev" environment (debug=true) was successfully cleared.` to appear in the console.

To run the tests:

- Run `make tests`

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