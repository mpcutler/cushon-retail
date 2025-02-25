# Cushon Retail
A small PHP API to record retail ISA deposits as per the Cushon Recruitment Scenario.

## Usage
clone the repo 
```
$ git clone git@github.com:mpcutler/cushon-retail.git
```

create .env
```
$ mv .env.example .env
```

install dependencies
```
$ composer install
```

run migrations
```
$ vendor/bin/phinx migrate -e development
```

run seeds
```
$ vendor/bin/phinx seed:run -e development
```

start app
```
$ composer start
```

## Endpoints
### GET /funds
Returns a list of available funds in JSON  format
### POST /deposit
Accepts a JSON post body and stores the deposit. Example JSON input:
```
{
    "account_id": "8974ed1c-cbec-4899-abf9-8d496534d71a",
    "deposits": [
        {
            "fund_id": "a10b5e72-3c6c-49b6-bc5d-a8525f20ae0a",
            "amount": "25000"
        }
    ]
}
```