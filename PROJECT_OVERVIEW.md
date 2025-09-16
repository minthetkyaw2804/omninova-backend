# Omninova API - Complete Project Documentation

## 🚀 Project Overview

**Omninova API** is a comprehensive Laravel-based REST API designed for managing web template services, company portfolios, blog content, and project showcases. The system provides both public customer endpoints and protected admin endpoints for complete content management.

## 🛠 Technology Stack

- **Framework**: Laravel 12.0
- **PHP Version**: 8.2+
- **Authentication**: JWT (JSON Web Tokens)
- **Database**: SQLite (configurable to MySQL/PostgreSQL)
- **File Handling**: Built-in Laravel file storage
- **Testing**: PHPUnit 11.5.3
- **Code Quality**: Laravel Pint

## 📋 System Requirements

- PHP 8.2 or higher
- Composer
- SQLite/MySQL/PostgreSQL
- Node.js (for asset compilation)

## 🏗 Database Architecture

### Core Models & Relationships

#### 1. User Model
```php
// Fields: id, name, email, password, phone_number, address
// Features: JWT Authentication, Soft Deletes
// Relationships: Creator/Updater for other models
```

#### 2. Company Model
```php
// Fields: id, name, description, vision, goal, logo_url, founded_date, address
// Relationships: 
// - belongsTo: creator (User), updater (User)
// - hasMany: companySocialMedias, companyContacts
```

#### 3. Blog Model
```php
// Fields: id, title, content, creator_user_id, updated_user_id
// Features: Soft Deletes
// Relationships:
// - belongsTo: creator (User), updater (User)
// - hasMany: blogImages (BlogImage)
```

#### 4. CompanyProject Model
```php
// Fields: id, name, project_type_id, description, demo_url, thumbnail_url
// Features: Soft Deletes
// Relationships:
// - belongsTo: creator (User), updater (User), projectType (ProjectType)
// - hasMany: projectFeatures (ProjectFeature)
```

#### 5. Supporting Models
- **BlogImage**: Stores blog images
- **CompanyContact**: Company contact information
- **CompanySocialMedia**: Social media links
- **ProjectType**: Categories for projects
- **ProjectFeature**: Individual project features
- **FeatureImage**: Images for project features

## 🔐 Authentication & Authorization

### JWT Authentication
- Token-based authentication using `php-open-source-saver/jwt-auth`
- Admin endpoints require Bearer token
- Automatic token expiration handling
- Login/logout functionality

### Security Features
- Password hashing (bcrypt)
- Input validation and sanitization
- CORS configuration available
- Protected route middleware

## 🌐 API Endpoints Structure

### Public Customer Endpoints (`/api/customer/`)
- **Company Details**: Get company information, contacts, social media
- **Blogs**: List blogs, get blog details with images
- **Projects**: List projects by type, get project details with features
- **No Authentication Required**

### Protected Admin Endpoints (`/api/admin/`)
- **Authentication**: Register, login, logout, profile management
- **User Management**: CRUD operations for all users
- **Blog Management**: Full CRUD with image uploads
- **Project Management**: Full CRUD with thumbnails and features
- **Project Types**: Manage project categories
- **Company Management**: Update company details, logo, contacts, social media
- **Requires JWT Token**

## 📁 File Upload System

### Supported File Operations
- **Blog Images**: Multiple images per blog post
- **Project Thumbnails**: Single thumbnail per project
- **Project Feature Images**: Multiple images per feature
- **Company Logo**: Single logo file
- **File Types**: Images (configurable formats)
- **Storage**: Laravel filesystem (local/cloud configurable)

## 🔧 Key Features

### Content Management
- **Rich Blog System**: Full WYSIWYG content with multiple images
- **Project Portfolio**: Categorized projects with features and galleries
- **Company Profile**: Complete company information management
- **User Management**: Multi-user admin system

