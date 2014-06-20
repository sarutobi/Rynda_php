-- Добавление информации о соцсетях и привязки профилей соцсетей к профилю пользователя
begin;
create table social_net (
id serial,
name character varying(50),
"icon" character varying(100),
url character varying(100),
primary key(id)
);

create table user_social_profile(
id serial,
user_id integer,
social_id integer,
profile_url character varying(200),
primary key(id),
constraint us_user_fk foreign key(user_id) references users(id) match simple on update cascade on delete cascade, 
constraint us_soc_fk foreign key(social_id) references social_net(id) match simple on update cascade on delete cascade 
);

update config set value='0.54' where key='db_version';
commit;
