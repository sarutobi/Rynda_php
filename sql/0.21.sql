-- Задача 73
begin;
create or replace function rynda.get_messages_distance(msg1_id integer, msg2_id integer)
returns double precision as
$BODY$
  declare l1 integer;
  declare l2 integer;
  declare ret double precision;  
  begin
    select into l1 location_id from "Message" where id=$1;
    select into l2 location_id from "Message" where id=$2;

    select into ret st_distance(geom, (select geom from "Location" where id=l2)) from "Location" where id=l1;
    return ret;
  end
$BODY$
language plpgsql immutable;
alter function rynda.get_messages_distance(integer, integer) owner to wwwrynda;
update config set value='0.21' where key='db_version';
commit;
