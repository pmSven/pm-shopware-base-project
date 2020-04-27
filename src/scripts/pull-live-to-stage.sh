#!/bin/bash

cd ~

# . ./www.dihk-bildung.shop/env-stage
. ./stage/stage.dihk-bildung.shop/public/env-stage
LIVE_DB_HOST='10.222.39.11'
LIVE_DB_USER='1001598_dbu_1'
LIVE_DB_PWD=foo6inuchaz7Sei:
LIVE_TABLE=1001598_db_1
DUMP_NAME=automatic_dump.sql
STAGE_MYSQL_BACKUP=backup.sql
DEFAULT_DEBTOR=2042760
LIVE_MEDIA="/data/www/live/www.dihk-bildung.shop/public/media/"
STAGE_MEDIA="/data/www/stage/stage.dihk-bildung.shop/public/media/"

# Print usage
usage() {
    echo -n "$(basename $0) [COMMAND]...
    Script for downloading the database from live and import it to stage. Usually it's enough to just call this script with the -p option.
    If on stage, run it inside the root folder (inside www.dihk-bildung.shop/).
        Commands:
        -d, --download      Downloads the database
        -c, --change        Changes the data to match the stage config
        -b, --backup        Creates a backup from stage
        -i, --import        Imports the database to stage
        -r, --run           Runs bin/console commands on the stage to install some plugins and import dummy data
        -m, --media         Downloads the media files (rsync)
        -p, --preferred     Executes the preferred order of commands (--download --change --backup --import --run --media)
        -h, --help          Display this help and exit
        "
    }

#dowload file
download(){
    echo "Downloading database"
    #    ${LIVE_SSH} /usr/local/mysql5/bin/mysqldump -p${LIVE_DB_PWD} --skip-triggers \
    ${LIVE_SSH} mysqldump --single-transaction=TRUE -u${LIVE_DB_USER} -h${LIVE_DB_HOST} -p${LIVE_DB_PWD} --skip-triggers \
        --ignore-table=${LIVE_TABLE}.s_plugin_pm_auditing \
        --ignore-table=${LIVE_TABLE}.articles_without_images \
        --ignore-table=${LIVE_TABLE}.invalid_prices \
        --ignore-table=${LIVE_TABLE}.debitor_contacts \
        --ignore-table=${LIVE_TABLE}.no_invoices \
        --ignore-table=${LIVE_TABLE}.seo_order_report \
        --ignore-table=${LIVE_TABLE}.wbv_view ${LIVE_TABLE} \
        --ignore-table=${LIVE_TABLE}.used_qr_codes \
        --ignore-table=${LIVE_TABLE}.christiani_orders \
        --ignore-table=${LIVE_TABLE}.s_plugin_pm_auditing_backup \
        --ignore-table=${LIVE_TABLE}.product_index_view > ${DUMP_NAME}
    }

dropTables(){
    echo "Dropping tables and views ...";

    export SQL="SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_SCHEMA=database() AND TABLE_TYPE='VIEW'";
    export VIEWS=$(${STAGE_MYSQL} -u${STAGE_MYSQL_USER} -p${STAGE_MYSQL_PWD} -D ${STAGE_MYSQL_DB} <<< $SQL | grep -v TABLE_NAME)

    for x in $VIEWS
    do
        export DROP="DROP VIEW IF EXISTS $x;";
        echo $DROP;
        ${STAGE_MYSQL} -u${STAGE_MYSQL_USER} -p${STAGE_MYSQL_PWD} -D ${STAGE_MYSQL_DB} <<< "SET FOREIGN_KEY_CHECKS=0; $DROP"
    done

    export SQL="SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_SCHEMA=database() AND TABLE_TYPE='BASE TABLE'";
    export TABLES=$(${STAGE_MYSQL} -u${STAGE_MYSQL_USER} -p${STAGE_MYSQL_PWD} -D ${STAGE_MYSQL_DB} <<< $SQL | grep -v TABLE_NAME)

    for x in $TABLES
    do
        export DROP="DROP TABLE IF EXISTS $x;";
        echo $DROP;
        ${STAGE_MYSQL} -u${STAGE_MYSQL_USER} -p${STAGE_MYSQL_PWD} -D ${STAGE_MYSQL_DB} <<< "SET FOREIGN_KEY_CHECKS=0; $DROP"
    done

    echo "Finished dropping tables and views ..."
}

