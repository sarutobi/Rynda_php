begin;
create function rynda.search_locations_by_name("seek" character varying) 
returns setof integer
as
$$
select id from "Location" where to_tsvector(translate(name, 'ё', 'е')) @@ plainto_tsquery(translate($1, 'ё', 'е'))
$$
language sql immutable;

update config set value='0.44' where key='db_version';
commit;
