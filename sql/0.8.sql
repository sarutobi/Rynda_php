Begin;
alter table "Category" alter column parent_id drop not null;

drop view category_view;
create or replace view category_view as
WITH childs_count AS (
    SELECT "Category".parent_id AS pid, count("Category".parent_id) AS count, subdomain_id as ch_s_id
    FROM "Category"
    GROUP BY "Category".parent_id, subdomain_id)
SELECT c.id AS category_id, coalesce(c.parent_id, 0) as parent_id, c.name AS category_name, c.description, c.color, 
c.slug AS category_slug, 
c.icon, COALESCE(cc.count, 0::bigint) AS children, c."order", c.subdomain_id, s.url AS subdomain,sch.url as ch_subdomain,
cc.ch_s_id as ch_subdomain_id
FROM "Category" c
LEFT JOIN childs_count cc ON c.id = cc.pid
LEFT JOIN subdomain s ON c.subdomain_id = s.id
LEFT JOIN subdomain sch ON cc.ch_s_id = sch.id;
update "Category" set parent_id=null where parent_id=0;
alter view category_view owner to wwwrynda;
update config set value='0.5' where key='db_version';
commit;
