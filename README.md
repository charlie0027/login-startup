# 🚀 Laravel + Vue + Inertia Starter

<p align="center">
  <img src="https://raw.githubusercontent.com/laravel/art/master/laravel-logo.png" alt="Laravel Logo" width="120"/>
  <img src="https://vuejs.org/images/logo.png" alt="Vue Logo" width="100"/>
  <img src="https://brandfetch.com/inertiajs.com?view=library&library=default&collection=logos&asset=id8_--kLGR&utm_source=https%253A%252F%252Fbrandfetch.com%252Finertiajs.com&utm_medium=copyAction&utm_campaign=brandPageReferral" alt="Inertia Logo" width="80"/>
</p>

---

## 📖 Overview
This project is built using **Laravel 12** (backend), **Vue 3** (frontend), and **Inertia.js version 2** (glue between them).  
It provides a modern full‑stack workflow without the complexity of a traditional SPA.

---

## 🛠 Features
- ⚡ Laravel backend with migrations, seeders, and authentication
- 🎨 Vue 3 components with TailwindCSS styling
- 🔗 Inertia.js for seamless server‑driven navigation
- 📦 Ready‑to‑use pagination, tab components, and search filters
- 🛡️ Role-based access control
- 📂 Sample Report generation
---

## 🚀 Getting Started

### 1. Clone the repository
```bash
git clone https://github.com/charlie0027/login-startup.git
cd your-project
```

### 2. Install Dependencies
```bash
composer install 
npm install
```

### 3. Environment setup and Generate application key
```bash
cp .env.example .env 
php artisan key:generate
```

### 4. Run migrations and seeders
```bash
php artisan migrate
php artisan db:seed
```

### 5. Set up storage link
```bash
php artisan storage:link
```

### 6. If Inertia is included in the application
```bash
npm install @inertiajs/vue3
```

### 7. Clear and cache configs (optional but common)
```bash
php artisan config:clear 
php artisan cache:clear 
php artisan route:clear 
php artisan view:clear
```

### 8. Start development servers
```bash
php artisan serve 
npm run dev
```








