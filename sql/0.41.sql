begin;
-- Корректировка функции поиска сообщений, находящихся на определенной дистанции от заданного

CREATE OR REPLACE FUNCTION rynda.find_nearest_messages(id integer, radius integer)
  RETURNS SETOF integer AS
  $BODY$
  declare l_id integer;
  begin
  select into l_id location_id from "Message" m where m.id=$1;
  return query
    select m.id from "Message" m where m.location_id in
      (select l.id from "Location" l where st_dwithin(geom, (select geom from "Location" l where l.id=l_id ), $2));
  return;
  end;
  $BODY$
  language plpgsql immutable;
alter FUNCTION rynda.find_nearest_messages(integer, integer) owner to wwwrynda;

update config set value='0.41' where key = 'db_version';
commit;
