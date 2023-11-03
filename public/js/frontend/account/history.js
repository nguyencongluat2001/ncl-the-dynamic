class History {
    module;
    baseUrl;
    urlPath;

    constructor(baseUrl, module) {
        this.module = module;
        this.baseUrl = baseUrl;
        this.urlPath = baseUrl + '/' + module;
    }

    loadIndex() {
        let myClass = this;
        this.loadList();
        $('#btn_search').on('click', () => this.loadList());
    }

    /**
     * Lay du lieu cho man hinh danh sach
     * @param {*} currentPage 
     * @param {*} perPage 
     */
    loadList(currentPage = 1, perPage = 50) {
        let myClass = this;
        let oForm = 'form#frm_history';
        let url = myClass.urlPath + '/lich-su-bai-thi';
        let data = $(oForm).serialize();
        data += `&offset=${currentPage}&limit=${perPage}`;
        $.ajax({
            url: url,
            type: "POST",
            data: data,
            success: function (response) {
                $("#table-container").html(response);
                $('.show-exam').off('click');
                $('.show-exam').on('click', function (e) {
                    myClass.show($(this).data('exam-id'))
                });
                // phan trang
                // $(oForm).find('.main_paginate .pagination a').click(function () {
                //     let page = $(this).attr('page');
                //     let perPage = $('#cbo_nuber_record_page').val();
                //     myClass.loadList(oForm, page, perPage);
                // });
                // $(oForm).find('#cbo_nuber_record_page').change(function () {
                //     let page = $(oForm).find('#_currentPage').val();
                //     let perPages = $(oForm).find('#cbo_nuber_record_page').val();
                //     myClass.loadList(oForm, page, perPages);
                // });
                // $(oForm).find('#cbo_nuber_record_page').val(perPage);
            }
        });
    }

    /**
     * Xem chi tiết bài thi
     * @param {*} id 
     */
    show(id) {
        let myClass = this;
        let url = this.urlPath + '/lich-su-bai-thi/' + id;
        $.ajax({
            url: url,
            type: "POST",
            success: function (response) {
                $('#modal1').html(response);
                $('#modal1').modal('show');
            }
        });
    }
}
