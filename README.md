# Approval Workflow System

## Overview

This project is a simple Approval Workflow System built with Laravel.

The system allows users to:
- Submit requests
- Route requests through a hierarchical approval process
- Approve or reject requests based on assigned approval levels
- Manage departments and department-specific approval hierarchies

The application supports sequential approval flows where:
- Requests start from the lowest approval level
- Approval progresses level by level
- Any rejection immediately terminates the workflow

---

# Features

## Authentication
- Laravel Passport authentication
- Login and logout endpoints
- Token-based API authentication

## User Management
- Admin can create users
- Users belong to departments
- Role-based structure (`admin`, `staff`)

## Department Management
- Create departments
- Update departments
- Delete departments
- Prevent deletion of departments assigned to users

## Approval Hierarchy Management
- Department-specific approval hierarchies
- Multi-level approver setup
- Ordered approval levels

## Request Workflow
- Submit requests
- Track request status
- Sequential approval flow
- Request rejection handling
- Approval audit trail

---

# Technologies Used

- PHP 8.3
- Laravel
- MySQL
- Docker
- Laravel Passport

---

# Database Design

The system uses the following core tables:

- users
- departments
- approval_hierarchies
- approval_levels
- requests
- request_approvals

## Workflow Design

The architecture separates:
- Workflow configuration (`approval_levels`)
- Workflow execution/audit (`request_approvals`)

This ensures approval history is preserved even if approval hierarchies change later.

---

# ERDs

The project includes:
- Initial Approval Workflow ERD
- Department-Based Approval Workflow ERD
- Workflow Process Diagram

These can be found in the `/docs` directory.

---

# API Documentation

A Postman collection is included in the `/docs` directory.

Import the collection into Postman to test all endpoints.

---

# Assumptions

The following assumptions were made during implementation:

- Only admins can create users and departments
- Each department has one approval hierarchy
- Requests move sequentially through approval levels
- A rejection immediately terminates the workflow
- Users can only view their own requests
- Approvers can only act on approvals assigned to them

---

# Setup Guide

## Local Setup (Without Docker)

### 1. Clone Repository

```bash
git clone <repository_url>
cd approval-workflow

composer install

php artisan key:generate

php artisan migrate --seed

php artisan passport:install

php artisan serve

Application will be available at:

http://127.0.0.1:8000

Docker Setup
Prerequisites

Install:

Docker Desktop

Start Containers
docker compose up -d
Enter Application Container
docker exec -it approval-workflow-app bash
Install Dependencies
composer install
Configure Environment
cp .env.example .env

Update database credentials:

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=approval_workflow
DB_USERNAME=root
DB_PASSWORD=root

Generate Key
php artisan key:generate
Run Migrations
php artisan migrate
Install Passport
php artisan passport:install

Application will be available at:

http://localhost:8000