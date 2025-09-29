# 🚀 INSTALACIÓN Y ARRANQUE RÁPIDO

## 📋 Requisitos
- PHP 8.2+
- Composer 2+
- Node 18+
- MySQL 8+ (o MariaDB 10.6+)

## ⚙️ Pasos
1. Clonar el repositorio y entrar a la carpeta.  
2. `composer install`  
3. `npm install && npm run build` (o `npm run dev` en desarrollo)  
4. Copiar `.env.example` a `.env` y configurar credenciales.  
5. `php artisan key:generate`  
6. `php artisan migrate --seed`  
7. `php artisan serve`  

---

*Si cargas datos desde JSON (bitacora/productos), incluye seeders y coloca los archivos en `database/seed-data/`.*
