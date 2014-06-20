-- Изменение 206
begin;
-- Поиск регионов, интересных пользователю
create function rynda.get_user_locations(user_id integer)
returns setof integer
as
$BODY$
select distinct region_id from "Location" where id in (select location_id from "Message" where user_id=$1)
$BODY$
language sql immutable;
alter function rynda.get_user_locations(integer) owner to wwwrynda;

-- Поиск категорий, интересных пользователю
create function rynda.get_user_categories(user_id integer)
returns setof integer
as
$BODY$
select distinct category_id from messagecategories where message_id in (select id from "Message" where user_id=11);
$BODY$
language sql immutable;
alter function rynda.get_user_categories(integer) owner to wwwrynda;

-- Поиск сообщений, потенциально итересных пользователю
create function rynda.get_user_recomendations(user_id integer)
returns setof integer
as
$BODY$
select distinct m.id from "Message" m
join "Location" l on m.location_id=l.id
join messagecategories mc on m.id=mc.message_id
where l.region_id=any(select rynda.get_user_locations($1))
and
mc.category_id=any(select rynda.get_user_categories($1))
and (m.user_id != $1 or m.user_id is null)
$BODY$
language sql immutable;
alter function rynda.get_user_recomendations(integer) owner to wwwrynda;

update config set value='0.22' where key='db_version';
commit;
