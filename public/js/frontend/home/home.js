class Home {
    module;
    baseUrl;
    urlPath;

    /** Đợt thi hiện tại */
    contest;
    /** Các đợt thi trong năm */
    contests;
    /** Index đợt thi hiển thị trên bảng xếp hạng */
    indexContestForRank;

    constructor(baseUrl, module) {
        this.module = module;
        this.baseUrl = baseUrl;
        this.urlPath = baseUrl;
    }

    loadIndex() {
        let myClass = this;
        this.getData();
        $('#btn_thamgia').on('click', function (e) {
            if (!$(this).data('Ncl-disabled')) {
                myClass.goToExam();
            }
        });
        $('#rank_filter_contest').on('change', function (e) {
            let val = $(this).val();
            let contest = undefined;
            for (const element of myClass.contests) {
                if (element.id == val) {
                    contest = element;
                    break;
                }
            }
            if (contest !== undefined) {
                $('#rank_from').text(contest.ngay_bat_dau);
                $('#rank_to').text(contest.ngay_ket_thuc);
                myClass.getRank();
            }
        });
    }

    /**
     * Lấy dữ liệu cho trang chủ
     */
    getData() {
        NclLib.loadding();
        let url = this.urlPath + '/home-data';
        let myClass = this;
        $.ajax({
            type: "GET",
            url: url,
            dataType: "json",
            success: function (response) {
                
            }
        });
    }
}