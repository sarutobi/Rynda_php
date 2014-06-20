--Модификация для работы с профилями пользователя
begin;
alter table meta add column flags integer not null default 0;
alter table meta add column phones character varying(10)[];

create view user_profile 
as
select u.id, u.username, u.email, u.created_on, u.active, u.last_login, m.first_name, m.last_name, m.flags, m.phones
from users u join meta m on u.id=m.user_id
order by u.id;
alter view user_profile owner to wwwrynda;

update config set value='0.18' where key='db_version';
commit;
