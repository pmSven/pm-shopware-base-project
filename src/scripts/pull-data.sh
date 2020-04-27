#!/bin/bash

source $2

DUMP_NAME="automatic-dump-for-local.sql"

# Print usage
usage() {
  echo -n "$(basename $0) [COMMAND]...
 Script for downloading the database and import it. Usually it's enough to just call this script with the -p option.
 Be sure to have the correct ssh config entry (${SOURCE_SSH_NAME}) and set the url to \"${DEST_SHOP_URL}\".
 Commands:
      -d, --download      Downloads the database
      -i, --import        Imports the database to local
      -m, --media         Downloads the media files (rsync)
      -p, --preferred     Executes the preferred order of commands (--download --import --media)
      -h, --help          Display this help and exit
"
}

download_db_dump(){
    echo "Downloading database"
    ${SOURCE_SSH} ${SOURCE_MYSQL_DUMP_COMMAND} -u${SOURCE_DB_USERNAME} -p${SOURCE_DB_PWD} --skip-triggers ${SOURCE_DB} > ${DUMP_NAME}

    echo "SET FOREIGN_KEY_CHECKS = 0;" >> ${DUMP_NAME}

    echo "Setting basic settings"
    echo "" >> ${DUMP_NAME}
    echo "-- Setting basic settings" >> ${DUMP_NAME}
    echo "Update s_core_shops SET host=\"${DEST_SHOP_URL}\" where id=1;" >> ${DUMP_NAME}
    echo "Update s_core_shops SET secure=0 where id=1;" >> ${DUMP_NAME}

    echo "Change Theme Settings";
    echo "UPDATE s_core_theme_settings SET compiler_create_source_map=1;" >> ${DUMP_NAME}

    echo "Truncating notifications";
    echo "" >> ${DUMP_NAME}
    echo "-- Truncating notifications" >> ${DUMP_NAME}
    echo "TRUNCATE s_articles_notification;" >> ${DUMP_NAME}
    echo "UPDATE s_crontab SET active=0 WHERE name='eMail-Benachrichtigung';" >> ${DUMP_NAME}

    echo "Truncating users";
    echo "" >> ${DUMP_NAME}
    echo "-- Truncating users" >> ${DUMP_NAME}
    echo "TRUNCATE s_user;" >> ${DUMP_NAME}
    echo "TRUNCATE s_user_addresses;" >> ${DUMP_NAME}
    echo "TRUNCATE s_user_addresses_attributes;" >> ${DUMP_NAME}
    echo "TRUNCATE s_user_attributes;" >> ${DUMP_NAME}
    echo "TRUNCATE s_user_billingaddress;" >> ${DUMP_NAME}
    echo "TRUNCATE s_user_billingaddress_attributes;" >> ${DUMP_NAME}
    echo "TRUNCATE s_user_shippingaddress;" >> ${DUMP_NAME}
    echo "TRUNCATE s_user_shippingaddress_attributes;" >> ${DUMP_NAME}

    echo "Truncating orders";
    echo "" >> ${DUMP_NAME}
    echo "-- Truncating orders" >> ${DUMP_NAME}
    echo "TRUNCATE s_order;" >> ${DUMP_NAME}
    echo "TRUNCATE s_order_attributes;" >> ${DUMP_NAME}
    echo "TRUNCATE s_order_basket;" >> ${DUMP_NAME}
    echo "TRUNCATE s_order_basket_attributes;" >> ${DUMP_NAME}
    echo "TRUNCATE s_order_billingaddress;" >> ${DUMP_NAME}
    echo "TRUNCATE s_order_billingaddress_attributes;" >> ${DUMP_NAME}
    echo "TRUNCATE s_order_comparisons;" >> ${DUMP_NAME}
    echo "TRUNCATE s_order_details;" >> ${DUMP_NAME}
    echo "TRUNCATE s_order_details_attributes;" >> ${DUMP_NAME}
    echo "TRUNCATE s_order_documents;" >> ${DUMP_NAME}
    echo "TRUNCATE s_order_documents_attributes;" >> ${DUMP_NAME}
    echo "TRUNCATE s_order_esd;" >> ${DUMP_NAME}
    echo "TRUNCATE s_order_history;" >> ${DUMP_NAME}
    echo "TRUNCATE s_order_notes;" >> ${DUMP_NAME}
    echo "TRUNCATE s_order_shippingaddress;" >> ${DUMP_NAME}
    echo "TRUNCATE s_order_shippingaddress_attributes;" >> ${DUMP_NAME}

    echo "Truncating statistic";
    echo "" >> ${DUMP_NAME}
    echo "-- Truncating statistic" >> ${DUMP_NAME}
    echo "TRUNCATE TABLE s_emarketing_lastarticles;" >> ${DUMP_NAME}
    echo "TRUNCATE TABLE s_statistics_article_impression;" >> ${DUMP_NAME}
    echo "TRUNCATE TABLE s_statistics_referer;" >> ${DUMP_NAME}
    echo "TRUNCATE TABLE s_articles_also_bought_ro;" >> ${DUMP_NAME}

    echo "Truncating logs";
    echo "" >> ${DUMP_NAME}
    echo "-- Truncating logs" >> ${DUMP_NAME}
    echo "TRUNCATE s_core_log;" >> ${DUMP_NAME}
    echo "TRUNCATE s_statistics_search;" >> ${DUMP_NAME}
    echo "TRUNCATE s_statistics_pool;" >> ${DUMP_NAME}
    echo "TRUNCATE s_statistics_visitors;" >> ${DUMP_NAME}

    echo "elastic search reindexing fix"
    echo "Delete FROM s_es_backlog;" >> ${DUMP_NAME}

    echo "remove old rewrite rules"
    echo "DELETE FROM s_core_rewrite_urls;" >> ${DUMP_NAME}

    echo "setting correct theme"
    echo "Update s_core_shops set template_id=( SELECT id FROM s_core_templates where name=\"${DEST_THEME_NAME}\");" >> ${DUMP_NAME}

    echo "SET FOREIGN_KEY_CHECKS = 1;" >> ${DUMP_NAME}
}

