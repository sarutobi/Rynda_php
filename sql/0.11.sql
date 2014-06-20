--Восстановление пропущенного вида для выбора поддоменов
begin;
create view subdomain_view as
SELECT subdomain.id, subdomain.url, subdomain.title, subdomain.is_current,
subdomain.status, subdomain."order"
from subdomain;
alter view subdomain_view owner to wwwrynda;

update config set value='0.11' where key='db_version';
commit;
