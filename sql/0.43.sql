begin;

-- Сущность категории групп
create table category_group(
id serial,
name character varying (100) not null,
slug character varying(50),
primary key (id)
);

-- Привязка категории к группам
create table category2group(
id serial,
category_id integer not null,
category_group_id integer not null,
primary key(id)
);

alter table category2group add constraint cat2gr_catgr_fk foreign key (category_group_id)
references category_group(id) match simple on update cascade on delete restrict;
alter table category2group add constraint cat2gr_category_fk foreign key(category_id)
references "Category" (id) match simple on update cascade on delete cascade;

-- Сущность Тема
create table thematic(
id serial,
name character varying(100),
slug character varying(100),
primary key(id)
);

--Привязка категорий к темам
create table category_thematic(
id serial,
category_id integer,
thematic_id integer
);
alter table category_thematic add constraint cat_th_thematic_fk foreign key (thematic_id)
references thematic(id) match simple on update cascade on delete restrict;
alter table category_thematic add constraint cat_th_category_fk foreign key(category_id)
references "Category" (id) match simple on update cascade on delete cascade;

--Привязка тем к поддоменам
create table subdomain_thematic(
id serial,
subdomain_id integer,
thematic_id integer
);
alter table subdomain_thematic add constraint subdomain_th_subdomain_fk foreign key (subdomain_id)
references subdomain(id) match simple on update cascade on delete restrict;
alter table subdomain_thematic add constraint subdomain_th_category_fk foreign key(thematic_id)
references thematic(id) match simple on update cascade on delete cascade;


update config set value='0.43' where key='db_version';

commit;
