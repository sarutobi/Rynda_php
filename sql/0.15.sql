-- Добавление возможности сортировки регионов
begin;
alter table "Region" add column "order" integer not null default 0;
update "Region" set "order" = id +4;

update "Region" set "order" = 1 where id=77;
update "Region" set "order" = 2 where id=50;
update "Region" set "order" = 3 where id=78;
update "Region" set "order" = 4 where id=47;

create or replace view "regions_view" as
SELECT "Region".id AS region_id, "Region".name AS region_name, "City".name AS capital_name, "City".latitude, "City".longtitude, "Region".slug AS region_slug, "Region".zoom_lvl
   FROM "Region"
      JOIN "City" ON "Region".city_id = "City".id order by "Region"."order";
update config set value='0.15' where key='db_version';
commit;
