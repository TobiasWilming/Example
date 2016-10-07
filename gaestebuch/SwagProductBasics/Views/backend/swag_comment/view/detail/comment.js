

Ext.define('Shopware.apps.SwagComment.view.detail.Comment', {
    extend: 'Shopware.model.Container',
    padding: 20,

    configure: function() {
        return {
            controller: 'SwagComment',
            fieldSets: [{
                title: 'Entry',
                fields: {
                    comment: 'Kommentar',
                    active: 'Freigegeben'
                }
            }]
        };
    }
});