-- PostgreSQL Database Initialization Script for FuelPHP Application
-- This script creates all necessary tables with PostgreSQL-compatible syntax

-- ==============================================================================
-- Migration Tracking Table
-- ==============================================================================
CREATE TABLE IF NOT EXISTS "migration" (
    "id" SERIAL PRIMARY KEY,
    "type" varchar(25) NOT NULL,
    "name" varchar(50) NOT NULL,
    "migration" varchar(100) DEFAULT '' NOT NULL
);

-- ==============================================================================
-- Auth Package Tables (SimpleAuth)
-- ==============================================================================

-- Users Table
CREATE TABLE IF NOT EXISTS "users" (
    "id" SERIAL PRIMARY KEY,
    "username" varchar(50) NOT NULL,
    "password" varchar(255) NOT NULL,
    "group" int DEFAULT 1 NOT NULL,
    "email" varchar(255) NOT NULL,
    "last_login" varchar(25),
    "login_hash" varchar(255),
    "profile_fields" text,
    "created_at" int DEFAULT 0 NOT NULL,
    "updated_at" int DEFAULT 0 NOT NULL,
    CONSTRAINT "users_username_unique" UNIQUE ("username"),
    CONSTRAINT "users_email_unique" UNIQUE ("email")
);

-- ==============================================================================
-- Application Tables
-- ==============================================================================

-- Projects Table
CREATE TABLE IF NOT EXISTS "projects" (
    "id" SERIAL PRIMARY KEY,
    "name" varchar(255) NOT NULL,
    "description" text,
    "user_id" int NOT NULL,
    "status" varchar(50) DEFAULT 'active' NOT NULL,
    "created_at" int,
    "updated_at" int
);

-- Tasks Table
CREATE TABLE IF NOT EXISTS "tasks" (
    "id" SERIAL PRIMARY KEY,
    "title" varchar(255) NOT NULL,
    "content" text,
    "user_id" int NOT NULL,
    "project_id" int,
    "done" boolean DEFAULT false NOT NULL,
    "priority" varchar(20) DEFAULT 'medium',
    "due_date" int,
    "created_at" int,
    "updated_at" int,
    CONSTRAINT "tasks_project_id_fkey" FOREIGN KEY ("project_id")
        REFERENCES "projects"("id") ON DELETE CASCADE
);

-- Task Checklists Table
CREATE TABLE IF NOT EXISTS "task_checklists" (
    "id" SERIAL PRIMARY KEY,
    "task_id" int NOT NULL,
    "item" varchar(255) NOT NULL,
    "completed" boolean DEFAULT false NOT NULL,
    "created_at" int,
    "updated_at" int,
    CONSTRAINT "task_checklists_task_id_fkey" FOREIGN KEY ("task_id")
        REFERENCES "tasks"("id") ON DELETE CASCADE
);

-- Project Members Table
CREATE TABLE IF NOT EXISTS "project_members" (
    "id" SERIAL PRIMARY KEY,
    "project_id" int NOT NULL,
    "user_id" int NOT NULL,
    "role" varchar(50) DEFAULT 'member' NOT NULL,
    "created_at" int,
    "updated_at" int,
    CONSTRAINT "project_members_project_id_fkey" FOREIGN KEY ("project_id")
        REFERENCES "projects"("id") ON DELETE CASCADE,
    CONSTRAINT "project_members_user_id_fkey" FOREIGN KEY ("user_id")
        REFERENCES "users"("id") ON DELETE CASCADE
);

-- Project Files Table
CREATE TABLE IF NOT EXISTS "project_files" (
    "id" SERIAL PRIMARY KEY,
    "project_id" int NOT NULL,
    "filename" varchar(255) NOT NULL,
    "filepath" varchar(500) NOT NULL,
    "filesize" int NOT NULL,
    "uploaded_by" int NOT NULL,
    "created_at" int,
    "updated_at" int,
    CONSTRAINT "project_files_project_id_fkey" FOREIGN KEY ("project_id")
        REFERENCES "projects"("id") ON DELETE CASCADE,
    CONSTRAINT "project_files_uploaded_by_fkey" FOREIGN KEY ("uploaded_by")
        REFERENCES "users"("id") ON DELETE CASCADE
);

-- ==============================================================================
-- Indexes for Performance
-- ==============================================================================
CREATE INDEX IF NOT EXISTS "tasks_user_id_idx" ON "tasks"("user_id");
CREATE INDEX IF NOT EXISTS "tasks_project_id_idx" ON "tasks"("project_id");
CREATE INDEX IF NOT EXISTS "projects_user_id_idx" ON "projects"("user_id");
CREATE INDEX IF NOT EXISTS "project_members_project_id_idx" ON "project_members"("project_id");
CREATE INDEX IF NOT EXISTS "project_members_user_id_idx" ON "project_members"("user_id");

-- ==============================================================================
-- Insert Migration Records
-- ==============================================================================
INSERT INTO "migration" ("type", "name", "migration") VALUES
    ('app', 'default', '001_create_tasks'),
    ('app', 'default', '002_create_projects'),
    ('app', 'default', '003_add_project_id_to_tasks'),
    ('app', 'default', '004_create_task_checklists'),
    ('app', 'default', '005_create_project_members'),
    ('app', 'default', '006_create_project_files'),
    ('app', 'default', '007_add_priority_and_due_date_to_tasks'),
    ('package', 'auth', '001_auth_create_usertables')
ON CONFLICT DO NOTHING;

-- ==============================================================================
-- Success Message (for logging)
-- ==============================================================================
DO $$
BEGIN
    RAISE NOTICE 'Database initialization completed successfully!';
END $$;
