--- Прудставление профиля волонтерства для пользователя
begin;


create or replace view volunteer_profile_view as
select vp.id vp_id, user_id, vp.location_id, (flags & 1) vp_active,(flags &2 >>1) vp_allcats, distance_normal, distance_crysis, days_normal, days_crysis, email vp_email, phone vp_phone,
l.name location_name, l.latitude, l.longtitude, l.region_id, r.name region_name, array(select category_id from volunteer_profile_category where vp_id=vp.id) categories 
from volunteer_profile vp join "Location" l on vp.location_id=l.id join "Region" r on r.id=l.region_id;

update config set value='0.46' where key='db_version';
commit;