dropViews(){
    export SQL="SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_SCHEMA=database() AND TABLE_TYPE='VIEW'";
    export TABLES=$(${STAGE_MYSQL} -u${STAGE_MYSQL_USER} -p${STAGE_MYSQL_PWD} -D ${STAGE_MYSQL_DB} <<< $SQL | grep -v TABLE_NAME)

    for x in $TABLES
    do
        export DROP="DROP TABLE IF EXISTS $x;";
        echo $DROP;
        ${STAGE_MYSQL} -u${STAGE_MYSQL_USER} -p${STAGE_MYSQL_PWD} -D ${STAGE_MYSQL_DB} <<< "SET FOREIGN_KEY_CHECKS=0; $DROP"
    done
}

#change data
change(){
    echo "inserting manipulations commands"
    echo "" >> ${DUMP_NAME}
    echo "-- Adding own manipulation" >> ${DUMP_NAME}
    echo "SET FOREIGN_KEY_CHECKS = 0;" >> ${DUMP_NAME}

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
    echo "TRUNCATE s_plugin_pm_certificate_course_order;" >> ${DUMP_NAME}
    echo "TRUNCATE s_plugin_pm_certificate_course;" >> ${DUMP_NAME}
    echo "TRUNCATE s_plugin_pm_comprehension_test_activation;" >> ${DUMP_NAME}
    echo "TRUNCATE s_plugin_pm_comprehension_test_participation;" >> ${DUMP_NAME}

    echo "Truncating ionesoft";
    echo "" >> ${DUMP_NAME}
    echo "-- Truncating ionesoft" >> ${DUMP_NAME}
    echo "TRUNCATE TABLE s_plugin_pm_ionesoft_coupons_products;" >> ${DUMP_NAME}
    echo "TRUNCATE TABLE s_plugin_pm_ionesoft_coupon;" >> ${DUMP_NAME}
    echo "TRUNCATE TABLE s_plugin_pm_ionesoft_user;" >> ${DUMP_NAME}

    echo "Truncating statistic";
    echo "" >> ${DUMP_NAME}
    echo "-- Truncating statistic" >> ${DUMP_NAME}
    echo "TRUNCATE TABLE s_emarketing_lastarticles;" >> ${DUMP_NAME}
    echo "TRUNCATE TABLE s_plugin_mopt_payone_api_log;" >> ${DUMP_NAME}
    echo "TRUNCATE TABLE s_statistics_article_impression;" >> ${DUMP_NAME}
    echo "TRUNCATE TABLE s_statistics_referer;" >> ${DUMP_NAME}
    echo "TRUNCATE TABLE s_plugin_mopt_payone_transaction_log;" >> ${DUMP_NAME}
    echo "TRUNCATE TABLE s_articles_also_bought_ro;" >> ${DUMP_NAME}

    echo "Truncating b2b";
    echo "" >> ${DUMP_NAME}
    echo "-- Truncating b2b" >> ${DUMP_NAME}
    echo "TRUNCATE TABLE b2b_debtor_contact;" >> ${DUMP_NAME}
    echo "TRUNCATE b2b_prices;" >> ${DUMP_NAME}
    echo "TRUNCATE b2b_acl_contact_address;" >> ${DUMP_NAME}
    echo "TRUNCATE b2b_acl_contact_budget;" >> ${DUMP_NAME}
    echo "TRUNCATE b2b_acl_contact_contact;" >> ${DUMP_NAME}
    echo "TRUNCATE b2b_acl_contact_contingent_group;" >> ${DUMP_NAME}
    echo "TRUNCATE b2b_acl_contact_order_list;" >> ${DUMP_NAME}
    echo "TRUNCATE b2b_acl_contact_role;" >> ${DUMP_NAME}
    echo "TRUNCATE b2b_acl_contact_route_privilege;" >> ${DUMP_NAME}
    echo "TRUNCATE b2b_acl_role_address;" >> ${DUMP_NAME}
    echo "TRUNCATE b2b_acl_role_budget;" >> ${DUMP_NAME}
    echo "TRUNCATE b2b_acl_role_contact;" >> ${DUMP_NAME}
    echo "TRUNCATE b2b_acl_role_contingent_group;" >> ${DUMP_NAME}
    echo "TRUNCATE b2b_acl_role_order_list;" >> ${DUMP_NAME}
    echo "TRUNCATE b2b_acl_role_role;" >> ${DUMP_NAME}
    echo "TRUNCATE b2b_acl_role_route_privilege;" >> ${DUMP_NAME}
    echo "TRUNCATE b2b_audit_log;" >> ${DUMP_NAME}
    echo "TRUNCATE b2b_audit_log_author;" >> ${DUMP_NAME}
    echo "TRUNCATE b2b_audit_log_index;" >> ${DUMP_NAME}
    echo "TRUNCATE b2b_line_item_list;" >> ${DUMP_NAME}
    echo "TRUNCATE b2b_line_item_reference;" >> ${DUMP_NAME}
    echo "TRUNCATE b2b_order_context;" >> ${DUMP_NAME}
    echo "TRUNCATE b2b_order_list;" >> ${DUMP_NAME}
    echo "TRUNCATE b2b_role;" >> ${DUMP_NAME}
    echo "TRUNCATE b2b_role_contact;" >> ${DUMP_NAME}
    echo "TRUNCATE b2b_sales_representative_clients;" >> ${DUMP_NAME}
    echo "TRUNCATE b2b_store_front_auth;" >> ${DUMP_NAME}
    echo "TRUNCATE s_plugin_pm_b2b_contact_extended;" >> ${DUMP_NAME}
    echo "TRUNCATE s_plugin_pm_customer_update;" >> ${DUMP_NAME}

    echo "Truncating logs";
    echo "" >> ${DUMP_NAME}
    echo "-- Truncating logs" >> ${DUMP_NAME}
    echo "TRUNCATE s_core_log;" >> ${DUMP_NAME}
    echo "TRUNCATE s_statistics_search;" >> ${DUMP_NAME}
    echo "TRUNCATE s_statistics_pool;" >> ${DUMP_NAME}
    echo "TRUNCATE s_statistics_visitors;" >> ${DUMP_NAME}

    echo "Truncating credentials";
    echo "" >> ${DUMP_NAME}
    echo "-- Truncating credentials" >> ${DUMP_NAME}
    echo "TRUNCATE s_plugin_pm_lplus_order;" >> ${DUMP_NAME}
    echo "TRUNCATE s_plugin_pm_lplus_result_credentials;" >> ${DUMP_NAME}
    echo "TRUNCATE s_plugin_pm_lplus_result;" >> ${DUMP_NAME}

    echo "Truncating personal data";
    echo "" >> ${DUMP_NAME}
    echo "-- Truncating personal data" >> ${DUMP_NAME}
    echo "TRUNCATE s_plugin_widgets_notes;" >> ${DUMP_NAME}

    echo "Truncating approval lists";
    echo "" >> ${DUMP_NAME}
    echo "-- Truncating approval lists" >> ${DUMP_NAME}
    echo "TRUNCATE s_plugin_pm_approval_list;" >> ${DUMP_NAME}
    echo "TRUNCATE s_plugin_pm_approval_list_details;" >> ${DUMP_NAME}

    echo "SET FOREIGN_KEY_CHECKS = 1;" >> ${DUMP_NAME}

    #echo "Deactivate SEO plugins ୧༼ʘ̆ںʘ̆༽୨"
    #echo "" >> ${DUMP_NAME}
    #echo "-- Deactivate ionCube PHP Loader plugins" >> ${DUMP_NAME}
    #echo "Update s_core_plugins SET active=0 where name=\"DreiscSeoExtArticleBulk\";" >> ${DUMP_NAME}
    #echo "Update s_core_plugins SET active=0 where name=\"DreiscSeoExtCategoryBulk\";" >> ${DUMP_NAME}
    #echo "Update s_core_plugins SET active=0 where name=\"DreiscSeoExtCross\";" >> ${DUMP_NAME}
    #echo "Update s_core_plugins SET active=0 where name=\"DreiscSeoExtFilterUrl\";" >> ${DUMP_NAME}
    #echo "Update s_core_plugins SET active=0 where name=\"DreiscSeoExtImageCompress\";" >> ${DUMP_NAME}
    #echo "Update s_core_plugins SET active=0 where name=\"DreiscSeoExtRedirect\";" >> ${DUMP_NAME}
    #echo "Update s_core_plugins SET active=0 where name=\"DreiscSeo\";" >> ${DUMP_NAME}
    #echo "Update s_core_plugins SET active=0 where name=\"SwagLicense\";" >> ${DUMP_NAME}

    #echo "Deactivate payment plugins"
    #echo "" >> ${DUMP_NAME}
    #echo "-- payment plugins" >> ${DUMP_NAME}

    echo -e "#PAYONE PLUGIN SQL SECTION\n" >> ${DUMP_NAME}
    echo "Activating PAYONE Payment Plugin";

    echo "UPDATE s_core_plugins SET active=1 WHERE name=\"MoptPaymentPayone\";" >> ${DUMP_NAME}

    #PAYONE SECTION
    echo -e "#PAYONE PLUGIN SQL SECTION\n" >> ${DUMP_NAME}

    echo "Activating PAYONE Payment Plugin";
    echo "UPDATE s_core_plugins SET active=1 WHERE name=\"MoptPaymentPayone\";" >> ${DUMP_NAME}

    echo "Set PAYONE plugin into sandbox mode";
    echo "UPDATE s_plugin_mopt_payone_config SET live_mode=0;" >> ${DUMP_NAME}

    echo "Enabling payment method: SOFORT Überwisung";
    echo "UPDATE s_core_paymentmeans SET active=1, esdactive=1 WHERE name='mopt_payone__ibt_sofortueberweisung';" >> ${DUMP_NAME}

    echo "Enabling payment method: VISA ";
    echo "UPDATE s_core_paymentmeans SET active=1, esdactive=1 WHERE name='mopt_payone__cc_visa';" >> ${DUMP_NAME}

    echo "Enabling payment method: MasterCard";
    echo "UPDATE s_core_paymentmeans SET active=1, esdactive=1 WHERE name='mopt_payone__cc_mastercard';" >> ${DUMP_NAME}
    #END PAYONE SECTION

    echo "Deactivate google plugins"
    echo "" >> ${DUMP_NAME}
    echo "-- google plugins" >> ${DUMP_NAME}
    echo "Update s_core_plugins SET active=0 where name=\"SwagGoogle\";" >> ${DUMP_NAME}

    echo "Updating plugin configs"
    echo "" >> ${DUMP_NAME}
    echo "-- Updating plugin configs" >> ${DUMP_NAME}
    echo "Update s_core_config_values cv left join s_core_config_elements ce on ce.id=cv.element_id set cv.value=\"s:3:\\\"foo\\\";\" where ce.name=\"api_password\";" >> ${DUMP_NAME}
    echo "Update s_core_config_values cv left join s_core_config_elements ce on ce.id=cv.element_id set cv.value=\"s:24:\\\"everything@pmagentur.com\\\";\" where ce.name=\"email_address_to_send_info_about_missing_roles\";" >> ${DUMP_NAME}
    echo "Update s_core_config_values cv left join s_core_config_elements ce on ce.id=cv.element_id set cv.value=\"s:13:\\\"rest_dihk_dev\\\";\" where ce.name=\"context\";" >> ${DUMP_NAME}
    echo "Update s_core_config_values cv left join s_core_config_elements ce on ce.id=cv.element_id set cv.value=\"s:24:\\\"everything@pmagentur.com\\\";\" where ce.name=\"wbv_compare_email\";" >> ${DUMP_NAME}
    #echo "Update s_core_config_values cv left join s_core_config_elements ce on ce.id=cv.element_id set cv.value=\"s:3:\\\"foo\\\";\" where ce.name=\"lplus_soap_username\";" >> ${DUMP_NAME}
    #echo "Update s_core_config_values cv left join s_core_config_elements ce on ce.id=cv.element_id set cv.value=\"s:3:\\\"foo\\\";\" where ce.name=\"lplus_soap_password\";" >> ${DUMP_NAME}
    echo "Update s_core_config_values cv left join s_core_config_elements ce on ce.id=cv.element_id set cv.value=\"s:24:\\\"everything@pmagentur.com\\\";\" where ce.name=\"lplus_moodle_email\";" >> ${DUMP_NAME}
    echo "Update s_core_config_values cv left join s_core_config_elements ce on ce.id=cv.element_id set cv.value=\"s:24:\\\"everything@pmagentur.com\\\";\" where ce.name=\"business_game_email_receiver_one\";" >> ${DUMP_NAME}
    echo "Update s_core_config_values cv left join s_core_config_elements ce on ce.id=cv.element_id set cv.value=\"s:24:\\\"everything@pmagentur.com\\\";\" where ce.name=\"business_game_email_receiver_two\";" >> ${DUMP_NAME}
    echo "Update s_core_config_values cv left join s_core_config_elements ce on ce.id=cv.element_id set cv.value=\"s:24:\\\"everything@pmagentur.com\\\";\" where ce.name=\"import_export_lplus_error_mail\";" >> ${DUMP_NAME}

    echo "Update s_core_config_elements ce set ce.value=\"s:3:\\\"foo\\\";\" where ce.name=\"api_password\";" >> ${DUMP_NAME}
    echo "Update s_core_config_elements ce set ce.value=\"s:24:\\\"everything@pmagentur.com\\\";\" where ce.name=\"email_address_to_send_info_about_missing_roles\";" >> ${DUMP_NAME}
    echo "Update s_core_config_elements ce set ce.value=\"s:13:\\\"rest_dihk_dev\\\";\" where ce.name=\"context\";" >> ${DUMP_NAME}
    echo "Update s_core_config_elements ce set ce.value=\"s:24:\\\"everything@pmagentur.com\\\";\" where ce.name=\"wbv_compare_email\";" >> ${DUMP_NAME}
    echo "Update s_core_config_elements ce set ce.value=\"s:3:\\\"foo\\\";\" where ce.name=\"lplus_soap_username\";" >> ${DUMP_NAME}
    echo "Update s_core_config_elements ce set ce.value=\"s:3:\\\"foo\\\";\" where ce.name=\"lplus_soap_password\";" >> ${DUMP_NAME}
    echo "Update s_core_config_elements ce set ce.value=\"s:24:\\\"everything@pmagentur.com\\\";\" where ce.name=\"lplus_moodle_email\";" >> ${DUMP_NAME}
    echo "Update s_core_config_elements ce set ce.value=\"s:24:\\\"everything@pmagentur.com\\\";\" where ce.name=\"business_game_email_receiver_one\";" >> ${DUMP_NAME}
    echo "Update s_core_config_elements ce set ce.value=\"s:24:\\\"everything@pmagentur.com\\\";\" where ce.name=\"business_game_email_receiver_two\";" >> ${DUMP_NAME}
    echo "Update s_core_config_elements ce set ce.value=\"s:24:\\\"everything@pmagentur.com\\\";\" where ce.name=\"import_export_lplus_error_mail\";" >> ${DUMP_NAME}

    echo "Setting basic settings"
    echo "" >> ${DUMP_NAME}
    echo "-- Setting basic settings" >> ${DUMP_NAME}
    echo "Update s_core_shops SET host=\"$STAGE_HOST_ENTRY\" where id=1;" >> ${DUMP_NAME}
    echo "Update s_core_shops SET secure=$USE_SECURE where id=1;" >> ${DUMP_NAME}
    echo "Update s_core_config_values cv set cv.value=\"s:24:\\\"everything@pmagentur.com\\\";\" where cv.id=1224;" >> ${DUMP_NAME}
    echo "Update s_core_config_values cv set cv.value=\"s:24:\\\"everything@pmagentur.com\\\";\" where cv.id=3475;" >> ${DUMP_NAME}

    echo "Update article keys"
    echo "" >> ${DUMP_NAME}
    echo "-- Update article keys" >> ${DUMP_NAME}
    echo "Update s_articles_attributes SET stagger_wawi_id_regular=NULL;" >> ${DUMP_NAME}
    echo "Update s_articles_attributes SET stagger_wawi_id_type_u=NULL;" >> ${DUMP_NAME}

    echo "elastic search reindexing fix"
    echo "Delete FROM s_es_backlog;" >> ${DUMP_NAME}
}

