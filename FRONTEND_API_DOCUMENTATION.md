# Legal Code API - Complete Frontend Documentation

## 🚀 Project Overview

This is a Laravel-based REST API for managing a legal services company's web presence, including blogs, projects, company information, and admin management.

### Tech Stack
- **Backend**: Laravel 12.x with PHP 8.2+
- **Authentication**: JWT (JSON Web Tokens) using php-open-source-saver/jwt-auth
- **Database**: SQLite (configurable to MySQL/PostgreSQL)
- **File Storage**: Local filesystem for images
- **API Format**: RESTful JSON API

## 📋 Environment Setup

### Required Environment Variables
```env
APP_NAME=LegalCodeAPI
APP_ENV=local
APP_KEY=[generate using: php artisan key:generate]
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=sqlite
# For MySQL:
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=legal_code_db
# DB_USERNAME=root
# DB_PASSWORD=

JWT_SECRET=[generate using: php artisan jwt:secret]
JWT_TTL=60  # Token lifetime in minutes (default: 60)
JWT_REFRESH_TTL=20160  # Refresh token lifetime in minutes (default: 2 weeks)
```

## 🔐 Authentication System

### JWT Token Details
- **Token Type**: Bearer Token
- **Token Lifetime**: 60 minutes (configurable via JWT_TTL)
- **Refresh Period**: 2 weeks (configurable via JWT_REFRESH_TTL)
- **Algorithm**: HS256 (HMAC SHA-256)

### Authentication Flow
1. User registers or logs in via `/api/admin/login`
2. Server returns JWT token
3. Frontend stores token (localStorage/sessionStorage)
4. Include token in Authorization header for protected routes
5. Token expires after 60 minutes
6. Can refresh token within 2-week window

### Header Format
```javascript
{
  "Authorization": "Bearer eyJ0eXAiOiJKV1QiLCJhbGc..."
}
```

## 📊 Database Structure & Relationships

### Core Models

#### Users
- **Fields**: id, name, email, password, phone_number, address, created_at, updated_at, deleted_at
- **Relationships**: Creator/updater for all content
- **Soft Deletes**: Yes

#### Company (Singleton - only one record)
- **Fields**: id, name, description, vision, goal, logo_url, founded_date, address, creator_user_id, updated_user_id
- **Relationships**: 
  - Has many CompanySocialMedia
  - Has many CompanyContacts
  - Belongs to User (creator/updater)

#### Blogs
- **Fields**: id, title, content, creator_user_id, updated_user_id, created_at, updated_at, deleted_at
- **Relationships**: 
  - Has many BlogImages
  - Belongs to User (creator/updater)
- **Soft Deletes**: Yes

#### CompanyProjects
- **Fields**: id, name, project_type_id, description, demo_url, thumbnail_url, creator_user_id, updated_user_id, deleted_at
- **Relationships**:
  - Belongs to ProjectType
  - Has many ProjectFeatures
  - Belongs to User (creator/updater)
- **Soft Deletes**: Yes

#### ProjectTypes
- **Fields**: id, type_name, description, creator_user_id, updated_user_id, deleted_at
- **Relationships**: Has many CompanyProjects
- **Soft Deletes**: Yes

#### ProjectFeatures
- **Fields**: id, project_id, title, description, deleted_at
- **Relationships**:
  - Belongs to CompanyProject
  - Has many FeatureImages
- **Soft Deletes**: Yes

## 🌐 API Endpoints Reference

### Base URL
```
http://localhost:8000/api
```

## 🔓 Public Endpoints (Customer-facing)

### Company Information
```http
GET /api/customer/company-details
```
**Response Structure**:
```json
{
  "message": "Company details fetched successfully",
  "data": {
    "name": "Legal Code Company",
    "about_us": "Company description...",
    "vision": "Our vision statement...",
    "goal": "Our goals...",
    "logo_url": "http://localhost:8000/images/company/logo.png",
    "founded_date": "01-01-2020",
    "address": "123 Legal Street, City, Country",
    "social_media": [
      {
        "platform_name": "Facebook",
        "page_url": "https://facebook.com/legalcode"
      }
    ],
    "contacts": [
      {
        "department": "General Inquiries",
        "phone_number": "+1234567890"
      }
    ]
  }
}
```

### Blogs
```http
GET /api/customer/blogs
```
**Response**: List of blogs with title, thumbnail, creator, date

```http
GET /api/customer/blogs/{id}
```
**Response**: Full blog details with content and all images

### Projects
```http
GET /api/customer/project-types
```
**Response**: List of all project categories

