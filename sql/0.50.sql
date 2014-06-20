-- Функция расчета расстояния между двумя Location
begin;
create function rynda.get_distance(loc_id1 integer, loc_id2 integer)
returns float
as
$$
select st_distance((select geom from "Location" where id = $1),(select geom from "Location" where id = $2));
$$
language sql immutable;

update config set value='0.50' where key = 'db_version';

commit;
