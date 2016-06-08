# GeoWahl
Provides data API for geo–political data for Web and provides data visualization views.

![alt tag](https://github.com/fhstp-mfg/geowahl/blob/master/design/Logo/logo_round.png)

GeoWahl visualizes geo–political data for Web, Smartphone (iPhone, Android) and Smartwatch (Apple Watch, Android Wear).

Platform-specific projects can be found here:
- [GeoWahl Android](https://github.com/fhstp-mfg/geowahl-android)
- [GeoWahl iOS](https://github.com/fhstp-mfg/geowahl-ios)

## GeoWahl API

### JSON Data
The following routes return **JSON** data.

### All Elections
`/elections`

Returns all available elections, plus the associated data from the [states](https://github.com/fhstp-mfg/geowahl-web/wiki/GeoWahl-API/#states), [parties](https://github.com/fhstp-mfg/geowahl-web/wiki/GeoWahl-API/#parties) and [results](https://github.com/fhstp-mfg/geowahl-web/wiki/GeoWahl-API/#results) web services.

**JSON format example:** `/elections`
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
    ...
  ]
}
```

### Election (single)
`/{electionSlug}`

Returns an available election for the provided `electionSlug`, plus the associated data from the [states](https://github.com/fhstp-mfg/geowahl-web/wiki/GeoWahl-API/#states), [parties](https://github.com/fhstp-mfg/geowahl-web/wiki/GeoWahl-API/#parties) and [results](https://github.com/fhstp-mfg/geowahl-web/wiki/GeoWahl-API/#results) web services.

**JSON format example:**
```js
{
  "id": 1,
  "slug": "bpw16a"
  "name": "BPW16 1. Wahlgang",

  "states": [ ... ], // also retrieved by: /{electionSlug}/states
  "parties": [ ... ], // also retrieved by: /{electionSlug}/parties
  "results": [ ... ] // also retrieved by: /{electionSlug}/results
}
```

### States
`/{electionSlug}/states`

Returns all available states for an election.

**JSON format example:** `/bpw16b/states`
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
    ...
  ]
}
```

### Parties
`/{electionSlug}/parties`

Returns all parties for an election. Each party contains corresponding colors, which you can look up under the [Parties Color Definition](https://github.com/fhstp-mfg/geowahl-web/wiki/Parties-Color-Definition) page.

**JSON format example:** `/bpw16b/parties`
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

### Results
`/{electionSlug}/{stateSlug}`

Returns the total results for the provided `stateSlug`.

**NOTE** If `stateSlug` is set to `results`, then the **total results** of **all available states** are returned.

**NOTE** Results arrays always contain:
- PARTY_NAME (String)
- PARTY_VOTES (Integer)
- PARTY_PERCENT_ROUNDED (Float w/ 2 decimals)
- PARTY_PERCENT_EXACT (Float w/ all decimals)

**JSON format example:** `/bpw16b/results` or `/bpw16b/w`
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

### Districts
`/{electionSlug}/{stateSlug}/districts`

Returns all districts and the corresponding results for the provided `stateSlug`. (The results array format stays the same.)

**JSON format example:** `/bpw16a/w`
```js
{
  "districts": [
    {
      "name": "Innere Stadt",
      "results": [ ... ]
    },
    ...
  ]
}
```

**NOTE** If `stateSlug` is set to `results` (see example below), then the **total results** of **each available state** are returned. In this case the property `districts` actually contains an array of `states` and the total results for each corresponding state.

**JSON format example:** `/bpw16b/results`
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
    ...
  ]
}
```

### District by Id
`/{electionSlug}/{stateSlug}/{districtId}`

Returns the result for the provided `districtId`. (The results arrays format stays the same.)

**JSON format example:** `/bpw16a/bgld/2`
```js
{
  district: {
    id: 2,
    name: "Rust",
    results: [ ... ]
  },
  state: {
    name: "Burgenland",
    results: [ ... ]
  },
  election: {
    name: "BPW16 1. Wahlgang",
    results: [ ... ]
  }
}
```

### District by Geolocation for Election (single)
`/{electionSlug}/{latitude},{longitude}`

**All-In-One** – Searches for a district by `latitude` and `longitude` for an election, defined by `electionSlug` and returns the corresponding district results, the district's parent state results and the state's parent election results. (The results arrays format stays the same.)

**JSON format example:** `/bpw16b/48.014223,16.5558545`
```js
{
  "district": {
    "name": "Götzendorf an der Leitha",    
    "results": [ ... ]
  },
  "state": {
    "name": "Niederösterreich",
    "results": [ ... ]
  },
  "election": {
    "name": "BPW16 2. Wahlgang",
    "results": [ ... ]
  }
}
```

### Visualizations
The following routes return **HTML** views using [D3](https://github.com/d3/d3) for visualization.

### Donut Chart
`/{electionSlug}/donut-chart`

![Donut Chart Visualization Example](https://github.com/fhstp-mfg/geowahl/blob/master/design/Wiki/donut-chart-example-01.png)

## Google API

### Setup

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

##Parties Color Definition

![Parties Color Definition](https://github.com/fhstp-mfg/geowahl/blob/master/design/Wiki/parties-colors.png)
### GRÜNE, VDB:
`#78AF35`

`rgba(120, 175, 53, 1)`

### SPÖ, HUNDSTORFER
`#F31137`

`rgba(243, 17, 55, 1)`

### ÖVP, KHOL
`#363636`

`rgba(54, 54, 54, 1)`

### NEOS
`#EA3D88`

`rgba(234, 61, 136, 1)`

### TEAM STRONACH, FRANK
`#F8E323`

`rgba(248, 227, 35, 1)`

### GRISS
`#BABABA`

`rgba(186, 186, 186, 1)`

### LUGNER
`#77068C`

`rgba(119, 6, 140, 1)`

### FPÖ, HOFER
`#0E428E`

`rgba(14, 66, 142, 1)`

## Contributors
- [Michaela Würz](https://github.com/michiw)
- [Daniel Koller](https://github.com/danielkoller)
- [Iosif Miclaus](https://github.com/miclaus)
- [Sebastian Ulbel](https://github.com/suits-at)
- [Martina Hack](https://github.com/maelen92)
- [Patrick Eberhardt](https://github.com/eberhapa)
- [Gerald Strobl](https://github.com/gstrobl)
- [Katrin Rudisch](https://github.com/katrinrudisch)

## License

[The MIT License](https://opensource.org/licenses/MIT) (MIT)


_that's all folks_