begin;
drop view user_view;
create view user_view 
as
select u.id, u.username, u.email, u.created_on, u.active, u.last_login, m.first_name, m.last_name, m.flags, m.phones, m.flags & 1 as is_private, mm.uri as avatar_url
from users u join meta m on u.id=m.user_id
LEFT JOIN multimedia mm ON m.my_photo = mm.id
order by u.id;

update config set value='0.63' where key='db_version';
commit;
