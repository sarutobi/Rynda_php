begin;
create function rynda.get_category_thematics(cat_id integer)
returns setof integer
as
$$
select thematic_id from category_thematic where category_id=$1;
$$
language sql stable;

update config set value='0.62' where key='db_version';
commit;
