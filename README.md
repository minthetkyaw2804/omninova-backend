# Legal Code API Documentation

A comprehensive REST API for managing web template services, blogs, projects, and company information.

## Base URL

```
http://localhost:8000/api
```

## Authentication

The API uses JWT (JSON Web Token) authentication for admin endpoints. Include the token in the Authorization header:

```
Authorization: Bearer {your_jwt_token}
```

## API Endpoints

### Customer Endpoints (Public)

These endpoints are accessible without authentication and are designed for customer-facing applications.

#### Company Information

**GET** `/customer/company-details`

Retrieves comprehensive company information including details, social media, and contact information.

**Response:**

```json
{
    "message": "Company details fetched successfully",
    "data": {
        "name": "Legal Code Company",
        "about_us": "Company description...",
        "vision": "Company vision...",
        "goal": "Company goals...",
        "logo_url": "https://example.com/logo.png",
        "founded_date": "01-01-2020",
        "address": "123 Legal Street, City, Country",
        "social_media": [
            {
                "platform_name": "Facebook",
                "page_url": "https://facebook.com/legalcode"
            },
            {
                "platform_name": "LinkedIn",
                "page_url": "https://linkedin.com/company/legalcode"
            }
        ],
        "contacts": [
            {
                "department": "General Inquiries",
                "phone_number": "+1234567890"
            },
            {
                "department": "Support",
                "phone_number": "+1234567891"
            }
        ]
    }
}
```

#### Blogs

**GET** `/customer/blogs`

Retrieves a list of all blogs with basic information.

**Response:**

```json
{
    "message": "All blogs fetched successfully",
    "data": [
        {
            "id": 1,
            "title": "Legal Tips for Startups",
            "created_by": "John Doe",
            "created_at": "15-08-2025",
            "thumbnail_image": "https://example.com/blog1-thumb.jpg"
        }
    ]
}
```

**GET** `/customer/blogs/{id}`

Retrieves detailed information about a specific blog.

**Response:**

```json
{
    "message": "Blog details fetched successfully",
    "data": {
        "id": 1,
        "title": "Legal Tips for Startups",
        "content": "Full blog content...",
        "created_by": "John Doe",
        "created_at": "15-08-2025",
        "images": [
            {
                "image_url": "https://example.com/blog1-image1.jpg"
            }
        ]
    }
}
```

#### Projects

**GET** `/customer/project-types`

Retrieves all available project types.

**Response:**

```json
{
    "message": "All project types fetched successfully",
    "data": [
        {
            "id": 1,
            "type_name": "Corporate Law",
            "description": "Legal services for corporations"
        }
    ]
}
```

**GET** `/customer/projects`

Retrieves a list of all projects with basic information.

**Response:**

```json
{
    "message": "All projects fetched successfully",
    "data": [
        {
            "id": 1,
            "name": "Startup Legal Consultation",
            "project_type": "Corporate Law",
            "description": "Comprehensive legal consultation for startups",
            "thumbnail_url": "https://example.com/project1-thumb.jpg"
        }
    ]
}
```

**GET** `/customer/projects/{id}`

Retrieves detailed information about a specific project.

**Response:**

```json
{
    "message": "Project details fetched successfully",
    "data": {
        "id": 1,
        "name": "Startup Legal Consultation",
        "project_type": "Corporate Law",
        "description": "Comprehensive legal consultation for startups",
        "thumbnail_url": "https://example.com/project1-thumb.jpg",
        "features": [
            {
                "feature_name": "Business Registration",
                "description": "Complete business registration process",
                "images": [
                    {
                        "image_url": "https://example.com/feature1-image1.jpg"
                    }
                ]
            }
        ]
    }
}
```

### Admin Endpoints (Protected)

These endpoints require JWT authentication and are designed for administrative purposes.

#### Authentication

**POST** `/admin/register`

Register a new admin user.

**Request Body:**

