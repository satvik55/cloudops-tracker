FROM mysql:8.0
ENV MYSQL_ROOT_PASSWORD=rootpassword
ENV MYSQL_DATABASE=cloudops_tracker

COPY init.sql /docker-entrypoint-initdb.d/
