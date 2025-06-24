
Table "academic_periods" {
  "id" bigint [pk, not null, increment]
  "name" varchar(100) [not null]
  "start_date" date [not null]
  "end_date" date [not null]
  "active" tinyint(1) [not null, default: '0']
  "created_at" timestamp [default: NULL]
  "updated_at" timestamp [default: NULL]
}

Table "announcements" {
  "id" bigint [pk, not null, increment]
  "title" varchar(255) [not null]
  "content" text [not null]
  "target" varchar(191) [not null]
  "section_id" bigint [default: NULL]
  "published_by" bigint [not null]
  "created_at" timestamp [default: NULL]
  "updated_at" timestamp [default: NULL]

  Indexes {
    section_id [name: "announcements_section_id_foreign"]
    published_by [name: "announcements_published_by_foreign"]
  }
}

Table "assignments" {
  "id" bigint [pk, not null, increment]
  "section_id" bigint [not null]
  "title" varchar(100) [not null]
  "description" text
  "published_at" datetime [not null, default: `CURRENT_TIMESTAMP`]
  "due_date" datetime [not null]
  "published_by" bigint [not null]
  "created_at" timestamp [default: NULL]
  "updated_at" timestamp [default: NULL]

  Indexes {
    section_id [name: "assignments_section_id_foreign"]
    published_by [name: "assignments_published_by_foreign"]
  }
}

Table "assignment_submissions" {
  "id" bigint [pk, not null, increment]
  "assignment_id" bigint [not null]
  "student_id" bigint [not null]
  "file_url" varchar(191) [default: NULL]
  "comment" text
  "submitted_at" datetime [not null, default: `CURRENT_TIMESTAMP`]
  "grade" decimal(5,2) [default: NULL]
  "feedback" text
  "graded_by" bigint [default: NULL]
  "created_at" timestamp [default: NULL]
  "updated_at" timestamp [default: NULL]

  Indexes {
    (assignment_id, student_id) [unique, name: "assignment_submissions_assignment_id_student_id_unique"]
    student_id [name: "assignment_submissions_student_id_foreign"]
    graded_by [name: "assignment_submissions_graded_by_foreign"]
  }
}

Table "attendances" {
  "id" bigint [pk, not null, increment]
  "class_session_id" bigint [not null]
  "student_id" bigint [not null]
  "status" varchar(191) [not null]
  "recorded_time" time [default: NULL]
  "justification" text
  "recorded_by" bigint [default: NULL]
  "created_at" timestamp [default: NULL]
  "updated_at" timestamp [default: NULL]

  Indexes {
    (class_session_id, student_id) [unique, name: "attendances_class_session_id_student_id_unique"]
    student_id [name: "attendances_student_id_foreign"]
    recorded_by [name: "attendances_recorded_by_foreign"]
  }
}

Table "class_sessions" {
  "id" bigint [pk, not null, increment]
  "section_id" bigint [not null]
  "topic" varchar(191) [default: NULL]
  "date" date [not null]
  "start_time" time [not null]
  "end_time" time [not null]
  "created_at" timestamp [default: NULL]
  "updated_at" timestamp [default: NULL]
  "created_by" bigint [default: NULL]

  Indexes {
    section_id [name: "class_sessions_section_id_foreign"]
    created_by [name: "class_sessions_created_by_foreign"]
  }
}

Table "courses" {
  "id" bigint [pk, not null, increment]
  "code" varchar(20) [not null]
  "name" varchar(100) [not null]
  "description" text
  "credits" int [not null, default: '0']
  "academic_period_id" bigint [not null]
  "created_at" timestamp [default: NULL]
  "updated_at" timestamp [default: NULL]

  Indexes {
    code [unique, name: "courses_code_unique"]
    academic_period_id [name: "courses_academic_period_id_foreign"]
  }
}

Table "course_materials" {
  "id" bigint [pk, not null, increment]
  "section_id" bigint [not null]
  "published_by" bigint [not null]
  "title" varchar(100) [not null]
  "description" text
  "type" varchar(191) [not null]
  "url" varchar(191) [not null]
  "created_at" timestamp [default: NULL]
  "updated_at" timestamp [default: NULL]

  Indexes {
    section_id [name: "course_materials_section_id_foreign"]
    published_by [name: "course_materials_published_by_foreign"]
  }
}

