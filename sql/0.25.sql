-- Исправление ошибки 214
begin;
create or replace function rynda.recommended_messages(integer, integer[])
returns setof integer
as
$$
select id from "Message" where ((flags & 240) >> 4) = any($2) and id in (select rynda.recommended_messages($1)) and status not in (1,6)
$$
language sql immutable;

update config set value='0.25' where key='db_version';
commit;