### API Features
- **RESTful Design**: Standard HTTP methods and status codes
- **JSON Responses**: Consistent response format
- **Error Handling**: Standardized error responses
- **Data Validation**: Comprehensive input validation
- **Soft Deletes**: Data recovery capabilities

### Performance & Scalability
- **Database Optimization**: Proper indexing and relationships
- **Caching**: Configurable caching system
- **Queue System**: Background job processing
- **File Storage**: Flexible storage options

## 🚦 Development Commands

### Setup Commands
```bash
composer install                 # Install dependencies
cp .env.example .env            # Setup environment
php artisan key:generate        # Generate app key
php artisan migrate             # Run migrations
php artisan serve               # Start development server
```

### Development Tools
```bash
composer run dev                # Full development environment
composer run test              # Run test suite
./vendor/bin/pint              # Code formatting
```

## 📊 Database Schema Overview

### Users Table
- Authentication and profile information
- Soft deletes enabled
- JWT token management

### Companies Table
- Company information and branding
- Logo file storage
- Relationship tracking (creator/updater)

### Blogs Table
- Content management system
- Soft deletes for recovery
- Multi-image support via BlogImages

### Company Projects Table
- Project portfolio system
- Categorization via ProjectTypes
- Thumbnail and demo URL support
- Feature system via ProjectFeatures

### Supporting Tables
- **BlogImages**: Blog image storage
- **CompanyContacts**: Contact information
- **CompanySocialMedia**: Social media links
- **ProjectTypes**: Project categorization
- **ProjectFeatures**: Project feature descriptions
- **FeatureImages**: Feature image galleries

## 🎯 Frontend Integration Guidelines

### API Base URL
```
http://localhost:8000/api
```

### Authentication Flow
1. **Login**: POST `/admin/login` with credentials
2. **Store Token**: Save JWT token in localStorage
3. **API Calls**: Include `Authorization: Bearer {token}` header
4. **Handle Expiry**: Redirect to login on 401 responses

### Common Data Patterns

#### Customer Frontend Needs
```javascript
// Company information for about page
GET /customer/company-details

// Blog listing for news/blog section
GET /customer/blogs

// Individual blog posts
GET /customer/blogs/{id}

// Project categories for filtering
GET /customer/project-types

// Project portfolio
GET /customer/projects

// Individual project details
GET /customer/projects/{id}
```

#### Admin Dashboard Needs
```javascript
// Authentication
POST /admin/login
GET /admin/profile
GET /admin/logout

// Content Management
GET /admin/blogs
POST /admin/blogs (with FormData for images)
PUT /admin/blogs/{id}
DELETE /admin/blogs/{id}

// Project Management
GET /admin/projects
POST /admin/projects (with FormData for thumbnail)
PUT /admin/projects/{id}/details
DELETE /admin/projects/{id}

// Company Management
GET /admin/company
PUT /admin/company
POST /admin/company/logo (FormData)
```

### File Upload Handling
- Use `FormData` for file uploads
- Multiple files supported for images arrays
- Content-Type: `multipart/form-data` for file endpoints
- Content-Type: `application/json` for data-only endpoints

### Error Handling Patterns
```javascript
// Standard error response format
{
    "message": "Error description",
    "errors": {
        "field_name": ["Validation error message"]
    }
}

// HTTP Status Codes
200: Success
201: Created
422: Validation Error
401: Authentication Error
404: Not Found
500: Server Error
```

## 🧪 Testing Strategy

### Test Coverage
- Feature tests for API endpoints
- Unit tests for business logic
- Authentication testing
- File upload testing
- Database relationship testing

### Testing Commands
```bash
php artisan test                # Run all tests
php artisan test --coverage    # With coverage report
```

## 🔄 Development Workflow

### Git Workflow
- Main branch: `main`
- Feature branches for new development
- Comprehensive commit messages with co-authorship
- Recent focus: API parameter standardization and error handling

