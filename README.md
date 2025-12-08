# Simple Chat Webpage for Local and Private Network

**Developed by:**  
**Caleb Rodriguez "Kelunie" Cordero**

## Description

This project is a web page designed to enable simple chats within a local and private network. Users can join without needing to register, simply by choosing a username, and can quickly and efficiently participate in group conversations.

## Features

- Group chats for specific areas (10 in this case, can add more or less if it need it).
- Ticket generation and management in the IT Support chat.
- Chat history accessible through a simple and functional interface.
- Lightweight design for deployment in private networks.

## Contact Information

For any inquiries, feel free to contact me at:  
**Email:** caleb.cordero1997@gmail.com

## Guide to Setting up the Project in XAMPP

1. Download or copy the project into your XAMPP project folder or any other PHP server you are using.
2. Create a new folder named img to store all the background images, logos, and other assets you need.
3. In the menu.inc file, change the line img src="codes/img/logo.png" to the path of the logo you want to use.
4. For the database connection, open the conexion.inc file and update the variables with your database configuration details.
5. In the BD folder, the query.txt file contains the SQL queries for creating the database. Please execute them in the order provided (top to bottom).
6. To create Technical Support (TI) users, navigate to localhost/ChatGrupales/crear_tecnico.php in your web browser and create a new user. Note that creating users directly through the database data tables is not recommended.
7. Ensure your operating system recognizes the directory paths correctly. Depending on your OS, paths may be formatted as something/else, something//else, or something\else.
8. Most of the code is commented in Spanish; you can use Google Translate to translate comments if needed.
9. ❤️❤️❤️❤️❤️╰(°▽°)╯ ☆: .｡. o(≧▽≦)o .｡.:☆ ❤️❤️❤️❤️❤️

---

**All Rights Reserved © Caleb Rodriguez "Kelunie" Cordero**

---

## Additional Information

### Ticket System

The integrated ticket system allows users to request help, report technical issues, or contact IT support directly from the chat interface.  
**How it works:**
1. Users submit a ticket via the form in the IT Support chat.
2. Tickets are stored in the database, which admins/support staff can access through a dedicated dashboard.
3. Admins review requests, respond to users within the system, and update ticket status (Open, In Progress, Resolved).
4. Users can follow up with new info or close their ticket when resolved; ticket history remains accessible to both users and staff.

### Database Setup Details

- The BD folder includes an SQL setup script generated using phpMyAdmin.
- Import the script into your database using phpMyAdmin or any MySQL-compatible management tool to create the required data structure and tables.

### Technologies Used

- **PHP**: Backend logic, chat and ticket management
- **JavaScript**: Live chat functionality, frontend dynamic behavior
- **CSS & HTML**: User interface and design