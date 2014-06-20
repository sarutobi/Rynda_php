-- К сообщению добавлено поле IP адрес отправителя
begin;

alter table "Message" add column sender_ip inet;

update config set value = '0.53' where key='db_version';

commit;
