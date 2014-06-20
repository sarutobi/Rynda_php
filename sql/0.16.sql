--Связь между сообщениями и зарегистрированными пользователями
begin;
alter table "Message" add column "user_id" integer default NULL;
alter table "Message" add constraint "FK_users" foreign key("user_id") references "users"(id);
update config set value='0.16' where key='db_version';
commit;