### Code Quality
- Laravel Pint for code formatting
- PHPUnit for testing
- Comprehensive validation rules
- Consistent naming conventions

## 📝 Configuration Notes

### Environment Variables
```env
APP_NAME=Omninova
APP_ENV=local
APP_DEBUG=true
DB_CONNECTION=sqlite
JWT_SECRET={generated_secret}
```

### Key Dependencies
```json
{
    "laravel/framework": "^12.0",
    "laravel/sanctum": "^4.0",
    "php-open-source-saver/jwt-auth": "^2.8",
    "doctrine/dbal": "^4.3"
}
```

## 🚀 Deployment Considerations

### Production Setup
1. Set `APP_ENV=production` and `APP_DEBUG=false`
2. Configure proper database connection
3. Set up file storage (AWS S3 or similar)
4. Configure caching and queues
5. Set up SSL certificates
6. Configure CORS for frontend domains

### Performance Optimization
- Enable database query caching
- Use Redis for session/cache storage
- Implement API rate limiting
- Optimize image storage and delivery
- Enable gzip compression

## 📋 Frontend Form Requirements & Data Models

### Authentication Forms

#### Login Form
```javascript
// Required Fields
{
    email: "string (required|email)",
    password: "string (required|min:6)"
}

// Response Data
{
    message: "User login successful.",
    token: "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..."
}
```

#### Register Form  
```javascript
// Required Fields
{
    name: "string (required|max:255)",
    email: "string (required|email|unique)",
    password: "string (required|min:6)",
    password_confirmation: "string (required|same:password)",
    phone_number: "string (required)",
    address: "string (required)"
}
```

#### Change Password Form
```javascript
// Required Fields
{
    old_password: "string (required)",
    new_password: "string (required|min:6)"
}
```

### User Management Forms

#### Edit Profile Form
```javascript
// Required Fields
{
    name: "string (required|max:255)",
    email: "string (required|email)",
    phone_number: "string (required)",
    address: "string (required)"
}

// Response Data Structure
{
    message: "User profile fetched successfully.",
    data: {
        id: 1,
        name: "Admin User",
        email: "admin@example.com", 
        phone_number: "+1234567890",
        address: "123 Admin Street, City, Country"
    }
}
```

### Company Management Forms

#### Company Details Form
```javascript
// Required Fields for Create/Update
{
    name: "string (required|max:255)",
    description: "text (required)", // About Us content
    vision: "text (required)",
    goal: "text (required)", 
    founded_date: "date (required|date_format:Y-m-d)",
    address: "text (required)",
    logo: "file (image|mimes:jpeg,png,jpg,gif|max:2048)" // Only for create/logo update
}

// Response Data Structure
{
    message: "Company details fetched successfully",
    data: {
        id: 1,
        name: "Legal Code Company",
        about_us: "Company description...", // Note: API returns 'description' as 'about_us'
        vision: "Company vision...",
        goal: "Company goals...",
        logo_url: "https://example.com/logo.png",
        founded_date: "01-01-2020", // Formatted date
        address: "123 Legal Street, City, Country",
        social_media: [
            {
                platform_name: "Facebook",
                page_url: "https://facebook.com/legalcode"
            }
        ],
        contacts: [
            {
                department: "General Inquiries", 
                phone_number: "+1234567890"
            }
        ]
    }
}
```

#### Social Media Form
```javascript
// Required Fields
{
    platform_name: "string (required|max:255)", // e.g., Facebook, Twitter, LinkedIn
    page_url: "string (required|url)"
}

// Response Data
{
    message: "Company social media added successfully",
    data: {
        id: 1,
        platform_name: "Facebook",
        page_url: "https://facebook.com/company",
        company_id: 1,
        created_user_id: 1
    }
}
```

#### Company Contact Form
```javascript 
// Required Fields
{
    department: "string (required|max:255)", // e.g., Sales, Support, General
    phone_number: "string (required)"
}

// Response Data
{
    message: "Company contact added successfully", 
    data: {
        id: 1,
        department: "General Inquiries",
        phone_number: "+1234567890",
        company_id: 1
    }
}
```

