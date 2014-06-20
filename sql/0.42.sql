begin;

create table "comment" (
id serial, 
message text not null,
date_add timestamp without time zone,
status integer not null default 0,
sender character varying(200),
email character varying(200) not null,
ip inet not null,
user_id integer,
primary key (id)
);

alter table comment add constraint user_comment foreign key(user_id)
references "user"(id) match simple on update cascade on delete restrict;

create table "in_reply_to"(
id serial,
message_id integer not null,
comment_id integer not null,
reply_to integer,
level integer not null default 0,
primary key(id)
);

alter table "in_reply_to" add constraint message_comment foreign key(message_id)
references "Message"(id) match simple on update cascade on delete cascade;
alter table "in_reply_to" add constraint comment_comment foreign key(comment_id)
references comment(id) match simple on update cascade on delete restrict;

create view comment_view as
select comment_id,message_id, reply_to, level, message as comment_txt, status, date_add, sender, email, ip, user_id
from comment join in_reply_to on comment.id=in_reply_to.comment_id
order by reply_to, date_add, level;

create function rynda.add_comment(msg_id integer, parent_id integer, cmnt_text text, cmnt_user_id integer, cmnt_name character varying(200), 
cmnt_email character varying(200), cmnt_ip inet)
returns void
as
$$
declare last_cmnt_id integer;
declare cur_lvl integer;
begin
    --Добавляем комментарий в табличку и получаем его идентификатор
    insert into comment(message, date_add, sender, email, ip, user_id) values($3, now(),$5, $6, $7, $4);
    select into last_cmnt_id currval('comment_id_seq');
    -- Определяем уровень вложенности ответа
    if parent_id is not null then
        select distinct into cur_lvl level+1 from in_reply_to where comment_id=$2;
    else
        cur_lvl:= 0;
    end if;

    -- Заносим метаинформацию о комментарии
    insert into in_reply_to(message_id, "comment_id", reply_to, level)
    values ($1, last_cmnt_id, $2, cur_lvl );
return;
end
$$
language plpgsql volatile;

update config set value='0.42' where key='db_version';
commit;
