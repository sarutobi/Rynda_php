begin;
alter table ci_sessions alter column user_agent type character varying(150);
update config set value='0.13' where key='db_version';
commit;
