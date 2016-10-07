
Ext.define('Shopware.apps.SwagComment.controller.Comment', {
    extend: 'Enlight.app.Controller',

    init: function() {
        var me = this;
        me.control({
            'comment-listing-grid':{
                'comment-before-create-right-toolbar-items':me.addToolbarButton
            }


        });

        Shopware.app.Application.on('change-create-date-process', me.onChangeCreateDate);
        me.mainWindow = me.getView('list.Window').create({ }).show();
    },

    addToolbarButton: function(grid, items){
        items.push(this.createToolbarButton(grid));
        return items;
    },
    onChangeCreateDate: function (task, record, callback) {
        Ext.Ajax.request({
            url: '{url controller=SwagComment action=savemany}',
            method: 'POST',
            params: {
                datan: record.get('id')
            },
            success: function(response, operation) {
                callback(response, operation);
            }
        });
    },


    createToolbarButton: function(grid){
        var me = this;
        var counter;
        return Ext.create('Ext.button.Button', {
            text: 'Freigabe ändern',
            //action:'notice',
            handler: function(view, rowIndex, colIndex,item){


                var selection = grid.getSelectionModel().getSelection();
                for(counter=0;counter<grid.getSelectionModel().getCount();counter++) {
                    if(selection[counter]['data']['active']==false) {

                        selection[counter]['data']['active'] = true;
                    }
                    else{
                        selection[counter]['data']['active']=false;
                    }

                }
                Ext.create('Shopware.window.Progress', {
                    title: 'Batch processing',
                    configure: function() {
                        return {
                            tasks: [{
                                event: 'change-create-date-process',
                                data: Ext.clone(selection),
                                text: 'Revised delivery date [0] of [1]'
                            }],

                            infoText: '<h2>Deactivate products</h2>' +
                            'You can use the <b><i>`Cancel process`</i></b> button the cancel the process. ' +
                            'Depending on the amount of the data set, this process might take a while.'
                        }
                    }
                }).show();
                

            }

        });
    }


});