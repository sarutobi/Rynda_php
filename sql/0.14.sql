begin;
--Добавление таблицы для сервиса определения региона
create table regions_poly(
id serial not null,
region_id integer,
name character varying(100),
geom geography(polygon, 4326),
primary key(id)
);

alter table regions_poly owner to wwwrynda;
-- Добавление поля "тип привествия" в таблицу метаинформации пользователя

alter table meta add column ref_type integer not null default 0;
update config set value='0.14' where key='db_version';
commit;
