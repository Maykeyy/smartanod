# SmartTanod - Barangay Management System

## 📁 Project Structure

```
smartanod/
├── config.php                    # Database config & helper functions
├── index.php                     # Dashboard (homepage)
│
├── includes/
│   ├── header.php               # Navigation header (included in all pages)
│   └── footer.php               # Footer template
│
├── auth/
│   ├── login.php                # Login page
│   ├── logout.php               # Logout handler
│   └── forgot.php               # Forgot password page
│
├── incidents/
│   ├── index.php                # Incident list with filters
│   ├── create.php               # Create new incident
│   ├── edit.php                 # Edit incident
│   ├── view.php                 # View incident details
│   └── assign.php               # Assign incident to Tanod
│
├── patrols/
│   ├── calendar.php             # Patrol calendar view
│   ├── list.php                 # Patrol list view
│   └── create.php               # Create patrol schedule
│
├── evidence/
│   └── index.php                # Evidence gallery & manager
│
├── reports/
│   └── index.php                # Reports & analytics
│
├── users/
│   ├── index.php                # User management list (Admin only)
│   └── create.php               # Create new user (Admin only)
│
├── notifications/
│   └── index.php                # Notifications center
│
├── settings/
│   └── index.php                # System settings (Admin only)
│
├── logs/
│   └── index.php                # Audit logs viewer (Admin only)
│
└── kiosk/
    └── index.php                # Kiosk mode for citizen intake
```

---

## 🔗 Page Navigation Map

### **Public Pages (No Login Required)**
- `/auth/login.php` → Login page
- `/auth/forgot.php` → Password recovery

### **After Login - All Users**
- `/index.php` → **Dashboard** (KPI cards, recent incidents, quick actions)
  - Links to: Incidents, Patrols, Evidence, Reports, Kiosk mode

### **Incident Management Flow**
```
/incidents/index.php (List)
    ↓
    ├── /incidents/create.php (New incident) → /incidents/view.php
    ├── /incidents/view.php (Details)
    │       ↓
    │       ├── /incidents/edit.php (Edit)
    │       ├── /incidents/assign.php (Assign to Tanod)
    │       └── /evidence/index.php (View evidence)
    └── /incidents/assign.php → Back to view.php
```

### **Patrol Management Flow**
```
/patrols/calendar.php (Calendar view)
    ↔ /patrols/list.php (List view)
    ↓
    /patrols/create.php (Schedule new patrol) → Back to calendar
```

### **Evidence Flow**
```
/evidence/index.php (Gallery)
    - View/download evidence
    - Links back to incident details
```

### **Reports Flow**
```
/reports/index.php
    - Generate various reports
    - Export PDF/CSV
```

### **Admin-Only Pages**
```
/users/index.php (User list)
    ↓
    /users/create.php (Add user) → Back to list

/settings/index.php (System settings)

/logs/index.php (Audit logs - read-only)
```

### **Notifications**
```
/notifications/index.php
    - Clickable notifications link to:
        - /incidents/view.php
        - /patrols/calendar.php
```

### **Kiosk Mode**
```
/kiosk/index.php (Citizen intake)
    - Simplified incident reporting
    - Success screen with incident number
    - Links back to main dashboard
```

---

## 🚀 Setup Instructions

### 1. **Install XAMPP/WAMP/LAMP**
   - PHP 7.4+
   - MySQL 5.7+
   - Apache Web Server

### 2. **Create Database**
```sql
CREATE DATABASE smartanod_db;
```

### 3. **Copy Files**
Place all files in your web server directory:
- **Windows (XAMPP)**: `C:/xampp/htdocs/smartanod/`
- **Mac (MAMP)**: `/Applications/MAMP/htdocs/smartanod/`
- **Linux**: `/var/www/html/smartanod/`

### 4. **Update config.php**
Edit database credentials if needed:
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'smartanod_db');
define('DB_USER', 'root');
define('DB_PASS', '');
```

### 5. **Access the System**
Open browser: `http://localhost/smartanod/auth/login.php`

### 6. **Demo Login Credentials**
- **Admin**: `admin` / `admin123`
- **Clerk**: `clerk` / `clerk123`

---

## 🎨 Design Features

### **Glassmorphism UI**
- Translucent cards with backdrop blur
- Orange (#f59e0b) and Green (#10b981) accent colors
- Dark gradient background
- Responsive design with Tailwind CSS

### **Key UI Components**
- Glass cards: `.glass` class
- Status badges with color coding
- Touch-friendly buttons (especially in kiosk mode)
- Responsive tables with hover effects
- Modal-ready evidence gallery

---

## 🔐 Security Features (To Implement)

Current pages have **placeholder security**. For production, add:

1. **PDO Prepared Statements** (already structured for it)
2. **CSRF Token Protection** on all forms
3. **Password Hashing** (bcrypt)
4. **Session Management** with regeneration
5. **Role-Based Access Control** (RBAC)
6. **File Upload Validation** (MIME type checks)
7. **XSS Protection** (htmlspecialchars on outputs)
8. **SQL Injection Prevention** (prepared statements)

---

## 📱 Role-Based Access

### **Admin**
- Full access to all modules
- User management (`/users/`)
- System settings (`/settings/`)
- Audit logs (`/logs/`)

### **Captain**
- View all incidents and patrols
- Assign incidents to Tanod
- View reports

### **Clerk**
- Create/edit incidents
- Upload evidence
- Kiosk mode operation
- View incidents

### **Tanod**
- View assigned incidents
- Update incident status
- View patrol schedules
- Upload evidence

### **Viewer**
- Read-only access to reports
- View incidents (no edit)

---

## 🔄 Page Connection Summary

### **Every Page (except kiosk) includes:**
```php
require_once '../config.php';        // DB & helper functions
require_once '../includes/header.php'; // Navigation menu
// Page content here
require_once '../includes/footer.php'; // Footer
```

### **Navigation Menu** (in header.php)
- **Dashboard** → `/index.php`
- **Incidents** → `/incidents/index.php`
- **Patrols** → `/patrols/calendar.php`
- **Evidence** → `/evidence/index.php`
- **Reports** → `/reports/index.php`
- **Users** (admin only) → `/users/index.php`
- **Settings** (admin only) → `/settings/index.php`
- **Audit Logs** (admin only) → `/logs/index.php`
- **Notifications** → `/notifications/index.php`
- **Logout** → `/auth/logout.php`

---

## 🛠️ Next Steps (For Full Implementation)

1. **Create database tables** (incidents, users, patrols, evidence, etc.)
2. **Implement actual CRUD operations** with PDO
3. **Add real authentication** with password hashing
4. **Implement file upload handling** with security checks
5. **Add Chart.js integration** for reports
6. **Implement FullCalendar** for patrol scheduling
7. **Add real-time notifications** (optional: WebSockets)
8. **Create stored procedures** as mentioned in the document
9. **Add PDF/CSV export functionality**
10. **Implement search/filter queries**

---

## 📞 Support

This is a **connected prototype** focused on navigation flow. The pages are linked together but need backend implementation for full functionality.

---

## 📄 License

Barangay Management System - SmartTanod
© 2025 All Rights Reserved