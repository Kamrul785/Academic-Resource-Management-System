# Academic Resource Management System (ARMS)

A comprehensive web-based platform for managing academic resources, departments, courses, and user interactions within an educational institution.

## Features

- **User Management**
  - Role-based access control (Admin/Student)
  - User registration and authentication
  - Profile management

- **Resource Management**
  - Upload and download academic resources
  - Resource categorization by department and course
  - File type and size validation
  - Resource metadata management

- **Department & Course Management**
  - Create and manage academic departments
  - Add and organize courses within departments
  - Hierarchical structure for easy navigation

- **Interactive Features**
  - Resource rating system
  - Commenting functionality
  - Search and filter capabilities
  - Responsive design for all devices

## Technology Stack

- **Frontend**
  - HTML5
  - CSS3 (Bootstrap 5)
  - JavaScript
  - Bootstrap Icons

- **Backend**
  - PHP
  - MySQL Database
  - Apache Web Server

## Installation

1. **Prerequisites**
   - XAMPP (Apache, MySQL, PHP)
   - Web browser (Chrome, Firefox, Safari, etc.)

2. **Setup**
   ```bash
   # Clone the repository
   git clone https://github.com/yourusername/arms_project.git

   # Move to the project directory
   cd arms_project

   # Import the database
   # Open phpMyAdmin and import database/schema.sql
   ```

3. **Configuration**
   - Ensure XAMPP is running
   - Place the project in the `htdocs` directory
   - Access the application through `http://localhost/arms_project`

## Database Structure

The system uses the following main tables:
- `users` - User accounts and authentication
- `departments` - Academic departments
- `courses` - Course information
- `resources` - Academic resources
- `votes` - Resource ratings
- `comments` - User comments on resources

## Security Features

- Password hashing
- SQL injection prevention
- XSS protection
- Session management
- Role-based access control
- Input validation and sanitization

## Usage

1. **Admin Access**
   - Default admin credentials:
     - Email: admin@example.com
     - Password: admin123

2. **Student Access**
   - Register through the signup page
   - Login with registered credentials

3. **Resource Management**
   - Upload resources with proper metadata
   - Browse and download resources
   - Rate and comment on resources

## Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Acknowledgments

- Bootstrap for the frontend framework
- XAMPP for the development environment
- All contributors and users of the system

## Contact

For any queries or support, please contact:
- Project Maintainer: [Your Name]
- Email: [Your Email]
- GitHub: [Your GitHub Profile]

---

**Note**: This is a work in progress. Features and documentation will be updated as the project evolves. 