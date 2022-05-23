define([
    'jquery',
    'Magento_Ui/js/form/element/select',
    'mage/url',
    'mage/translate',
    'Perspective_IpLocate/js/lib/select2/select2'
], function ($, Select, url) {
    'use strict';
    return Select.extend({

        defaults: {
            cityName: '',
            postcode: '',
            exports: {
                "cityName": "${ $.parentName }.city:value",
                "postCode": "${ $.parentName }.postcode:value"
            }
        },

        initialize: function () {
            this._super();
            this.cityName(this.getPreview());
            this.postCode(this.getPreview());
            return this;
        },

        initObservable: function () {
            this._super();
            this.observe('cityName');
            this.observe('postCode');
            return this;
        },

        select2: function (element) {
            var lang = "ru";
            if ($('html').attr('lang') === "uk") {
                lang = "uk";
            }
            $(element).select2({
                placeholder: $('.city-value').text(),
                dropdownAutoWidth: true,
                width: '100%',
                minimumInputLength: 2,
                language: lang,
                ajax: {
                    url: url.build('rest/V1/novaposhta/city'),
                    type: "POST",
                    dataType: 'json',
                    contentType: "application/json",
                    delay: 1000,
                    data: function (params) {
                        var query = JSON.stringify({
                            name: params.term
                        })
                        return query;
                    },
                    processResults: function (data) {
                        return {
                            results: JSON.parse(data)
                        };
                    }
                }
            });
            if ($(element).text().length > 0) {
                this.cityName($(element).text());
            }
        },

        getPreview: function () {
            return $('[name="' + this.inputName + '"] option:selected').text();
        },

        getPreviewPostCode: function () {
            return $('[name="' + this.inputName + '"] option:selected').val();
        },

        getCityName: function () {
            return this.cityName();
        },

        setDifferedFromDefault: function () {
            this._super();
            this.cityName(this.getPreview());
            this.postCode(this.getPreviewPostCode());
        }
    });
});
