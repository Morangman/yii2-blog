drop table if exists usergroup;
drop table if exists  posts;
drop table if exists  users;
drop table if exists  groups;

CREATE TABLE users(
    id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    name VARCHAR(128) NOT NULL UNIQUE,
    pwd VARCHAR(64) NOT NULL,
    descr TEXT,
    registered DATETIME DEFAULT NOW(),
    last_enter DATETIME
    );
delimiter |
CREATE TRIGGER ins_user BEFORE INSERT ON users
FOR EACH ROW 
  IF LENGTH(NEW.pwd) > 0 THEN 
    SET NEW.registered = NOW();
    SET NEW.pwd = md5(concat(NEW.pwd,DATE_FORMAT(NEW.registered, '%i%H%d%m%Y'))); 
  END IF
|
delimiter ;
delimiter |
CREATE TRIGGER upd_user BEFORE UPDATE ON users
FOR EACH ROW 
  IF LENGTH(NEW.pwd) > 0 THEN 
    SET NEW.pwd = md5(concat(NEW.pwd,DATE_FORMAT(NEW.registered, '%i%H%d%m%Y')));
  ELSE 
    SET NEW.pwd = OLD.pwd;
  END IF;
|
delimiter ;
INSERT INTO users(name,pwd,descr) VALUES ('root','root','СУПЕРпользователь');
INSERT INTO users(name,pwd,descr) VALUES ('user1','1','Автор статей, текстов и постов');
INSERT INTO users(name,pwd,descr) VALUES ('user2','2','Аффтар');
INSERT INTO users(name,pwd,descr) VALUES ('user3','3','Аффтар без прав на фотки');

create table groups(
    id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    name VARCHAR(128) NOT NULL UNIQUE,
    descr TEXT,
    created DATETIME DEFAULT NOW()
);

insert into groups(name,descr) values('root','Супергруппа');
insert into groups(name,descr) values('admins','Админы');
insert into groups(name,descr) values('authors','Авторы');
insert into groups(name,descr) values('photos','Фотки');

create table usergroup (
  user_id int,
  group_id int,
  primary key(user_id,group_id),
  foreign key(user_id) references users(id),
  foreign key(group_id) references groups(id)
);
INSERT INTO usergroup (user_id,group_id) values(1,1),(2,2),(2,3),(2,4),(3,3),(3,4),(4,3);

create table posts(
  id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
  topic VARCHAR(128) NOT NULL,
  content TEXT,
  created DATETIME DEFAULT NOW(), 
  modified DATETIME,
  user_id INT NOT NULL,
  `file` VARCHAR(255) NULL,
  foreign key(user_id) references users(id)
);
delimiter |
CREATE TRIGGER upd_post BEFORE UPDATE ON posts
FOR EACH ROW 
  SET NEW.modified = NOW(); 
|
delimiter ;
insert into posts(topic,content,user_id,`file`) values ('Первый пост','Первонах! Lorem ipsum и так далее...', 2, '/yii2-proj/web/img/temp/fs2go4jqe75nj9083dhk2gvgj0/15210368955aa92e5fd77cd9.79046521.gif');