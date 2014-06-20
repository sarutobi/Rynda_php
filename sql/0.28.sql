-- Введение главного сообщения для раздела атласа
begin;
alter table subdomain add column disclaimer text;
insert into subdomain (id, url, title, is_current, status, "order") values(0, '', '', false, 0, 0);

create function rynda.subdomain_disclaimer(domain_id integer)
returns text
as
$$
declare base_disc text;
declare disc text;
begin
select into base_disc disclaimer from subdomain where id=0;
select into disc coalesce(disclaimer, base_disc) from subdomain where id=$1;
return disc;
end
$$
language plpgsql immutable;
alter function rynda.subdomain_disclaimer(integer) owner to wwwrynda;

drop view subdomain_view;
create view subdomain_view as
SELECT subdomain.id, subdomain.url, subdomain.title, subdomain.is_current,
subdomain.status, subdomain."order", rynda.subdomain_disclaimer(id) as sub_disclaimer
from subdomain;
alter view subdomain_view owner to wwwrynda;
 
update config set value='0.28' where key='db_version';
commit;
