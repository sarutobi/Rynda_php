begin;

alter table "Category" add column group_id integer;
alter table "Category" add constraint fk_cat_group foreign key (group_id)
references category_group(id) match simple on update cascade on delete restrict;

drop table category2group;

create view new_category_view as
select c.id category_id, c.name category_name, description, color, c.slug category_slug, icon, "order", coalesce(group_id, 0) group_id, g.name group_name, g.slug group_slug
from "Category" c left join category_group g on c.group_id=g.id;

update config set value='0.56' where key='db_version';
commit;
