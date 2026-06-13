CREATE TABLE IF NOT EXISTS "migrations"(
  "id" integer primary key autoincrement not null,
  "migration" varchar not null,
  "batch" integer not null
);
CREATE TABLE IF NOT EXISTS "users"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "email" varchar not null,
  "email_verified_at" datetime,
  "password" varchar not null,
  "remember_token" varchar,
  "created_at" datetime,
  "updated_at" datetime
);
CREATE UNIQUE INDEX "users_email_unique" on "users"("email");
CREATE TABLE IF NOT EXISTS "password_reset_tokens"(
  "email" varchar not null,
  "token" varchar not null,
  "created_at" datetime,
  primary key("email")
);
CREATE TABLE IF NOT EXISTS "sessions"(
  "id" varchar not null,
  "user_id" integer,
  "ip_address" varchar,
  "user_agent" text,
  "payload" text not null,
  "last_activity" integer not null,
  primary key("id")
);
CREATE INDEX "sessions_user_id_index" on "sessions"("user_id");
CREATE INDEX "sessions_last_activity_index" on "sessions"("last_activity");
CREATE TABLE IF NOT EXISTS "cache"(
  "key" varchar not null,
  "value" text not null,
  "expiration" integer not null,
  primary key("key")
);
CREATE INDEX "cache_expiration_index" on "cache"("expiration");
CREATE TABLE IF NOT EXISTS "cache_locks"(
  "key" varchar not null,
  "owner" varchar not null,
  "expiration" integer not null,
  primary key("key")
);
CREATE INDEX "cache_locks_expiration_index" on "cache_locks"("expiration");
CREATE TABLE IF NOT EXISTS "jobs"(
  "id" integer primary key autoincrement not null,
  "queue" varchar not null,
  "payload" text not null,
  "attempts" integer not null,
  "reserved_at" integer,
  "available_at" integer not null,
  "created_at" integer not null
);
CREATE INDEX "jobs_queue_index" on "jobs"("queue");
CREATE TABLE IF NOT EXISTS "job_batches"(
  "id" varchar not null,
  "name" varchar not null,
  "total_jobs" integer not null,
  "pending_jobs" integer not null,
  "failed_jobs" integer not null,
  "failed_job_ids" text not null,
  "options" text,
  "cancelled_at" integer,
  "created_at" integer not null,
  "finished_at" integer,
  primary key("id")
);
CREATE TABLE IF NOT EXISTS "failed_jobs"(
  "id" integer primary key autoincrement not null,
  "uuid" varchar not null,
  "connection" text not null,
  "queue" text not null,
  "payload" text not null,
  "exception" text not null,
  "failed_at" datetime not null default CURRENT_TIMESTAMP
);
CREATE UNIQUE INDEX "failed_jobs_uuid_unique" on "failed_jobs"("uuid");
CREATE TABLE IF NOT EXISTS "profiles"(
  "id" integer primary key autoincrement not null,
  "avatar_path" varchar,
  "cv_path" varchar,
  "github_url" varchar,
  "linkedin_url" varchar,
  "twitter_url" varchar,
  "email" varchar,
  "created_at" datetime,
  "updated_at" datetime,
  "hero_video_path" varchar,
  "location" varchar,
  "hero_top_text" varchar,
  "hero_title" varchar,
  "hero_subtitle" varchar,
  "hero_blur_amount" integer not null default '35',
  "hero_html_content" text,
  "hero_gradient_enabled" tinyint(1) not null default '0',
  "hero_gradient_type" varchar not null default 'linear',
  "hero_gradient_angle" integer not null default '180',
  "hero_gradient_stops" text,
  "hero_gradient_opacity" integer not null default '100',
  "exp_default_bg_mode" varchar not null default 'cycle',
  "exp_default_bg_type" varchar,
  "exp_default_bg_media_path" varchar,
  "exp_default_bg_gallery_images" text
);
CREATE TABLE IF NOT EXISTS "projects"(
  "id" integer primary key autoincrement not null,
  "title" varchar not null,
  "slug" varchar not null,
  "description" text not null,
  "thumbnail_path" varchar,
  "tags" varchar,
  "demo_url" varchar,
  "github_url" varchar,
  "featured" tinyint(1) not null default '0',
  "created_at" datetime,
  "updated_at" datetime,
  "subtitle" varchar,
  "client" varchar,
  "role" varchar,
  "year" varchar,
  "medium" varchar,
  "collaborators" text,
  "body_content" text,
  "category" varchar not null default 'ui',
  "media_type" varchar not null default 'image',
  "video_url" varchar,
  "gallery_images" text,
  "thumbnail_type" varchar not null default 'image',
  "thumbnail_video_path" varchar,
  "video_loop_start" float not null default '0',
  "video_loop_end" float,
  "date_published" varchar,
  "full_video_url" varchar,
  "thumbnail_images" text,
  "main_media_type" varchar,
  "main_video_path" varchar,
  "main_images" text,
  "main_image_path" varchar,
  "use_custom_thumbnail" tinyint(1) not null default '0',
  "featured_thumbnail" varchar,
  "is_best_work" tinyint(1) not null default '0',
  "is_archived" tinyint(1) not null default '0',
  "is_top" tinyint(1) not null default '0',
  "sort_order" integer not null default '0',
  "embed_url" varchar
);
CREATE UNIQUE INDEX "projects_slug_unique" on "projects"("slug");
CREATE TABLE IF NOT EXISTS "contact_messages"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "email" varchar not null,
  "subject" varchar,
  "message" text not null,
  "is_read" tinyint(1) not null default '0',
  "created_at" datetime,
  "updated_at" datetime
);
CREATE TABLE IF NOT EXISTS "skills"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "category" varchar not null,
  "proficiency" integer not null default '80',
  "created_at" datetime,
  "updated_at" datetime,
  "tooltip_info" varchar,
  "image_path" varchar
);
CREATE TABLE IF NOT EXISTS "achievements"(
  "id" integer primary key autoincrement not null,
  "title" varchar not null,
  "issuer" varchar not null,
  "year" varchar not null,
  "type" varchar not null,
  "description" text,
  "created_at" datetime,
  "updated_at" datetime,
  "media_path" varchar
);
CREATE TABLE IF NOT EXISTS "experiences"(
  "id" integer primary key autoincrement not null,
  "company" varchar not null,
  "role" varchar not null,
  "duration" varchar not null,
  "description" text,
  "created_at" datetime,
  "updated_at" datetime,
  "image_path" varchar,
  "sort_order" integer not null default '0',
  "body_content" text,
  "is_active" tinyint(1) not null default '0',
  "bg_media_type" varchar not null default 'image',
  "bg_media_path" varchar,
  "bg_gallery_images" text
);
CREATE TABLE IF NOT EXISTS "intro_slides"(
  "id" integer primary key autoincrement not null,
  "chapter_label" varchar not null default 'Chapter',
  "title" varchar not null,
  "subtitle" varchar,
  "description" text,
  "image_path" varchar,
  "sort_order" integer not null default '0',
  "is_locked" tinyint(1) not null default '0',
  "created_at" datetime,
  "updated_at" datetime
);
CREATE TABLE IF NOT EXISTS "tool_items"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "row_label" varchar not null,
  "image_path" varchar,
  "sort_order" integer not null default '0',
  "created_at" datetime,
  "updated_at" datetime,
  "tooltip_info" varchar,
  "proficiency" integer not null default '5'
);

