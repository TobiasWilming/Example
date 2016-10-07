

Ext.define('Shopware.apps.SwagProduct.view.detail.Product', {
    extend: 'Shopware.model.Container',
    padding: 20,

    configure: function() {
        return {
            controller: 'SwagProduct',
            fieldSets: [{
                title: 'Account Data',
                fields: {
                    username:{
                        fieldLabel: 'Email',
                        allowBlank:false
                    },
                    newpassword: 'neues Password',
                    rights: {
                        xtype: 'combobox',
                        triggerAction: 'all',
                        fieldLabel: 'Rechte',
                        editable: false,
                        allowBlank: false,
                        valueField: 'key',
                        displayField: 'label',
                        labelWidth: 155,
                        store: ['Administrator', 'Moderator', 'User']
                    },
                    Lastname:{
                        fieldLabel: 'Nachname',
                        allowBlank:false
                    },
                    Firstname:{
                        fieldLabel: 'Vorname',
                        allowBlank:false
                    },
                    salutation: {
                        xtype: 'combobox',
                        triggerAction: 'all',
                        fieldLabel: 'Anrede',
                        editable: false,
                        allowBlank: false,
                        valueField: 'key',
                        displayField: 'label',
                        labelWidth: 155,
                        store: ['mr', 'ms']
                    },
                    zipcode:{
                        fieldLabel: 'Postleitzahl',
                        allowBlank:false
                    },
                    city:{
                        fieldLabel: 'Stadt',
                        allowBlank:false
                    },
                    street:{
                        fieldLabel: 'Straße',
                        allowBlank:false
                    }
                    
                }
            }]
        };
    }
});