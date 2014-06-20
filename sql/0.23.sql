--Ввод информации для типов организаций и привязки типов организаций к поддоменам
begin;
create table organization_type(
id serial not null,
name character varying(50) not null,
primary key(id)
);
alter table organization_type owner to wwwrynda;

insert into organization_type values
(1, 'Некоммерческая'),
(2, 'Коммерческая'),
(3, 'Государственная'),
(4, 'Станция переливания крови');

create table organization_subdomain(
id serial not null,
org_id integer not null,
sub_id integer not null,
primary key (id),
foreign key(org_id) references organization(id) on delete cascade,
foreign key (sub_id) references subdomain(id) on delete cascade
);
alter table organization_subdomain owner to wwwrynda;

update config set value='0.23' where key='db_version';
commit;
