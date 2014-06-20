begin;
--Изменение географических расчетов на более точное

CREATE OR REPLACE FUNCTION rynda.find_nearest_messages(id integer, radius integer)
  RETURNS SETOF integer AS
  $BODY$
  declare l_id integer;
  begin
  select into l_id location_id from "Message" where id=$1;
  return query
    select id from "Message" where location_id in
      (select id from "Location" where st_dwithin(geom, (select geom from "Location" l where l.id=l_id ), $2));
  return;
  end;
  $BODY$
  language plpgsql immutable;
alter FUNCTION rynda.find_nearest_messages(integer, integer) owner to wwwrynda;

CREATE OR REPLACE FUNCTION rynda.find_nearest_messages(longtitude double precision, latitude double precision, radius integer)
  RETURNS SETOF integer AS
  $BODY$
  select m.id 
  from "Message" m where m.location_id in
    (select id from "Location" where st_dwithin(geom, st_transform(st_setsrid(st_makepoint($1, $2),4326),32661), $3))
  
  $BODY$
  LANGUAGE sql IMMUTABLE;
ALTER FUNCTION rynda.find_nearest_messages(double precision, double precision, integer) OWNER TO wwwrynda;

create or replace function rynda.get_location_distance(location1_id integer, location2_id integer)
returns double precision as
$BODY$
  select st_distance(geom, (select geom from "Location" where id=$2)) from "Location" where id=$1;
$BODY$
language sql immutable;
alter function rynda.get_location_distance(integer, integer) owner to wwwrynda;
update config set value='0.7' where key = 'db_version';
commit;
