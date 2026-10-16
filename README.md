🎓 Final Year Project Management System (FYPMS)

A comprehensive, role-based web application designed to streamline the Final Year Project (FYP) management process in universities. The system facilitates communication and coordination between Students, Faculty Members, and Focal Persons by managing project proposals, approvals, submissions, and evaluations.

📽️ Live Demo

The demo video will be available soon.

<!-- Add your demo video link here when available. -->


✨ Key Features

- 🔐 Role-Based Access Control (RBAC):** Separate dashboards and permissions for Students, Faculty Members, and Departmental Focal Persons.
- 📄 Project Proposal Management:** Enables students to submit project ideas, select supervisors, and track proposal approval status.
- 🛡️ Authentication and Security:** Includes secure authentication, input validation, and role-based access restrictions.
- 📊 Interactive Dashboards:** Provides an overview of project progress, important deadlines, and review feedback.
- 🔄 Project Workflow Management:** Supports project coordination and approval processes throughout the FYP lifecycle.
- 🏗️ MVC Architecture:** Uses the Model-View-Controller (MVC) design pattern to maintain organized and manageable code.

🛠️ Technology Stack

| Component | Technologies |
|---|---|
| Backend | PHP, Laravel |
| Frontend | HTML5, CSS3, Bootstrap, JavaScript, AJAX |
| Database | MySQL |
| Development Environment | XAMPP, Apache |
| Architecture | Model-View-Controller (MVC) |


🚀 Installation and Setup Guide

Prerequisites

Make sure the following tools are installed on your computer:

- PHP 8.1 or later
- Composer
- MySQL
- XAMPP or another compatible local development environment
- Git

Steps to Run Locally

1. Clone the Repository**

```bash
git clone https://github.com/YOUR_GITHUB_USERNAME/FYPMS.git
cd FYPMS
```

Replace `YOUR_GITHUB_USERNAME` with your actual GitHub username.

2. Install Dependencies**

```bash
composer install
```

3. Create the Environment File**

```bash
cp .env.example .env
```

On Windows, you can also copy `.env.example` and rename the copy to `.env`.

4. Generate the Application Key**

```bash
php artisan key:generate
```

5. Configure the Database**

Create a MySQL database for the project, then update the following settings in your `.env` file:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=fypms
DB_USERNAME=root
DB_PASSWORD=
```

Update the database name, username, and password according to your local MySQL configuration.

6. Run Database Migrations**

```bash
php artisan migrate
```

If the project includes seeders and requires initial data, run the appropriate seeding command according to the project's configuration.

7. Start the Development Server**

```bash
php artisan serve
```

8. Open the Application**

Visit the following URL in your browser:

```text
http://127.0.0.1:8000
```

👥 User Roles

- Student: Submits project proposals and tracks project progress and approval status.
- Faculty Member: Participates in project supervision and review processes according to assigned permissions.
- Focal Person: Manages departmental project workflows and oversees relevant approval and evaluation activities.

🎯 Project Objective

The primary objective of FYPMS is to simplify and organize the university Final Year Project management process through a centralized platform. It aims to improve coordination, maintain project records, support timely submissions, and make project-related activities more efficient.

 👩‍💻 Developed As

Project Name: Final Year Project Management System (FYPMS)

Project Type: Final Year Project (FYP)

📌 Future Enhancements

- Improved reporting and analytics.
- Additional workflow automation.
- Enhanced project progress monitoring.
- Further improvements to the user interface and user experience.

📬 Contact

For questions, suggestions, or collaboration, please contact the project contributors through their GitHub profiles.


Developed as an academic project to improve the management of university Final Year Projects.
