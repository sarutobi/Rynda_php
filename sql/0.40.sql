begin;
update "Message" set subdomain_id=0 where subdomain_id is null;
alter table "Message" alter column subdomain_id set not null;
alter table "Message" alter column subdomain_id set default 0;

update config set value='0.40' where key='db_version';
commit;