```http
GET /api/customer/projects
```
**Response**: List of all projects with basic info

```http
GET /api/customer/projects/{id}
```
**Response**: Detailed project with features and images

## 🔒 Protected Admin Endpoints

### Authentication Endpoints

#### Register Admin
```http
POST /api/admin/register
```
**Request Body**:
```json
{
  "name": "Admin Name",
  "email": "admin@example.com",
  "password": "password123",
  "password_confirmation": "password123",
  "phone_number": "+1234567890",
  "address": "Admin Address"
}
```

#### Login
```http
POST /api/admin/login
```
**Request Body**:
```json
{
  "email": "admin@example.com",
  "password": "password123"
}
```
**Response**:
```json
{
  "message": "User login successful.",
  "token": "eyJ0eXAiOiJKV1QiLCJhbGc..."
}
```

#### Profile Management
- `GET /api/admin/profile` - Get current user profile
- `PUT /api/admin/edit-profile` - Update profile
- `POST /api/admin/change-password` - Change password
- `GET /api/admin/logout` - Logout and invalidate token

### User Management (Admin Only)
- `GET /api/admin/users` - List all users
- `GET /api/admin/users/{id}` - Get specific user
- `PUT /api/admin/users/{id}` - Update user details
- `POST /api/admin/users/{id}/change-password` - Change user password
- `DELETE /api/admin/users/{id}` - Soft delete user

### Blog Management
- `GET /api/admin/blogs` - List all blogs (admin view)
- `GET /api/admin/blogs/{id}` - Get specific blog
- `POST /api/admin/blogs` - Create blog (multipart/form-data with images)
- `PUT /api/admin/blogs/{id}` - Update blog text
- `POST /api/admin/blogs/{id}/images` - Add images to blog
- `DELETE /api/admin/blog-images/{id}` - Delete specific image
- `DELETE /api/admin/blogs/{id}` - Soft delete blog

### Project Management
- `GET /api/admin/projects` - List all projects
- `GET /api/admin/projects/{id}` - Get specific project
- `POST /api/admin/projects` - Create project (with thumbnail)
- `PUT /api/admin/projects/{id}/details` - Update project info
- `POST /api/admin/projects/{id}/new-thumbnail` - Replace thumbnail
- `DELETE /api/admin/projects/{id}` - Soft delete project

### Project Features
- `POST /api/admin/projects/{id}/features` - Add feature with images
- `PUT /api/admin/project-features/{id}/details` - Update feature
- `POST /api/admin/project-features/{id}/images` - Add feature images
- `DELETE /api/admin/project-feature-images/{id}` - Delete feature image
- `DELETE /api/admin/project-features/{id}` - Delete feature

### Project Types
- `GET /api/admin/project-types` - List all types
- `GET /api/admin/project-types/{id}` - Get specific type
- `POST /api/admin/project-types` - Create type
- `PUT /api/admin/project-types/{id}` - Update type
- `DELETE /api/admin/project-types/{id}` - Delete type (cascades)

### Company Management
- `GET /api/admin/company` - Get company details
- `POST /api/admin/company` - Add company (first time)
- `PUT /api/admin/company` - Update company details
- `POST /api/admin/company/logo` - Replace logo

### Social Media & Contacts
- `POST /api/admin/company/social-media` - Add social media
- `PUT /api/admin/company/social-media/{id}` - Update social media
- `DELETE /api/admin/company/social-media/{id}` - Delete social media
- `POST /api/admin/company/contacts` - Add contact
- `PUT /api/admin/company/contacts/{id}` - Update contact
- `DELETE /api/admin/company/contacts/{id}` - Delete contact

## 📝 Request Validation Rules

### User Registration/Edit
- **name**: required, string
- **email**: required, valid email, unique
- **password**: required, confirmed (for registration)
- **phone_number**: required
- **address**: required

### Blog Creation
- **title**: required
- **content**: required
- **images**: required array (on creation)
- **images.***: valid image file (jpeg, png, jpg, gif, svg)

### Project Creation
- **name**: required
- **project_type_id**: required, must exist
- **description**: required
- **demo_url**: required, valid URL
- **thumbnail**: required image file

### Company Details
- **name**: required
- **description**: required
- **vision**: required
- **goal**: required
- **founded_date**: required, valid date
- **address**: required
- **logo**: required image (on creation)

## 🎨 File Upload Handling

### Image Storage Paths
- **Blogs**: `/public/images/blogs/`
- **Projects**: `/public/images/projects/`
- **Company**: `/public/images/company/`

