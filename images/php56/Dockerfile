FROM nmcteam/php56
MAINTAINER Espen Andersen <post@espenandersen.no>

ENV DEBIAN_FRONTEND noninteractive
RUN apt-get update
RUN apt-get -y --force-yes install gawk
RUN apt-get -y --force-yes install php5-dev
RUN apt-get -y install libcurl4-openssl-dev
RUN apt-get -y install libevent-dev
RUN echo "extension=ncurses.so" > /etc/php5/mods-available/http.ini
RUN yes "" | pecl install ncurses-1.0.2
