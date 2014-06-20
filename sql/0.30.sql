-- Изменение числовых значений для типов собщений
begin;
alter table "Message" drop constraint "Message_message_type_fkey";
alter table "Message" add foreign key(message_type) references message_type(id) on delete restrict on update cascade;

update message_type set id=5 where id=4;
update message_type set id=4 where id=3;
update message_type set id=3 where id=0;

alter table message_type add column slug character varying(100);
update message_type set slug='request' where id=1;
update message_type set slug='offer' where id=2;
update message_type set slug='info' where id=3;
update message_type set slug='advise' where id=4;
update message_type set slug='comment' where id=5;

update config set value='0.30' where key='db_version';
commit;