### Blog Management Forms

#### Blog Create/Edit Form
```javascript
// Required Fields for Create
{
    title: "string (required|max:255)",
    content: "text (required)", // Rich text content
    images: "array (required)" // Array of image files for create only
    // Note: images[] - multiple files for creation
}

// Required Fields for Update
{
    title: "string (required|max:255)", 
    content: "text (required)" 
    // Note: Images updated separately via add/delete endpoints
}

// Complete Blog Data Structure
{
    message: "Blog details fetched successfully",
    data: {
        id: 1,
        title: "Legal Tips for Startups",
        content: "Full blog content...", // HTML content
        created_by: "John Doe",
        updated_by: "Jane Doe", 
        created_at: "15-08-2025", // d-m-y format
        updated_at: "16-08-2025",
        images: [
            {
                id: 1,
                image_name: "blog1-image1.jpg",
                image_url: "https://example.com/blog1-image1.jpg"
            }
        ]
    }
}

// Blog List Data Structure (for listings)
{
    message: "All blogs fetched successfully",
    data: [
        {
            id: 1,
            title: "Legal Tips for Startups", 
            created_by: "John Doe",
            created_at: "15-08-2025",
            thumbnail_image: "https://example.com/blog1-thumb.jpg" // First image as thumbnail
        }
    ]
}
```

#### Add Blog Images Form
```javascript
// Required Fields (separate endpoint)
{
    images: "array (required)" // Array of image files
    // images[] - multiple files
}
```

### Project Management Forms

#### Project Type Form
```javascript
// Required Fields
{
    type_name: "string (required|max:255)", // e.g., "Corporate Law", "Web Development"
    description: "text (required)"
}

// Response Data
{
    message: "All project types fetched successfully",
    data: [
        {
            id: 1,
            type_name: "Corporate Law", 
            description: "Legal services for corporations"
        }
    ]
}
```

#### Project Create Form
```javascript
// Required Fields
{
    name: "string (required|max:255)",
    project_type_id: "integer (required|exists:project_types,id)",
    description: "text (required)",
    demo_url: "string (nullable|url)", // Optional demo link
    thumbnail: "file (required|image|mimes:jpeg,png,jpg,gif|max:2048)"
}
```

#### Project Update Form  
```javascript
// Required Fields (details only)
{
    name: "string (required|max:255)",
    project_type_id: "integer (required|exists:project_types,id)", 
    description: "text (required)",
    demo_url: "string (nullable|url)" // Optional
    // Note: Thumbnail updated separately
}

// Complete Project Data Structure
{
    message: "Project details fetched successfully",
    data: {
        id: 1,
        name: "Startup Legal Consultation",
        project_type_id: 1,
        project_type: "Corporate Law", // Populated name
        description: "Comprehensive legal consultation for startups",
        demo_url: "https://demo.example.com", // Optional
        thumbnail_url: "https://example.com/project1-thumb.jpg",
        created_by: "Admin User",
        created_at: "15-08-2025",
        updated_by: "Admin User", 
        updated_at: "16-08-2025",
        project_features: [
            {
                id: 1,
                title: "Business Registration", // Feature name
                description: "Complete business registration process",
                images: [
                    {
                        id: 1,
                        image_name: "feature1-image1.jpg",
                        image_url: "https://example.com/feature1-image1.jpg"
                    }
                ]
            }
        ]
    }
}
```

#### Project Features Form
```javascript
// Required Fields (bulk create)
{
    features: [
        {
            feature_name: "string (required|max:255)", // Called 'title' in database
            description: "text (required)"
        }
        // Multiple features can be added at once
    ]
}

// Single Feature Update
{
    feature_name: "string (required|max:255)", // Called 'title' in database  
    description: "text (required)"
}
```

