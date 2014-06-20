-- Исправление ошибки в привязке организаций к субдоменам
begin;
drop table organization_subdomain;

create table orgtype_subdomain(
id serial not null,
orgtype_id integer not null,
sub_id integer not null,
primary key (id),
foreign key(orgtype_id) references organization_type(id) on delete cascade,
foreign key (sub_id) references subdomain(id) on delete cascade
);
alter table orgtype_subdomain owner to wwwrynda;

-- Добавление категорий организации в представление
drop view organization_view;
create view organization_view as
SELECT o.id AS organization_id, l.region_id, r.name AS region_name, o.name AS organization_name, o.description AS organization_description, 
l.name AS organization_address, o.phone, o.site, o.email, o.contacts, l.latitude, l.longtitude, o.type AS organization_type,
ARRAY( SELECT organization_categories.category_id
           FROM organization_categories
          WHERE organization_categories.organization_id = o.id) AS categories
   FROM organization o
   JOIN "Location" l ON o.location_id = l.id
   JOIN "Region" r ON l.region_id = r.id;
alter view organization_view owner to wwwrynda; 
update config set value='0.24' where key='db_version';
commit;
