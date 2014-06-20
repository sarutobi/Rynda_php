--Изменения в данных пользователя - добавление полей "О себе" "фото" "пол" "дата рождения"
--Удаление неиспользуемых полей "телефон" и "компания"
begin;

alter table meta add column about_me text;
alter table meta add column my_photo integer;
alter table meta add constraint fk_mm_user foreign key(my_photo)
references multimedia(id) match simple on update cascade on delete set null;
alter table meta add column gender integer not null default 0;
alter table meta add column birthday date;
alter table meta drop column phone;
alter table meta drop column company;
update config set value='0.49' where key='db_version'; 
commit;
