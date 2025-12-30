# EAR Shyogwe - Church Management System

A comprehensive web-based management system designed for EAR Shyogwe Diocese to manage multiple churches, financial records, attendance, and reporting.

## 🚀 Key Features

### 1. Multi-Level Access Control
- **Boss (Diocese Admin):** Full view of all churches, aggregate analytics, and system settings.
- **Archid (Regional Pastor):** Manages a specific group of assigned churches.
- **Pastor:** Access limited to their specific church's data.

### 2. Financial Management
*   **Giving & Tithes:**
    *   Record weekly/daily givings by type (Tithes, Offerings, etc.)
    *   Track transfers to Diocese (20% or custom amounts)
    *   Generate financial reports (CSV/Print)
*   **Expenses:**
    *   Track church expenses by category
    *   Receipt upload and storage
    *   Approval workflow for large expenses

### 3. Church Administration
*   **Attendance Tracking:**
    *   Record service attendance (Men, Women, Children)
    *   Dynamic Service Types (Sunday Service, Prayer Meeting, etc.)
    *   Statistical analysis of church growth
*   **Evangelism Reports:**
    *   Monthly reporting on converts, baptisms, and new members
    *   Discipleship tracking
*   **Activity Logging:**
    *   Audit trail of who created/edited/deleted records

### 4. Department & User Management
*   **HR Module:** Worker contracts, renewals, and retirement planing.
*   **User Management:** Create and manage user accounts and roles.
*   **Notifications:** Alerts for submitted expenses and contract expiries.

---

## 🛠 Tech Stack

- **Framework:** Laravel 9.x
- **Language:** PHP 8.1+
- **Database:** MySQL
- **CSS Framework:** Tailwind CSS (via Laravel Breeze)
- **Authentication:** Laravel Breeze & Spatie Permission

---

## ⚙️ Installation & Setup

1.  **Clone the repository:**
    ```bash
    git clone https://github.com/your-repo/earshyogwe.git
    cd earshyogwe
    ```

2.  **Install Dependencies:**
    ```bash
    composer install
    npm install && npm run build
    ```

3.  **Environment Setup:**
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```
    *Configure your database credentials in `.env`*

4.  **Database Migration & Seeding:**
    ```bash
    php artisan migrate --seed
    ```
    *This will create the default roles (Boss, Archid, Pastor) and initial data.*

5.  **Run the Server:**
    ```bash
    php artisan serve
    ```

---

## 👤 User Roles & Default Credentials

| Role | Access Level | Notes |
|------|--------------|-------|
| **Boss** | Diocese Admin | Can see everything. |
| **Archid** | Regional Admin | Can see assigned churches. |
| **Pastor** | Local Admin | Can only see their church. |

---

## 📝 Usage Guide

### For Pastors
1.  **Login** using your provided credentials.
2.  **Dashboard:** Overview of your church's finances and attendance.
3.  **Record Data:** Use the sidebar to add Givings, Expenses, or Attendance.
4.  **Reports:** Use the "Export CSV" button on list pages to download reports.

### For Archids
1.  **Dashboard:** Overview of all churches in your region.
2.  **Verify:** Check submitted reports from your pastors.
3.  **Filter:** Use the filter bar to select a specific church to view.

### For Boss
1.  **Global View:** Aggregated data for the entire diocese.
2.  **Management:** Use "Service Types", "Giving Types", and "Users" to configure the system.
3.  **Audit:** Check "Audit Logs" to see user activity.

---

## 🛡 Security

- **Data Isolation:** Middleware ensures pastors cannot access data from other churches.
- **Role Permissions:** Granular permissions controlled via Spatie Permission.
- **Validation:** All inputs are validated; uploads are checked for file type and size.

---

## 📄 License

Proprietary software for EAR Shyogwe Diocese.
