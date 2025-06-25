# Laravel Skill Test

## 1. Overview

Implement RESTful routes for a Post model using Laravel, with support for drafts, scheduled publishing, and user-authenticated operations.

## 2. Workflow

1. Set a deadline and let us know. This deadline will depend on your schedule.
2. Clone this repository and set up the environment.
3. Change the remote repository to your public repository (do not delete the commit history).
4. Implement the required features according to the requirements below.
5. Push your changes to your public repository.

## 3. Specifications

- **Drafts and Scheduling**: Posts can be saved as drafts or scheduled for future publishing.
- **Scheduled Posts**: Scheduled posts should be published automatically when the publish date comes.
- **Authentication**: Use Laravel’s built-in session and cookie-based authentication services.

## 4. Requirements

### 4-1. General

- Implement Laravel best practices.
- For team development, commit with an appropriate commit size and write suitable commit messages.
- View file implementations are NOT required. The responses should be JSON or redirects.

### 4-2. `posts.index` route

- Retrieve a paginated list (20 per page) of active posts.
- Include the user data associated with each post.
- Drafts or scheduled posts should not be included.
- Response must be in JSON.

### 4-3. `posts.create` route

- You may skip implementing this route or return the string `posts.create`.

### 4-4. `posts.store` route

- Only authenticated users can create new posts.
- Validate submitted data before creating the post.

### 4-5. `posts.show` route

- Retrieve a single post.
- Response must be in JSON.
- Return 404 if the post is draft or scheduled.

### 4-6. `posts.edit` route

- You may skip implementing this route or return the string `posts.edit`.

### 4-7. `posts.update` route

- Only the post's author can update the post.
- Validate submitted data before updating the post.

### 4-8. `posts.destroy` route

- Only the post's author can delete the post.

### 4-9. Testing
- Write feature (HTTP) tests for all posts routes to verify expected behavior, including both successful and failure scenarios.

## 5. Hints

1. The correct implementation should follow Laravel 12’s official documentation (https://laravel.com/docs/12.x). Using outdated or deprecated syntax may be considered incorrect.
2. You can use any references or AI tools, such as Laracasts, Stack Overflow, ChatGPT, Copilot, Cursor, and Devin. However, don't forget to review the official documentation and your code carefully.
3. The `posts` table is already defined in the migration file. Refer to its fields to determine how to structure submitted data and how to identify whether a post is active, a draft, or scheduled.
4. Although these routes behave like an API, you may use Laravel’s built-in cookie-based authentication instead of token-based systems such as Sanctum or Passport.

### Recommended environment

- PHP 8.3
- Node v22.15.0
- Database: SQLite
- Server: Built-in development server

### Database Seeding

Seeders create sample data of User and Post.

```
php artisan db:seed
```