### Image URL Format
```
http://localhost:8000/images/{type}/{unique_id}_{filename}
```

### Upload Implementation Example (JavaScript)
```javascript
async function uploadBlog(token, blogData, imageFiles) {
  const formData = new FormData();
  formData.append('title', blogData.title);
  formData.append('content', blogData.content);
  
  // Add multiple images
  imageFiles.forEach(file => {
    formData.append('images[]', file);
  });
  
  const response = await fetch('http://localhost:8000/api/admin/blogs', {
    method: 'POST',
    headers: {
      'Authorization': `Bearer ${token}`
      // Do NOT set Content-Type header - let browser set it with boundary
    },
    body: formData
  });
  
  return await response.json();
}
```

## 🚨 Error Handling

### Standard Error Response Format
```json
{
  "message": "Error description",
  "errors": {
    "field_name": ["Validation error message"]
  }
}
```

### HTTP Status Codes
- **200**: Success
- **201**: Created successfully
- **401**: Unauthorized (invalid/expired token)
- **404**: Resource not found
- **422**: Validation error
- **500**: Server error

## 💻 Frontend Integration Examples

### Authentication Service (React/Next.js)
```javascript
class AuthService {
  constructor() {
    this.baseURL = 'http://localhost:8000/api';
    this.token = localStorage.getItem('jwt_token');
  }
  
  async login(email, password) {
    const response = await fetch(`${this.baseURL}/admin/login`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({ email, password })
    });
    
    const data = await response.json();
    if (data.token) {
      this.token = data.token;
      localStorage.setItem('jwt_token', data.token);
    }
    return data;
  }
  
  async makeAuthRequest(endpoint, options = {}) {
    if (!this.token) throw new Error('No auth token');
    
    const response = await fetch(`${this.baseURL}${endpoint}`, {
      ...options,
      headers: {
        'Authorization': `Bearer ${this.token}`,
        'Content-Type': 'application/json',
        ...options.headers
      }
    });
    
    if (response.status === 401) {
      // Token expired - redirect to login
      localStorage.removeItem('jwt_token');
      window.location.href = '/login';
    }
    
    return await response.json();
  }
  
  logout() {
    localStorage.removeItem('jwt_token');
    this.token = null;
  }
}
```

### API Service Class
```javascript
class LegalCodeAPI {
  constructor() {
    this.auth = new AuthService();
  }
  
  // Public endpoints
  async getCompanyDetails() {
    const response = await fetch('http://localhost:8000/api/customer/company-details');
    return await response.json();
  }
  
  async getAllBlogs() {
    const response = await fetch('http://localhost:8000/api/customer/blogs');
    return await response.json();
  }
  
  async getBlogDetails(id) {
    const response = await fetch(`http://localhost:8000/api/customer/blogs/${id}`);
    return await response.json();
  }
  
  // Admin endpoints
  async createBlog(blogData, images) {
    const formData = new FormData();
    formData.append('title', blogData.title);
    formData.append('content', blogData.content);
    images.forEach(img => formData.append('images[]', img));
    
    return await this.auth.makeAuthRequest('/admin/blogs', {
      method: 'POST',
      headers: {}, // Let browser set Content-Type for FormData
      body: formData
    });
  }
  
  async updateProject(id, projectData) {
    return await this.auth.makeAuthRequest(`/admin/projects/${id}/details`, {
      method: 'PUT',
      body: JSON.stringify(projectData)
    });
  }
}
```

### React Component Example
```jsx
import { useState, useEffect } from 'react';
import { LegalCodeAPI } from './services/api';

