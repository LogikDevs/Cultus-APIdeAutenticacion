FROM ggmartinez/laravel:php-82
WORKDIR /app
# ignore  ./mysql in copying the project


COPY . /app
#make .env
#install git (is centos 7 
RUN yum install git -y
RUN cp .env.example .env
#put the database in the .env
ENV DB_HOST=proyectosiep.duckdns.org
ENV DB_PORT=33006
ENV DB_DATABASE=logik
ENV DB_USERNAME=franco.fedullo
ENV DB_PASSWORD=55700275

RUN composer install
RUN php artisan key:generate
RUN php artisan migrate
RUN php artisan db:seed
RUN php artisan passport:install
RUN php artisan storage:link
CMD php artisan serve --host=8041

