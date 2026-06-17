CREATE TABLE AQUALI_users (
    id CHAR(36) NOT NULL,
    name VARCHAR(50) NOT NULL,
    surname VARCHAR(50) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') NOT NULL DEFAULT 'user',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE AQUALI_format_reponses (
    id CHAR(36) NOT NULL,
    type ENUM('texte', 'qcu', 'qcm', 'evn') NOT NULL DEFAULT 'texte',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE AQUALI_repondants (
    id CHAR(36) NOT NULL,
    session_id VARCHAR(255) NOT NULL UNIQUE,
    started_at TIMESTAMP NULL,
    completed_at TIMESTAMP NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE AQUALI_items (
    id CHAR(36) NOT NULL,
    user_id CHAR(36) NOT NULL,
    format_reponse_id CHAR(36) NOT NULL,
    question VARCHAR(300) NOT NULL,
    min_case_to_check INT NULL,
    max_case_to_check INT NULL,
    obligatoire TINYINT(1) NOT NULL DEFAULT 1,
    nom_court VARCHAR(30) NOT NULL,
    archived TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    PRIMARY KEY (id),
    CONSTRAINT fk_items_user FOREIGN KEY (user_id) REFERENCES AQUALI_users(id) ON DELETE CASCADE,
    CONSTRAINT fk_items_format_reponse FOREIGN KEY (format_reponse_id) REFERENCES AQUALI_format_reponses(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE AQUALI_bank_items (
    id CHAR(36) NOT NULL,
    user_id CHAR(36) NOT NULL,
    name VARCHAR(255) NOT NULL,
    archived TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    PRIMARY KEY (id),
    CONSTRAINT fk_bank_items_user FOREIGN KEY (user_id) REFERENCES AQUALI_users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE AQUALI_enquetes (
    id CHAR(36) NOT NULL,
    user_id CHAR(36) NOT NULL,
    title VARCHAR(255) NOT NULL,
    description VARCHAR(800) NOT NULL,
    start_message VARCHAR(800) NOT NULL,
    end_message VARCHAR(800) NOT NULL,
    archived TINYINT(1) NOT NULL DEFAULT 0,
    url_enquete VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    PRIMARY KEY (id),
    CONSTRAINT fk_enquetes_user FOREIGN KEY (user_id) REFERENCES AQUALI_users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE AQUALI_modalite_reponses (
    id CHAR(36) NOT NULL,
    format_reponse_id CHAR(36) NOT NULL,
    item_id CHAR(36) NOT NULL,
    intitule VARCHAR(255) NULL DEFAULT 'pas intitulé',
    ordre INT UNSIGNED NOT NULL DEFAULT 0,
    min_value VARCHAR(255) NULL,
    max_value VARCHAR(255) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    PRIMARY KEY (id),
    CONSTRAINT fk_modalite_format_reponse FOREIGN KEY (format_reponse_id) REFERENCES AQUALI_format_reponses(id) ON DELETE CASCADE,
    CONSTRAINT fk_modalite_item FOREIGN KEY (item_id) REFERENCES AQUALI_items(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE AQUALI_bank_item_items (
    id CHAR(36) NOT NULL,
    bank_item_id CHAR(36) NOT NULL,
    item_id CHAR(36) NOT NULL,
    ordre INT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uq_bank_item_items (bank_item_id, item_id),
    CONSTRAINT fk_bii_bank_item FOREIGN KEY (bank_item_id) REFERENCES AQUALI_bank_items(id) ON DELETE CASCADE,
    CONSTRAINT fk_bii_item FOREIGN KEY (item_id) REFERENCES AQUALI_items(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE AQUALI_enquete_banks (
    id CHAR(36) NOT NULL,
    enquete_id CHAR(36) NOT NULL,
    bank_item_id CHAR(36) NOT NULL,
    ordre INT NULL,
    mode ENUM('systematique', 'aleatoire') NOT NULL DEFAULT 'systematique',
    nombre_items_aleatoires INT UNSIGNED NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uq_enquete_banks (enquete_id, bank_item_id),
    CONSTRAINT fk_eb_enquete FOREIGN KEY (enquete_id) REFERENCES AQUALI_enquetes(id) ON DELETE CASCADE,
    CONSTRAINT fk_eb_bank_item FOREIGN KEY (bank_item_id) REFERENCES AQUALI_bank_items(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE AQUALI_enquete_repondants (
    id CHAR(36) NOT NULL,
    enquete_id CHAR(36) NOT NULL,
    repondant_id CHAR(36) NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uq_enquete_repondants (enquete_id, repondant_id),
    CONSTRAINT fk_er_enquete FOREIGN KEY (enquete_id) REFERENCES AQUALI_enquetes(id) ON DELETE CASCADE,
    CONSTRAINT fk_er_repondant FOREIGN KEY (repondant_id) REFERENCES AQUALI_repondants(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE AQUALI_reponses (
    id CHAR(36) NOT NULL,
    repondant_id CHAR(36) NOT NULL,
    item_id CHAR(36) NOT NULL,
    enquete_id CHAR(36) NOT NULL,
    modalite_reponse_id CHAR(36) NULL,
    valeur_texte VARCHAR(255) NULL,
    valeur_evn VARCHAR(255) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    PRIMARY KEY (id),
    CONSTRAINT fk_rep_repondant FOREIGN KEY (repondant_id) REFERENCES AQUALI_repondants(id) ON DELETE CASCADE,
    CONSTRAINT fk_rep_item FOREIGN KEY (item_id) REFERENCES AQUALI_items(id) ON DELETE CASCADE,
    CONSTRAINT fk_rep_enquete FOREIGN KEY (enquete_id) REFERENCES AQUALI_enquetes(id) ON DELETE CASCADE,
    CONSTRAINT fk_rep_modalite FOREIGN KEY (modalite_reponse_id) REFERENCES AQUALI_modalite_reponses(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE AQUALI_password_reset_tokens (
    email VARCHAR(255) NOT NULL,
    token VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS migrations (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    migration VARCHAR(255) NOT NULL,
    batch INT NOT NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;