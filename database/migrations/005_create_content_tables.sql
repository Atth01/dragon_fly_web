
-- =========================================
-- DRAGONFLY SWIMMING CLUB
-- Migration: 005_content_management
-- =========================================

-- 1. GALLERY
CREATE TABLE gallery (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    title VARCHAR(150) NOT NULL,
    media_type ENUM('image', 'video') NOT NULL,

    media_url VARCHAR(500) NOT NULL,
    thumbnail_url VARCHAR(500) NULL,

    description TEXT NULL,
    event_date DATE NULL,

    is_published BOOLEAN NOT NULL DEFAULT FALSE,

    created_by BIGINT UNSIGNED NOT NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_gallery_creator
        FOREIGN KEY (created_by)
        REFERENCES users(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    INDEX idx_gallery_type (media_type),
    INDEX idx_gallery_published (is_published),
    INDEX idx_gallery_event_date (event_date)

) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;


-- 2. ACHIEVEMENTS
CREATE TABLE achievements (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    member_id BIGINT UNSIGNED NULL,

    title VARCHAR(200) NOT NULL,
    competition_name VARCHAR(200) NULL,

    achievement_date DATE NULL,

    description TEXT NULL,
    image_url VARCHAR(500) NULL,

    is_published BOOLEAN NOT NULL DEFAULT FALSE,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_achievements_member
        FOREIGN KEY (member_id)
        REFERENCES members(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    INDEX idx_achievements_member (member_id),
    INDEX idx_achievements_published (is_published),
    INDEX idx_achievements_date (achievement_date)

) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;


-- 3. SITE CONTENTS
CREATE TABLE site_contents (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    content_key VARCHAR(100) NOT NULL UNIQUE,

    title VARCHAR(200) NOT NULL,
    body LONGTEXT NULL,

    image_url VARCHAR(500) NULL,

    is_published BOOLEAN NOT NULL DEFAULT FALSE,

    updated_by BIGINT UNSIGNED NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_site_contents_user
        FOREIGN KEY (updated_by)
        REFERENCES users(id)
        ON UPDATE CASCADE
        ON DELETE SET NULL,

    INDEX idx_site_contents_published (is_published)

) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;
