# Design Document

## Overview

The Product Management website will be a PHP-based web application that provides a complete CRUD interface for managing products. The system follows a simple MVC-like pattern with separate files for different operations, uses MySQL for data persistence, and Bootstrap for responsive UI design.

## Architecture

### File Structure
```
product-management/
├── db.php              # Database connection and configuration
├── index.php           # Main page - list all products
├── create.php          # Add new product form and processing
├── update.php          # Edit product form and processing
├── delete.php          # Delete product with confirmation
├── uploads/            # Directory for uploaded product images
├── css/               # Custom CSS files (if needed)
└── sql/
    └── database.sql    # Database schema creation script
```

### Database Design

**Products Table Schema:**
```sql
CREATE TABLE products (
    id INT PRIMARY KEY AUTO_INCREMENT,
    image VARCHAR(255) NOT NULL,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    stock INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

### Technology Stack
- **Backend:** PHP 
- **Database:** MySQL
- **Frontend:** HTML5, Bootstrap 5.x, JavaScript
- **Server:** Apache (via XAMPP for local development)

## Components and Interfaces

### Database Connection (db.php)
- Centralized database configuration
- PDO connection with error handling
- Connection parameters (host, database, username, password)
- UTF-8 charset configuration

### Product Listing (index.php)
- Fetch all products from database
- Display products in Bootstrap table
- Include product images (thumbnails)
- Action buttons for edit/delete operations
- Add new product button
- Responsive design for mobile devices

### Product Creation (create.php)
- Form validation (client-side and server-side)
- Image upload handling with file validation
- Support for both file upload and URL input
- Form processing and database insertion
- Success/error message handling
- Redirect after successful creation

### Product Update (update.php)
- Pre-populate form with existing product data
- Handle image replacement (upload or URL)
- Form validation and processing
- Database update operations
- Success/error message handling

### Product Deletion (delete.php)
- Confirmation dialog before deletion
- Secure deletion with product ID validation
- File cleanup for uploaded images
- Success message and redirect

## Data Models

### Product Model
```php
class Product {
    public $id;
    public $image;
    public $name;
    public $description;
    public $price;
    public $stock;
    public $created_at;
    public $updated_at;
}
```

### Image Handling Strategy
- Support both file uploads and URL inputs
- File uploads stored in `/uploads` directory
- Allowed file types: JPG, JPEG, PNG, GIF
- Maximum file size: 5MB
- Generate unique filenames to prevent conflicts
- Validate image dimensions and file integrity

## Error Handling

### Database Errors
- Use try-catch blocks for all database operations
- Log errors to PHP error log
- Display user-friendly error messages
- Graceful degradation when database is unavailable

### File Upload Errors
- Validate file size and type before processing
- Handle upload directory permissions
- Provide specific error messages for different failure scenarios
- Clean up temporary files on errors

### Form Validation
- Required field validation
- Data type validation (price as decimal, stock as integer)
- Input sanitization to prevent XSS
- CSRF protection for form submissions

### Security Measures
- Use prepared statements for all database queries
- Input sanitization and validation
- File upload security (type checking, size limits)
- Proper error message handling (no sensitive information exposure)

## Testing Strategy

### Manual Testing Checklist
1. **Product Creation:**
   - Test with valid data
   - Test with invalid data (empty fields, invalid price)
   - Test image upload functionality
   - Test image URL functionality
   - Test file size and type restrictions

2. **Product Listing:**
   - Test with empty database
   - Test with multiple products
   - Test image display (both uploaded and URL)
   - Test responsive design on different screen sizes

3. **Product Update:**
   - Test form pre-population
   - Test updating all fields
   - Test image replacement
   - Test validation errors

4. **Product Deletion:**
   - Test confirmation dialog
   - Test successful deletion
   - Test cancellation
   - Test file cleanup for uploaded images

