#!/bin/sh

mysqldump -uroot -p1 automaster > tests/_data/full_dump.sql
