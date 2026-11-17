
-- =========================================
-- DRAGONFLY SWIMMING CLUB
-- Migration: 003_coach_schedule
-- =========================================

-- 1. COACHES
CREATE TABLE coaches (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    user_id BIGINT UNSIGNED NULL UNIQUE,

    name VARCHAR(150) NOT NULL,
    phone VARCHAR(25) NULL,
    specialization VARCHAR(150) NULL,

    status ENUM('active', 'inactive')
        NOT NULL DEFAULT 'active',

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_coaches_user
        FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON UPDATE CASCADE
        ON DELETE SET NULL,

    INDEX idx_coaches_name (name),
    INDEX idx_coaches_status (status)

) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;


-- 2. SCHEDULES
CREATE TABLE schedules (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    program_id BIGINT UNSIGNED NOT NULL,

    day_of_week TINYINT UNSIGNED NOT NULL,

    start_time TIME NOT NULL,
    end_time TIME NOT NULL,

    location VARCHAR(150) NULL,

    status ENUM('active', 'inactive')
        NOT NULL DEFAULT 'active',

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_schedules_program
        FOREIGN KEY (program_id)
        REFERENCES programs(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    INDEX idx_schedules_program (program_id),
    INDEX idx_schedules_day (day_of_week),
    INDEX idx_schedules_status (status)

) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;


-- 3. TRAINING SESSIONS
CREATE TABLE training_sessions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    schedule_id BIGINT UNSIGNED NOT NULL,

    session_date DATE NOT NULL,

    start_time TIME NOT NULL,
    end_time TIME NOT NULL,

    status ENUM(
        'planned',
        'completed',
        'cancelled'
    ) NOT NULL DEFAULT 'planned',

    notes TEXT NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_sessions_schedule
        FOREIGN KEY (schedule_id)
        REFERENCES schedules(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    INDEX idx_sessions_date (session_date),
    INDEX idx_sessions_status (status),
    INDEX idx_sessions_schedule_date (
        schedule_id,
        session_date
    )

) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;
