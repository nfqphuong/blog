.PHONY: init remove-infras download
DB_CONTAINER?=blog_db

dev:
	symfony server:start

remove-infras:
	docker-compose stop; docker-compose  rm -f

migrate:
	php bin/console --no-interaction doctrine:migrations:migrate

seed:
	php bin/console --no-interaction doctrine:fixtures:load

init: remove-infras
	@docker-compose  up -d db
	@echo "Waiting for database connection..."
#	@while ! docker exec $(DB_CONTAINER) mysqlcheck -u blog_user -pblog_pass blog &> /dev/null; do \
#    		sleep 1; \
#    done
	@while ! docker exec $(DB_CONTAINER) mysql -u blog_user -pblog_pass blog &> /dev/null; do \
			sleep 1; \
	done
	@make migrate
	@make seed

logs:
	docker-compose logs

up: remove-infras
	@docker-compose up -d
