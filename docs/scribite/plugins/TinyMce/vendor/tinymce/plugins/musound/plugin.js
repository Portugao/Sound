/**
 * plugin.js
 *
 * Copyright 2009, Moxiecode Systems AB
 * Released under LGPL License.
 *
 * License: http://tinymce.moxiecode.com/license
 * Contributing: http://tinymce.moxiecode.com/contributing
 */

(function () {
    // Load plugin specific language pack
    tinymce.PluginManager.requireLangPack('musound');

    tinymce.create('tinymce.plugins.MUSoundPlugin', {
        /**
         * Initializes the plugin, this will be executed after the plugin has been created.
         * This call is done before the editor instance has finished it's initialization so use the onInit event
         * of the editor instance to intercept that event.
         *
         * @param {tinymce.Editor} ed Editor instance that the plugin is initialised in
         * @param {string} url Absolute URL to where the plugin is located
         */
        init : function (ed, url) {
            // Register the command so that it can be invoked by using tinyMCE.activeEditor.execCommand('mceMUSound');
            ed.addCommand('mceMUSound', function () {
                ed.windowManager.open({
                    file : Zikula.Config.baseURL + Zikula.Config.entrypoint + '?module=MUSound&type=external&func=finder&editor=tinymce',
                    width : (screen.width * 0.75),
                    height : (screen.height * 0.66),
                    inline : 1,
                    scrollbars : true,
                    resizable : true
                }, {
                    plugin_url : url, // Plugin absolute URL
                    some_custom_arg : 'custom arg' // Custom argument
                });
            });

            // Register musound button
            ed.addButton('musound', {
                title : 'musound.desc',
                cmd : 'mceMUSound',
             // image : url + '/img/MUSound.gif'
                image : '/images/icons/extrasmall/favorites.png',
                onPostRender: function() {
                    var ctrl = this;

                    // Add a node change handler, selects the button in the UI when an anchor or an image is selected
                    ed.on('NodeChange', function(e) {
                        ctrl.active(e.element.nodeName == 'A' || e.element.nodeName == 'IMG');
                    });
                }
            });
        },

        /**
         * Creates control instances based in the incomming name. This method is normally not
         * needed since the addButton method of the tinymce.Editor class is a more easy way of adding buttons
         * but you sometimes need to create more complex controls like listboxes, split buttons etc then this
         * method can be used to create those.
         *
         * @param {String} n Name of the control to create
         * @param {tinymce.ControlManager} cm Control manager to use in order to create new control
         * @return {tinymce.ui.Control} New control instance or null if no control was created
         */
        createControl : function (n, cm) {
            return null;
        },

        /**
         * Returns information about the plugin as a name/value array.
         * The current keys are longname, author, authorurl, infourl and version.
         *
         * @return {Object} Name/value array containing information about the plugin
         */
        getInfo : function () {
            return {
                longname : 'MUSound for tinymce',
                author : 'Michael Ueberschaer',
                authorurl : 'http://webdesign-in-bremen.com',
                infourl : 'http://webdesign-in-bremen.com',
                version : '1.1.0'
            };
        }
    });

    // Register plugin
    tinymce.PluginManager.add('musound', tinymce.plugins.MUSoundPlugin);
}());
