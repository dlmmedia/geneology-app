-- Create users table
CREATE TABLE users (
    id BIGSERIAL PRIMARY KEY,
    firstname VARCHAR(255),
    surname VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    email_verified_at TIMESTAMP(0) WITHOUT TIME ZONE,
    password VARCHAR(255) NOT NULL,
    two_factor_secret TEXT,
    two_factor_recovery_codes TEXT,
    two_factor_confirmed_at TIMESTAMP(0) WITHOUT TIME ZONE,
    remember_token VARCHAR(100),
    current_team_id BIGINT,
    profile_photo_path VARCHAR(2048),
    language VARCHAR(5) DEFAULT 'en' NOT NULL,
    timezone VARCHAR(255) DEFAULT 'UTC' NOT NULL,
    is_developer BOOLEAN DEFAULT FALSE NOT NULL,
    seen_at TIMESTAMP(0) WITHOUT TIME ZONE,
    created_at TIMESTAMP(0) WITHOUT TIME ZONE,
    updated_at TIMESTAMP(0) WITHOUT TIME ZONE,
    deleted_at TIMESTAMP(0) WITHOUT TIME ZONE
);
CREATE INDEX users_deleted_at_index ON users(deleted_at);

-- Create password_reset_tokens table
CREATE TABLE password_reset_tokens (
    email VARCHAR(255) PRIMARY KEY,
    token VARCHAR(255) NOT NULL,
    created_at TIMESTAMP(0) WITHOUT TIME ZONE
);

-- Create sessions table
CREATE TABLE sessions (
    id VARCHAR(255) PRIMARY KEY,
    user_id BIGINT,
    ip_address VARCHAR(45),
    user_agent TEXT,
    payload TEXT NOT NULL,
    last_activity INTEGER NOT NULL
);
CREATE INDEX sessions_user_id_index ON sessions(user_id);
CREATE INDEX sessions_last_activity_index ON sessions(last_activity);

-- Create cache table
CREATE TABLE cache (
    key VARCHAR(255) PRIMARY KEY,
    value TEXT NOT NULL,
    expiration INTEGER NOT NULL
);

-- Create cache_locks table
CREATE TABLE cache_locks (
    key VARCHAR(255) PRIMARY KEY,
    owner VARCHAR(255) NOT NULL,
    expiration INTEGER NOT NULL
);

-- Create jobs table
CREATE TABLE jobs (
    id BIGSERIAL PRIMARY KEY,
    queue VARCHAR(255) NOT NULL,
    payload TEXT NOT NULL,
    attempts SMALLINT NOT NULL,
    reserved_at INTEGER,
    available_at INTEGER NOT NULL,
    created_at INTEGER NOT NULL
);
CREATE INDEX jobs_queue_index ON jobs(queue);

-- Create job_batches table
CREATE TABLE job_batches (
    id VARCHAR(255) PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    total_jobs INTEGER NOT NULL,
    pending_jobs INTEGER NOT NULL,
    failed_jobs INTEGER NOT NULL,
    failed_job_ids TEXT NOT NULL,
    options TEXT,
    cancelled_at INTEGER,
    created_at INTEGER NOT NULL,
    finished_at INTEGER
);

-- Create failed_jobs table
CREATE TABLE failed_jobs (
    id BIGSERIAL PRIMARY KEY,
    uuid VARCHAR(255) NOT NULL UNIQUE,
    connection TEXT NOT NULL,
    queue TEXT NOT NULL,
    payload TEXT NOT NULL,
    exception TEXT NOT NULL,
    failed_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL
);

-- Create settings table
CREATE TABLE settings (
    id BIGSERIAL PRIMARY KEY,
    key VARCHAR(255) NOT NULL UNIQUE,
    value VARCHAR(255),
    created_at TIMESTAMP(0) WITHOUT TIME ZONE,
    updated_at TIMESTAMP(0) WITHOUT TIME ZONE
);

