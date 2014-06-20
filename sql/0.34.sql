--Хранение данных мультимедиа
begin;
-- Multimedia
create table multimedia(
id serial,
type integer not null default 0,
uri character varying(255) not null,
thumb_uri character varying (255) not null,
message_id integer not null,
checksum character varying,
primary key (id),
foreign key (message_id) references "Message" on update cascade on delete restrict
);
alter table multimedia owner to wwwrynda;
grant all privileges on multimedia to public;

update config set value='0.34' where key='db_version';
commit;
