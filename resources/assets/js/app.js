// require('./component/common');
//equire('./../../../node_modules/toastr/toastr');
//require('./component/particleJS');

import './component/common';
import toastr from 'toastr';

jQuery(($) => {
    function WebCrawler() {
        const _this = this;

        _this.scripts = [];

        _this.toastr = null;

        _this.init = () => {
            _this.toastr = toastr;
            if (typeof WebCrawlerAdditionalScript !== 'undefined') {
                _this.scripts = WebCrawlerAdditionalScript;
            }
            _this.clickFunc();
            _this.insertDomain();
        }

        _this.clickFunc = () => {
            $('body').on('click', '.btn[data-target="#immModal"]', function (evt) {
                var contentUrl = $(this).attr('data-content-url');
                $.ajax({
                    method: "GET",
                    url: contentUrl,
                    success: function (response) {
                        $('#immModal #modal-push').html(response);
                    },
                    error: function (msg) {
                        $('#immModal #modal-push').html(msg['statusText']);
                    }
                });
                return true;
            });
        };

        _this.insertDomain = () => {
            $('#insertDomain').on('submit', function (e) {
                e.preventDefault();
                var contentUrl = $(this).attr('action');
                var data = $(this).serialize();
                var url = $(this).find('[name="url"]').val();
                $.ajax({
                    method: "POST",
                    data: data,
                    url: contentUrl,
                    success: function (msg) {
                        if (msg['status'] == 1) {
                            _this.toastr.success('Domain Status 200 and Added to Queue', url);
                        } else {
                            _this.toastr.error(msg['message'], url);
                        }
                    },
                    error: function (msg) {
                        _this.toastr.error('check your internet connection', 'failed');
                    }
                });
            });
        };
    }

    const App = new WebCrawler();
    App.init();
});