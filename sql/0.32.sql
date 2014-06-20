begin;
create or replace function rynda.recommended_messages(integer, integer[])
returns setof integer
as
$$
select id from "Message" where message_type = any($2) and id in (select rynda.recommended_messages($1)) and status not in (1,6)
$$
language sql immutable;

alter function rynda.recommended_messages(integer, integer[]) owner to wwwrynda;

create or replace function rynda.recommended_messages(integer, character varying [])
returns setof integer
as
$$
select m.id from "Message" m join message_type mt  on m.message_type = mt.id
where mt.slug = any($2) and m.id in (select rynda.recommended_messages($1)) and status not in (1,6)
$$
language sql immutable;
alter function rynda.recommended_messages(integer, character varying[]) owner to wwwrynda;

update config set value='0.32' where key='db_version';
commit;
