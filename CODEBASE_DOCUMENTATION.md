# Laravel Marga - Complete Codebase Documentation

## Table of Contents
1. [Project Overview](#project-overview)
2. [Architecture](#architecture)
3. [Directory Structure](#directory-structure)
4. [Models](#models)
5. [Controllers](#controllers)
6. [Middleware](#middleware)
7. [Routes](#routes)
8. [Views](#views)
9. [Database](#database)
10. [Configuration](#configuration)
11. [Providers](#providers)

---

## Project Overview

**Laravel Marga** is a Laravel 10-based Content Management System (CMS) with Role-Based Access Control (RBAC). It allows administrators to create and manage dynamic webpages with customizable sections, images, and content.

### Key Features:
- **RBAC System**: Users, Roles, and Permissions management
- **Dynamic Webpage Builder**: Create landing pages, articles, and shop pages
- **Section-Based Content**: Modular sections (hero, grid, testimonials, etc.)
- **Image Management**: Upload and manage images for sections
- **Permission-Based Access**: Control access to features via permissions

---

## Architecture

The application follows the **MVC (Model-View-Controller)** pattern:

- **Models**: Database entities (User, Role, Permission, Webpage, WebpageSection)
- **Controllers**: Handle HTTP requests and business logic
- **Views**: Blade templates for rendering HTML
- **Middleware**: Authentication and authorization
- **Routes**: Define URL endpoints

---

## Directory Structure

```
laravel-marga/
├── app/                          # Application core
│   ├── Console/                  # Artisan commands
│   ├── Exceptions/               # Exception handlers
│   ├── Http/                     # HTTP layer
│   │   ├── Controllers/          # Request handlers
│   │   ├── Kernel.php            # HTTP middleware registry
│   │   └── Middleware/           # Custom middleware
│   ├── Models/                   # Eloquent models
│   └── Providers/                # Service providers
├── bootstrap/                    # Application bootstrap
├── config/                       # Configuration files
├── database/                     # Database files
│   ├── migrations/              # Database schema
│   └── seeders/                 # Database seeders
├── public/                       # Public assets
├── resources/                    # Views and assets
│   └── views/                   # Blade templates
├── routes/                       # Route definitions
├── storage/                      # File storage
└── vendor/                       # Composer dependencies
```

---

## Models

### 1. User Model (`app/Models/User.php`)

**Purpose**: Represents application users with authentication and role management.

**Properties**:
- `fillable`: `name`, `email`, `password`
- `hidden`: `password`, `remember_token`
- `casts`: `email_verified_at` (datetime), `password` (hashed)

**Relationships**:
- `roles()`: Many-to-many relationship with Role model via `user_roles` pivot table

**Methods**:

#### `hasRole($role)`
- **Purpose**: Check if user has a specific role
- **Parameters**: `$role` (string role name or Role model instance)
- **Returns**: Boolean
- **Logic**: Checks if user's roles collection contains the specified role

#### `hasPermission($permission)`
- **Purpose**: Check if user has a permission through any of their roles
- **Parameters**: `$permission` (string permission slug like "manage-webpages")
- **Returns**: Boolean
- **Logic**: Iterates through user's roles, checks if any role has the permission by slug

#### `assignRole($role)`
- **Purpose**: Assign a role to the user
- **Parameters**: `$role` (string role name or Role model)
- **Logic**: If string provided, resolves to Role model, then syncs without detaching

#### `removeRole($role)`
- **Purpose**: Remove a role from the user
- **Parameters**: `$role` (string role name or Role model)
- **Logic**: Detaches the role from user

---

### 2. Role Model (`app/Models/Role.php`)

**Purpose**: Represents user roles in the RBAC system.

**Properties**:
- `fillable`: `name`, `slug`, `description`

**Relationships**:
- `users()`: Many-to-many with User via `user_roles`
- `permissions()`: Many-to-many with Permission via `role_permissions`

**Methods**:

#### `hasPermission($permission)`
- **Purpose**: Check if role has a specific permission
- **Parameters**: `$permission` (string slug or Permission model)
- **Returns**: Boolean
- **Logic**: Checks permissions collection by slug (for strings) or ID (for models)

#### `assignPermission($permission)`
- **Purpose**: Assign permission to role
- **Parameters**: `$permission` (string slug or Permission model)
- **Logic**: Resolves slug to Permission if needed, syncs without detaching

#### `removePermission($permission)`
- **Purpose**: Remove permission from role
- **Parameters**: `$permission` (string slug or Permission model)
- **Logic**: Detaches permission from role

---

### 3. Permission Model (`app/Models/Permission.php`)

**Purpose**: Represents permissions that can be assigned to roles.

**Properties**:
- `fillable`: `name`, `slug`, `description`

**Relationships**:
- `roles()`: Many-to-many with Role via `role_permissions`

**Note**: Simple model with no custom methods, uses Eloquent relationships only.

---

### 4. Webpage Model (`app/Models/Webpage.php`)

**Purpose**: Represents a webpage/landing page in the system.

**Properties**:
- `fillable`: `title`, `slug`, `type`, `meta_description`, `meta_keywords`, `is_published`, `order`, `created_by`
- `casts`: `is_published` (boolean)

**Relationships**:
- `creator()`: Belongs to User (who created the webpage)
- `sections()`: Has many WebpageSection (ordered by `order` field)

**Methods**:

#### `setSlugAttribute($value)`
- **Purpose**: Mutator to auto-generate slug from title if not provided
- **Logic**: If slug is empty, generates from title using `Str::slug()`

#### `getUrlAttribute()`
- **Purpose**: Accessor to get the public URL of the webpage
- **Returns**: Full URL like `/pages/{slug}`
- **Usage**: Access as `$webpage->url`

---

### 5. WebpageSection Model (`app/Models/WebpageSection.php`)

**Purpose**: Represents a content section within a webpage.

**Properties**:
- `fillable`: `webpage_id`, `type`, `title`, `content`, `image_path`, `button_text`, `button_link`, `button_style`, `order`, `metadata`
- `casts`: `metadata` (array) - stored as JSON, automatically cast to array

**Relationships**:
- `webpage()`: Belongs to Webpage

**Methods**:

#### `getImageUrlAttribute()`
- **Purpose**: Accessor to get full URL for section image
- **Returns**: Full asset URL or null if no image
- **Usage**: Access as `$section->image_url`

**Section Types**:
- `hero`: Hero section with title, subtitle, button, large image
- `heading`: Simple heading section
- `features_grid`: 3-column grid layout
- `grid_item`: Individual card for 3-column grid
- `text_image_split`: Text on left, image on right
- `two_column`: 2-column layout
- `column_item`: Individual card for 2-column layout
- `testimonials_grid`: 3 testimonial cards
- `footer_cta`: Footer call-to-action with buttons
- `content`: Content block
- `image`: Image-only section
- `button`: Button section
- `cta`: Call-to-action section

---

## Controllers

### 1. WebpageController (`app/Http/Controllers/WebpageController.php`)

**Purpose**: Handles all webpage and section management operations.

**Middleware**: Requires authentication except for `show()` method (public viewing)

**Methods**:

#### `index()`
- **Purpose**: List all webpages
- **Returns**: View with webpages list
- **Logic**: Loads webpages with creator relationship, ordered by `order` field

#### `create()`
- **Purpose**: Show form to create new webpage
- **Returns**: Create webpage view

#### `store(Request $request)`
- **Purpose**: Save new webpage
- **Validation**: Title (required), slug (unique), type (landing/article/shop), meta fields
- **Logic**: 
  - Auto-generates slug if not provided
  - Sets `created_by` to current user
  - Handles `is_published` checkbox
- **Returns**: Redirects to edit page

#### `show(Webpage $webpage)`
- **Purpose**: Display public webpage
- **Access**: Public (no auth required)
- **Logic**: 
  - Checks if published (404 if not published and not logged in)
  - Loads sections relationship
- **Returns**: Public webpage view

#### `edit(Webpage $webpage)`
- **Purpose**: Show edit form for webpage
- **Returns**: Edit view with webpage and sections loaded

#### `update(Request $request, Webpage $webpage)`
- **Purpose**: Update webpage details
- **Validation**: Same as store, but slug unique except current webpage
- **Returns**: Redirects to index with success message

#### `destroy(Webpage $webpage)`
- **Purpose**: Delete webpage
- **Logic**: 
  - Deletes all associated section images from storage
  - Deletes webpage
- **Returns**: Redirects to index

#### `addSection(Request $request, Webpage $webpage)`
- **Purpose**: Add new section to webpage
- **Validation**: 
  - Type (required)
  - Image files (max 5MB)
  - Text fields (max 255 chars)
  - Testimonial fields (for testimonials_grid type)
- **Logic**:
  1. Handles image uploads (stores in `storage/app/public/webpages`)
  2. Handles background image upload
  3. Calculates order (max order + 1)
  4. Builds metadata array:
     - Subtitle, alignment, colors
     - Background image path
     - Secondary button data
     - Text blocks (for text_image_split: 3 subheading/body pairs)
     - Testimonials (for testimonials_grid: 3 testimonials with image, name, description, text)
  5. Creates WebpageSection record
- **Returns**: Redirects back with success message

#### `updateSection(Request $request, WebpageSection $section)`
- **Purpose**: Update existing section
- **Validation**: Same as addSection, plus `remove_image` and `remove_background_image` flags
- **Logic**:
  1. Handles image replacement (deletes old, stores new)
  2. Handles image removal (if checkbox checked)
  3. Updates metadata (preserves existing, updates changed fields)
  4. Handles testimonial image updates (preserves existing if not re-uploaded)
  5. Cleans up deleted testimonial images
- **Returns**: Redirects back with success

#### `deleteSection(WebpageSection $section)`
- **Purpose**: Delete section
- **Logic**: Deletes associated image file, then deletes section record
- **Returns**: Redirects back

#### `getSection(WebpageSection $section)`
- **Purpose**: Get section data as JSON (for AJAX requests)
- **Returns**: JSON response with section data including metadata

#### `reorderSections(Request $request, Webpage $webpage)`
- **Purpose**: Reorder sections via drag-and-drop
- **Validation**: Array of section IDs
- **Logic**: Updates `order` field for each section based on array position
- **Returns**: JSON success response

---

### 2. DashboardController (`app/Http/Controllers/DashboardController.php`)

**Purpose**: Handles dashboard display.

**Middleware**: Requires authentication

**Methods**:

#### `index()`
- **Purpose**: Display dashboard
- **Returns**: Dashboard view

---

### 3. LoginController (`app/Http/Controllers/Auth/LoginController.php`)

**Purpose**: Handles user authentication.

**Methods**:

#### `showLoginForm()`
- **Purpose**: Display login form
- **Returns**: Login view

#### `login(Request $request)`
- **Purpose**: Authenticate user
- **Validation**: Email (required, valid email), password (required)
- **Logic**:
  - Attempts authentication with credentials
  - If successful: regenerates session, redirects to intended URL or dashboard
  - If failed: throws validation exception
- **Returns**: Redirect to dashboard or validation error

#### `logout(Request $request)`
- **Purpose**: Logout user
- **Logic**: Logs out, invalidates session, regenerates CSRF token
- **Returns**: Redirect to home

---

### 4. UserController (`app/Http/Controllers/Admin/UserController.php`)

**Purpose**: CRUD operations for users (admin only).

**Middleware**: Requires authentication and `role:admin`

**Methods**:

#### `index()`
- **Purpose**: List all users with their roles
- **Returns**: Users index view

#### `create()`
- **Purpose**: Show create user form
- **Returns**: Create view with all roles

#### `store(Request $request)`
- **Purpose**: Create new user
- **Validation**: Name, email (unique), password (min 8, confirmed), roles (array)
- **Logic**: Creates user, hashes password, syncs roles
- **Returns**: Redirects to index

#### `edit(User $user)`
- **Purpose**: Show edit form
- **Returns**: Edit view with user and roles

#### `update(Request $request, User $user)`
- **Purpose**: Update user
- **Validation**: Name, email (unique except current), password (optional, min 8), roles
- **Logic**: Updates user, updates password if provided, syncs roles
- **Returns**: Redirects to index

#### `destroy(User $user)`
- **Purpose**: Delete user
- **Returns**: Redirects to index

---

### 5. RoleController (`app/Http/Controllers/Admin/RoleController.php`)

**Purpose**: CRUD operations for roles (admin only).

**Middleware**: Requires authentication and `role:admin`

**Methods**: Standard CRUD (index, create, store, edit, update, destroy)
- **Key Logic**: Syncs permissions when creating/updating roles

---

### 6. PermissionController (`app/Http/Controllers/Admin/PermissionController.php`)

**Purpose**: CRUD operations for permissions (admin only).

**Middleware**: Requires authentication and `role:admin`

**Methods**: Standard CRUD (index, create, store, edit, update, destroy)

---

## Middleware

### 1. PermissionMiddleware (`app/Http/Middleware/PermissionMiddleware.php`)

**Purpose**: Check if authenticated user has required permission(s).

**Usage**: `Route::middleware('permission:manage-webpages')`

**Logic**:
1. Checks if user is authenticated (redirects to login if not)
2. Iterates through provided permissions
3. If user has any of the permissions, allows request
4. If none match, returns 403 Forbidden

**Example**: `permission:manage-webpages,edit-users` (user needs at least one)

---

### 2. RoleMiddleware (`app/Http/Middleware/RoleMiddleware.php`)

**Purpose**: Check if user has specific role.

**Usage**: `Route::middleware('role:admin')`

**Logic**: Similar to PermissionMiddleware but checks roles instead

---

### 3. Other Middleware

Standard Laravel middleware:
- `Authenticate`: Redirects unauthenticated users to login
- `EncryptCookies`: Encrypts cookies
- `VerifyCsrfToken`: Validates CSRF tokens
- `TrimStrings`: Trims string inputs
- `TrustProxies`: Handles proxy headers
- `PreventRequestsDuringMaintenance`: Blocks requests during maintenance
- `RedirectIfAuthenticated`: Redirects authenticated users away from auth pages
- `ValidateSignature`: Validates signed URLs

---

## Routes

### File: `routes/web.php`

**Public Routes**:
- `GET /`: Redirects to home webpage if exists, otherwise welcome page
- `GET /login`: Show login form
- `POST /login`: Process login
- `POST /logout`: Logout user
- `GET /pages/{slug}`: Public webpage view (route model binding by slug)

**Authenticated Routes** (require login):
- `GET /dashboard`: Dashboard page

**Admin Routes** (require `role:admin`):
- Resource routes for `/admin/users`
- Resource routes for `/admin/roles`
- Resource routes for `/admin/permissions`

**Webpage Management Routes** (require `permission:manage-webpages`):
- Resource routes for `/webpages` (index, create, store, show, edit, update, destroy)
- `GET /webpages/{webpage}/sections`: Redirects to edit page
- `POST /webpages/{webpage}/sections`: Add section
- `GET /webpages/sections/{section}`: Get section JSON
- `PUT /webpages/sections/{section}`: Update section
- `DELETE /webpages/sections/{section}`: Delete section
- `POST /webpages/{webpage}/reorder`: Reorder sections

---

## Views

### Layout Files

#### `resources/views/layouts/app.blade.php`
- **Purpose**: Main application layout
- **Features**: 
  - Bootstrap 5 styling
  - Navigation bar with conditional menu items
  - Shows "Webpages" link if user has `manage-webpages` permission
  - Shows "Admin" dropdown if user has `admin` role
  - Flash message display (success/error)

### Webpage Views

#### `resources/views/webpages/index.blade.php`
- **Purpose**: List all webpages
- **Features**: Table with title, type, published status, actions (edit, delete)

#### `resources/views/webpages/create.blade.php`
- **Purpose**: Create new webpage form
- **Fields**: Title, slug, type, meta description, meta keywords, published checkbox

#### `resources/views/webpages/edit.blade.php`
- **Purpose**: Edit webpage and manage sections
- **Features**:
  - Webpage details form (left column)
  - Sections management panel with:
    - List of existing sections
    - "Add Section" button (opens modal)
    - Edit/Delete buttons for each section
  - Preview link (right column)
  - **Add Section Modal**: Form with all section fields, conditionally shows:
    - Extra text blocks (for `text_image_split` type)
    - Testimonials fields (for `testimonials_grid` type)
  - **Edit Section Modal**: Same as add, but pre-populated with section data
  - JavaScript: Toggles visibility of conditional fields based on section type

#### `resources/views/webpages/show.blade.php`
- **Purpose**: Public webpage display
- **Features**:
  - Navigation header with login button
  - Includes appropriate type template (landing/article/shop)
  - Footer with CTA sections and links
  - Custom CSS for Inter font and styling

### Section Type Templates

#### `resources/views/webpages/types/landing.blade.php`
- **Purpose**: Render landing page sections
- **Section Types Handled**:
  1. **Hero**: Large title, subtitle, content, button, full-width image
  2. **Features Grid**: 3-column grid with child `grid_item` sections
  3. **Text + Image Split**: Title, 3 text blocks, buttons on left; image on right
  4. **2-Column Layout**: 2 columns with child `column_item` sections
  5. **Testimonials Grid**: 3 testimonial cards from metadata
  6. **Footer CTA**: Buttons section
  7. **Content Block**: Simple text content
  8. **Image**: Image-only section
  9. **Button**: Button section
  10. **CTA**: Call-to-action section

**Rendering Logic**:
- Loops through sections ordered by `order` field
- Extracts metadata (alignment, colors, background image, subtitle)
- Applies dynamic styling based on metadata
- Renders appropriate HTML for each section type

---

## Database

### Migrations

#### User & Auth Tables
- `2014_10_12_000000_create_users_table.php`: Users table
- `2014_10_12_100000_create_password_reset_tokens_table.php`: Password reset tokens
- `2014_10_12_200000_create_sessions_table.php`: Session storage

#### RBAC Tables
- `2024_01_01_000001_create_roles_table.php`: Roles (id, name, slug, description, timestamps)
- `2024_01_01_000002_create_permissions_table.php`: Permissions (id, name, slug, description, timestamps)
- `2024_01_01_000003_create_user_roles_table.php`: User-Role pivot (user_id, role_id)
- `2024_01_01_000004_create_role_permissions_table.php`: Role-Permission pivot (role_id, permission_id)

#### Cache Table
- `2024_01_01_000005_create_cache_table.php`: Cache storage (key, value, expiration)

#### Webpage Tables
- `2024_01_02_000001_create_webpages_table.php`: 
  - Columns: id, title, slug (unique), type, meta_description, meta_keywords, is_published, order, created_by, timestamps
- `2024_01_02_000002_create_webpage_sections_table.php`:
  - Columns: id, webpage_id (foreign key), type, title, content (text), image_path, button_text, button_link, button_style, order, metadata (json), timestamps

### Seeders

#### `DatabaseSeeder.php`
- Calls: `RolePermissionSeeder`, `WebpageSeeder`

#### `RolePermissionSeeder.php`
- Creates default roles: Administrator
- Creates default permissions: Manage Users, Manage Roles, Manage Permissions, Manage Webpages
- Assigns all permissions to Administrator role
- Creates admin user: admin@example.com / password

#### `WebpageSeeder.php`
- Creates default "Home" landing page (slug: "home", published: true)

---

## Configuration

### Key Config Files

#### `config/app.php`
- Application name, environment, debug mode
- Service providers (includes `RouteServiceProvider`)
- Timezone, locale settings

#### `config/cache.php`
- Default cache driver: `file` (changed from `database` to avoid cache table issues)

#### `config/filesystems.php`
- Defines storage disks
- `public` disk: `storage/app/public`
- Storage link: `public/storage` → `storage/app/public`

#### `config/database.php`
- Database connection settings (MySQL/MariaDB)
- Connection: `laravel_marga`

---

## Providers

### AppServiceProvider (`app/Providers/AppServiceProvider.php`)

**Purpose**: Application service provider for bootstrapping.

**Methods**:

#### `boot()`
- **Purpose**: Bootstrap application services
- **Logic**: Sets default string length to 191 for migrations (fixes MySQL key length issues with utf8mb4)

### RouteServiceProvider (`app/Providers/RouteServiceProvider.php`)

**Purpose**: Loads route files.

**Logic**: Loads `routes/web.php` and `routes/api.php`

---

## Key Features Explained

### 1. RBAC System Flow

1. **User** has many **Roles** (via `user_roles` pivot)
2. **Role** has many **Permissions** (via `role_permissions` pivot)
3. To check permission: `$user->hasPermission('manage-webpages')`
   - Iterates through user's roles
   - Checks if any role has permission with matching slug
4. Middleware: `permission:manage-webpages` checks before allowing route access

### 2. Section Management Flow

1. **Create Webpage**: User creates webpage with type (landing/article/shop)
2. **Add Sections**: User adds sections to webpage via modal
3. **Section Types**: Each section has a type that determines rendering
4. **Metadata**: Additional data stored as JSON (colors, alignment, testimonials, etc.)
5. **Ordering**: Sections have `order` field for display sequence
6. **Rendering**: Template loops through sections, renders based on type

### 3. Image Management

1. **Upload**: Images stored in `storage/app/public/webpages/`
2. **Symlink**: `public/storage` symlinked to `storage/app/public` (via `php artisan storage:link`)
3. **Access**: Images accessed via `asset('storage/' . $image_path)`
4. **Deletion**: When section deleted, associated image file also deleted

### 4. Conditional Form Fields

- JavaScript in `edit.blade.php` shows/hides fields based on section type:
  - "Extra Text Blocks" only for `text_image_split`
  - "Testimonials" only for `testimonials_grid`
- Uses `d-none` Bootstrap class to toggle visibility

---

## File Summary

### Application Core
- **Models**: 5 (User, Role, Permission, Webpage, WebpageSection)
- **Controllers**: 7 (WebpageController, DashboardController, LoginController, UserController, RoleController, PermissionController, Controller)
- **Middleware**: 10 (8 Laravel standard + 2 custom: PermissionMiddleware, RoleMiddleware)
- **Providers**: 2 (AppServiceProvider, RouteServiceProvider)

### Views
- **Layouts**: 1 (app.blade.php)
- **Webpage Views**: 4 (index, create, edit, show)
- **Section Type Templates**: 3 (landing, article, shop)
- **Admin Views**: 9 (users, roles, permissions CRUD)
- **Auth Views**: 1 (login)
- **Other**: 1 (dashboard, welcome)

### Database
- **Migrations**: 10
- **Seeders**: 3

---

## How to Use This System

### For Administrators

1. **Login**: Use admin@example.com / password
2. **Manage Users**: Create users, assign roles
3. **Manage Roles**: Create roles, assign permissions
4. **Manage Permissions**: Create new permissions
5. **Manage Webpages**: 
   - Create webpage
   - Add sections (choose type, upload images, add content)
   - Edit sections (modify content, replace images)
   - Reorder sections (drag-and-drop)
   - Publish/unpublish webpage

### For Developers

1. **Add New Section Type**:
   - Add option to section type dropdown in `edit.blade.php`
   - Add rendering logic in `landing.blade.php`
   - Update validation in `WebpageController`

2. **Add New Permission**:
   - Create via admin panel or seeder
   - Use in routes: `Route::middleware('permission:new-permission')`
   - Check in views: `@if(auth()->user()->hasPermission('new-permission'))`

3. **Customize Styling**:
   - Modify CSS in `show.blade.php` or `landing.blade.php`
   - Update Bootstrap classes in templates

---

## Technical Notes

- **Laravel Version**: 10.10
- **PHP Requirement**: 8.1+
- **Database**: MySQL/MariaDB
- **Storage**: File-based (images in `storage/app/public`)
- **Cache**: File-based
- **Session**: Database-based
- **Authentication**: Laravel Sanctum (for API) + Session (for web)

---

## Security Features

1. **CSRF Protection**: All forms include CSRF tokens
2. **Authentication**: Required for all management routes
3. **Authorization**: Permission-based access control
4. **Password Hashing**: Bcrypt via Laravel's Hash facade
5. **SQL Injection Protection**: Eloquent ORM with parameter binding
6. **XSS Protection**: Blade templating auto-escapes output
7. **File Upload Validation**: Image type and size limits (5MB max)

---

This documentation covers all major components of the Laravel Marga codebase. For specific implementation details, refer to the source code comments in each file.