#### Project Feature Images Form
```javascript
// Required Fields
{
    images: "array (required)" // Array of image files
    // images[] - multiple files per feature
}
```

### File Upload Requirements

#### Image Upload Specifications
```javascript
// General Image Requirements
{
    mimes: ["jpeg", "jpg", "png", "gif"], // Allowed formats
    max_size: "2048KB", // 2MB maximum
    dimensions: "No specific restrictions" // But consider responsive design
}

// Specific Upload Endpoints
const uploadEndpoints = {
    blog_creation: "POST /admin/blogs (with images[] in FormData)",
    blog_images: "POST /admin/blogs/{id}/images (images[])",
    project_creation: "POST /admin/projects (with thumbnail file)",
    project_thumbnail: "POST /admin/projects/{id}/new-thumbnail (thumbnail file)",
    project_feature_images: "POST /admin/project-features/{id}/images (images[])",
    company_logo: "POST /admin/company/logo (logo file)",
    company_creation: "POST /admin/company (with logo file)"
}
```

### Validation Rules Summary

#### Field Validation Requirements
```javascript
const validationRules = {
    // Text Fields
    name: "required|string|max:255",
    email: "required|email|unique",
    title: "required|string|max:255",
    type_name: "required|string|max:255",
    platform_name: "required|string|max:255",
    department: "required|string|max:255",
    
    // Long Text Fields  
    content: "required|string",
    description: "required|string",
    vision: "required|string", 
    goal: "required|string",
    address: "required|string",
    
    // Passwords
    password: "required|string|min:6",
    password_confirmation: "required|same:password",
    old_password: "required|string",
    new_password: "required|string|min:6",
    
    // URLs
    page_url: "required|url",
    demo_url: "nullable|url",
    
    // Dates
    founded_date: "required|date|date_format:Y-m-d",
    
    // Numbers/IDs
    project_type_id: "required|integer|exists:project_types,id",
    
    // Files
    images: "required|array", // For multiple images
    "images.*": "image|mimes:jpeg,png,jpg,gif|max:2048", // Each image
    thumbnail: "required|image|mimes:jpeg,png,jpg,gif|max:2048",
    logo: "required|image|mimes:jpeg,png,jpg,gif|max:2048"
}
```

### Error Response Structures

#### Validation Error Format
```javascript
// 422 Unprocessable Entity Response
{
    message: "The given data was invalid.",
    errors: {
        email: ["The email field is required.", "The email must be a valid email address."],
        password: ["The password field is required."],
        images: ["The images field is required."]
    }
}
```

#### Common Error Responses
```javascript
const errorResponses = {
    // Authentication Errors
    401: {
        message: "Incorrect login credentials."
    },
    
    // Authorization Errors  
    403: {
        message: "This action is unauthorized."
    },
    
    // Not Found Errors
    404: {
        message: "Resource not found." // User, blog, project, etc. not found
    },
    
    // Server Errors
    500: {
        message: "Operation failed.",
        error: "Detailed error message for debugging"
    }
}
```

### Frontend State Management Recommendations

#### Required State Structure
```javascript
// Authentication State
const authState = {
    isAuthenticated: false,
    token: null,
    user: null,
    loading: false,
    error: null
}

// Company Data State
const companyState = {
    details: null,
    socialMedia: [],
    contacts: [],
    loading: false,
    error: null
}

// Blog Management State
const blogState = {
    blogs: [],
    currentBlog: null,
    loading: false,
    error: null,
    pagination: null
}

// Project Management State  
const projectState = {
    projects: [],
    currentProject: null,
    projectTypes: [],
    loading: false,
    error: null,
    pagination: null
}

// File Upload State
const uploadState = {
    uploading: false,
    progress: 0,
    error: null,
    uploadedFiles: []
}
```

This documentation provides a complete overview of the Omninova API project, including all models, relationships, endpoints, form requirements, validation rules, and integration requirements for frontend development.