INSERT INTO migrations VALUES(1,'0001_01_01_000000_create_users_table',1);
INSERT INTO migrations VALUES(2,'0001_01_01_000001_create_cache_table',1);
INSERT INTO migrations VALUES(3,'0001_01_01_000002_create_jobs_table',1);
INSERT INTO migrations VALUES(4,'2026_05_24_114229_create_profiles_table',1);
INSERT INTO migrations VALUES(5,'2026_05_24_114229_create_projects_table',1);
INSERT INTO migrations VALUES(6,'2026_05_24_114230_create_contact_messages_table',1);
INSERT INTO migrations VALUES(7,'2026_05_24_114230_create_skills_table',1);
INSERT INTO migrations VALUES(8,'2026_05_24_115904_create_achievements_table',1);
INSERT INTO migrations VALUES(9,'2026_05_24_115904_create_experiences_table',1);
INSERT INTO migrations VALUES(10,'2026_05_25_000001_add_cms_fields_to_projects_table',1);
INSERT INTO migrations VALUES(11,'2026_05_25_080142_add_visual_fields_to_projects_table',1);
INSERT INTO migrations VALUES(12,'2026_05_26_125035_add_hero_video_to_profiles_table',2);
INSERT INTO migrations VALUES(13,'2026_05_26_125905_add_fields_to_experiences_table',3);
INSERT INTO migrations VALUES(14,'2026_05_26_125959_create_intro_slides_table',4);
INSERT INTO migrations VALUES(15,'2026_05_26_131258_create_tool_items_table',5);
INSERT INTO migrations VALUES(16,'2026_05_26_131304_add_location_to_profiles_table',5);
INSERT INTO migrations VALUES(17,'2026_05_26_131941_add_gallery_to_projects_table',6);
INSERT INTO migrations VALUES(18,'2026_05_26_143040_add_hero_fields_to_profiles_table',7);
INSERT INTO migrations VALUES(19,'2026_05_26_145833_add_rich_media_fields_to_projects_table',8);
INSERT INTO migrations VALUES(20,'2026_05_26_160526_add_full_video_url_to_projects_table',9);
INSERT INTO migrations VALUES(21,'2026_05_26_171211_add_thumbnail_images_to_projects_table',10);
INSERT INTO migrations VALUES(22,'2026_05_27_062741_add_hero_content_fields_to_profiles_table',11);
INSERT INTO migrations VALUES(23,'2026_05_27_085555_add_gradient_fields_to_profiles_table',12);
INSERT INTO migrations VALUES(24,'2026_05_27_092613_add_hero_gradient_stops_to_profiles_table',13);
INSERT INTO migrations VALUES(25,'2026_05_27_102858_add_hero_gradient_opacity_back_to_profiles_table',14);
INSERT INTO migrations VALUES(26,'2026_06_02_051809_add_main_media_and_custom_thumbnail_fields_to_projects_table',15);
INSERT INTO migrations VALUES(29,'2026_06_05_065002_add_tooltip_info_to_tool_items_table',16);
INSERT INTO migrations VALUES(30,'2026_06_05_081226_add_proficiency_to_tool_items_table',17);
INSERT INTO migrations VALUES(31,'2026_06_05_082132_add_tooltip_info_to_skills_table',18);
INSERT INTO migrations VALUES(32,'2026_06_05_083030_add_image_path_to_skills_table',19);
INSERT INTO migrations VALUES(33,'2026_06_05_085317_add_featured_thumbnail_to_projects_table',20);
INSERT INTO migrations VALUES(34,'2026_06_05_091532_add_is_best_work_to_projects_table',21);
INSERT INTO migrations VALUES(35,'2026_06_07_075916_add_media_path_to_achievements_table',22);
INSERT INTO migrations VALUES(36,'2026_06_07_122856_add_notion_editor_fields_to_experiences_table',23);
INSERT INTO migrations VALUES(37,'2026_06_07_140317_add_experience_bg_settings_to_profiles_table',24);
INSERT INTO migrations VALUES(38,'2026_06_11_030704_add_archive_and_top_to_projects_table',25);
INSERT INTO migrations VALUES(39,'2026_06_11_081802_add_sort_order_to_projects_table',26);
INSERT INTO migrations VALUES(40,'2026_06_12_144501_add_embed_url_to_projects_table',27);
