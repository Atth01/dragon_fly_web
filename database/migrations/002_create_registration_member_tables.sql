
-- =========================================
-- DRAGONFLY SWIMMING CLUB
-- Migration: 002_registration_member
-- =========================================

CREATE TABLE registrations (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    parent_id BIGINT UNSIGNED NOT NULL,
    program_id BIGINT UNSIGNED NOT NULL,

    child_name VARCHAR(150) NOT NULL,
    birth_date DATE NOT NULL,

    gender ENUM('male', 'female') NOT NULL,

    notes TEXT NULL,

    status ENUM(
        'pending',
        'processing',
        'accepted',
        'rejected'
    ) NOT NULL DEFAULT 'pending',

    reviewed_by BIGINT UNSIGNED NULL,
    reviewed_at DATETIME NULL,
    rejection_reason TEXT NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_registrations_parent
        FOREIGN KEY (parent_id)
        REFERENCES parents(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    CONSTRAINT fk_registrations_program
        FOREIGN KEY (program_id)
        REFERENCES programs(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    CONSTRAINT fk_registrations_reviewer
        FOREIGN KEY (reviewed_by)
        REFERENCES users(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    INDEX idx_registrations_status (status),
    INDEX idx_registrations_created (created_at),
    INDEX idx_registrations_child (child_name)

) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;


CREATE TABLE members (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    registration_id BIGINT UNSIGNED NOT NULL UNIQUE,
    parent_id BIGINT UNSIGNED NOT NULL,
    program_id BIGINT UNSIGNED NOT NULL,

    member_code VARCHAR(50) NOT NULL UNIQUE,

    name VARCHAR(150) NOT NULL,
    birth_date DATE NOT NULL,
    gender ENUM('male', 'female') NOT NULL,

    join_date DATE NOT NULL,

    status ENUM(
        'active',
        'inactive'
    ) NOT NULL DEFAULT 'active',

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_members_registration
        FOREIGN KEY (registration_id)
        REFERENCES registrations(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    CONSTRAINT fk_members_parent
        FOREIGN KEY (parent_id)
        REFERENCES parents(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    CONSTRAINT fk_members_program
        FOREIGN KEY (program_id)
        REFERENCES programs(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    INDEX idx_members_name (name),
    INDEX idx_members_status (status),
    INDEX idx_members_program (program_id)

) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;
