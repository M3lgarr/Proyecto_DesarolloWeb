# 🏀 Torneo Basket — Sistema de Gestión

Este proyecto es una aplicación web desarrollada con **PHP + Slim Framework** y **MySQL**, diseñada para administrar torneos de baloncesto.  
Permite gestionar **equipos**, **jugadores** y próximamente **partidos** y **tablas de posiciones** de forma sencilla, moderna y con alertas visuales amigables.

---

## 🚀 Características principales

- ✨ Interfaz moderna y responsiva con **Bootstrap 5**.  
- 🧑‍🤝‍🧑 Gestión de **Equipos** (crear, editar, eliminar, listar).  
- 🏃 Gestión de **Jugadores** con carga de fotografía y asignación a equipos.  
- 📸 Subida de imágenes con validaciones de tipo y tamaño.  
- 🔔 Alertas **toast dinámicas** con **SweetAlert2** para feedback de acciones.  
- 🧭 Navegación clara con barra superior y diseño minimalista.  
- 🧰 Código organizado por capas (Controllers, Repositories, Views, Utils).

---

## 🧾 Requisitos previos

- PHP 8.1 o superior  
- Composer  
- MySQL / MariaDB  
- XAMPP o equivalente (entorno local)

---

## 🧰 Tecnologías usadas

| Tipo                      | Herramienta / Librería                                 |
|----------------------------|-------------------------------------------------------|
| Backend                    | [Slim Framework](https://www.slimframework.com/)       |
| Base de datos              | MySQL / MariaDB                                       |
| Frontend UI                | Bootstrap 5, Bootstrap Icons                           |
| Alertas y notificaciones   | [SweetAlert2](https://sweetalert2.github.io/)          |
| Control de versiones       | Git + GitHub                                          |

---

## 📂 Estructura del proyecto

torneo-basket-slim/
│── app/ # Configuración y bootstrap de Slim
│── public/ # Archivos públicos (CSS, JS, imágenes, index.php)
│── src/
│ ├── Controllers/ # Controladores (Equipos, Jugadores, etc.)
│ ├── Repositories/ # Acceso a la base de datos
│ ├── Utils/ # Utilidades generales (View.php)
│ └── Views/ # Vistas PHP con Bootstrap
│── assets/
│ └── css/ # Estilos CSS separados (layout.css, home.css)
│── vendor/ # Dependencias de Composer
│── .gitignore
│── composer.json
│── README.md


---

## ⚡ Instalación

1. **Clonar el repositorio**

```bash
git clone https://github.com/TU_USUARIO/torneo-basket-slim.git
cd torneo-basket-slim

Instalar dependencias
composer install

Configurar base de datos
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=torneo_basket
DB_USERNAME=root
DB_PASSWORD=

Levantar el servidor local
php -S localhost:8080 -t public


🧭 Módulos implementados
Módulo	Estado	Descripción
🧑 Equipos	✅ Completado	CRUD completo con alertas toast
🏃 Jugadores	✅ Completado	CRUD + subida de foto + relación con equipos
🏀 Partidos	⏳ Pendiente	CRUD + marcador + estado
🏆 Tabla de posiciones	🕓 Planeado	Cálculo automático desde partidos finalizados
🧼 Buenas prácticas aplicadas

Repositorios para separar acceso a datos.
    Controladores limpios con redirecciones + toasts.
    SweetAlert2 para feedback claro de las acciones.
    Archivos CSS separados (layout general y páginas específicas).
    .gitignore para evitar subir archivos sensibles o pesados.
    Uso de ramas (main y feature/partidos) para desarrollo organizado.

📜 Licencia

Este proyecto está bajo licencia MIT.
Puedes usarlo libremente con fines educativos y de desarrollo.

✍️ Autores

Julio David Melgar Barillas

David Sergio Samuel Vazques Samayoa

📅 Versión: 1.0 — “Primera versión funcional”