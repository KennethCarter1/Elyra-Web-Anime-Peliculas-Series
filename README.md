# Elyra-Web-Anime-Peliculas-Series
Este repositorio esta hecho para subir el proyecto final de Desarollo de Software VII

# Proyecto Final – Desarrollo de Software VII

**Universidad Tecnológica de Panamá**  
**Facultad de Ingeniería de Sistemas Computacionales**  
**Departamento de Programación de Computadoras**  
**Desarrollo de Software VII**  
**Valor total:** 100 puntos  

---

## Objetivo General

Desarrollar una aplicación web dinámica utilizando PHP acerca de una **Plataforma de Recomendación de Películas/Series**.  
La aplicación debe permitir a los usuarios registrarse, iniciar sesión, explorar películas o series, y recibir recomendaciones personalizadas según sus intereses, utilizando **cookies**, **sesiones** y **webservices con XML/JSON**.

---

## Requisitos

### 1. Autenticación de Usuarios
- Registro e inicio de sesión de usuarios.
- Roles de usuario: **usuario estándar** y **administrador**.

### 2. Manejo de Sesiones y Cookies
- Usar sesiones para mantener el inicio de sesión.
- Usar cookies para personalizar la experiencia (temas, nombre del usuario, últimas vistas, etc.).
- Diferenciar la experiencia del usuario según el rol.

### 3. Base de Datos
- Guardar información de usuarios, películas/series, y calificaciones o preferencias del usuario.
- Relación adecuada entre tablas (`usuarios`, `contenido`, `géneros`, etc.).

### 4. Formulario de Registro de Preferencias
- Los usuarios podrán seleccionar sus géneros favoritos mediante checkboxes.
- Estas preferencias se usan para filtrar recomendaciones.

### 5. Sistema de Recomendaciones
- Mostrar contenido recomendado basado en los géneros seleccionados o el historial de navegación del usuario.

### 6. Administrador
- Puede agregar, editar o eliminar películas/series.
- Puede ver un resumen del comportamiento de los usuarios (por ejemplo, géneros más visitados).

### 7. Consumo de Webservices
- Usar al menos un archivo **XML** y un **JSON** para alimentar o intercambiar información con la aplicación (ej: cargar contenido, guardar/exportar datos).

### 8. Seguridad
- Validación y sanitización de formularios para evitar vulnerabilidades (**XSS**, **SQLi** y fuerza bruta).
- Estructura organizada de carpetas para restringir el acceso directo a ficheros sensibles.

### 9. Interfaz
- Diseño amigable, organizado por secciones (inicio, recomendaciones, perfil, etc.).
- Personalización visual básica según usuario (por ejemplo: tema claro/oscuro con cookies).

---

## Evaluación

- Se llamarán a los grupos de 2 en 2 aleatoriamente.  
- Un grupo presentará su aplicación y el otro grupo hará las pruebas de la aplicación presentada.  
- Cada grupo tendrá un turno para presentar la aplicación desarrollada y un turno para realizar pruebas.  
- Presentar la aplicación y que la misma responda adecuadamente a las distintas pruebas (**50 pts**).  

- Poner a prueba la aplicación en presentación para hallar fallas, ya sean de seguridad o de introducción de datos.  
- Cada grupo contará con un máximo de 20 minutos para realizar sus pruebas.  
- Las pruebas ya deben estar previamente listadas y programadas por el grupo; no se permite improvisar en las pruebas.  
- Las pruebas con sus resultados deben documentarse, ya sea en un documento **Word**, **PDF** o **Markdown** (**50 pts**).  

---
