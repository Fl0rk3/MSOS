# MSOS — Student Organizer

**Work in progress — this project is not finished.**

MSOS (*Mobilny System Obsługi Studenta*, roughly translated as *Mobile Student Service System*) is a web application being developed to help students organize their academic activities in one place. Its intended focus is a personal workspace for courses, calendar events, homework, exams, notes, and useful study links.

The current codebase provides the foundation for user accounts, personal preferences, saved links, and the student dashboard. Course scheduling and calendar features are still under development.

## Project Vision

The goal is to connect a student's timetable with the information they need for each class. Instead of keeping a schedule, homework descriptions, and exam notes separately, MSOS is intended to bring them together around individual calendar events.

For example, a student could import a timetable, open a specific lecture or class, and attach a comment, homework assignment, or exam information to that event. This workflow is planned and is not yet implemented.

## Current Development Status

| Area | What is present in the codebase |
| --- | --- |
| User accounts | Registration and login handlers, password hashing and verification, and PHP session handling |
| Personal links | User-specific link storage, adding and removing links, duplicate checks, and asynchronous requests |
| User settings | Default preferences created during registration, settings rendering, and database updates |
| Student dashboard | Main workspace layout with areas for homework, exams, notes, useful URLs, and upcoming classes; academic event lists are currently placeholders |
| Courses | A course page, a user-linked subject entity, and an unfinished side panel for lectures, classes, and seminars |
| Interface text | A JSON-based localization layer with an English dictionary and shared messages |
| Code analysis | PHPStan configuration with Doctrine support and a GitHub Actions workflow for static analysis |

This is an evolving implementation rather than a completed student management system. The presence of a section or form in the interface does not mean its full workflow is available yet.

## Planned Features

- **Calendar import** — bring an existing timetable into the application as the basis for organizing academic activities.
- **Notes attached to specific events** — associate information with an individual class or calendar entry rather than only keeping general notes.
- **Event comments** — add personal remarks, preparation details, or follow-up information to a selected event.
- **Homework entries** — attach assignment descriptions and related notes to the relevant calendar event.
- **Exam entries** — associate exam information and preparation notes with a specific event.
- **Complete course scheduling** — finish the course workflow for lectures, classes, and seminars and connect it to the calendar.

Calendar import and event-linked notes are planned additions, not existing capabilities.

## Code and Architecture

The application combines server-rendered PHP pages with JavaScript interactions and a Doctrine-based persistence layer. Backend responsibilities are split across entities, query classes, providers, repositories, request handlers, and HTML renderers.

- **Entities** describe users, personal links, user settings, and subjects through PHP attributes and Doctrine mappings. Links, settings, and subjects are associated with individual users.
- **Query classes** implement operations such as registration, login, link creation and removal, and settings updates. Registration hashes passwords and creates default preferences for the new account.
- **Provider interfaces and Doctrine implementations** retrieve user-specific data for the pages, keeping data access separate from presentation.
- **Repositories** provide dedicated access to stored user settings.
- **Request handlers** process submitted data and connect browser requests to backend operations. These handlers live in the `Scraper/` directory; in this project, that name refers to form and request handling.
- **Renderers** turn retrieved links and settings into HTML for the student dashboard.
- **Browser scripts** control pop-ups, the course side panel, the clock, localized text, and asynchronous link and settings requests.

The course interface already includes controls for a date range and separate lecture, class, and seminar schedules. The schedule-day logic and complete persistence workflow are unfinished.

## Personalization and Interface

The settings model stores preferences for interface language, time format, color mode, calendar start and end hours, and the first day of the week. Some preferences establish the foundation for calendar functionality that is still being built.

The current options include English interface text and a dark color mode. The localization script reads JSON dictionaries and updates text and HTML attributes through `data-i18n` markers, providing a structure for extending the interface to additional languages later.

JavaScript handles link and settings requests through `XMLHttpRequest`. The settings flow reloads the page after a successful update, while notifications provide feedback about the operation.

## Tech Stack

| Technology | Role |
| --- | --- |
| PHP | Server-rendered pages, sessions, account handling, and backend operations |
| Doctrine ORM / DBAL | Entity mapping, relational data access, and persistence |
| MySQL | Application data storage |
| JavaScript | Interface behavior, localization, and asynchronous requests |
| HTML / Sass / CSS | Dashboard layout, forms, pop-ups, and course panels |
| Composer | Dependency management and PSR-4 autoloading |
| PHPStan with Doctrine extension | Static analysis configuration |
| GitHub Actions | Automated PHPStan workflow on pushes |

## Project Structure

```text
MSOS/
|-- public/                 # Entry page and student-facing PHP pages
|-- src/
|   |-- Config/             # Application bootstrap and database schema
|   |-- Ajax/               # Browser scripts for links and settings requests
|   `-- backend/
|       |-- Entity/         # Doctrine entity classes
|       |-- Query/          # Account, link, and settings operations
|       |-- Provider/       # Data provider interfaces and implementations
|       |-- Repository/     # User settings repository
|       |-- Scraper/        # Form and asynchronous request handlers
|       |-- ctrl/           # HTML renderers and default settings
|       `-- Constants/      # Settings keys and available options
|-- assets/
|   |-- Javascript/         # Interface behavior and localization
|   |-- Sass/               # Sass sources and compiled CSS
|   `-- languages/          # JSON interface dictionaries
|-- .github/                # Static analysis workflow
|-- composer.json           # Dependencies and autoloading
`-- phpstan.neon            # Static analysis configuration
```

## Development Focus

MSOS is a personal project for developing a student organizer while practicing object-oriented PHP, relational data modeling with Doctrine, separation of backend responsibilities, and interactive browser interfaces. Its next major step is connecting calendar events with the notes and academic tasks that belong to them.

## Author

**Florian Ficek** — [GitHub](https://github.com/Fl0rk3)
