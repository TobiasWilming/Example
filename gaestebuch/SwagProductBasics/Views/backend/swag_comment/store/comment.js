
Ext.define('Shopware.apps.SwagComment.store.Comment', {
    extend:'Shopware.store.Listing',

    configure: function() {
        return {
            controller: 'SwagComment'
        };
    },
    model: 'Shopware.apps.SwagComment.model.Comment'

});