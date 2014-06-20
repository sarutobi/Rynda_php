-- Добавление даты ввода организации в вид
begin;

drop view organization_view;

create view organization_view as
SELECT o.id AS organization_id, l.region_id, r.name AS region_name, o.name AS organization_name, o.description AS organization_description, l.name AS organization_address, o.phone, o.site, o.email, o.contacts, l.latitude, l.longtitude, o.type AS organization_type, o.date_add
   FROM organization o
   JOIN "Location" l ON o.location_id = l.id
   JOIN "Region" r ON l.region_id = r.id;
alter view organization_view owner to wwwrynda;
update config set value='0.19' where key='db_version';
commit;
