begin;

create or replace function rynda.add_message_comment(
cmt_txt text,
c_sender character varying(200),
c_email character varying(200),
c_ip inet,
c_user_id integer,
c_message_id integer,
c_reply_to integer
) returns integer 
as
$$
declare cmt_id integer;
declare rpl_lvl integer;
begin
insert into comment(message, date_add, sender, email, ip, user_id )
values($1, now(), $2, $3, $4, $5);
select currval('comment_id_seq') into cmt_id;
if $7 is not null then
    select "level"+1 from in_reply_to where comment_id=$7 into rpl_lvl;
else
    select 0 into rpl_lvl;
end if;

insert into in_reply_to(message_id, comment_id, reply_to, "level")
values($6, cmt_id, $7, rpl_lvl);
return cmt_id;
end;
$$
language plpgsql volatile;

--update config  set value='0.59' where key='db_version';
commit;
