
-- =========================================
-- DRAGONFLY SWIMMING CLUB
-- Migration: 004_training_operations
-- =========================================

-- 1. COACH ASSIGNMENTS
CREATE TABLE coach_assignments (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    training_session_id BIGINT UNSIGNED NOT NULL,
    coach_id BIGINT UNSIGNED NOT NULL,

    assignment_notes TEXT NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_assignments_session
        FOREIGN KEY (training_session_id)
        REFERENCES training_sessions(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    CONSTRAINT fk_assignments_coach
        FOREIGN KEY (coach_id)
        REFERENCES coaches(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    UNIQUE KEY uq_session_coach (
        training_session_id,
        coach_id
    )

) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;


-- 2. ATTENDANCE
CREATE TABLE attendance (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    training_session_id BIGINT UNSIGNED NOT NULL,
    member_id BIGINT UNSIGNED NOT NULL,

    status ENUM(
        'present',
        'absent',
        'sick',
        'permission'
    ) NOT NULL DEFAULT 'present',

    recorded_by BIGINT UNSIGNED NOT NULL,
    recorded_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    notes TEXT NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_attendance_session
        FOREIGN KEY (training_session_id)
        REFERENCES training_sessions(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    CONSTRAINT fk_attendance_member
        FOREIGN KEY (member_id)
        REFERENCES members(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    CONSTRAINT fk_attendance_user
        FOREIGN KEY (recorded_by)
        REFERENCES users(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    UNIQUE KEY uq_session_member (
        training_session_id,
        member_id
    ),

    INDEX idx_attendance_status (status)

) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;


-- 3. TRAINING RECORDS
CREATE TABLE training_records (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    training_session_id BIGINT UNSIGNED NOT NULL,
    member_id BIGINT UNSIGNED NOT NULL,

    recorded_by BIGINT UNSIGNED NOT NULL,

    activity_type VARCHAR(100) NOT NULL,
    result_value DECIMAL(12,2) NULL,
    result_unit VARCHAR(30) NULL,

    notes TEXT NULL,

    recorded_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_records_session
        FOREIGN KEY (training_session_id)
        REFERENCES training_sessions(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    CONSTRAINT fk_records_member
        FOREIGN KEY (member_id)
        REFERENCES members(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    CONSTRAINT fk_records_user
        FOREIGN KEY (recorded_by)
        REFERENCES users(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    INDEX idx_records_member (member_id),
    INDEX idx_records_session (training_session_id),
    INDEX idx_records_activity (activity_type)

) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;
