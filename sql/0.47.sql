begin;
-- Вспомогательная функция для поиска категорий сообщений в случае,
-- если пользователь установил флаг "Все категории"
create or replace function rynda.search_vp_categories(profile_id integer)
returns setof integer
as
$$
declare all_msg integer;
begin
select into all_msg flags & 2 from volunteer_profile where id=$1;
if all_msg = 2 then
return query select id from "Category";
else
return query select category_id from volunteer_profile_category where vp_id=$1;
end if;
end;
$$
language plpgsql stable;

-- Функция для поиска сообщений, релевантных профилю волонтерства
create or replace function rynda. search_relevant_messages(profile_id integer)
returns setof integer
as
$$
with potential_locations as(
select distinct location_id from "Message" m join messagecategories mc on m.id=mc.message_id 
where category_id in(select * from rynda.search_vp_categories($1))
and m.status > 1 and m.status < 4 and m.flags & 1 = 1
), 
distance_confirmed as (
select id from "Location" where st_dwithin(geom, (select geom from volunteer_profile vp join "Location" l on vp.location_id=l.id where vp.id=$1), (select distance_normal from volunteer_profile where id=$1))
and id in ( select location_id from potential_locations)
)
select id from "Message"  where location_id in (select * from distance_confirmed) order by id;
$$ 
language sql stable;

update config set value='0.47' where key='db_version';
commit;
