# GeoService

## Info

Geo data REST API service with admin dashboard to manage regions, countries, nationalities, timezones, states, cities and airports.

[RoadRunner](https://roadrunner.dev) |
[PHP8.3](https://www.php.net/releases/8.3/en.php) |
[Symfony 6](https://symfony.com) |
[EasyAdminBundle](https://symfony.com/bundles/EasyAdminBundle/current/index.html) |
[OpenAPI](https://swagger.io/specification/)

### Admin Dashboard

![Screenshot](docs/img/country_dark.png)
![Screenshot](docs/img/airport_light.png)

### Swagger UI

```text
http://127.0.0.1:8085/apidoc
```

### Running

```bash
make up
```

### Create a user

```bash
make user
```

### Tests

```bash
make test
```

### OpenAPI

Regenerate openapi.yaml in project root directory

```bash
make openapi
```

### Client installation to your typescript frontend or backend application

```bash
npm install @amvid/geo-service
```

### SSL

Roadrunner [automatically](https://docs.roadrunner.dev/http/http#lets-encrypt) obtains and refreshes Let's Encrypt certificate.

### Import

All geo data

```bash
make geo
```

Currencies

```bash
make currencies
```

Timezones

```bash
make timezones
```

Regions

```bash
make regions
```

Sub regions

```bash
make subregions
```

Countries

```bash
make countries
```

States

```bash
make states
```

Cities

```bash
make cities
```

Airports

```bash
make airports
make airports2
```

Nationalities

```bash
make nationalities
```

## API example

### Airport

#### Query

GET /api/v1/airports/London

Response

```json
[
  {
    "title": "London (Any)",
    "iata": "LON",
    "country": "United Kingdom",
    "subregion": "Northern Europe",
    "region": "Europe",
    "children": [
      {
        "title": "London Metropolitan Area",
        "iata": "LON",
        "country": "United Kingdom",
        "region": "Europe",
        "subregion": "Northern Europe"
      },
      {
        "title": "London Heathrow Airport",
        "iata": "LHR",
        "country": "United Kingdom",
        "region": "Europe",
        "subregion": "Northern Europe"
      },
      {
        "title": "London Gatwick Airport",
        "iata": "LGW",
        "country": "United Kingdom",
        "region": "Europe",
        "subregion": "Northern Europe"
      },
      {
        "title": "London City Airport",
        "iata": "LCY",
        "country": "United Kingdom",
        "region": "Europe",
        "subregion": "Northern Europe"
      },
      {
        "title": "London Stansted Airport",
        "iata": "STN",
        "country": "United Kingdom",
        "region": "Europe",
        "subregion": "Northern Europe"
      },
      {
        "title": "London Luton Airport",
        "iata": "LTN",
        "country": "United Kingdom",
        "region": "Europe",
        "subregion": "Northern Europe"
      },
      {
        "title": "London Biggin Hill Airport",
        "iata": "BQH",
        "country": "United Kingdom",
        "region": "Europe",
        "subregion": "Northern Europe"
      },
      {
        "title": "RAF Northolt",
        "iata": "NHT",
        "country": "United Kingdom",
        "region": "Europe",
        "subregion": "Northern Europe"
      }
    ]
  },
  {
    "title": "London Airport",
    "iata": "YXU",
    "country": "Canada",
    "region": "Americas",
    "subregion": "Northern America"
  },
  {
    "title": "London-Corbin Airport/Magee Field",
    "iata": "LOZ",
    "country": "United States",
    "region": "Americas",
    "subregion": "Northern America"
  }
]
```

## License

This software is published under the [MIT License](LICENSE.md)
