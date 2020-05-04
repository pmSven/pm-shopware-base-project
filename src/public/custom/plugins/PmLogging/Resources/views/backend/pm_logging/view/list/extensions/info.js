Ext.define('Shopware.apps.PmLogging.view.list.extensions.Info', {
    extend: 'Shopware.listing.InfoPanel',
    alias:  'widget.product-listing-info-panel',
    width: 270,

    configure: function() {
        return {
            model: 'Shopware.apps.PmLogging.model.Main',
            fields: {
                time: 	        '<p style="padding: 2px">' +
                                     '<b>{s name="detail/time"}Zeitpunkt:{/s}</b> ' +
                                     '{literal}{time}{/literal}' +
                                '</p>',
                type: 	        '<p style="padding: 2px">' +
                                    '<b>{s name="detail/type"}Type:{/s}</b> ' +
                                     '{literal}{type}{/literal}' +
                                '</p>',
                caller:         '<p style="padding: 2px">' +
                                     '<b>{s name="detail/caller"}Caller:{/s}</b> ' +
                                     '{literal}{caller}{/literal}' +
                                '</p>',
                referenceId:    '<p style="padding: 2px">' +
                                     '<b>{s name="detail/reference"}Referenz:{/s}</b> ' +
                                     '{literal}{referenceId}{/literal}' +
                                '</p>',
                entity:         '<p style="padding: 2px">' +
                                     '<b>{s name="detail/entity"}Entity:{/s}</b> ' +
                                     '{literal}{entity}{/literal}' +
                                 '</p>',
            	event: 	         '<p style="padding: 2px">' +
                                     '<b>{s name="detail/event"}Event:{/s}</b> ' +
                                     '{literal}{event}{/literal}' +
                                 '</p>',
            	msg: 	         '<p style="padding: 2px">' +
                                     '<b>{s name="detail/message"}Nachricht:{/s}</b> ' +
                                     '{literal}{msg}{/literal}' +
                                 '</p>',
                data: 	         '<p style="padding: 2px">' +
                                     '<b>{s name="detail/data"}Daten:{/s}</b> ' +
                                     '{literal}{data}{/literal}' +
                                 '</p>',
            }
        };
    }
});