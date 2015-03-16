/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
/*jshint browser:true jquery:true*/
define([
    'jquery',
    'jquery/ui',
    'mage/template',
    'text!./templates/result.html'
], function($, ui, mageTemplate, resultTmpl){
    "use strict";

    $.widget('mage.sampleWebapi', {
        options: {
            filterGroup: "#filter_group",
            filter: "#filter",
            filterField: "#filter-field",
            filterValue: "#filter-value",
            filterCondition: "#condition-type",
            resultContainer: "[data-role='result']",
            template: resultTmpl
        },

        /**
         * Bind handlers to events
         */
        _create: function () {
            this._on({'click #search_products': $.proxy(this._search, this)});
        },

        /**
         * Method triggers an AJAX request to make API query
         * @private
         */
        _search: function () {
            var self = this;

            $("div[class='message notice']").remove();
            this.element.find(this.options.resultContainer).empty();
            var url = this._prepareUrl();
            var params = {
                "searchCriteria": {
                    "filter_groups": [],
                    "current_page": 1,
                    "page_size": 2
                }
            };
            $(self.options.filterGroup).each(function () {
                var filters = [];
                $(this).children(self.options.filter).each(function () {
                    filters.push({
                        field: $(this).find(self.options.filterField).val(),
                        value: $(this).find(self.options.filterValue).val(),
                        condition_type: $(this).find(self.options.filterCondition).val()
                    });
                });
                params.searchCriteria.filter_groups.push({"filters": filters});
            });
            $.ajax({
                url: url,
                dataType: 'json',
                data: params
            }).done(function (data) {
                console.dir(data);
                self._drawResultTable(data);
            }).fail(function (response) {
                self.element.prepend('<div class="message notice">' + response.responseJSON.message + "</div>");
            });
        },

        /**
         * Build API url
         * @returns {string}
         * @private
         */
        _prepareUrl: function () {
            var path = $(location).attr("pathname").split('/');
            path.pop();
            return path.join('/') + '/index.php/rest/default/V1/products';
        },

        /**
         * Display results
         * @param data
         * @private
         */
        _drawResultTable: function(data){
            var tmpl = mageTemplate(this.options.template);
            tmpl = tmpl({data: data});
            this.element.find(this.options.resultContainer).append($(tmpl));
        }
    })

    return $.mage.sampleWebapi;
});