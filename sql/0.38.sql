begin;
alter table "multimedia" alter column message_id drop not null; 

update config set value='0.38' where key='db_version';
commit;
