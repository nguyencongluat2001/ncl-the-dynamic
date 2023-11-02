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
            if (!$(this).data('efy-disabled')) {
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
        let url = this.urlPath + '/home-data';
        let myClass = this;
        $.ajax({
            type: "GET",
            url: url,
            dataType: "json",
            success: function (response) {
                $('#home_text_1').text(response.text1);
                $('#home_text_2').text(response.text2);
                myClass.indexContestForRank = response.index_contest_for_rank;
                if (response.duration && response.duration > 0) myClass.startTimer(response.duration);
                if (response.contest && typeof response.contest.id == 'string' && response.contest.id != '') {
                    myClass.contest = response.contest;
                    $('#btn_thamgia').removeAttr('disabled');
                    $('#btn_thamgia').attr('data-efy-disabled', false);
                }
                if (response.contests && response.contests.length > 0) {
                    myClass.contests = response.contests;
                    myClass.genSelectContest();
                }
            }
        });
    }

    /**
     * Tạo html cho select đợt thi
     */
    genSelectContest() {
        let myClass = this;
        let html = '<option value="CHUNG_CUOC">Chung cuộc</option>';
        let val = 'CHUNG_CUOC';
        this.contests.forEach((contest, key) => {
            let selected = '';
            if (key === myClass.indexContestForRank) {
                selected = 'selected';
                val = contest.id;
            }
            html += `<option value="${contest.id}" ${selected}>${contest.ten}</option>`;
        });
        $('#rank_filter_contest').html(html);
        $('#rank_filter_contest').val(val).change();
    }

    /**
     * Lấy thông tin bảng xếp hạng
     */
    getRank() {
        let url = this.urlPath + '/bang-xep-hang/' + $('#rank_filter_contest').val();
        $.ajax({
            type: "GET",
            url: url,
            dataType: "json",
            success: function (response) {
                $('#total_exam_turn').text(response.total);
                $('#pills-person').html(response.personal);
                $('#pills-group').html(response.group);
            }
        });
    }

    /**
     * Tính thời gian làm bài thi
     * @param {number} duration Thời gian ban đầu (giây)
     */
    startTimer(duration) {
        let myClass = this;
        let timer = duration, days, hours, minutes, seconds;
        let intervalId = setInterval(function () {
            days = Math.floor(timer / (3600 * 24));
            hours = Math.floor((timer % (3600 * 24)) / 3600);
            minutes = Math.floor((timer % 3600) / 60);
            seconds = timer % 60;

            days = days < 10 ? "0" + days : days;
            hours = hours < 10 ? "0" + hours : hours;
            minutes = minutes < 10 ? "0" + minutes : minutes;
            seconds = seconds < 10 ? "0" + seconds : seconds;

            $('#home_countdown_day').text(days);
            $('#home_countdown_hour').text(hours);
            $('#home_countdown_minute').text(minutes);
            $('#home_countdown_second').text(seconds);

            if (--timer < 0) {
                clearInterval(intervalId);
                // gọi 1 hàm thông báo hết thời gian thi
                myClass.timeUp();
            }
        }, 1000);
    }

    /**
     * Event khi hết thời gian
     */
    timeUp() {
        // $('#home_text_2').text($('#home_text_2').text().replace('kết thúc sau', 'đã kết thúc'));
        // $('#home_countdown_day').text('--');
        // $('#home_countdown_hour').text('--');
        // $('#home_countdown_minute').text('--');
        // $('#home_countdown_second').text('--');
        // $('#btn_thamgia').attr('disabled', true);
        // $('#btn_thamgia').attr('data-efy-disabled', true);
        window.location.reload();
    }

    /**
     * Chuyển đến trang thi
     */
    goToExam() {
        let myClass = this;
        let url = this.urlPath + '/data/kiem-tra-da-thi'
        // check đã thi đợt này chưa
        $.ajax({
            type: "POST",
            url: url,
            data: {
                contest_id: this.contest.id,
            },
            dataType: "json",
            success: function (response) {
                if (response.is_login == false) {
                    window.location.href = myClass.baseUrl + '/dang-nhap?path=bai-thi&param=' + myClass.contest.id;
                } else if (response.is_taken_test) {
                    Swal.fire({
                        title: "Bạn đã tham gia đợt thi này rồi!",
                        text: "Vui lòng chờ đợt thi kế tiếp",
                        icon: "error"
                    });
                } else {
                    window.location.href = myClass.urlPath + '/bai-thi/' + myClass.contest.id;
                }
            }
        });
    }
}