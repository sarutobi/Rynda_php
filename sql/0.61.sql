begin;

drop view new_category_view;

create view new_category_view as
select c.id category_id, c.name category_name, description, color, c.slug category_slug, icon, "order" 
from "Category" c;

drop function rynda.search_category_by_theme(theme_id integer);
create function rynda.get_thematic_categories(theme_id integer)
returns setof integer
as
$$
select category_id from category_thematic where thematic_id=$1;
$$
language sql stable;

drop function rynda.search_category_by_domain(domain_id integer);
create function rynda.get_subdomain_categories(domain_id integer)
returns setof integer
as
$$
select category_id from category_thematic ct join subdomain_thematic st on ct.thematic_id = st.thematic_id; 
$$
language sql stable;


update config set value='0.61' where key='db_version';
commit;
