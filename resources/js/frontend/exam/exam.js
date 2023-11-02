class Exam {
    module;
    baseUrl;
    urlPath;

    /** Đợt thi */
    contest;
    /** Câu hỏi hiện tại */
    question;
    /** Danh sách các câu hỏi */
    questions;
    /** Thời gian làm bài còn lại */
    timeRemaining;
    /** Thời gian làm bài ban đầu */
    timeRemainingOrigin;

    constructor(baseUrl, module) {
        this.module = module;
        this.baseUrl = baseUrl;
        this.urlPath = baseUrl + '/' + module;
    }

    loadIndex() {
        let myClass = this;

        // Lấy bài thi
        this.getExam();

        // Xóa ký tự chữ, chỉ để số
        $('#so_nguoi_du_doan').keyup(function (e) {
            let value = e.target.value.replace(/[^0-9]/g, '');
            myClass.questions[myClass.questions.length - 1].du_doan_so_nguoi = value;
            myClass.questions[myClass.questions.length - 1].selected = true;
            $(e.target).val(value);
            myClass.updateControl();
        });

        $('#btn_previous').click(function (e) {
            if (myClass.question.index > 0) {
                let previousIndex = myClass.question.index - 1;
                // Lấy câu hỏi trước
                myClass.question = myClass.questions[previousIndex];
                myClass.question.index = previousIndex;
                // Cập nhật control
                myClass.updateControl();
                // Tạo câu hỏi
                myClass.genQuestion();
            }
        });

        $('#btn_next').click(function (e) {
            if (myClass.question.index < (myClass.questions.length - 1)) {
                let nextIndex = myClass.question.index + 1;
                // Lấy câu hỏi sau
                myClass.question = myClass.questions[nextIndex];
                myClass.question.index = nextIndex;
                // Cập nhật control
                myClass.updateControl();
                // Tạo câu hỏi
                myClass.genQuestion();
            }
        });

        $('#btn_submit').on('click', function (e) {
            myClass.checkSubmit();
        });
    }

    /**
     * Lấy thông tin và câu hỏi cho bài thi
     */
    getExam() {
        let url = this.urlPath + '/lay-bai-thi';
        let myClass = this;
        $.ajax({
            type: "POST",
            url: url,
            data: {
                contest_id: $('input[name=contest_id]').val(),
            },
            dataType: "json",
            success: function (response) {
                let contest = response.contest;
                myClass.contest = contest;
                myClass.questions = response.questions;
                let t = parseInt(response.contest.thoi_gian_lam_bai);
                myClass.timeRemainingOrigin = t;
                myClass.timeRemaining = t;
                // Gán câu hỏi ban đầu là câu 1
                myClass.question = myClass.questions[0];
                myClass.question.index = 0;
                $('#title').text(`Bài thi trực tuyến tìm hiểu công tác cải cách hành chính trong cán bộ, công chức, viên chức tỉnh Hải Dương năm ${contest.nam} ${contest.ten}`);
                // Khởi tạo control
                myClass.updateControl();
                // Tạo câu hỏi
                myClass.genQuestion();
                // Bắt đầu tính thời gian
                myClass.startTimer(t);
            }
        });
    }

    /**
     * Cập nhật question control
     */
    updateControl() {
        let myClass = this;
        // Tạo html
        let html = '';
        this.questions.forEach((question, key) => {
            html += `<span class="question-item ${question.selected && question.selected == true ? 'question-item-selected' : ''}" data-index="${key}">${key + 1}</span>`;
        });
        $('#question_control').html(html);
        this.refreshBtnPreviousNext();
        // Event khi click vào câu hỏi
        $('.question-item').on('click', function (e) {
            let index = $(this).data('index');
            myClass.question = myClass.questions[index];
            myClass.question.index = index;
            myClass.genQuestion();
            myClass.refreshBtnPreviousNext();
        });
    }

    /**
     * Check hiển thị các nút tiến - lùi
     */
    refreshBtnPreviousNext() {
        if (this.question.index == 0) {
            $('#btn_previous').addClass('btn-change-question-disabled');
        } else {
            $('#btn_previous').removeClass('btn-change-question-disabled');
        }
        if (this.question.index == (this.questions.length - 1)) {
            $('#btn_next').addClass('btn-change-question-disabled');
        } else {
            $('#btn_next').removeClass('btn-change-question-disabled');
        }
    }

    /**
     * Tạo html cho câu hỏi
     */
    genQuestion() {
        let myClass = this;
        // Câu hỏi
        $('#question_number').text(this.question.index + 1);
        $('#question_name').html(document.createElement('div').innerHTML = this.question.ten_cau_hoi);
        // Đáp án
        this.genAnswer();
        // Event khi click radio đáp án
        $('input[type="radio"][name="dap_an"]').off('click');
        $('input[type="radio"][name="dap_an"]').on('click', function () {
            let checkedValue = $(this).val();
            myClass.questions[myClass.question.index].selected = true;
            myClass.questions[myClass.question.index].dap_an_lua_chon = checkedValue;
            myClass.question.dap_an_lua_chon = checkedValue;
            myClass.updateControl();
        });
    }

    /**
     * Tạo html cho đáp án
     */
    genAnswer() {
        if (this.question.id != 'CAU_HOI_11') {
            let html = '<table>';
            let charCode = 65;
            this.question.answer_random.forEach(question => {
                let elementId = Object.keys(question)[0];
                let elementText = Object.values(question)[0];
                let prefix = 'dap_an_';
                let value = elementId.substring(prefix.length).toUpperCase();
                let checked = this.question.dap_an_lua_chon && this.question.dap_an_lua_chon == value ? 'checked' : '';
                html +=
                    `<tr>
                        <td>
                            <input class="form-check-input" type="radio" name="dap_an" id="${elementId}" value="${value}" ${checked}> 
                            <label class="form-check-label" for="${elementId}">${String.fromCharCode(charCode)}.</label>
                        </td>
                        <td>
                            <label class="form-check-label" for="${elementId}">${elementText}</label>
                        </td>
                    </tr>`;
                charCode++;
            });
            html += '</table>';
            $('#question_type_nomal').html(html);
            $('#question_type_nomal').show();
            $('#question_type_11').hide();
        } else {
            $('#question_type_nomal').hide();
            $('#question_type_nomal').html('');
            $('#question_type_11').show();
        }
    }

    /**
     * Tính thời gian làm bài thi
     * @param {number} duration Thời gian ban đầu (giây)
     */
    startTimer(duration) {
        let myClass = this;
        let timer = duration, hours, minutes, seconds;
        let intervalId = setInterval(function () {
            hours = parseInt(timer / 3600, 10);
            minutes = parseInt((timer % 3600) / 60, 10);
            seconds = parseInt(timer % 60, 10);

            hours = hours < 10 ? "0" + hours : hours;
            minutes = minutes < 10 ? "0" + minutes : minutes;
            seconds = seconds < 10 ? "0" + seconds : seconds;

            myClass.timeRemaining = timer;
            $('#time_remaining').text(`${hours}:${minutes}:${seconds}`);

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
        let myClass = this;
        Swal.fire({
            title: 'Hết thời gian làm bài. Bạn có muốn nộp bài không?',
            showDenyButton: true,
            confirmButtonText: "Nộp",
            denyButtonText: "Hủy",
            allowOutsideClick: false
        }).then((result) => {
            if (result.isConfirmed) {
                myClass.submit();
            } else if (result.isDenied) {
                Swal.fire({
                    title: 'Bài thi đã hủy!',
                    text: 'Bạn sẽ quay trở lại trang chủ',
                    icon: 'info',
                    showConfirmButton: false,
                });
                setTimeout(() => {
                    window.location.replace(myClass.baseUrl);
                }, 3000);
            }
        })
    }

    /**
     * Event khi click Nộp bài thi
     */
    checkSubmit() {
        let myClass = this;
        Swal.fire({
            title: "Bạn có chắc chắc nộp bài?",
            icon: "info",
            showDenyButton: true,
            confirmButtonText: "Nộp",
            denyButtonText: "Hủy",
            allowOutsideClick: false
        }).then((result) => {
            if (result.isConfirmed) {
                myClass.submit();
            }
        });
    }

    /**
     * Nộp bài thi
     */
    submit() {
        let myClass = this;
        let url = this.urlPath + '/nop-bai';
        $.ajax({
            type: "POST",
            url: url,
            data: {
                contest: this.contest,
                questions: this.questions,
                submit_time: this.getCurrentDateTime(),
                exam_time: this.getExamTime(),
            },
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    Swal.fire({
                        title: response.message,
                        text: `Tổng điểm đạt được là: ${response.score}`,
                        icon: "success",
                        confirmButtonText: 'OK',
                        allowOutsideClick: false
                    }).then((result) => {
                        window.location.replace(myClass.baseUrl);
                    })
                } else {
                    Swal.fire({
                        title: response.message,
                        icon: "error",
                        confirmButtonText: 'OK',
                        allowOutsideClick: false
                    }).then((result) => {
                        window.location.replace(myClass.baseUrl);
                    })
                }
            }
        });
    }

    /**
     * Thời gian làm bài
     * @returns 
     */
    getExamTime() {
        return this.timeRemainingOrigin - this.timeRemaining;
    }

    /**
     * Lấy thời gian hiện tại
     * @returns 
     */
    getCurrentDateTime() {
        let inputDate = new Date();
        let year = inputDate.getFullYear();
        let month = String(inputDate.getMonth() + 1).padStart(2, '0'); // Month is 0-based, so add 1 and pad with '0'
        let day = String(inputDate.getDate()).padStart(2, '0');
        let hours = String(inputDate.getHours()).padStart(2, '0');
        let minutes = String(inputDate.getMinutes()).padStart(2, '0');
        let seconds = String(inputDate.getSeconds()).padStart(2, '0');
        let formattedDateTime = `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;

        return formattedDateTime;
    }
}