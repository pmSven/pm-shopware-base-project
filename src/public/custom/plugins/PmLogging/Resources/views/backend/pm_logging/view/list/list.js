
Ext.define('Shopware.apps.PmLogging.view.list.List', {
    extend: 'Shopware.grid.Panel',
    alias:  'widget.pm-logging-listing-grid',
    region: 'center',

    configure: function() {
        return {
            detailWindow: 'Shopware.apps.PmLogging.view.detail.Window',
            addButton: false,
            actionColumn: false,
            columns: {
                type: { header: "Type", flex: 3 },
                caller: { header: "Caller", flex: 10 },
                referenceId: { header: "Referenz", flex: 3 },
                entity: { header: "Entity", flex: 3 },
                event: { header: "Event", flex: 6 },
                msg: { header: "Nachricht", flex: 8 },
                data: { header: "Daten", flex: 8 },
                time: { header: "Zeitpunkt", flex: 5 }
            }
        };
    },
});
