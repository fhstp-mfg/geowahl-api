![alt tag](https://github.com/fhstp-mfg/geowahl/blob/master/design/Logo/logo_round.png)

GeoWahl visualizes geo–political data for the Web, Smartphone (iOS and Android) and Smartwatch (Apple Watch and Android Wear).

Platform–specific projects can be found here:
- [GeoWahl Android](https://github.com/fhstp-mfg/geowahl-android)
- [GeoWahl iOS](https://github.com/fhstp-mfg/geowahl-ios)

# API
This repository provides an API for geo–political data and data visualization views.

## JSON Data
The following routes return a `Content-Type: application/json` response.

### All Elections
`/elections`

Returns all available elections, plus the associated data from the [states](https://github.com/fhstp-mfg/geowahl-web/wiki/GeoWahl-API/#states), [parties](https://github.com/fhstp-mfg/geowahl-web/wiki/GeoWahl-API/#parties) and [results](https://github.com/fhstp-mfg/geowahl-web/wiki/GeoWahl-API/#results) web services.

**Response example:** `/elections`
```js
{
  "elections": [
    {
      "id": 1,
      "slug": "bpw16a"
      "name": "BPW16 1. Wahlgang",

      "states": [ ... ], // also retrieved by: /{electionSlug}/states
      "parties": [ ... ], // also retrieved by: /{electionSlug}/parties
      "results": [ ... ] // also retrieved by: /{electionSlug}/results
    },
    ... // further elections
  ]
}
```

### Election (single)
`/{electionSlug}`

Returns an available election for the provided `electionSlug`, plus the associated data from the [states](https://github.com/fhstp-mfg/geowahl-web/wiki/GeoWahl-API/#states), [parties](https://github.com/fhstp-mfg/geowahl-web/wiki/GeoWahl-API/#parties) and [results](https://github.com/fhstp-mfg/geowahl-web/wiki/GeoWahl-API/#results) web services.

**Response example:** `/bpw16b`
```js
{
  "id": 2,
  "slug": "bpw16b"
  "name": "BPW16 2. Wahlgang",

  "states": [ ... ], // also retrieved by: /{electionSlug}/states
  "parties": [ ... ], // also retrieved by: /{electionSlug}/parties
  "results": [ ... ] // also retrieved by: /{electionSlug}/results
}
```

### Election States
`/{electionSlug}/states`

Returns all available states for an election, specified by `electionSlug`.

**Response example:** `/bpw16b/states`
```js
{
  "states": [
    {
      "id": 0,
      "slug": "results"
      "name": "Alle Bundesländer"
    },
    {
      "id": 1,
      "slug": "w",
      "name": "Wien"
    },
    ... // further states
  ]
}
```

### Election Parties
`/{electionSlug}/parties`

Returns all parties for an election, specified by `electionSlug`.

**NOTE** Each party contains corresponding colors, which you can look up under the [Parties Color Definition](https://github.com/fhstp-mfg/geowahl-web/wiki/Parties-Color-Definition) page.

**Response example:** `/bpw16b/parties`
```js
{
  "parties": [
    {
      "name": "Hofer",
      "rgba": { "r": 14, "g": 66, "b": 142, "a": 1 },
      "hex": "#0E428E"
    },
    {
      "name": "VdB",
      "rgba": { "r": 120, "g": 175, "b": 53, "a": 1 },
      "hex": "#78AF35"
    }
  ]
}
```

### State Results
`/{electionSlug}/{stateSlug}`

Returns the total results for the provided `stateSlug`.

**NOTE** When `stateSlug` is set to `results`, then the **total results** of **all available states** are returned.

**NOTE** The `results` arrays always contain the following props:
- `name`: String – the party name
- `votes`: Integer – how many votes the party received (in context)
- `percent`: Float (2 decimals) – the rounded votes percentage
- `exact`: Float (all decimals) – the exact votes percentage

**Response example:** `/bpw16b/results` or `/bpw16b/w`
```js
{
  "results": [
    {
      "name": "Hofer",
      "votes": {PARTY_VOTES},
      "percent": {PARTY_PERCENT_ROUNDED},
      "exact": {PARTY_PERCENT_EXACT}
    },
    {
      "name": "VdB",
      "votes": {PARTY_VOTES},
      "percent": {PARTY_PERCENT_ROUNDED},
      "exact": {PARTY_PERCENT_EXACT}
    }
  ]
}
```

### State Districts
`/{electionSlug}/{stateSlug}/districts`

Returns all districts and the corresponding results for the provided `electionSlug` and `stateSlug`.

**NOTE** The `results` array format stays the same.

**Response example:** `/bpw16a/w/districts`
```js
{
  "districts": [
    {
      "id": 1,
      "name": "Innere Stadt",
      "results": [ ... ]
    },
    ... // further districts
  ]
}
```

**NOTE** If `stateSlug` is set to `results` _(see example below)_, then the **total results** of **each available state** are returned. In this case the property `districts` will actually contain an array of `states` and the **total results** for **each corresponding state**.

**Response example:** `/bpw16b/results/districts`
```js
{
  "districts": [
    {
      "name": "Wien",
      "results": [
        {
          "name": "Hofer",
          "votes": {PARTY_TOTAL_VOTES},
          "percent": {PARTY_TOTAL_PERCENT_ROUNDED},
          "exact": {PARTY_TOTAL_PERCENT_EXACT}
        },
        {
          "name": "VdB",
          "votes": {PARTY_TOTAL_VOTES},
          "percent": {PARTY_TOTAL_PERCENT_ROUNDED},
          "exact": {PARTY_TOTAL_PERCENT_EXACT}
        }
      ]
    },
    ... // further districts
  ]
}
```

### District Results _by id_
`/{electionSlug}/{stateSlug}/{districtId}`

**All–In–One** – Returns the results for a district, specified by `districtId`. Furthermore it returns the results for the parent state, specified by `stateSlug`, and the results for the election, specified by `electionSlug`.

**NOTE** The `results` array format stays the same.

**Response example:** `/bpw16b/w/1`
```js
{
  "district": {
    "id": 1,
    "name": "Innere Stadt",
    "results": [ ... ]
  },

  "state": {
    "slug": "w",
    "name": "Wien",
    "results": [ ... ]
  },

  "election": {
    "slug": "bpw16b",
    "name": "BPW16 2. Wahlgang",
    "results": [ ... ]
  }
}
```

### District Results _by Geolocation_
`/{electionSlug}/{latitude},{longitude}`

**All–In–One** – Searches for a district by `latitude` and `longitude` and returns the corresponding district results. Furthermore it returns the results for the parent state, specified by `stateSlug`, and the results for the election, specified by `electionSlug`.

**NOTE** The `results` array format stays the same.

**Response example:** `/bpw16b/48.014223,16.5558545`
```js
{
  "district": {
    "id": 75,
    "name": "Götzendorf an der Leitha",    
    "results": [ ... ]
  },

  "state": {
    "slug": "noe",
    "name": "Niederösterreich",
    "results": [ ... ]
  },

  "election": {
    "slug": "bpw16b"
    "name": "BPW16 2. Wahlgang",
    "results": [ ... ]
  }
}
```

## Visualizations
The following routes return `Content-Type: text/html` views using [D3](https://github.com/d3/d3) for visualizing data.

### Election Results Donut–Chart
`/{electionSlug}/donut-chart`

![Election Results Donut–Chart Visualization](https://github.com/fhstp-mfg/geowahl/blob/master/design/Wiki/donut-chart-example-01.png)

### State Results Donut Chart
`/{electionSlug}/{stateSlug}/donut-chart`

_(State Results Donut–Chart Visualization example coming soon ...)_

### District Results Donut–Chart
`/{electionSlug}/{stateSlug}/{districtId}/donut-chart`

_(District Results Donut–Chart Visualization example coming soon ...)_

# Setup Google API

In order to use Google APIs, you have to add your own **Server API key**.

1. Go to the [Google Developer Console](https://console.developers.google.com/project/_/apiui/apis/library)

2. From the project drop-down, select an existing project, or create a new one by selecting **Create a new project**.

3. In the sidebar under "API Manager", select **Credentials**, then select the **OAuth consent screen** tab.
  - Choose an **Email Address**, specify a **Product Name** (e.g. "GeoWahl"), and press **Save**.

4. In the **Credentials** tab, select the **New credentials** drop-down list, and choose **API key**.

5. In the **Create a new key dialog box** select **Server key**.

6. Specify a **Name** for the Server key (e.g. "GeoWahl Server").
  - Optionally you can limit requests to a list of **IP addresses**.

7. Finally press the **Create** button.

**NOTE** It may take up to 5 minutes for settings to take effect.

### Activate APIs

The following Google APIs have to be activated:
  - Google Maps Geocoding API
  - Google Places API Web Service

In the sidebar under "API Manager", select **Google APIs**, the search and select the afore mentioned APIs and press the **Enable** button.

### Usage

1. In the sidebar under "API Manager", select **Credentials**, then copy your key from the **API keys** section.

2. Change the environment variable `API_KEY` in your `.env` file to your selected API key.

## Available Data

### Bundespräsidentenwahlen 2016

The results for all the federal states and communities from both ballots are **available to the full extent !**

### Nationalratswahlen 2013

The results for all the federal states and communities are available. Only the state _Steiermark_ is missing, since they don't provide any `.csv` files for their result.

### Gemeinderatswahlen 2013

The community results for the states _Burgenland_, _Niederösterreich_, _Oberösterreich_, _Salzburg_ and _Vienna_ are available.
There is no data for the states _Steiermark_, _Kärnten_, _Vorarlberg_ and _Tirol_.

## Data errors

Data errors should not be excluded, as all files have been curated to `.json` format manually.

**We assume no liability for possible mistakes !**


## Contributors
- [Daniel Koller](https://github.com/danielkoller)
- [Gerald Strobl](https://github.com/gstrobl)
- [Iosif Miclaus](https://github.com/miclaus)
- [Katrin Rudisch](https://github.com/katrinrudisch)
- [Martina Hack](https://github.com/maelen92)
- [Michaela Würz](https://github.com/michiw)
- [Patrick Eberhardt](https://github.com/eberhapa)
- [Sebastian Ulbel](https://github.com/suits-at)

## License

[The MIT License](https://opensource.org/licenses/MIT) (MIT)


_that's all folks_
