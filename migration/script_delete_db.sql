show tables ssdestino | while read table; do mysql -e "drop table $table" ssdestino; done;
