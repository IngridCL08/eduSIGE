# eduSIGE — Sistema Integral de Gestión Educativa

> Plataforma web desarrollada en Laravel 11 para la administración integral de procesos de admisión, control escolar y recursos financieros en instituciones de Educación Superior (IES).

---

## Índice

1. [Descripción General](#descripción-general)
2. [Módulos del Sistema](#módulos-del-sistema)
3. [Roles y Permisos](#roles-y-permisos)
4. [Stack Tecnológico](#stack-tecnológico)
5. [Estructura del Proyecto](#estructura-del-proyecto)
6. [Documentación Adicional](#documentación-adicional)
7. [Instalación Rápida](#instalación-rápida)
8. [Capturas de Pantalla](#capturas-de-pantalla)

---

## Descripción General

**eduSIGE** es un sistema de gestión educativa orientado a instituciones de educación superior. Centraliza los procesos de admisión de aspirantes, seguimiento de pagos de fichas, control escolar y generación de reportes estadísticos.

El sistema está dividido en dos grandes departamentos con acceso diferenciado:

| Departamento | Enfoque Principal |
|---|---|
| **Recursos Financieros** | Fichas de pago, ingresos, pagos de aspirantes, integración con pasarela de cobro |
| **Control Escolar** | Datos académicos de aspirantes y alumnos, estadísticas escolares, historial |

Un **Super Administrador** tiene acceso total al sistema, incluyendo configuración de usuarios, roles y parámetros globales.

---

## Módulos del Sistema

### A. SUPER ADMINISTRADOR

| Módulo | Descripción |
|---|---|
| Dashboard Global | Métricas unificadas del sistema |
| Gestión de Usuarios | CRUD de usuarios con asignación de roles |
| Gestión de Roles y Permisos | Configuración de accesos por rol |
| Configuración del Sistema | Parámetros generales (institución, ciclos, pasarela de pagos) |
| Bitácora del Sistema | Registro de actividad y auditoría |
| Gestión de Carreras | Alta, baja y modificación de programas académicos |
| Gestión de Períodos | Administración de ciclos escolares |

---

### B. RECURSOS FINANCIEROS

| Módulo | Descripción |
|---|---|
| Dashboard Financiero | KPIs de ingresos, fichas pagadas/pendientes, gráficas por período |
| Fichas de Pago | Listado completo con filtros por estado, fecha y carrera |
| Aspirantes (Vista Financiera) | Datos básicos + estado de pago de cada aspirante |
| Generación de Fichas PDF | Emisión de comprobante PDF individual por aspirante |
| Reportes de Ingresos | Consolidado de cobros por período, carrera o método de pago |
| Exportación a Excel | Exportar cualquier listado filtrado a `.xlsx` |
| Pasarela de Pagos | Integración con Conekta / PayPal para cobro en línea |
| Estado de Pago | Seguimiento: pendiente → pagado → vencido / cancelado |

---

### C. CONTROL ESCOLAR

| Módulo | Descripción |
|---|---|
| Dashboard Escolar | Estadísticas de aspirantes activos, alumnos por carrera/período |
| Gestión de Aspirantes | CRUD de aspirantes con datos personales y académicos |
| Proceso de Admisión | Seguimiento del estatus del aspirante (inscrito, en proceso, rechazado, etc.) |
| Documentación Requerida | Control de documentos entregados por aspirante |
| Gestión de Alumnos | CRUD de alumnos matriculados con datos completos |
| Historial Académico | Registro de materias, calificaciones y avance |
| Estadísticas Escolares | Gráficas por carrera, período, sexo, municipio de origen |
| Exportación a Excel | Listados de aspirantes y alumnos exportables |

---

## Roles y Permisos

```
Super Administrador
  ├── Acceso total a todos los módulos
  ├── Configuración del sistema
  └── Gestión de usuarios y roles

Recursos Financieros
  ├── Dashboard Financiero
  ├── Fichas de Pago (ver, crear, actualizar estado)
  ├── Aspirantes (solo datos básicos + estado de pago)
  ├── Reportes financieros (PDF y Excel)
  └── Consulta de pasarela de pagos

Control Escolar
  ├── Dashboard Escolar
  ├── Aspirantes (datos académicos completos)
  ├── Alumnos (CRUD completo)
  ├── Proceso de admisión
  ├── Documentación de aspirantes
  └── Estadísticas y exportación Excel
```

---

## Stack Tecnológico

### Backend
| Tecnología | Versión | Uso |
|---|---|---|
| PHP | 8.2+ | Lenguaje base |
| Laravel | 11.x | Framework principal |
| Laravel Breeze | 2.x | Autenticación base |
| Spatie Permission | 6.x | Roles y permisos |
| Laravel Excel (Maatwebsite) | 3.x | Exportación a Excel |
| DomPDF (barryvdh) | 2.x | Generación de PDF |
| Conekta PHP SDK | 6.x | Pasarela de pagos |
| Laravel Auditing | 13.x | Bitácora de actividad |

### Base de Datos
| Tecnología | Versión | Uso |
|---|---|---|
| MySQL | 8.0+ | Base de datos principal |

### Frontend
| Tecnología | Versión | Uso |
|---|---|---|
| Blade | (Laravel nativo) | Motor de plantillas |
| Tailwind CSS | 3.x | Framework de estilos |
| Alpine.js | 3.x | Interactividad JS ligera |
| ApexCharts | 3.x | Gráficas interactivas en dashboard |
| Vite | 5.x | Bundler de assets |

### Servidor (Producción/Pruebas)
| Componente | Versión |
|---|---|
| Ubuntu Server | 22.04 LTS |
| Nginx | 1.24+ |
| PHP-FPM | 8.2+ |
| MySQL | 8.0+ |
| Composer | 2.x |
| Node.js | 20 LTS |

---

## Estructura del Proyecto

```
eduSIGE/
├── app/
│   ├── Console/
│   ├── Exceptions/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/
│   │   │   ├── Admin/               # Controladores Super Admin
│   │   │   │   ├── UserController.php
│   │   │   │   ├── RoleController.php
│   │   │   │   ├── CarreraController.php
│   │   │   │   ├── PeriodoController.php
│   │   │   │   └── ConfigController.php
│   │   │   ├── Financiero/          # Controladores Recursos Financieros
│   │   │   │   ├── DashboardController.php
│   │   │   │   ├── FichaPagoController.php
│   │   │   │   ├── AspiranteFinancieroController.php
│   │   │   │   ├── ReporteController.php
│   │   │   │   └── PasarelaController.php
│   │   │   └── Escolar/             # Controladores Control Escolar
│   │   │       ├── DashboardController.php
│   │   │       ├── AspiranteController.php
│   │   │       ├── AlumnoController.php
│   │   │       ├── DocumentoController.php
│   │   │       └── EstadisticaController.php
│   │   ├── Middleware/
│   │   │   ├── CheckRole.php
│   │   │   └── LogActivity.php
│   │   └── Requests/
│   │       ├── Aspirante/
│   │       ├── Alumno/
│   │       └── FichaPago/
│   ├── Models/
│   │   ├── User.php
│   │   ├── Aspirante.php
│   │   ├── Alumno.php
│   │   ├── FichaPago.php
│   │   ├── Transaccion.php
│   │   ├── Carrera.php
│   │   ├── Periodo.php
│   │   ├── Documento.php
│   │   └── Bitacora.php
│   └── Services/
│       ├── PagoService.php          # Lógica de integración de pagos
│       ├── ReporteService.php       # Generación de reportes
│       └── EstadisticaService.php   # Cálculos estadísticos
├── database/
│   ├── migrations/
│   └── seeders/
│       ├── RoleSeeder.php
│       ├── UserSeeder.php
│       ├── CarreraSeeder.php
│       └── PeriodoSeeder.php
├── resources/
│   ├── views/
│   │   ├── layouts/
│   │   │   ├── app.blade.php        # Layout principal
│   │   │   ├── admin.blade.php      # Layout super admin
│   │   │   ├── financiero.blade.php # Layout recursos financieros
│   │   │   └── escolar.blade.php    # Layout control escolar
│   │   ├── auth/
│   │   ├── admin/
│   │   ├── financiero/
│   │   ├── escolar/
│   │   └── components/
│   ├── css/
│   └── js/
├── routes/
│   ├── web.php
│   ├── admin.php
│   ├── financiero.php
│   └── escolar.php
├── docs/
│   ├── PROJECT.md          # Este documento
│   ├── ARCHITECTURE.md     # Arquitectura técnica detallada
│   ├── DATABASE.md         # Esquema de base de datos
│   └── INSTALLATION.md     # Guía de instalación en Ubuntu
└── tests/
```

---

## Documentación Adicional

| Documento | Descripción |
|---|---|
| [docs/ARCHITECTURE.md](docs/ARCHITECTURE.md) | Diagrama de arquitectura, flujos de datos, decisiones técnicas |
| [docs/DATABASE.md](docs/DATABASE.md) | Esquema completo de base de datos con relaciones |
| [docs/INSTALLATION.md](docs/INSTALLATION.md) | Guía paso a paso para instalar en Ubuntu Server 22.04 |

---

## Instalación Rápida

```bash
# Clonar el repositorio
git clone https://github.com/tu-usuario/eduSIGE.git
cd eduSIGE

# Instalar dependencias PHP
composer install

# Instalar dependencias Node
npm install && npm run build

# Configurar entorno
cp .env.example .env
php artisan key:generate

# Migrar y sembrar base de datos
php artisan migrate --seed

# Servidor de desarrollo
php artisan serve
```

Acceso por defecto: `http://localhost:8000`

| Usuario | Contraseña | Rol |
|---|---|---|
| admin@edusige.com | *(ver seeder)* | Super Administrador |
| financiero@edusige.com | *(ver seeder)* | Recursos Financieros |
| escolar@edusige.com | *(ver seeder)* | Control Escolar |

> Para instalación en servidor Ubuntu, ver [docs/INSTALLATION.md](docs/INSTALLATION.md).

---

## Paleta de Colores

| Color | Hex | Uso |
|---|---|---|
| Azul Marino | `#0F172A` | Sidebar, headers |
| Azul Medio | `#1E3A5F` | Botones primarios, acentos |
| Azul Claro | `#3B82F6` | Links, estados activos |
| Negro | `#0A0A0A` | Texto principal |
| Gris Claro | `#F1F5F9` | Fondo de paneles |
| Blanco | `#FFFFFF` | Cards, fondos secundarios |

---

*eduSIGE v1.0 — Desarrollado con Laravel 11 + Tailwind CSS*