```json
{
    "name": "Admin User",
    "email": "admin@example.com",
    "password": "password123",
    "password_confirmation": "password123",
    "phone_number": "+1234567890",
    "address": "123 Admin Street, City, Country"
}
```

**Response:**

```json
{
    "message": "User created successfully.",
    "data": {
        "id": 1,
        "name": "Admin User",
        "email": "admin@example.com",
        "phone_number": "+1234567890",
        "address": "123 Admin Street, City, Country"
    }
}
```

**POST** `/admin/login`

Authenticate and receive JWT token.

**Request Body:**

```json
{
    "email": "admin@example.com",
    "password": "password123"
}
```

**Response:**

```json
{
    "message": "User login successful.",
    "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..."
}
```

#### User Management (Protected)

**GET** `/admin/profile`

Get authenticated user's profile.

**Headers:**

```
Authorization: Bearer {jwt_token}
```

**Response:**

```json
{
    "message": "User profile fetched successfully.",
    "data": {
        "id": 1,
        "name": "Admin User",
        "email": "admin@example.com",
        "phone_number": "+1234567890",
        "address": "123 Admin Street, City, Country"
    }
}
```

**PUT** `/admin/edit-profile`

Update authenticated user's profile.

**Headers:**

```
Authorization: Bearer {jwt_token}
```

**Request Body:**

```json
{
    "name": "Updated Admin User",
    "email": "updated@example.com",
    "phone_number": "+1234567890",
    "address": "456 Updated Street, City, Country"
}
```

**POST** `/admin/change-password`

Change authenticated user's password.

**Headers:**

```
Authorization: Bearer {jwt_token}
```

**Request Body:**

```json
{
    "old_password": "currentpassword",
    "new_password": "newpassword123"
}
```

**GET** `/admin/logout`

Logout the authenticated user.

**Headers:**

```
Authorization: Bearer {jwt_token}
```

#### User Management (All Users)

**GET** `/admin/users`

Get all users (admin only).

**Headers:**

```
Authorization: Bearer {jwt_token}
```

**Response:**

```json
{
    "message": "Admins details fetched successfully.",
    "data": [
        {
            "id": 1,
            "name": "Admin User",
            "email": "admin@example.com",
            "phone_number": "+1234567890",
            "address": "123 Admin Street, City, Country"
        }
    ]
}
```

**GET** `/admin/users/{id}`

Get specific user details.

**Headers:**

```
Authorization: Bearer {jwt_token}
```

**PUT** `/admin/users/{id}`

Update specific user details.

**Headers:**

```
Authorization: Bearer {jwt_token}
```

**Request Body:**

```json
{
    "name": "Updated User",
    "email": "updated@example.com",
    "phone_number": "+1234567890",
    "address": "456 Updated Street, City, Country"
}
```

**POST** `/admin/users/{id}/change-password`

Change specific user's password.

**Headers:**

```
Authorization: Bearer {jwt_token}
```

**Request Body:**

```json
{
    "password": "newpassword123",
    "password_confirmation": "newpassword123"
}
```

**DELETE** `/admin/users/{id}`

Delete a specific user.

**Headers:**

```
Authorization: Bearer {jwt_token}
```

**Response:**

```json
{
    "message": "User deleted successfully."
}
```

#### Blog Management (Protected)

**GET** `/admin/blogs`

Get all blogs (admin view).

**Headers:**

```
Authorization: Bearer {jwt_token}
```

**Response:**

```json
{
    "message": "All blogs fetched successfully",
    "data": [
        {
            "id": 1,
            "title": "Legal Tips for Startups",
            "content": "Blog content...",
            "created_by": "John Doe",
            "created_at": "15-08-2025",
            "thumbnail_image": "https://example.com/blog1-thumb.jpg",
            "images": [
                {
                    "id": 1,
                    "image_url": "https://example.com/blog1-image1.jpg"
                }
            ]
        }
    ]
}
```

**GET** `/admin/blogs/{id}`

Get specific blog details (admin view).

**Headers:**

```
Authorization: Bearer {jwt_token}
```

