define([
    'jquery',
    'Magento_Ui/js/form/element/select',
    'mage/url',
    'Magento_Ui/js/modal/modal',
    'uiRegistry',
    'ko',
    'mage/cookies',
    'mage/translate',
    'Perspective_IpLocate/js/lib/select2/select2',

], function ($, Select, url, modal, registry, ko) {
    'use strict';
    return Select.extend({

        defaults: {
            cityName: '',
            postcode: '',
            city_fast: [],
            exports: {
                "cityName": "${ $.parentName }.city:value",
                "postcode": "${ $.parentName }.postcode:value"
            }
        },

        options: ko.observable(),
        currentCity: ko.observable(),
        initialize: function () {
            this._super();
            this.currentCity(this.getCurrentCity());
            this.cityName(this.getPreview());
            this.initModal();
            return this;
        },

        initObservable: function () {
            this._super();
            this.observe('cityName');
            this.observe('postcode');
            return this;
        },

        select2: function (element) {
            var lang = "ru";
            if ($('html').attr('lang') == "uk") {
                lang = "uk";
            }
            $(element).select2({
                placeholder: $('.city-value').text(),
                dropdownAutoWidth: true,
                width: '40%',
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

        getCityName: function () {
            return this.cityName();
        },

        setDifferedFromDefault: function () {
            this._super();
            this.cityName(this.getPreview());
            this.postcode(this.getPreview());
        },

        getCurrentCity: function () {
            $.ajax({
                url: url.build("detection_ip/city/city"),
                type: 'get',
                dataType: 'json',
                context: $(this),
                showLoader: true,
                complete: function (result, status, error) {
                    let resultData = JSON.parse(result.responseText);
                    $.mage.cookies.set('city_cookie_name', resultData['city']);
                    $.mage.cookies.set('zip_cookie_data', resultData['zip']);
                    return {
                        results: this[0].currentCity(resultData['city'])
                    };
                }
            });
        },

        openPopup: function () {
            var popup = modal(this.options(), $('#modal-city'));
            $('#modal-city').modal('openModal');
        },
        initModal: function () {
            let opts = {
                type: 'popup',
                width: '40%',
                responsive: true,
                innerScroll: true,
                modalClass: 'modal-popup-ordercancel',
                title: 'Сhoose your city',
                buttons: [{
                    text: $.mage.__('Cancel'),
                    class: '',
                    click: function () {
                        this.closeModal();
                    },
                },
                    {
                        text: $.mage.__('Ok'),
                        click: function () {
                            event.preventDefault();
                            event.stopPropagation();
                            event.stopImmediatePropagation();
                            $('#ajax_loader').show();
                            var cityId = registry.get('cityInputAutocomplete').value();
                            var cityName = $('.select2-selection__rendered').text();
                            $.ajax({
                                url: url.build("detection_ip/save/index"),
                                type: 'POST',
                                data: {
                                    "zipCode": cityId,
                                    "cityName": cityName
                                },
                                showLoader: true,
                                complete: function (xhr, status, error) {
                                    window.location.reload();
                                    return {
                                        results: JSON.parse(xhr.responseText)
                                    };
                                }
                            });
                            this.closeModal();
                        }
                }]
            };
            this.options(opts);
        }
    });
});
