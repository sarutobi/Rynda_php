-- Функция выборки пользователей, интересующихся регионом
begin;
create or replace function rynda.search_users_by_region(region_id integer)
returns setof integer
as
$$
select distinct user_id from volunteer_profile vp join "Location" l on vp.location_id = l.id where l.region_id=$1;
$$
language sql stable;

update config set value='0.64' where key='db_version';

commit;
