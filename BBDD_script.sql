CREATE TABLE users(
user_id int not null auto_increment primary key,
name varchar(100) not null,
surname varchar(100) not null,
password varchar(100) not null,
mail varchar(100) not null,
token varchar(100) not null,
init_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
user_type int not null
)ENGINE=InnoDB;


CREATE TABLE projects(
   project_id int not null auto_increment primary key,
   creator_id int not null,
   FOREIGN KEY fk_user(creator_id)
   REFERENCES users(user_id) 
   ON UPDATE CASCADE
   ON DELETE RESTRICT,
   tittle varchar(100) not null,
   description varchar(1000) not null,
   NProgrammers int not null,
   NDesigners int not null,
   NAnimators int not null,
   limit_date TIMESTAMP NOT NULL 
)ENGINE=InnoDB;

CREATE TABLE users_in_projects(
   project_id INT NOT NULL,
   user_id INT NOT NULL,
   PRIMARY KEY(project_id,user_id),
   FOREIGN KEY fk_project(project_id) 
   REFERENCES projects(project_id)
   ON UPDATE CASCADE
   ON DELETE RESTRICT,
   FOREIGN KEY fk_user_project(user_id)
   REFERENCES users(user_id)
   ON UPDATE CASCADE
   ON DELETE RESTRICT
)ENGINE=InnoDB;
	

   