**Response:**

```json
{
    "message": "Blog details fetched successfully",
    "data": {
        "id": 1,
        "title": "Legal Tips for Startups",
        "content": "Full blog content...",
        "created_by": "John Doe",
        "created_at": "15-08-2025",
        "thumbnail_image": "https://example.com/blog1-thumb.jpg",
        "images": [
            {
                "id": 1,
                "image_url": "https://example.com/blog1-image1.jpg"
            }
        ]
    }
}
```

**POST** `/admin/blogs`

Create a new blog.

**Headers:**

```
Authorization: Bearer {jwt_token}
Content-Type: multipart/form-data
```

**Request Body:**

```
title: "New Legal Blog"
content: "Blog content here..."
images[]: image_file1
images[]: image_file2
```

**Note:** Images are required when creating a blog.

**Response:**

```json
{
    "message": "Blog created successfully",
    "data": {
        "id": 2,
        "title": "New Legal Blog",
        "content": "Blog content here...",
        "created_by": "Admin User",
        "created_at": "15-08-2025"
    }
}
```

**PUT** `/admin/blogs/{id}`

Update a blog.

**Headers:**

```
Authorization: Bearer {jwt_token}
Content-Type: application/json
```

**Request Body:**

```json
{
    "title": "Updated Legal Blog",
    "content": "Updated blog content..."
}
```

**DELETE** `/admin/blogs/{id}`

Delete a blog.

**Headers:**

```
Authorization: Bearer {jwt_token}
```

**POST** `/admin/blogs/{id}/images`

Add images to a blog.

**Headers:**

```
Authorization: Bearer {jwt_token}
Content-Type: multipart/form-data
```

**Request Body:**

```
images[]: image_file1
images[]: image_file2
```

**DELETE** `/admin/blog-images/{id}`

Delete a blog image.

**Headers:**

```
Authorization: Bearer {jwt_token}
```

#### Project Management (Protected)

**GET** `/admin/projects`

Get all projects (admin view).

**Headers:**

```
Authorization: Bearer {jwt_token}
```

**Response:**

```json
{
    "message": "All projects fetched successfully",
    "data": [
        {
            "id": 1,
            "name": "Startup Legal Consultation",
            "project_type": "Corporate Law",
            "description": "Comprehensive legal consultation for startups",
            "thumbnail_url": "https://example.com/project1-thumb.jpg",
            "features": [
                {
                    "id": 1,
                    "feature_name": "Business Registration",
                    "description": "Complete business registration process"
                }
            ]
        }
    ]
}
```

**GET** `/admin/projects/{id}`

Get specific project details (admin view).

**Headers:**

```
Authorization: Bearer {jwt_token}
```

**Response:**

```json
{
    "message": "Project details fetched successfully",
    "data": {
        "id": 1,
        "name": "Startup Legal Consultation",
        "project_type": "Corporate Law",
        "description": "Comprehensive legal consultation for startups",
        "thumbnail_url": "https://example.com/project1-thumb.jpg",
        "features": [
            {
                "id": 1,
                "feature_name": "Business Registration",
                "description": "Complete business registration process",
                "images": [
                    {
                        "id": 1,
                        "image_url": "https://example.com/feature1-image1.jpg"
                    }
                ]
            }
        ]
    }
}
```

**POST** `/admin/projects`

Create a new project.

**Headers:**

```
Authorization: Bearer {jwt_token}
Content-Type: multipart/form-data
```

**Request Body:**

```
name: "New Legal Project"
project_type_id: 1
description: "Project description..."
thumbnail: image_file
```

**PUT** `/admin/projects/{id}/details`

Update project details.

**Headers:**

```
Authorization: Bearer {jwt_token}
Content-Type: application/json
```

**Request Body:**

```json
{
    "name": "Updated Legal Project",
    "project_type_id": 1,
    "description": "Updated project description..."
}
```

**POST** `/admin/projects/{id}/new-thumbnail`

Replace project thumbnail.

**Headers:**