Table "course_sections" {
  "id" bigint [pk, not null, increment]
  "code" varchar(20) [not null]
  "course_id" bigint [not null]
  "classroom" varchar(50) [default: NULL]
  "max_capacity" int [not null, default: '30']
  "created_at" timestamp [default: NULL]
  "updated_at" timestamp [default: NULL]

  Indexes {
    (course_id, code) [unique, name: "course_sections_course_id_code_unique"]
  }
}

Table "enrollments" {
  "id" bigint [pk, not null, increment]
  "student_id" bigint [not null]
  "section_id" bigint [not null]
  "academic_period_id" bigint [not null]
  "enrolled_at" timestamp [not null, default: `CURRENT_TIMESTAMP`]
  "status" varchar(191) [not null]
  "created_at" timestamp [default: NULL]
  "updated_at" timestamp [default: NULL]

  Indexes {
    (student_id, section_id, academic_period_id) [unique, name: "enrollments_student_id_section_id_academic_period_id_unique"]
    section_id [name: "enrollments_section_id_foreign"]
    academic_period_id [name: "enrollments_academic_period_id_foreign"]
  }
}

Table "evaluations" {
  "id" bigint [pk, not null, increment]
  "section_id" bigint [not null]
  "evaluation_type_id" bigint [not null]
  "academic_period_id" bigint [not null]
  "title" varchar(191) [not null]
  "description" text
  "weight" decimal(5,2) [not null, default: '0.00']
  "date" date [not null]
  "due_date" datetime [default: NULL]
  "created_at" timestamp [default: NULL]
  "updated_at" timestamp [default: NULL]

  Indexes {
    section_id [name: "evaluations_section_id_foreign"]
    evaluation_type_id [name: "evaluations_evaluation_type_id_foreign"]
    academic_period_id [name: "evaluations_academic_period_id_foreign"]
  }
}

Table "evaluation_types" {
  "id" bigint [pk, not null, increment]
  "name" varchar(50) [not null]
  "description" text
  "weight" decimal(5,2) [not null, default: '0.00']
  "created_at" timestamp [default: NULL]
  "updated_at" timestamp [default: NULL]

  Indexes {
    name [unique, name: "evaluation_types_name_unique"]
  }
}

Table "failed_jobs" {
  "id" bigint [pk, not null, increment]
  "uuid" varchar(191) [not null]
  "connection" text [not null]
  "queue" text [not null]
  "payload" longtext [not null]
  "exception" longtext [not null]
  "failed_at" timestamp [not null, default: `CURRENT_TIMESTAMP`]

  Indexes {
    uuid [unique, name: "failed_jobs_uuid_unique"]
  }
}

Table "grades" {
  "id" bigint [pk, not null, increment]
  "evaluation_id" bigint [not null]
  "student_id" bigint [not null]
  "graded_by" bigint [default: NULL]
  "score" decimal(4,2) [not null]
  "comment" text
  "graded_at" timestamp [not null, default: `CURRENT_TIMESTAMP`]
  "created_at" timestamp [default: NULL]
  "updated_at" timestamp [default: NULL]

  Indexes {
    (evaluation_id, student_id) [unique, name: "grades_evaluation_id_student_id_unique"]
    student_id [name: "grades_student_id_foreign"]
    graded_by [name: "grades_graded_by_foreign"]
  }
}

Table "guardians" {
  "id" bigint [pk, not null, increment]
  "user_id" bigint [not null]
  "created_at" timestamp [default: NULL]
  "updated_at" timestamp [default: NULL]

  Indexes {
    user_id [name: "guardians_user_id_foreign"]
  }
}

Table "messages" {
  "id" bigint [pk, not null, increment]
  "sender_id" bigint [not null]
  "recipient_id" bigint [not null]
  "content" text [not null]
  "is_read" tinyint(1) [not null, default: '0']
  "created_at" timestamp [default: NULL]
  "updated_at" timestamp [default: NULL]

  Indexes {
    sender_id [name: "messages_sender_id_foreign"]
    recipient_id [name: "messages_recipient_id_foreign"]
  }
}

Table "migrations" {
  "id" int [pk, not null, increment]
  "migration" varchar(191) [not null]
  "batch" int [not null]
}

Table "password_resets" {
  "email" varchar(191) [not null]
  "token" varchar(191) [not null]
  "created_at" timestamp [default: NULL]

  Indexes {
    email [name: "password_resets_email_index"]
  }
}

