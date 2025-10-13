-- =====================================================
-- Script SQL para Base de Datos: reportes_ciudadanos
-- Generado automáticamente desde migraciones de Laravel
-- =====================================================

-- Eliminar base de datos si existe y crearla nuevamente
DROP DATABASE IF EXISTS `reportes_ciudadanos`;
CREATE DATABASE `reportes_ciudadanos` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `reportes_ciudadanos`;

-- =====================================================
-- TABLA: users
-- =====================================================
CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'ciudadano',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLA: password_resets
-- =====================================================
CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLA: failed_jobs
-- =====================================================
CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLA: personal_access_tokens
-- =====================================================
CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLA: admins
-- =====================================================
CREATE TABLE `admins` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `admins_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLA: categorias
-- =====================================================
CREATE TABLE `categorias` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `icono` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLA: colonias
-- =====================================================
CREATE TABLE `colonias` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLA: solicitudes
-- =====================================================
CREATE TABLE `solicitudes` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `titulo` varchar(255) NOT NULL,
  `descripcion` text NOT NULL,
  `categoria_id` bigint(20) UNSIGNED NOT NULL,
  `colonia_id` bigint(20) UNSIGNED NOT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `lat` decimal(10,8) DEFAULT NULL,
  `lng` decimal(11,8) DEFAULT NULL,
  `estado` varchar(255) NOT NULL DEFAULT 'Pendiente',
  `ciudadano_id` bigint(20) UNSIGNED DEFAULT NULL,
  `funcionario_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `solicitudes_categoria_id_foreign` (`categoria_id`),
  KEY `solicitudes_colonia_id_foreign` (`colonia_id`),
  KEY `solicitudes_ciudadano_id_foreign` (`ciudadano_id`),
  KEY `solicitudes_funcionario_id_foreign` (`funcionario_id`),
  CONSTRAINT `solicitudes_categoria_id_foreign` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`) ON DELETE CASCADE,
  CONSTRAINT `solicitudes_colonia_id_foreign` FOREIGN KEY (`colonia_id`) REFERENCES `colonias` (`id`) ON DELETE CASCADE,
  CONSTRAINT `solicitudes_ciudadano_id_foreign` FOREIGN KEY (`ciudadano_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `solicitudes_funcionario_id_foreign` FOREIGN KEY (`funcionario_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLA: evidencias
-- =====================================================
CREATE TABLE `evidencias` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `solicitud_id` bigint(20) UNSIGNED NOT NULL,
  `ruta_archivo` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `evidencias_solicitud_id_foreign` (`solicitud_id`),
  CONSTRAINT `evidencias_solicitud_id_foreign` FOREIGN KEY (`solicitud_id`) REFERENCES `solicitudes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLA: migrations (Control de migraciones de Laravel)
-- =====================================================
CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- DATOS INICIALES (SEEDERS)
-- =====================================================

-- Insertar usuarios de prueba
INSERT INTO `users` (`id`, `name`, `email`, `role`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Juan Pérez', 'ciudadano@test.com', 'ciudadano', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NOW(), NOW()),
(2, 'María García', 'funcionario@test.com', 'funcionario', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NOW(), NOW());

-- Insertar administradores
INSERT INTO `admins` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Administrador Principal', 'admin@test.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NOW(), NOW()),
(2, 'Supervisor Municipal', 'supervisor@test.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NOW(), NOW());

-- Insertar categorías
INSERT INTO `categorias` (`id`, `nombre`, `descripcion`, `icono`, `created_at`, `updated_at`) VALUES
(1, 'Baches', 'Reporta baches en las calles que afecten la circulación vehicular', 'road', NOW(), NOW()),
(2, 'Alumbrado Público', 'Lámparas fundidas, postes dañados o problemas de iluminación', 'lightbulb', NOW(), NOW()),
(3, 'Fugas de Agua', 'Fugas en tuberías, válvulas o sistemas de agua potable', 'tint', NOW(), NOW()),
(4, 'Limpieza', 'Basura acumulada, grafiti o problemas de higiene urbana', 'broom', NOW(), NOW()),
(5, 'Semáforos', 'Semáforos dañados, fuera de servicio o con fallas', 'traffic-light', NOW(), NOW()),
(6, 'Señalización', 'Señales de tránsito dañadas, faltantes o ilegibles', 'sign', NOW(), NOW());

-- Insertar colonias
INSERT INTO `colonias` (`id`, `nombre`, `created_at`, `updated_at`) VALUES
(1, 'Centro', NOW(), NOW()),
(2, 'Zona Norte', NOW(), NOW()),
(3, 'Zona Sur', NOW(), NOW()),
(4, 'Zona Este', NOW(), NOW()),
(5, 'Zona Oeste', NOW(), NOW()),
(6, 'Residencial', NOW(), NOW());

-- Insertar solicitudes de ejemplo
INSERT INTO `solicitudes` (`id`, `titulo`, `descripcion`, `categoria_id`, `colonia_id`, `direccion`, `lat`, `lng`, `estado`, `ciudadano_id`, `funcionario_id`, `created_at`, `updated_at`) VALUES
(1, 'Bache en calle principal', 'Hay un bache grande en la calle principal que está causando problemas a los vehículos.', 1, 1, 'Calle Principal #123', NULL, NULL, 'Pendiente', 1, NULL, NOW(), NOW()),
(2, 'Lámpara fundida', 'La lámpara de la esquina está fundida desde hace varios días.', 2, 2, 'Esquina de las calles A y B', NULL, NULL, 'En Proceso', 1, 2, NOW(), NOW()),
(3, 'Fuga de agua en la calle', 'Hay una fuga de agua en la calle que está causando charcos.', 3, 3, 'Calle Secundaria #456', NULL, NULL, 'Resuelto', 1, 2, NOW(), NOW());

-- Registrar migraciones ejecutadas
INSERT INTO `migrations` (`migration`, `batch`) VALUES
('2014_10_12_000000_create_users_table', 1),
('2014_10_12_100000_create_password_resets_table', 1),
('2019_08_19_000000_create_failed_jobs_table', 1),
('2019_12_14_000001_create_personal_access_tokens_table', 1),
('2024_01_01_000000_add_role_to_users_table', 1),
('2024_01_01_000001_create_categorias_table', 1),
('2024_01_01_000002_create_colonias_table', 1),
('2024_01_01_000003_create_solicitudes_table', 1),
('2024_01_01_000004_create_evidencias_table', 1),
('2024_01_01_000005_create_admins_table', 1),
('2025_10_09_155323_add_descripcion_and_icono_to_categorias_table', 1);

-- =====================================================
-- INFORMACIÓN IMPORTANTE
-- =====================================================
-- Base de datos creada: reportes_ciudadanos
-- 
-- USUARIOS DE PRUEBA:
-- Ciudadano: ciudadano@test.com / password
-- Funcionario: funcionario@test.com / password
-- 
-- ADMINISTRADORES:
-- Admin: admin@test.com / password
-- Supervisor: supervisor@test.com / password
-- 
-- NOTA: Todas las contraseñas están hasheadas con bcrypt
-- La contraseña para todos es: password
-- =====================================================