#import db
import(){
    echo "Importing database"

    if [[ "$DROP_STAGE_DB" = true ]] ; then
     dropTables;
    fi

    echo "Importing database dump, please wait ..."
    ${STAGE_SSH} ${STAGE_MYSQL} -u${STAGE_MYSQL_USER} -p${STAGE_MYSQL_PWD} ${STAGE_MYSQL_DB} < ${DUMP_NAME}
    echo "Done importing database dump"
}

#backup db
backup(){
    echo "Creating a backup of the stage database"
    ${STAGE_SSH} ${STAGE_MYSQL_DUMP} -u${STAGE_MYSQL_USER} -p${STAGE_MYSQL_PWD} ${STAGE_MYSQL_DB} --ignore-table=${STAGE_MYSQL_DB}.s_plugin_mailcatcher --ignore-table=${STAGE_MYSQL_DB}.s_plugin_mailcatcher_attachments > ${STAGE_MYSQL_BACKUP}
}

#run commands
run(){
    echo "Running SW plugin Commands"
    ${STAGE_SSH_COMMANDS} ${STAGE_RUN_COMMAND} sw:plugin:reinstall PmAuditing
    ${STAGE_SSH_COMMANDS} ${STAGE_RUN_COMMAND} sw:plugin:reinstall FroshMailCatcher
    ${STAGE_SSH_COMMANDS} ${STAGE_RUN_COMMAND} sw:plugin:activate FroshMailCatcher

    echo "Running SW Cache Commands"
    ${STAGE_SSH_COMMANDS} ${STAGE_RUN_COMMAND} sw:generate:attributes
    ${STAGE_SSH_COMMANDS} ${STAGE_RUN_COMMAND} sw:customer:stream:index:populate
    ${STAGE_SSH_COMMANDS} ${STAGE_RUN_COMMAND} sw:cache:clear
    ${STAGE_SSH_COMMANDS} ${STAGE_RUN_COMMAND} sw:rebuild:seo:index
    ${STAGE_SSH_COMMANDS} ${STAGE_RUN_COMMAND} sw:refresh:search:index
    ${STAGE_SSH_COMMANDS} ${STAGE_RUN_COMMAND} sw:session:cleanup
    ${STAGE_SSH_COMMANDS} ${STAGE_RUN_COMMAND} sw:theme:cache:generate

    ${STAGE_SSH_COMMANDS} ${STAGE_RUN_COMMAND} pm:es:index:populate
    ${STAGE_SSH_COMMANDS} ${STAGE_RUN_COMMAND} sw:es:index:cleanup

    echo "Importing one debtor"
    ${STAGE_SSH_COMMANDS} ${STAGE_RUN_COMMAND} pm:wbv:import:debtor ${DEFAULT_DEBTOR}

    #'pm:wawi:import:debtors' doesn't work right now, so we just import static data
    echo "Importing ihk addresses from local file"
    ${STAGE_SSH} ${STAGE_MYSQL} -u${STAGE_MYSQL_USER} -p${STAGE_MYSQL_PWD} ${STAGE_MYSQL_DB} < /data/www/stage/stage.dihk-bildung.shop/public/additional/ihk_addresses.sql

    echo "Creating admin account with demo password (COMMENT ME IN, IF WANTED)"
    ${STAGE_SSH_COMMANDS} ${STAGE_RUN_COMMAND} sw:admin:create --email=admin@admin.com --username=admin --name=admin --password=demo -n
}

#downloads the media folder
media(){
    echo "Syncing media"
    rsync -avzP --rsh=ssh ${RSYNC_LIVE}:${LIVE_MEDIA} ${STAGE_MEDIA};
}

#executes the preferred order of commands
preferred(){
    download
    change
    backup
    import
    run
    media

    finish
    rm -f ${DUMP_NAME}
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
        -d|--download)  download ;;
        -c|--change)  change ;;
        -i|--import)  import ;;
        -b|--backup)  backup ;;
        -r|--run)  run ;;
        -m|--media)  media ;;
        -p|--preferred)  preferred ;;
        --endopts) break ;;
        *) echo "invalid option: $1" ;;
    esac
    shift;
done
