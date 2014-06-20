begin;

create or replace function rynda.get_category4message(message_id integer)
returns setof "Category"
as
$$
select * from "Category" where id in (select category_id from messagecategories where message_id=$1);
$$
language sql stable;

create or replace function rynda.get_mmedia4message(message_id integer)
returns setof multimedia
as
$$
select * from multimedia where message_id=$1;
$$
language sql stable;

update config set value='0.55' where key='db_version';
commit;
