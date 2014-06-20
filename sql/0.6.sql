begin;
drop function search_messages_by_location_name(character varying);
drop function rynda.search_messages_with(character varying);
drop function rynda.search_titles_with(character varying);
drop view message_view;
create view message_view as
SELECT m.id AS message_id, m.title AS message_title, m.message AS message_text, m.date_add AS message_date, 
m.subdomain_id as subdomain_id, s.url as subdomain,
(xpath('/sender/firstname/text()[1]'::text, m.sender))[1]::character varying(100) AS sender_first_name,
(xpath('/sender/lastname/text()[1]'::text, m.sender))[1]::character varying(100) AS sender_last_name,
(xpath('/sender/patrname/text()[1]'::text, m.sender))[1]::character varying(100) AS sender_patr_name,
xpath('/sender/phone/text()'::text, m.sender)::character varying(10)[] AS sender_phone,
(xpath('/sender/email/text()[1]'::text, m.sender))[1]::character varying(100) AS sender_email, 
l.latitude, l.longtitude, l.name AS location_name, l.region_id, r.name AS region_name, 
m.flags & 1::bigint AS is_active, (m.flags & 2::bigint) >> 1 AS is_untimed, 
(m.flags & 4::bigint) >> 2 AS is_public, (m.flags & 8::bigint) >> 3 AS is_notify, 
(m.flags & 240::bigint) >> 4 AS message_type, m.status AS message_status
FROM "Message" m
JOIN "Location" l ON m.location_id = l.id
LEFT JOIN "Region" r ON l.region_id = r.id
left join subdomain s on m.subdomain_id = s.id;
alter view message_view owner to wwwrynda;

create function rynda.search_messages_by_location_name("seek" character varying) 
returns setof message_view
as
$$
select * from message_view where to_tsvector(location_name) @@ to_tsquery($1)
$$
language sql immutable;
alter function rynda.search_messages_by_location_name(character varying) owner to wwwrynda;
create function rynda.search_messages_with("seek" character varying)
returns setof message_view
as
$$
select * from message_view where to_tsvector(message_text) @@ to_tsquery($1);
$$
language sql immutable;
alter function rynda.search_messages_with(character varying) owner to wwwrynda;

create function rynda.search_titles_with("seek" character varying)
returns setof message_view
as
$$
select * from message_view where to_tsvector(message_title) @@ to_tsquery($1);
$$
language sql immutable;
alter function rynda.search_titles_with(character varying) owner to wwwrynda;

drop view category_view;
create view category_view as
WITH childs_count AS (
 SELECT "Category".parent_id AS pid, count("Category".parent_id) AS count
 FROM "Category"
 GROUP BY "Category".parent_id)
SELECT c.id AS category_id, c.parent_id, c.name AS category_name, c.description, c.color, c.slug AS category_slug, c.icon, 
COALESCE(cc.count, 0::bigint) AS children, c."order", c.subdomain_id, s.url AS subdomain
FROM "Category" c
LEFT JOIN childs_count cc ON c.id = cc.pid
left JOIN subdomain s ON c.subdomain_id = s.id;
alter view category_view owner to wwwrynda;

Drop table "Status";
update config set value='0.6' where key='db_version';
commit;
