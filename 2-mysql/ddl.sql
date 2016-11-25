create index link_data_id on link(data_id);
create index link_info_id on link(info_id);

SELECT *
FROM
  link l
    left join data d on d.id = l.data_id
    left join info i on i.id = l.info_id;