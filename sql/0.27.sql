begin;
-- Добавление  иконок в типы организаций
alter table organization_type add column icon character varying(150);
drop view organization_view;
create view organization_view as
SELECT o.id AS organization_id, l.region_id, r.name AS region_name, o.name AS organization_name, o.description AS organization_description, 
  l.name AS organization_address, o.phone, o.site, o.email, o.contacts, l.latitude, l.longtitude, o.type AS organization_type, o.date_add,
  ot.name, ot.icon,
  ARRAY( SELECT organization_categories.category_id
           FROM organization_categories
           WHERE organization_categories.organization_id = o.id) AS categories
FROM organization o
JOIN "Location" l ON o.location_id = l.id
JOIN "Region" r ON l.region_id = r.id
JOIN organization_type ot on o.type=ot.id;
alter view organization_view owner to wwwrynda;

update config set value='0.27' where key='db_version';
commit;
