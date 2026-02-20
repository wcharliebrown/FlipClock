# FlipClock

A full-screen split-flap countdown display designed for a 1920×1080 TV or monitor. Shows countdowns to custom events alongside auto-generated US holidays, styled with the SplitFlapTV font and animated with [PQINA Tick](https://pqina.nl/).

![FlipClock preview](FlipClock.gif)

## Features

- Split-flap flip animation (days · hours · minutes · seconds)
- Loads personal events from `FlipClockEvents.json`
- Auto-generates major US holidays (New Year's Day, Easter, 4th of July, Thanksgiving, Christmas)
- Repeating events (e.g. birthdays) automatically advance to the next occurrence
- Pinned events appear at the top of the list
- Displays up to 9 events, sorted by nearest date
- Auto-refreshes the page every hour

## Running with Docker

```bash
docker compose up -d
```

Open [http://localhost:8080/FlipClock.html](http://localhost:8080/FlipClock.html).

The default admin password is `test123`. Change it by setting `FLIPCLOCK_PASSWORD` in `docker-compose.yml` before starting the container.

## Running without Docker

Serve the directory with any PHP-capable web server (Apache, Nginx + PHP-FPM, etc.) and create `~/.env` in the web server user's home directory:

```bash
# Generate a bcrypt hash of your chosen password
php -r "echo password_hash('yourpassword', PASSWORD_BCRYPT) . PHP_EOL;"
```

Then write the hash to `~/.env` (see `.env.example` for the expected format).

## Events file — `FlipClockEvents.json`

```json
[
    {
        "label": "Event Name",
        "targetDate": "2027-01-01T00:00:00-05:00",
        "pinned": false,
        "repeats": true
    }
]
```

| Field | Type | Description |
|---|---|---|
| `label` | string | Display name |
| `targetDate` | ISO 8601 string | Target date/time with timezone offset |
| `pinned` | boolean | `true` pins the event to the top |
| `repeats` | boolean | `true` auto-advances the year when the date has passed |

## Updating events via POST

`FlipClock.php` accepts a password-protected `POST` request to overwrite `FlipClockEvents.json`:

```
POST /FlipClock.php
Content-Type: application/x-www-form-urlencoded

password=yourpassword&events[0][label]=...&events[0][targetDate]=...&events[0][pinned]=1
```

On success it redirects back to `FlipClock.html`.

## File overview

| File | Purpose |
|---|---|
| `FlipClock.html` | Main display page |
| `FlipClock.php` | Password-protected endpoint to save events |
| `FlipClock_ajax.php` | Returns last-modified timestamp of `FlipClock.html` |
| `FlipClockEvents.json` | Persisted event list |
| `flip.min.css` / `flip.min.js` | PQINA Tick library |
| `fonts/` | SplitFlapTV font files |
| `Dockerfile` / `docker-compose.yml` | Docker setup |
| `.env.example` | Template for the `~/.env` password file |
