-- Прфили волонтерства
begin;

-- Профиль
create table volunteer_profile(
id serial,
user_id integer not null,
location_id integer not null,
flags integer not null default 0, --Флаги профиля
distance_normal integer not null default 1000, --Расстояние для помощи в обычное время
distance_crysis integer, -- Расстояние для помощи в кризисное время
days_normal integer[], -- Дни помощи в обычное время
days_crysis integer[], --Дни помощи во время кризисов
email character varying(100), -- Почта для отпраавки уведомлений
phone character varying(20), -- Телефон для отправки уведомлений
primary key(id)
);

alter table volunteer_profile add constraint vp_user_fk foreign key(user_id)
references users(id) match simple on update cascade on delete cascade;
alter table volunteer_profile add constraint vp_location_fk foreign key(location_id)
references "Location"(id) match simple on update cascade on delete restrict;

--Привязка профиля к категориям
create table volunteer_profile_category(
id bigserial,
vp_id integer not null,
category_id integer not null,
primary key(id)
);
alter table volunteer_profile_category add constraint vpc_vp_fk foreign key(vp_id)
references volunteer_profile(id) match simple on update cascade on delete cascade;
alter table volunteer_profile_category add constraint vpc_category_fk foreign key(category_id)
references "Category"(id) match simple on update cascade on delete cascade;

update config set value='0.45' where key='db_version';
commit;
