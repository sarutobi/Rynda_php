-- Добавление привязки комментариев к организациям
begin;

create table "organization_comments"(
id serial,
organization_id integer not null,
comment_id integer not null,
reply_to integer,
level integer not null default 0,
primary key(id)
);

alter table "organization_comments" add constraint org_comment foreign key(organization_id)
references "organization"(id) match simple on update cascade on delete cascade;
alter table "in_reply_to" add constraint org_comment_comment foreign key(comment_id)
references comment(id) match simple on update cascade on delete restrict;


update config set value='0.52' where key='db_version';
commit;
