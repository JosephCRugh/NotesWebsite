CREATE TABLE user_credentials (
  user_id    INTEGER  PRIMARY KEY ,
  email      TEXT NOT NULL UNIQUE ,
  password   TEXT NOT NULL        ,
  first_name TEXT NOT NULL        ,
  last_name  TEXT NOT NULL
);

CREATE TABLE user_projects (
  project_id     INTEGER PRIMARY KEY ,
  name           TEXT NOT NULL       ,
  description    TEXT                ,
  privateToF     INTEGER             ,
  added_user_ids TEXT NOT NULL       ,
  user_id        INTEGER NOT NULL,
    FOREIGN KEY (user_id) REFERENCES user_credentials (user_id) ON DELETE CASCADE
);

CREATE TABLE user_notes (
  note_id    INTEGER PRIMARY KEY ,
  title      TEXT NOT NULL       ,
  content    TEXT NOT NULL       ,
  pos_x      INTEGER             ,
  pos_y      INTEGER             ,
  project_id INTEGER NOT NULL,
  user_id    INTEGER NOT NULL,
    FOREIGN KEY (project_id) REFERENCES user_projects (project_id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES user_credentials (user_id) ON DELETE CASCADE
);
