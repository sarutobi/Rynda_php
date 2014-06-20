begin;

create function rynda.search_category_by_theme(theme_id integer)
returns setof integer
as
$$
select category_id from category_thematic where thematic_id=$1;
$$
language sql stable;

create function rynda.search_category_by_domain(domain_id integer)
returns setof integer
as
$$
select category_id from category_thematic ct join subdomain_thematic st on ct.thematic_id = st.thematic_id; 
$$
language sql stable;

update config set value='0.60' where key='db_version';
commit;
