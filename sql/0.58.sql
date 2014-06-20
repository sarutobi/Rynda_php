begin;

create or replace function rynda.get_subdomain_messages(subdomain_id integer)
returns setof integer
as
$$
select distinct message_id 
from subdomain_thematic st join category_thematic ct on st.thematic_id=ct.thematic_id 
  join messagecategories mc on mc.category_id=ct.category_id
where subdomain_id=$1;
$$
language sql stable;

create or replace function rynda.get_thematic_messages(thematic_id integer)
returns setof integer
as
$$
select distinct message_id 
from category_thematic ct join messagecategories mc on mc.category_id=ct.category_id
where thematic_id=$1;
$$
language sql stable;

update config set value='0.58' where key='db_version';
commit;

