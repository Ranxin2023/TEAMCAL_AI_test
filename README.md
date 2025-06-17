# Calendly Availability Fetcher

This project lets users enter a **Calendly event API URL** and view **available date & time slots** dynamically for the next 4 weeks.
## Table Of Contents
- [Techs](#techs)
- [Features](#features)
- [Installation](#installation)
- [Setup](#setup)
- [Usage](#usage)
- [Project Structure](#project-structure)

## Techs

- CodeIgniter 4 (PHP) – Server-side backend

- jQuery + Bootstrap – Frontend UI

- Calendly internal API (via browser request) – Data source

## Features
1. Dynamically builds Calendly API request with:
    - Smart `range_start` and `range_end` (today or future month +4 weeks)
    - Timezone support (`America/Los_Angeles`)
2. Fetch available slots for the next 4 weeks
3. Supports different users / event types by configuring variables
4. Uses CodeIgniter 4 (PHP) + jQuery frontend

## Installation
1. Install Composer
- Go to: [Compuser Official site](https://getcomposer.org/)
- Download the Windows Installer (`Composer-Setup.exe`) or use your OS package manager (brew/apt).
2. Verify Composer is installed
In terminal / CMD / PowerShell:
```bash
composer -V
```
3.  Create your CodeIgniter project(Skip this step since I alread build the codebase)

```bash
composer create-project codeigniter4/appstarter calendly-app

```
4. Run install
```bash
composer install
```
5. Run the app
```bash
php spark serve
```
 Open: `http://localhost:8080`

## SetUp
1. Clone the repo
```bash
git clone https://github.com/yourusername/calendly-availability-fetcher.git
```
2.  Set up your `CodeIgniter 4 environment`

3. Open `.env` and add:
```env
CALENDLY_EVENT_TYPE_ID='YOUR_EVENT_TYPE_ID'
CALENDLY_SCHEDULING_UUID='YOUR_SCHEDULING_UUID'
CALENDLY_TIMEZONE='America/Los_Angeles'
```
4. How to fetch `eventTypeId`, `schedulingLinkUuid`
- Go to your Calendly public link (e.g. `https://calendly.com/matt123/30min`)
- Open **DevTools** → **Network tab** → **XHR filter**
- Click on a date → Look for a request like:
```bash
/api/booking/event_types/xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx/calendar/range
```
-  Look at the URL params — you’ll see:
```ini
scheduling_link_uuid=xxxx-xxx-xxx
```
-  Copy both and set in `.env`

That is your **schedulingLinkUuid**
## Usage
1. Start your server
```bash
php spark serve
```
2. Open your browser at
`http://localhost:8080`

3. Paste a Calendly link and click Check Availability

4. See available slots for selected dates!

## Project Structure
```pgsql
/app/Controllers/CalendlyController.php  → Backend controller to fetch and process slots
/public/js/main.js                        → Frontend JS for form and dynamic UI
/public/index.php                         → Entry point for CodeIgniter
/app/Views/calendar_view.php              → Main view (if used)
```