Table "personal_access_tokens" {
  "id" bigint [pk, not null, increment]
  "tokenable_type" varchar(191) [not null]
  "tokenable_id" bigint [not null]
  "name" varchar(191) [not null]
  "token" varchar(64) [not null]
  "abilities" text
  "last_used_at" timestamp [default: NULL]
  "created_at" timestamp [default: NULL]
  "updated_at" timestamp [default: NULL]

  Indexes {
    token [unique, name: "personal_access_tokens_token_unique"]
    (tokenable_type, tokenable_id) [name: "personal_access_tokens_tokenable_type_tokenable_id_index"]
  }
}

Table "roles" {
  "id" bigint [pk, not null, increment]
  "name" varchar(100) [not null]
  "description" text
  "created_at" timestamp [default: NULL]
  "updated_at" timestamp [default: NULL]

  Indexes {
    name [unique, name: "roles_name_unique"]
  }
}

Table "schedules" {
  "id" bigint [pk, not null, increment]
  "section_id" bigint [not null]
  "day_of_week" varchar(191) [not null]
  "start_date" time [not null]
  "end_date" time [not null]
  "is_recurring" tinyint(1) [not null, default: '1']
  "specific_date" date [default: NULL]
  "created_at" timestamp [default: NULL]
  "updated_at" timestamp [default: NULL]

  Indexes {
    section_id [name: "schedules_section_id_foreign"]
  }
}

Table "students" {
  "id" bigint [pk, not null, increment]
  "user_id" bigint [not null]
  "grade" varchar(20) [default: NULL]
  "section" varchar(10) [default: NULL]
  "created_at" timestamp [default: NULL]
  "updated_at" timestamp [default: NULL]

  Indexes {
    user_id [name: "students_user_id_foreign"]
  }
}

Table "students_guardians" {
  "id" bigint [pk, not null, increment]
  "student_id" bigint [not null]
  "guardian_id" bigint [not null]
  "relationship" varchar(100) [default: NULL]
  "created_at" timestamp [default: NULL]
  "updated_at" timestamp [default: NULL]

  Indexes {
    (student_id, guardian_id) [unique, name: "students_guardians_student_id_guardian_id_unique"]
    guardian_id [name: "students_guardians_guardian_id_foreign"]
  }
}

Table "tasks" {
  "id" bigint [pk, not null, increment]
  "title" varchar(191) [not null]
  "description" text
  "completed" tinyint(1) [not null, default: '0']
  "user_id" bigint [default: NULL]
  "created_at" timestamp [default: NULL]
  "updated_at" timestamp [default: NULL]

  Indexes {
    user_id [name: "tasks_user_id_foreign"]
  }
}

Table "teachers" {
  "id" bigint [pk, not null, increment]
  "user_id" bigint [not null]
  "specialty" varchar(100) [default: NULL]
  "academic_degree" varchar(100) [default: NULL]
  "created_at" timestamp [default: NULL]
  "updated_at" timestamp [default: NULL]

  Indexes {
    user_id [name: "teachers_user_id_foreign"]
  }
}

Table "teacher_sections" {
  "id" bigint [pk, not null, increment]
  "section_id" bigint [not null]
  "teacher_id" bigint [not null]
  "is_primary" tinyint(1) [not null, default: '1']
  "created_at" timestamp [default: NULL]
  "updated_at" timestamp [default: NULL]

  Indexes {
    section_id [name: "teacher_sections_section_id_foreign"]
    teacher_id [name: "teacher_sections_teacher_id_foreign"]
  }
}

Table "users" {
  "id" bigint [pk, not null, increment]
  "role_id" bigint [not null]
  "first_name" varchar(100) [not null]
  "last_name" varchar(100) [not null]
  "user_name" varchar(100) [not null]
  "email" varchar(100) [not null]
  "password" varchar(191) [not null]
  "dni" varchar(10) [not null]
  "birth_date" date [default: NULL]
  "photo_url" varchar(191) [default: NULL]
  "phone" varchar(20) [default: NULL]
  "address" varchar(191) [default: NULL]
  "last_sign_in" timestamp [default: NULL]
  "created_at" timestamp [default: NULL]
  "updated_at" timestamp [default: NULL]

  Indexes {
    user_name [unique, name: "users_user_name_unique"]
    email [unique, name: "users_email_unique"]
    dni [unique, name: "users_dni_unique"]
    role_id [name: "users_role_id_foreign"]
  }
}