```
Authorization: Bearer {jwt_token}
Content-Type: multipart/form-data
```

**Request Body:**

```
thumbnail: new_image_file
```

**POST** `/admin/projects/{id}/features`

Create project features.

**Headers:**

```
Authorization: Bearer {jwt_token}
Content-Type: application/json
```

**Request Body:**

```json
{
    "features": [
        {
            "feature_name": "Feature 1",
            "description": "Feature description..."
        }
    ]
}
```

**PUT** `/admin/project-features/{id}/details`

Edit project features.

**Headers:**

```
Authorization: Bearer {jwt_token}
Content-Type: application/json
```

**Request Body:**

```json
{
    "feature_name": "Updated Feature",
    "description": "Updated feature description..."
}
```

**POST** `/admin/project-features/{id}/images`

Add images to project features.

**Headers:**

```
Authorization: Bearer {jwt_token}
Content-Type: multipart/form-data
```

**Request Body:**

```
images[]: image_file1
images[]: image_file2
```

**DELETE** `/admin/project-feature-images/{id}`

Delete project feature images.

**Headers:**

```
Authorization: Bearer {jwt_token}
```

**DELETE** `/admin/projects/{id}`

Delete a project and all associated resources.

**Headers:**

```
Authorization: Bearer {jwt_token}
```

**Response:**

```json
{
    "message": "Project deleted successfully"
}
```

**DELETE** `/admin/project-features/{id}`

Delete a project feature.

**Headers:**

```
Authorization: Bearer {jwt_token}
```

**Response:**

```json
{
    "message": "Project feature deleted successfully"
}
```

#### Project Types Management (Protected)

**GET** `/admin/project-types`

Get all project types (admin view).

**Headers:**

```
Authorization: Bearer {jwt_token}
```

**Response:**

```json
{
    "message": "All project types fetched successfully",
    "data": [
        {
            "id": 1,
            "type_name": "Corporate Law",
            "description": "Legal services for corporations"
        }
    ]
}
```

**GET** `/admin/project-types/{id}`

Get specific project type details (admin view).

**Headers:**

```
Authorization: Bearer {jwt_token}
```

**Response:**

```json
{
    "message": "Project type details fetched successfully",
    "data": {
        "id": 1,
        "type_name": "Corporate Law",
        "description": "Legal services for corporations"
    }
}
```

**POST** `/admin/project-types`

Create a new project type.

**Headers:**

```
Authorization: Bearer {jwt_token}
Content-Type: application/json
```

**Request Body:**

```json
{
    "type_name": "New Project Type",
    "description": "Description of the new project type"
}
```

**PUT** `/admin/project-types/{id}`

Update a project type.

**Headers:**

```
Authorization: Bearer {jwt_token}
Content-Type: application/json
```

**Request Body:**

```json
{
    "type_name": "Updated Project Type",
    "description": "Updated description"
}
```

**DELETE** `/admin/project-types/{id}`

Delete a project type.

**Headers:**

```
Authorization: Bearer {jwt_token}
```

#### Company Management (Protected)

**GET** `/admin/company`

Get company details (admin view).

**Headers:**

```
Authorization: Bearer {jwt_token}
```

**Response:**

```json
{
    "message": "Company details fetched successfully",
    "data": {
        "id": 1,
        "name": "Legal Code Company",
        "description": "Company description...",
        "vision": "Company vision...",
        "goal": "Company goals...",
        "logo_url": "https://example.com/logo.png",
        "founded_date": "2020-01-01",
        "address": "123 Legal Street, City, Country",
        "created_user_id": 1,
        "updated_user_id": 1
    }
}
```

**POST** `/admin/company`

Add new company details.

**Headers:**

```
Authorization: Bearer {jwt_token}
Content-Type: multipart/form-data
```

**Request Body:**

```
name: "New Company Name"
description: "Company description..."
vision: "Company vision..."
goal: "Company goals..."
founded_date: "2020-01-01"
address: "123 Company Street, City, Country"
logo: image_file
```

