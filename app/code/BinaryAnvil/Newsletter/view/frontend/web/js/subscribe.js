
define([
    "jquery",
    "Magento_Ui/js/modal/modal",
    'matchMedia'
], function ($, modal, mediaCheck) {
    'use strict';

    function emailValidation(email) {
        var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        return regex.test(email);
    }

    return function main(config, element) {
        var options = {
            type: 'popup',
            responsive: true,
            innerScroll: true,
            buttons: false
        };

        var modalWindow = $(config.NewsletterSignUp),
            popup = modal(options, modalWindow),
            postURL = 'https://spi-functions.azurewebsites.net/api/InfinitySalesForceNewsletterSignUp';

        $(".header-newsletter a").on('click',function(e){
            console.log("click");
            e.preventDefault();
            modalWindow.modal("openModal");
        });

        if (window.location.href.indexOf('local') > -1 || window.location.href.indexOf('mcstaging') > -1) {
            postURL = 'https://spi-functions-dev.azurewebsites.net/api/InfinitySalesForceNewsletterSignUp';
        }

        $(config.FormClass).submit(function (e) {
            var self = $(this);

            e.preventDefault();

            if ($('#newsletter-2').val().length > 4 && emailValidation($('#newsletter-2').val())) { 
                $.ajax({
                    method: "POST",
                    url: postURL,
                    data: JSON.stringify({
                        'source': 'InfinityWeb_subscriber',
                        'emailAddress': $(this).find('.email').val()
                    }),
                    contentType: 'application/json;charset=utf-8'
                }).done(function(responce, textStatus, jqXHR) {
                    modalWindow.modal("closeModal");
                    self.find('.action.subscribe').blur();

                    if(jqXHR.status === 200) {
                        var successModalWindow = $(config.NewsletterSuccess),
                            popup = modal(options, successModalWindow);
                        $(config.NewsletterSuccess + ' .email').text(responce.email);
                        successModalWindow.modal("openModal");
                    }
                    else if(jqXHR.status !== 200 && responce.email !== false) {
                        mediaCheck({
                            media: '(max-width: 767px)',
                            entry: function () {
                                var html = $('html');

                                if (html.hasClass('nav-open')) {
                                    html.removeClass('nav-open');
                                    setTimeout(function () {
                                        html.removeClass('nav-before-open');
                                    }, 100);
                                }
                            },
                            exit: function () {
                                if(self.closest('.footer')) {
                                    $([document.documentElement, document.body]).animate({
                                        scrollTop: ($("#maincontent").offset().top - 30)
                                    }, 1000);
                                }
                            }
                        });
                    }
                });
            }
        });
    }
});