-- Create userlogs table
CREATE TABLE userlogs (
    id BIGSERIAL PRIMARY KEY,
    user_id BIGINT NOT NULL,
    country_name VARCHAR(100),
    country_code VARCHAR(2),
    created_at TIMESTAMP(0) WITHOUT TIME ZONE,
    updated_at TIMESTAMP(0) WITHOUT TIME ZONE,
    CONSTRAINT userlogs_user_id_foreign FOREIGN KEY (user_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE CASCADE
);
CREATE INDEX userlogs_country_name_index ON userlogs(country_name);

-- Create teams table
CREATE TABLE teams (
    id BIGSERIAL PRIMARY KEY,
    user_id BIGINT NOT NULL,
    name VARCHAR(255) NOT NULL,
    description VARCHAR(255),
    personal_team BOOLEAN NOT NULL,
    created_at TIMESTAMP(0) WITHOUT TIME ZONE,
    updated_at TIMESTAMP(0) WITHOUT TIME ZONE
);
CREATE INDEX teams_user_id_index ON teams(user_id);

-- Create team_user table
CREATE TABLE team_user (
    id BIGSERIAL PRIMARY KEY,
    team_id BIGINT NOT NULL,
    user_id BIGINT NOT NULL,
    role VARCHAR(255),
    created_at TIMESTAMP(0) WITHOUT TIME ZONE,
    updated_at TIMESTAMP(0) WITHOUT TIME ZONE,
    CONSTRAINT team_user_team_id_user_id_unique UNIQUE (team_id, user_id)
);

-- Create team_invitations table
CREATE TABLE team_invitations (
    id BIGSERIAL PRIMARY KEY,
    team_id BIGINT NOT NULL,
    email VARCHAR(255) NOT NULL,
    role VARCHAR(255),
    created_at TIMESTAMP(0) WITHOUT TIME ZONE,
    updated_at TIMESTAMP(0) WITHOUT TIME ZONE,
    CONSTRAINT team_invitations_team_id_email_unique UNIQUE (team_id, email),
    CONSTRAINT team_invitations_team_id_foreign FOREIGN KEY (team_id) REFERENCES teams(id) ON DELETE CASCADE
);

-- Create personal_access_tokens table
CREATE TABLE personal_access_tokens (
    id BIGSERIAL PRIMARY KEY,
    tokenable_type VARCHAR(255) NOT NULL,
    tokenable_id BIGINT NOT NULL,
    name VARCHAR(255) NOT NULL,
    token VARCHAR(64) NOT NULL UNIQUE,
    abilities TEXT,
    last_used_at TIMESTAMP(0) WITHOUT TIME ZONE,
    expires_at TIMESTAMP(0) WITHOUT TIME ZONE,
    created_at TIMESTAMP(0) WITHOUT TIME ZONE,
    updated_at TIMESTAMP(0) WITHOUT TIME ZONE
);
CREATE INDEX personal_access_tokens_tokenable_type_tokenable_id_index ON personal_access_tokens(tokenable_type, tokenable_id);

-- Create genders table
CREATE TABLE genders (
    id BIGSERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    created_at TIMESTAMP(0) WITHOUT TIME ZONE,
    updated_at TIMESTAMP(0) WITHOUT TIME ZONE
);
CREATE INDEX genders_name_index ON genders(name);

-- Create people table
CREATE TABLE people (
    id BIGSERIAL PRIMARY KEY,
    firstname VARCHAR(255),
    surname VARCHAR(255) NOT NULL,
    birthname VARCHAR(255),
    nickname VARCHAR(255),
    sex VARCHAR(1) DEFAULT 'm' NOT NULL,
    gender_id BIGINT,
    father_id BIGINT,
    mother_id BIGINT,
    parents_id BIGINT,
    dob DATE,
    yob INTEGER,
    pob VARCHAR(255),
    dod DATE,
    yod INTEGER,
    pod VARCHAR(255),
    summary TEXT,
    street VARCHAR(100),
    number VARCHAR(20),
    postal_code VARCHAR(20),
    city VARCHAR(100),
    province VARCHAR(100),
    state VARCHAR(100),
    country CHAR(2),
    phone VARCHAR(50),
    photo VARCHAR(255),
    team_id BIGINT,
    created_at TIMESTAMP(0) WITHOUT TIME ZONE,
    updated_at TIMESTAMP(0) WITHOUT TIME ZONE,
    deleted_at TIMESTAMP(0) WITHOUT TIME ZONE,
    CONSTRAINT people_gender_id_foreign FOREIGN KEY (gender_id) REFERENCES genders(id) ON UPDATE CASCADE ON DELETE RESTRICT
);
CREATE INDEX people_firstname_index ON people(firstname);
CREATE INDEX people_surname_index ON people(surname);
CREATE INDEX people_birthname_index ON people(birthname);
CREATE INDEX people_nickname_index ON people(nickname);
CREATE INDEX people_sex_index ON people(sex);
CREATE INDEX people_father_id_index ON people(father_id);
CREATE INDEX people_mother_id_index ON people(mother_id);
CREATE INDEX people_parents_id_index ON people(parents_id);
CREATE INDEX people_team_id_index ON people(team_id);
CREATE INDEX people_deleted_at_index ON people(deleted_at);
CREATE INDEX people_deleted_father_index ON people(deleted_at, father_id);
CREATE INDEX people_deleted_mother_index ON people(deleted_at, mother_id);
-- Additional indexes from 2025 migration
CREATE INDEX people_teamid_fatherid_index ON people(team_id, father_id);
CREATE INDEX people_teamid_motherid_index ON people(team_id, mother_id);
CREATE INDEX people_teamid_parentsid_index ON people(team_id, parents_id);
CREATE INDEX people_teamid_dob_index ON people(team_id, dob);
CREATE INDEX people_surname_firstname_index ON people(team_id, surname, firstname);
CREATE INDEX people_firstname_surname_index ON people(firstname, surname);
CREATE INDEX people_fatherid_motherid_index ON people(father_id, mother_id);


-- Create person_metadata table
CREATE TABLE person_metadata (
    id BIGSERIAL PRIMARY KEY,
    person_id BIGINT NOT NULL,
    key VARCHAR(255) NOT NULL,
    value VARCHAR(255),
    created_at TIMESTAMP(0) WITHOUT TIME ZONE,
    updated_at TIMESTAMP(0) WITHOUT TIME ZONE,
    CONSTRAINT person_metadata_person_id_key_unique UNIQUE (person_id, key),
    CONSTRAINT person_metadata_person_id_foreign FOREIGN KEY (person_id) REFERENCES people(id) ON UPDATE CASCADE ON DELETE CASCADE
);
CREATE INDEX person_metadata_key_index ON person_metadata(key);

-- Create couples table
CREATE TABLE couples (
    id BIGSERIAL PRIMARY KEY,
    person1_id BIGINT NOT NULL,
    person2_id BIGINT NOT NULL,
    date_start DATE,
    date_end DATE,
    is_married BOOLEAN DEFAULT FALSE NOT NULL,
    has_ended BOOLEAN DEFAULT FALSE NOT NULL,
    team_id BIGINT,
    created_at TIMESTAMP(0) WITHOUT TIME ZONE,
    updated_at TIMESTAMP(0) WITHOUT TIME ZONE,
    CONSTRAINT couples_person1_id_person2_id_date_start_unique UNIQUE (person1_id, person2_id, date_start)
);
CREATE INDEX couples_person1_id_index ON couples(person1_id);
CREATE INDEX couples_person2_id_index ON couples(person2_id);
CREATE INDEX couples_date_start_index ON couples(date_start);
CREATE INDEX couples_team_id_index ON couples(team_id);
-- Additional indexes from 2025 migration
CREATE INDEX couples_teamid_person1id_person2id_index ON couples(team_id, person1_id, person2_id);

-- Create activity_log table
CREATE TABLE activity_log (
    id BIGSERIAL PRIMARY KEY,
    log_name VARCHAR(255),
    description TEXT NOT NULL,
    subject_type VARCHAR(255),
    subject_id BIGINT,
    event VARCHAR(255),
    causer_type VARCHAR(255),
    causer_id BIGINT,
    properties JSON,
    batch_uuid CHAR(36),
    team_id BIGINT,
    created_at TIMESTAMP(0) WITHOUT TIME ZONE,
    updated_at TIMESTAMP(0) WITHOUT TIME ZONE,
    CONSTRAINT activity_log_team_id_foreign FOREIGN KEY (team_id) REFERENCES teams(id) ON DELETE CASCADE
);
CREATE INDEX activity_log_log_name_index ON activity_log(log_name);
CREATE INDEX activity_log_subject_index ON activity_log(subject_type, subject_id);
CREATE INDEX activity_log_causer_index ON activity_log(causer_type, causer_id);
-- Additional indexes from migration
CREATE INDEX updated_at_index ON activity_log(updated_at);
CREATE INDEX idx_activity_log_performance ON activity_log(log_name, team_id, updated_at);
CREATE INDEX idx_activity_log_team ON activity_log(team_id);

-- Create media table
CREATE TABLE media (
    id BIGSERIAL PRIMARY KEY,
    model_type VARCHAR(255) NOT NULL,
    model_id BIGINT NOT NULL,
    uuid CHAR(36) UNIQUE,
    collection_name VARCHAR(255) NOT NULL,
    name VARCHAR(255) NOT NULL,
    file_name VARCHAR(255) NOT NULL,
    mime_type VARCHAR(255),
    disk VARCHAR(255) NOT NULL,
    conversions_disk VARCHAR(255),
    size BIGINT NOT NULL,
    manipulations JSON NOT NULL,
    custom_properties JSON NOT NULL,
    generated_conversions JSON NOT NULL,
    responsive_images JSON NOT NULL,
    order_column INTEGER,
    created_at TIMESTAMP(0) WITHOUT TIME ZONE,
    updated_at TIMESTAMP(0) WITHOUT TIME ZONE
);
CREATE INDEX media_model_type_model_id_index ON media(model_type, model_id);
CREATE INDEX media_order_column_index ON media(order_column);

-- Seeding/Data initialization not included, strictly schema.