**Response:**

```json
{
    "message": "Company details added successfully",
    "data": {
        "id": 1,
        "name": "New Company Name",
        "description": "Company description...",
        "vision": "Company vision...",
        "goal": "Company goals...",
        "logo_url": "https://example.com/logo.png",
        "founded_date": "2020-01-01",
        "address": "123 Company Street, City, Country"
    }
}
```

**PUT** `/admin/company`

Edit company details.

**Headers:**

```
Authorization: Bearer {jwt_token}
Content-Type: application/json
```

**Request Body:**

```json
{
    "name": "Updated Company Name",
    "description": "Updated company description...",
    "vision": "Updated company vision...",
    "goal": "Updated company goals...",
    "founded_date": "2020-01-01",
    "address": "456 Updated Street, City, Country"
}
```

**Response:**

```json
{
    "message": "Company details updated successfully"
}
```

**POST** `/admin/company/logo`

Replace company logo.

**Headers:**

```
Authorization: Bearer {jwt_token}
Content-Type: multipart/form-data
```

**Request Body:**

```
logo: new_logo_image_file
```

**Response:**

```json
{
    "message": "Company logo updated successfully"
}
```

#### Company Social Media Management (Protected)

**POST** `/admin/company/social-media`

Add new company social media.

**Headers:**

```
Authorization: Bearer {jwt_token}
Content-Type: application/json
```

**Request Body:**

```json
{
    "platform_name": "Facebook",
    "page_url": "https://facebook.com/company"
}
```

**Response:**

```json
{
    "message": "Company social media added successfully",
    "data": {
        "id": 1,
        "platform_name": "Facebook",
        "page_url": "https://facebook.com/company",
        "company_id": 1,
        "created_user_id": 1
    }
}
```

**PUT** `/admin/company/social-media/{id}`

Edit company social media.

**Headers:**

```
Authorization: Bearer {jwt_token}
Content-Type: application/json
```

**Request Body:**

```json
{
    "platform_name": "Updated Facebook",
    "page_url": "https://facebook.com/updated-company"
}
```

**Response:**

```json
{
    "message": "Company social media updated successfully"
}
```

**DELETE** `/admin/company/social-media/{id}`

Delete company social media.

**Headers:**

```
Authorization: Bearer {jwt_token}
```

**Response:**

```json
{
    "message": "Company social media deleted successfully"
}
```

#### Company Contacts Management (Protected)

**POST** `/admin/company/contacts`

Add new company contact.

**Headers:**

```
Authorization: Bearer {jwt_token}
Content-Type: application/json
```

**Request Body:**

```json
{
    "department": "General Inquiries",
    "phone_number": "+1234567890"
}
```

**Response:**

```json
{
    "message": "Company contact added successfully",
    "data": {
        "id": 1,
        "department": "General Inquiries",
        "phone_number": "+1234567890",
        "company_id": 1
    }
}
```

**PUT** `/admin/company/contacts/{id}`

Edit company contact.

**Headers:**

```
Authorization: Bearer {jwt_token}
Content-Type: application/json
```

**Request Body:**

```json
{
    "department": "Updated Department",
    "phone_number": "+1234567891"
}
```

**Response:**

```json
{
    "message": "Company contact updated successfully"
}
```

**DELETE** `/admin/company/contacts/{id}`

Delete company contact.

**Headers:**

```
Authorization: Bearer {jwt_token}
```

**Response:**

```json
{
    "message": "Company contact deleted successfully"
}
```

## Error Responses

The API returns standardized error responses:

### Validation Errors (422)

```json
{
    "message": "The given data was invalid.",
    "errors": {
        "email": ["The email field is required."],
        "password": ["The password field is required."]
    }
}
```

### Authentication Errors (401)

```json
{
    "message": "Incorrect login credentials."
}
```

### Not Found Errors (404)

```json
{
    "message": "Resource not found."
}
```

### Server Errors (500)

```json
{
    "message": "Operation failed.",
    "error": "Detailed error message"
}
```

