--Изменение представления сообщений - добавление slug и названия типа сщщбщения
begin;
drop function rynda.search_messages_by_location_name(character varying);
drop function rynda.search_messages_with(character varying);
drop function rynda.search_titles_with(character varying);

drop view message_view;
create view message_view as
SELECT m.id AS message_id, m.title AS message_title, m.message AS message_text, m.date_add AS message_date, m.subdomain_id, s.url AS subdomain,
m.user_id  user_id,
(xpath('/sender/firstname/text()[1]'::text, m.sender))[1]::character varying(100) AS sender_first_name, 
(xpath('/sender/lastname/text()[1]'::text, m.sender))[1]::character varying(100) AS sender_last_name, 
(xpath('/sender/patrname/text()[1]'::text, m.sender))[1]::character varying(100) AS sender_patr_name, 
xpath('/sender/phone/text()'::text, m.sender)::character varying(10)[] AS sender_phone,
(xpath('/sender/email/text()[1]'::text, m.sender))[1]::character varying(100) AS sender_email,
l.latitude, l.longtitude, l.name AS location_name, l.region_id, r.name AS region_name, 
m.flags & 1::bigint AS is_active, 
(m.flags & 2::bigint) >> 1 AS is_untimed, 
(m.flags & 4::bigint) >> 2 AS is_public, 
(m.flags & 8::bigint) >> 3 AS is_notify, 
m.message_type, mt.slug as message_typeslug, mt.name as message_typename,
m.status AS message_status,
array(select category_id from messagecategories where message_id=m.id) categories
FROM "Message" m
JOIN "Location" l ON m.location_id = l.id
LEFT JOIN "Region" r ON l.region_id = r.id
LEFT JOIN message_type mt on m.message_type = mt.id
LEFT JOIN subdomain s ON m.subdomain_id = s.id;
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


update config set value='0.31' where key='db_version';
commit;
