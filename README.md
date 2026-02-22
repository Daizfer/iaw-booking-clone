# 🏨 IAW Booking-Style Web Application

Proyecto académico desarrollado en 2º ASIR (Implantación de Aplicaciones Web).

Aplicación web estilo Booking.com con gestión de hoteles, reservas y usuarios, conectada a base de datos MySQL.

---

## 🚀 Características principales

- Registro e inicio de sesión de usuarios
- Gestión de hoteles (crear, editar, eliminar)
- Sistema de reservas
- Panel de administración básico
- Subida de imágenes
- Base de datos sincronizada con MySQL
- Separación de cabecera y pie mediante includes

---

## 🛠️ Tecnologías utilizadas

- PHP (sin framework)
- MySQL / MariaDB
- HTML5 / CSS3
- XAMPP / Apache (entorno de desarrollo)

---

## 🗄️ Base de datos

El archivo `database/gestionhoteles.sql` contiene:

- Tablas de usuarios
- Hoteles
- Habitaciones
- Reservas
- Reseñas

Importar mediante:

```sql
SOURCE gestionhoteles.sql;
