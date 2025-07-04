# Job Board App

A modern, full-stack **Job Board platform** built with **Laravel 12** and **Vue 3 + TypeScript**, designed for rapid job posting, moderation, and real-time updates.

---

##  Live Demo
- **Frontend**: [job-board.rjmsalamida.site](https://job-board.rjmsalamida.site/) 
- **Mail Server**: [52.65.54.20](http://52.65.54.20/) *(hosted on AWS EC2 for demonstration purposes)*
- **API Docs (OpenAPI)**: [Swagger UI](https://api-jb.rjmsalamida.site/docs?api-docs.json)

---

## Features

- Post jobs via a responsive web UI
- Email moderation workflow for first-time users
- Real-time UI updates using Laravel Echo + Pusher
- Secure signed moderation links
- Fully tested with feature tests
- Follows **PSR-12**, **CLEAN CODE**, and **S.O.L.I.D. principles**

---

## Architecture

### Tech Stack

| Layer       | Technology                    |
|------------|-------------------------------|
| Frontend    | Vue 3 + TypeScript + Vite     |
| Backend     | Laravel 12 (API only)         |
| Realtime    | Laravel Echo + Pusher         |
| Testing     | PHPUnit, Laravel Test Helpers |
| Email       | Maildev or SMTP (configurable)|
| API Docs    | Swagger via OpenAPI spec      |

---

## Docker Setup

This project is containerized using Docker and Docker Compose. Below are the key services:

### Services

| Service     | Description                          | Port     |
|-------------|--------------------------------------|----------|
| `mysql`     | MySQL 8 for database                 | 3306     |
| `maildev`   | Dev mail inbox for email preview     | 1080/1025|
| `backend`   | Laravel 12 PHP app                   | via Nginx|
| `nginx`     | Serves Laravel via `backend/public`  | 8010     |
| `frontend`  | Vue 3 + Vite Dev Server              | 5173     |
| `scheduler` | Runs `php artisan schedule:run` loop| -        |
| `queue`     | Runs `php artisan queue:work`       | -        |

### Getting Started

```bash
# Start everything (build containers and run services)
docker compose up --build

# Shut everything down and remove volumes
docker compose down -v --remove-orphans
```

### Access Points

| URL                        | Description            |
|----------------------------|------------------------|
| http://localhost:5173      | Frontend (Vue Dev)     |
| http://localhost:8010      | Backend (Laravel via Nginx) |
| http://localhost:1080      | MailDev inbox          |

> Ensure `.env.example` files are present in both `/backend` and `/frontend`, as `.env` will be auto-copied during container startup.

### Pusher Configuration Required
To enable real-time updates, you must create a [Pusher account](https://pusher.com) and configure the app credentials:

1. Register at [https://pusher.com](https://pusher.com)
2. Create a new app and select a cluster (e.g., `ap1`)
3. Copy the following keys:
   - `PUSHER_APP_ID`
   - `PUSHER_APP_KEY`
   - `PUSHER_APP_SECRET`
   - `PUSHER_APP_CLUSTER`
4. Add these to your `.env` file in `backend`:

```env
BROADCAST_DRIVER=pusher
PUSHER_APP_ID=your_app_id
PUSHER_APP_KEY=your_app_key
PUSHER_APP_SECRET=your_app_secret
PUSHER_APP_CLUSTER=your_cluster
```

---

### CLEAN CODE Practices
- Clear naming for variables, components, and services.
- Small, composable Vue components with single responsibilities.
- Code and logic are readable without deep nesting or overloading.
- Removed all unused and dead code.
---
### S.O.L.I.D Principles Applied
- **Single Responsibility:** Components and classes only do one thing.
- **Open/Closed:** Easily extend moderation logic without changing controller logic.
- **Liskov Substitution:** Mailer uses interface-based injection, supports mocks in testing.
- **Interface Segregation:** Controllers only depend on the job posting interface.
- **Dependency Inversion:** Laravel’s service container used for inversion of control.

---

### Code Quality
- PSR-12 Code Style
- SOLID & Clean Architecture
- Factory + Seeder driven tests
- Real-time queue + event broadcast logic
- Swagger API documentation

---

### Contributing
Pull requests and issues are welcome. Please follow the [conventional commits](https://www.conventionalcommits.org/) standard.