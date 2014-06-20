begin;

--drop view new_message_view;

create view new_message_view as
SELECT m.id AS message_id, m.title AS message_title, m.message AS message_text, m.date_add AS message_date, m.date_modify,m.location_id, 
m.user_id, (xpath('/sender/firstname/text()[1]'::text, m.sender))[1]::character varying(100) AS sender_first_name, 
(xpath('/sender/lastname/text()[1]'::text, m.sender))[1]::character varying(100) AS sender_last_name, 
(xpath('/sender/patrname/text()[1]'::text, m.sender))[1]::character varying(100) AS sender_patr_name,
xpath('/sender/phone/text()'::text, m.sender)::character varying(12)[] AS sender_phone, 
(xpath('/sender/email/text()[1]'::text, m.sender))[1]::character varying(100) AS sender_email,
m.flags & 1::bigint AS is_active, (m.flags & 2::bigint) >> 1 AS is_untimed, (m.flags & 4::bigint) >> 2 AS is_public, 
(m.flags & 8::bigint) >> 3 AS is_notify, m.message_type, mt.slug AS message_typeslug, m.status AS message_status
FROM "Message" m
LEFT JOIN message_type mt ON m.message_type = mt.id;

create view location_view as
select l.id location_id, l.latitude latitude, l.longtitude longtitude, l.name address, l.region_id region_id,
r.name region_name, r.slug region_slug
from "Location" l left join "Region" r on l.region_id = r.id;

update config set value='0.57' where key='db_version';
commit;
