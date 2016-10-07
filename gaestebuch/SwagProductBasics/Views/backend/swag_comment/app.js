
Ext.define('Shopware.apps.SwagComment', {
    extend: 'Enlight.app.SubApplication',
    name:'Shopware.apps.SwagComment',

    loadPath: '{url action=load}',
    bulkLoad: true,

    controllers: [ 'Comment' ],

    views: [
        'list.Window',
        'list.Comment',

        'detail.Comment',
        'detail.Window'
    ],

    models: [ 'Comment' ],
    stores: [ 'Comment' ],

    launch: function() {
        return this.getController('Comment').mainWindow;
    }
});