function BlogList() {
  const [blogs, setBlogs] = useState([]);
  const [loading, setLoading] = useState(true);
  const api = new LegalCodeAPI();
  
  useEffect(() => {
    async function fetchBlogs() {
      try {
        const response = await api.getAllBlogs();
        setBlogs(response.data);
      } catch (error) {
        console.error('Failed to fetch blogs:', error);
      } finally {
        setLoading(false);
      }
    }
    fetchBlogs();
  }, []);
  
  if (loading) return <div>Loading...</div>;
  
  return (
    <div className="blog-grid">
      {blogs.map(blog => (
        <BlogCard 
          key={blog.id}
          title={blog.title}
          thumbnail={blog.thumbnail_image}
          author={blog.created_by}
          date={blog.created_at}
        />
      ))}
    </div>
  );
}
```

## 🔄 State Management Considerations

### Recommended State Structure
```javascript
{
  auth: {
    isAuthenticated: boolean,
    token: string | null,
    user: UserProfile | null,
    loading: boolean,
    error: string | null
  },
  company: {
    details: CompanyDetails | null,
    socialMedia: SocialMedia[],
    contacts: Contact[],
    loading: boolean
  },
  blogs: {
    list: Blog[],
    currentBlog: BlogDetail | null,
    loading: boolean,
    pagination: {
      page: number,
      total: number
    }
  },
  projects: {
    list: Project[],
    currentProject: ProjectDetail | null,
    types: ProjectType[],
    loading: boolean
  }
}
```

## 🛠 Development Tips

### CORS Configuration
For local development, ensure your Laravel backend allows CORS:
```php
// In Laravel cors.php config
'allowed_origins' => ['http://localhost:3000'], // Your frontend URL
'allowed_headers' => ['Authorization', 'Content-Type'],
```

### Token Storage Best Practices
1. **localStorage**: Persistent but vulnerable to XSS
2. **sessionStorage**: Safer but lost on tab close
3. **httpOnly cookies**: Most secure but requires backend changes
4. **Memory + refresh token**: Balance of security and UX

### Image Optimization
- Implement image resizing on backend
- Use lazy loading for image galleries
- Consider CDN for production
- Compress images before upload

### Error Handling Strategy
```javascript
class APIError extends Error {
  constructor(response) {
    super(response.message);
    this.status = response.status;
    this.errors = response.errors;
  }
}

async function apiRequest(url, options) {
  const response = await fetch(url, options);
  const data = await response.json();
  
  if (!response.ok) {
    throw new APIError({
      status: response.status,
      message: data.message,
      errors: data.errors
    });
  }
  
  return data;
}
```

## 📈 Performance Optimization

### API Call Optimization
1. **Batch requests** where possible
2. **Cache** company details and project types (rarely change)
3. **Paginate** blog and project lists
4. **Lazy load** images and detailed content
5. **Debounce** search and filter operations

### Caching Strategy
```javascript
const cache = new Map();
const CACHE_DURATION = 5 * 60 * 1000; // 5 minutes

async function cachedFetch(key, fetcher) {
  const cached = cache.get(key);
  if (cached && Date.now() - cached.timestamp < CACHE_DURATION) {
    return cached.data;
  }
  
  const data = await fetcher();
  cache.set(key, { data, timestamp: Date.now() });
  return data;
}

// Usage
const companyDetails = await cachedFetch(
  'company-details',
  () => api.getCompanyDetails()
);
```

## 🚀 Deployment Checklist

### Backend Preparation
- [ ] Set APP_ENV to production
- [ ] Generate new APP_KEY and JWT_SECRET
- [ ] Configure production database
- [ ] Set up image storage (local/S3)
- [ ] Configure CORS for production domain
- [ ] Set up SSL certificate
- [ ] Configure rate limiting
- [ ] Set up logging and monitoring

### Frontend Preparation
- [ ] Update API base URL
- [ ] Implement error boundaries
- [ ] Add loading states
- [ ] Implement retry logic
- [ ] Set up environment variables
- [ ] Optimize bundle size
- [ ] Configure CDN for assets

## 📚 Additional Resources

### TypeScript Interfaces
```typescript
interface User {
  id: number;
  name: string;
  email: string;
  phone_number: string;
  address: string;
}

interface Blog {
  id: number;
  title: string;
  content: string;
  created_by: string;
  created_at: string;
  thumbnail_image?: string;
  images?: BlogImage[];
}

interface Project {
  id: number;
  name: string;
  project_type: string;
  description: string;
  demo_url: string;
  thumbnail_url: string;
  features?: ProjectFeature[];
}

interface ApiResponse<T> {
  message: string;
  data: T;
}

interface ErrorResponse {
  message: string;
  errors?: Record<string, string[]>;
  error?: string;
}
```

### Testing Utilities
```javascript
// Mock API for testing
export const mockAPI = {
  login: jest.fn(() => Promise.resolve({
    message: 'User login successful.',
    token: 'mock-jwt-token'
  })),
  
  getAllBlogs: jest.fn(() => Promise.resolve({
    message: 'All blogs fetched successfully',
    data: mockBlogs
  }))
};

// Test helper
export function withAuth(testFn) {
  const token = 'test-jwt-token';
  localStorage.setItem('jwt_token', token);
  testFn();
  localStorage.removeItem('jwt_token');
}
```

## 🤝 Support & Contact

For API issues or questions, please refer to:
- API Documentation: This document
- Backend Repository: [Your GitHub repo]
- Issue Tracker: [Your issue tracker]
- API Status: [Your status page]

---

Last Updated: August 2025
Version: 1.0.0