import_db_dump(){
    echo "Importing database with name '${DEST_DB}'"
    ${DEST_MYSQL_COMMAND} -u${DEST_DB_USERNAME} -p${DEST_DB_PWD} <<< "DROP DATABASE IF EXISTS ${DEST_DB}"
    ${DEST_MYSQL_COMMAND} -u${DEST_DB_USERNAME} -p${DEST_DB_PWD} <<< "CREATE DATABASE ${DEST_DB}"
    ${DEST_MYSQL_COMMAND} -u${DEST_DB_USERNAME} -p${DEST_DB_PWD} ${DEST_DB} < ${DUMP_NAME}
    rm ${DUMP_NAME}

    echo "Installing Frosh Profiler"
    ${DEST_PHP_COMMAND} bin/console sw:plugin:reinstall FroshProfiler --clear-cache
    ${DEST_PHP_COMMAND} php bin/console sw:plugin:activate FroshProfiler

    echo "Installing Frosh Mail Catcher"
    ${DEST_PHP_COMMAND} bin/console sw:plugin:reinstall FroshMailCatcher --clear-cache
    ${DEST_PHP_COMMAND} php bin/console sw:plugin:activate FroshMailCatcher

    echo "Installing Frosh Mail Catcher"
    ${DEST_PHP_COMMAND} bin/console sw:plugin:reinstall FroshShareBasket --clear-cache
    ${DEST_PHP_COMMAND} php bin/console sw:plugin:activate FroshShareBasket

    echo "Running SW Cache Commands"
    ${DEST_PHP_COMMAND} bin/console sw:cache:clear
    ${DEST_PHP_COMMAND} bin/console sw:session:cleanup
    ${DEST_PHP_COMMAND} bin/console sw:theme:cache:generate
    ${DEST_PHP_COMMAND} bin/console sw:generate:attributes
    ${DEST_PHP_COMMAND} bin/console sw:customer:stream:index:populate
    ${DEST_PHP_COMMAND} bin/console sw:rebuild:seo:index
    ${DEST_PHP_COMMAND} bin/console sw:refresh:search:index

    ${DEST_PHP_COMMAND} bin/console sw:admin:create --email=admin@admin.com --username=admin --name=admin --password=demo -n
}

media(){
    echo "Syncing media"
    rsync -auv ${SOURCE_SSH_NAME}:${SOURCE_MEDIA_PATH} ../public
}

#executes the preferred order of commands
preferred(){
    download_db_dump
    import_db_dump
    media
    finish
}

finish(){
    echo "";
    echo "";
    echo "";
    echo "@@@  @@@   @@@@@@   @@@@@@@   @@@@@@@   @@@ @@@      @@@@@@@   @@@@@@   @@@@@@@   @@@  @@@  @@@   @@@@@@@@ "
    echo "@@@  @@@  @@@@@@@@  @@@@@@@@  @@@@@@@@  @@@ @@@     @@@@@@@@  @@@@@@@@  @@@@@@@@  @@@  @@@@ @@@  @@@@@@@@@ "
    echo "@@!  @@@  @@!  @@@  @@!  @@@  @@!  @@@  @@! !@@     !@@       @@!  @@@  @@!  @@@  @@!  @@!@!@@@  !@@"
    echo "!@!  @!@  !@!  @!@  !@!  @!@  !@!  @!@  !@! @!!     !@!       !@!  @!@  !@!  @!@  !@!  !@!!@!@!  !@!"
    echo "@!@!@!@!  @!@!@!@!  @!@@!@!   @!@@!@!    !@!@!      !@!       @!@  !@!  @!@  !@!  !!@  @!@ !!@!  !@! @!@!@"
    echo "!!!@!!!!  !!!@!!!!  !!@!!!    !!@!!!      @!!!      !!!       !@!  !!!  !@!  !!!  !!!  !@!  !!!  !!! !!@!!"
    echo "!!:  !!!  !!:  !!!  !!:       !!:         !!:       :!!       !!:  !!!  !!:  !!!  !!:  !!:  !!!  :!!   !!:"
    echo ":!:  !:!  :!:  !:!  :!:       :!:         :!:       :!:       :!:  !:!  :!:  !:!  :!:  :!:  !:!  :!:   !::"
    echo "::   :::  ::   :::   ::        ::          ::        ::: :::  ::::: ::   :::: ::   ::   ::   ::   ::: ::::"
    echo " :   : :   :   : :    :         :           :         :: :::   : :  :    :: :  :   :    ::    :    :: :: : "
    echo "";
    echo "";
    echo "";
}

# A non-destructive exit for when the script exits naturally.
safe_exit() {
  trap - INT TERM EXIT
  exit
}

# Print help if no arguments were passed.
[[ $# -eq 0 ]] && set -- "--help"

echo "¯\_(ツ)_/¯"
# Read the options and set stuff
while [[ $1 = -?* ]]; do
  case $1 in
    -h|--help) usage >&2; safe_exit ;;
    -d|--download)  download_db_dump ;;
    -i|--import)  import_db_dump ;;
    -m|--media)  media ;;
    -p|--preferred)  preferred ;;
    --endopts) break ;;
    *) die "invalid option: $1" ;;
  esac
  shift;
done