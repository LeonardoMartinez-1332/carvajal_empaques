Proyecto académico-profesional para la **Estadía**. Sistema web en **Laravel** con módulos públicos (Inicio, Historia, Misión y Visión, Blog) y zona de **Herramientas WMS** con autenticación y **roles** (usuario, supervisor y superusuario). Incluye consulta de productos, bitácora de camiones y paneles por rol.

## 🎯 Objetivo
Demostrar un sistema web funcional con **autenticación, autorización por roles, operaciones CRUD**, carga de datos desde JSON y flujo operativo de inventario (consulta de productos y bitácora), siguiendo buenas prácticas.

## 🧱 Stack
- **Framework**: Laravel 10+
- **Base de Datos**: MySQL 8+ (dev: MariaDB compatible)
- **Frontend**: Blade, Bootstrap/Tailwind (según vistas actuales)
- **Autenticación**: Laravel Auth / Fortify (según implementación)
- **Despliegue**: Localhost / Hosting PHP / Docker (opcional)

## 📦 Módulos principales
- **Público**: Inicio, Historia, Misión y Visión, Blog.
- **Privado (WMS)**:
  - **Consulta de Productos** (vista con buscador y detalle).
  - **Bitácora de Camiones** (registro, consulta, eliminación; edición en proceso).
  - **Panel Supervisor** (accesos rápidos y acciones permitidas).
  - **Panel Superusuario** (accesos completos y administración).

## 🔐 Roles y Accesos
- **Usuario**: Consulta productos.
- **Supervisor**: Consulta productos + Bitácora de camiones.
- **Superusuario**: Acceso completo (productos, bitácora y admin).

## 📁 Estructura del Proyecto
```
app/
bootstrap/
config/
database/
  ├─ migrations/
  ├─ seeders/
  └─ seed-data/
      ├─ bitacora.json
      └─ productos.json
public/
resources/
  ├─ views/
  │  ├─ auth/
  │  ├─ layouts/
  │  ├─ panel-supervisor.blade.php
  │  ├─ panel-superusuario.blade.php
  │  ├─ consulta-producto.blade.php
  │  └─ bitacora_camiones.blade.php
  └─ css/
      ├─ supervisor.css
      ├─ bitacora.css
      └─ consulta-producto.css
routes/
  ├─ web.php
  └─ api.php
docs/
  ├─ INSTALACION.md
  ├─ TECNICO.md
  └─ PRESENTACION.md
```

## ⚙️ Configuración Rápida (Dev)
1. Clonar repo y entrar a la carpeta
```bash
git clone https://github.com/REEMPLAZAR_USUARIO/REEMPLAZAR_REPO.git
cd REEMPLAZAR_REPO
```
2. Instalar dependencias
```bash
composer install
npm install && npm run build # o npm run dev
```
3. Variables de entorno
```bash
cp .env.example .env
php artisan key:generate
```
4. Configurar DB en `.env` y correr migraciones/seed
```bash
php artisan migrate --seed
# Opcional: importar datos JSON si ya cuentas con seeder para bitácora y productos
```
5. Levantar servidor
```bash
php artisan serve
```

## 🧪 Datos de prueba (ejemplo)
- **Usuario**: supervisor@example.com / **Pass**: password
- **Usuario**: admin@example.com / **Pass**: password
> Reemplazar por tus credenciales de prueba.

## 🚦 Rutas Clave
- `/login`
- `/panel-supervisor`
- `/panel-superusuario`
- `/consulta-producto`
- `/bitacora-camiones`

## 📝 Scripts útiles
```bash
# Formato del código (si usas Laravel Pint)
./vendor/bin/pint

# Limpieza de cachés
php artisan cache:clear && php artisan config:clear && php artisan route:clear && php artisan view:clear
```

## 🔄 Flujo de trabajo con Git
```bash
git checkout -b dev
# ... desarrollas
git add . && git commit -m "feat: módulo consulta producto"
git push -u origin dev
# PR de dev → main cuando cierres un bloque
```

## 📄 Licencia
MIT (ajusta si la universidad requiere otro formato).

## 👤 Autor
**Nombre:** Leonardo Teófilo Martínez 
**Rol:** Estudiante 10°A  | Estadia Profesional
**Contacto:** 542211452@upgarcia.edu.mx
```

---
