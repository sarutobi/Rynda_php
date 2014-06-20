begin;
create or replace function rynda.get_db_version() returns character varying(10)
as
$$ select value from config where key='db_version';$$
language sql immutable;

alter function rynda.get_db_version() owner to wwwrynda;
update config set value='0.4' where key='db_version';
commit;
