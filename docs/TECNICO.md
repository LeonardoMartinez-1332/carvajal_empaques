# 🛠️ DOCUMENTACIÓN TÉCNICA

## 🏗️ Arquitectura
- **Laravel** como backend MVC y vistas Blade.  
- **Módulo WMS** con middleware de roles.  
- **DB** con tablas: `usuarios`, `turnos`, `productos`, `camiones`, `bitacora_camiones` (+ relaciones).  

## 📦 Modelos y Relaciones (ejemplo)
- `Usuario` → hasOne `Turno` (opcional).  
- `Producto` → belongsTo `Turno` (via `turno_id`).  
- `Camion` → hasMany `BitacoraCamiones`.  
- `BitacoraCamiones` → belongsTo `Camion`.  

## 🧑‍💻 Controladores (ejemplo)
- `AuthController` → login/logout.  
- `ProductoController` → consulta, detalles.  
- `BitacoraController` → listar, crear, eliminar (editar en progreso).  
- `SupervisorController` / `AdminController` → dashboards.  

## 🔑 Middleware y Roles
- `role:usuario|supervisor|superusuario` con redirecciones post-login.  

## 🌐 Rutas clave (`routes/web.php`)
- **Públicas:** `/`, `/historia`, `/mision-vision`, `/blog`  
- **Auth:** `/login`, `/logout`  
- **WMS:** `/consulta-producto`, `/bitacora-camiones`, `/panel-supervisor`, `/panel-superusuario`  

## 🎨 Estilos por Vista
- `resources/css/` → `supervisor.css`, `bitacora.css`, `consulta-producto.css`.  

## 🌱 Seeders / Datos
- `database/seeders/` y `database/seed-data/*.json` (bitácora, productos).  

## ⚙️ Comandos útiles
- **Limpieza caché:**  
  ```bash
  php artisan cache:clear && php artisan config:clear && php artisan route:clear && php artisan view:clear