## Usage Examples

### Frontend JavaScript Example

```javascript
// Customer endpoints (no authentication required)
async function getCompanyDetails() {
    const response = await fetch(
        "http://localhost:8000/api/customer/company-details"
    );
    const data = await response.json();
    return data;
}

async function getAllBlogs() {
    const response = await fetch("http://localhost:8000/api/customer/blogs");
    const data = await response.json();
    return data;
}

// Admin endpoints (authentication required)
async function loginAdmin(email, password) {
    const response = await fetch("http://localhost:8000/api/admin/login", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify({ email, password }),
    });
    const data = await response.json();
    return data;
}

async function getAdminProfile(token) {
    const response = await fetch("http://localhost:8000/api/admin/profile", {
        headers: {
            Authorization: `Bearer ${token}`,
            "Content-Type": "application/json",
        },
    });
    const data = await response.json();
    return data;
}

// Company Management Examples
async function getCompanyDetails(token) {
    const response = await fetch("http://localhost:8000/api/admin/company", {
        headers: {
            Authorization: `Bearer ${token}`,
            "Content-Type": "application/json",
        },
    });
    const data = await response.json();
    return data;
}

async function updateCompanyDetails(token, companyData) {
    const response = await fetch("http://localhost:8000/api/admin/company", {
        method: "PUT",
        headers: {
            Authorization: `Bearer ${token}`,
            "Content-Type": "application/json",
        },
        body: JSON.stringify(companyData),
    });
    const data = await response.json();
    return data;
}

async function addCompanySocialMedia(token, socialMediaData) {
    const response = await fetch(
        "http://localhost:8000/api/admin/company/social-media",
        {
            method: "POST",
            headers: {
                Authorization: `Bearer ${token}`,
                "Content-Type": "application/json",
            },
            body: JSON.stringify(socialMediaData),
        }
    );
    const data = await response.json();
    return data;
}

async function addCompanyContact(token, contactData) {
    const response = await fetch(
        "http://localhost:8000/api/admin/company/contacts",
        {
            method: "POST",
            headers: {
                Authorization: `Bearer ${token}`,
                "Content-Type": "application/json",
            },
            body: JSON.stringify(contactData),
        }
    );
    const data = await response.json();
    return data;
}
```

### cURL Examples

```bash
# Get company details
curl -X GET http://localhost:8000/api/customer/company-details

# Get all blogs
curl -X GET http://localhost:8000/api/customer/blogs

# Login as admin
curl -X POST http://localhost:8000/api/admin/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@example.com","password":"password123"}'

# Get admin profile (with token)
curl -X GET http://localhost:8000/api/admin/profile \
  -H "Authorization: Bearer YOUR_JWT_TOKEN"

# Get company details
curl -X GET http://localhost:8000/api/admin/company \
  -H "Authorization: Bearer YOUR_JWT_TOKEN"

# Update company details
curl -X PUT http://localhost:8000/api/admin/company \
  -H "Authorization: Bearer YOUR_JWT_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Updated Company Name",
    "description": "Updated description",
    "vision": "Updated vision",
    "goal": "Updated goal",
    "founded_date": "2020-01-01",
    "address": "Updated address"
  }'

# Add company social media
curl -X POST http://localhost:8000/api/admin/company/social-media \
  -H "Authorization: Bearer YOUR_JWT_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "platform_name": "Facebook",
    "page_url": "https://facebook.com/company"
  }'

# Add company contact
curl -X POST http://localhost:8000/api/admin/company/contacts \
  -H "Authorization: Bearer YOUR_JWT_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "department": "General Inquiries",
    "phone_number": "+1234567890"
  }'
```

## Development Setup

1. Clone the repository
2. Install dependencies: `composer install`
3. Copy `.env.example` to `.env` and configure your database
4. Generate application key: `php artisan key:generate`
5. Run migrations: `php artisan migrate`
6. Start the development server: `php artisan serve`

# omninova-backend
