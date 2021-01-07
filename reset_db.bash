#!/usr/bin/env bash
# shellcheck disable=SC2181

# Recreate and reset the database to default
#
# Created 2021-01-06

#
# Load a SQL file into db
#
function loadSqlIntoDB
{
    echo ">>> $4 ($3)"
    mysql "-u$1" "-p$2" ramverk1_project < "$3" > /dev/null
    if [ $? -ne 0 ]; then
        echo "The command failed, you may have issues with your SQL code."
        echo "Verify that all SQL commands can be exeucted in sequence in the file:"
        echo " '$3'"
        exit 1
    fi
}

#
# Reset and recreate database
#
echo ">>> Initializing database reset"
loadSqlIntoDB "user" "pass" "sql/mysql/setup.sql" "Initiate database and user"
loadSqlIntoDB "user" "pass" "sql/mysql/ddl_tables.sql" "Create tables"
# loadSqlIntoDB "user" "pass" "sql/ddl/ddl_functions.sql" "Create functions"
# loadSqlIntoDB "user" "pass" "sql/ddl/ddl_procedures.sql" "Create procedures"
# loadSqlIntoDB "user" "pass" "sql/ddl/ddl_triggers.sql" "Create triggers"
loadSqlIntoDB "user" "pass" "sql/mysql/insert.sql" "Insert data into tables using csv-files"
