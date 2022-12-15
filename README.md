# WIP Geo Service

## Info

Geo data REST API service with admin dashboard to manage regions, countries, states, cities and airports.

### Installation
```bash
make install
make migrate
make sh
bin/console app:create-user admin@app.test qwerty
```

### Tech stack

PHP8.2, Symfony6, EasyAdminBundle and Mariadb.