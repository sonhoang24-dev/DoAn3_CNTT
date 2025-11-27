$(document).ready(function () {
  let questions = [];
  const made = $("#dethicontent").data("id");
  const dethi = "dethi" + made;
  const cautraloi = "cautraloi" + made;

  function getQuestion() {
    console.log("Starting getQuestion(), made=", made);
    return $.ajax({
      type: "post",
      url: "./test/getQuestion",
      data: { made: made },
      dataType: "json",
      success: function (response) {
        console.log("getQuestion response:", response);
        console.log("typeof response:", typeof response);
        console.log("Array.isArray(response):", Array.isArray(response));
        console.log("response length:", response.length);

        if (response && Array.isArray(response) && response.length > 0) {
          questions = response;
          console.log("Questions loaded:", questions);
        } else {
          console.error("No valid questions received:", response);
          $("#list-question").html(
            "<p class='text-center text-danger'>Không tải được câu hỏi! Vui lòng kiểm tra mã đề hoặc liên hệ quản trị viên.</p>"
          );
        }
      },
      error: function (xhr) {
        console.error(
          "Error fetching questions:",
          xhr.status,
          xhr.responseText
        );
        $("#list-question").html(
          "<p class='text-center text-danger'>Lỗi khi tải câu hỏi: " +
            (xhr.responseText || "Kết nối thất bại") +
            "</p>"
        );
      },
    });
  }

  function showListQuestion(questions, answers) {
    let html = "";

    if (!questions || !Array.isArray(questions) || questions.length === 0) {
      $("#list-question").html(
        "<p class='text-center text-danger'>Không có câu hỏi</p>"
      );
      return;
    }

    // Không bắt buộc answers phải có đúng độ dài, chỉ cần là mảng hoặc undefined
    if (!answers || !Array.isArray(answers)) {
      answers = []; // Đảm bảo answers luôn là mảng
    }

    questions.forEach((question, index) => {
      // Lấy đáp án đã chọn của người dùng (nếu có)
      const userAnswer = answers[index]; // có thể là undefined
      const userChosenId = userAnswer ? userAnswer.cautraloi : null; // macautl đã chọn

      html += `
        <style>
            .img-question { max-width: 350px; max-height: 250px; object-fit: contain; display: block; margin: 0 auto; }
            .img-answer { width: 120px; height: 120px; object-fit: cover; border-radius: 10px; margin-left: 10px; }
        </style>
        <div class="question rounded border mb-3 bg-white" id="c${index + 1}">
            <div class="question-top p-3">
                <p class="question-content fw-bold mb-2">${index + 1}. ${
        question.noidung
      }</p>
        `;

      // Hình ảnh câu hỏi
      if (question.hinhanh && question.hinhanh.trim() !== "") {
        let srcQ = question.hinhanh.startsWith("data:image")
          ? question.hinhanh
          : "data:image/png;base64," + question.hinhanh;
        html += `
                <div class="text-center mb-3">
                    <img src="${srcQ}" class="img-question" alt="Hình câu hỏi">
                </div>`;
      }

      // Các đáp án
      html += `<div class="row">`;
      question.cautraloi.forEach((ctl, i) => {
        let imgData = ctl.hinhanhtl || ctl.hinhanh || "";
        let srcAns = "";
        if (imgData && imgData.trim() !== "") {
          srcAns = imgData.startsWith("data:image")
            ? imgData
            : "data:image/png;base64," + imgData;
        }

        html += `
            <div class="col-6 mb-2">
                <div>
                    <b>${String.fromCharCode(65 + i)}.</b> ${
          ctl.noidungtl || ""
        }
                    ${
                      srcAns
                        ? `<br><img src="${srcAns}" class="img-answer" alt="Hình đáp án">`
                        : ""
                    }
                </div>
            </div>`;
      });
      html += `</div></div>`;

      // Phần hiển thị đáp án đã chọn của người dùng
      html += `
        <div class="test-ans bg-primary rounded-bottom py-2 px-3 d-flex align-items-center">
            <p class="mb-0 text-white me-4">Đáp án của bạn:</p>
            <div>`;

      question.cautraloi.forEach((ctl, i) => {
        const isChecked = userChosenId === ctl.macautl; // So sánh an toàn
        const checkedAttr = isChecked ? "checked" : "";

        html += `
            <input type="radio" class="btn-check" name="options-c${index + 1}"
                   id="ctl-${ctl.macautl}" autocomplete="off"
                   data-index="${index + 1}" data-macautl=" $${
          ctl.macautl
        }" ${checkedAttr}>
            <label class="btn btn-light rounded-pill me-2 btn-answer ${
              isChecked ? "btn-warning" : ""
            }"
                   for="ctl-${ctl.macautl}">
                ${String.fromCharCode(65 + i)}
            </label>`;
      });

      html += `
            </div>
        </div>
        </div>`;
    });

    $("#list-question").html(html);
  }
  // Hàm hiển thị toast thông báo chọn đáp án
  function showAnswerToast(cau, dapan) {
    // Xóa toast cũ nếu còn
    $("#answer-toast").remove();

    const toastHtml = `
    <div id="answer-toast" class="position-fixed top-0 start-50 translate-middle-x p-3" style="z-index: 9999; margin-top: 20px;">
      <div class="toast show align-items-center text-white bg-success border-0 shadow-lg" role="alert">
        <div class="d-flex">
          <div class="toast-body fw-bold fs-5 text-center">
           Bạn đã chọn:<span class="text-warning"> Câu ${cau}</span> → Đáp án <span class="text-warning fs-4">${dapan}</span>
          </div>
          <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" onclick="$('#answer-toast').fadeOut(200, function(){$(this).remove()})"></button>
        </div>
      </div>
    </div>
  `;

    $("body").append(toastHtml);

    // Tự động ẩn sau 2.2 giây
    setTimeout(() => {
      $("#answer-toast").fadeOut(400, function () {
        $(this).remove();
      });
    }, 2200);
  }

  function initListAnswer(questions) {
    let listAns = questions.map((item) => {
      let itemAns = {};
      itemAns.macauhoi = item.macauhoi;
      itemAns.cautraloi = 0;
      return itemAns;
    });
    return listAns;
  }

  function changeAnswer(index, dapan) {
    let listAns = JSON.parse(localStorage.getItem(cautraloi));
    listAns[index].cautraloi = dapan;
    localStorage.setItem(cautraloi, JSON.stringify(listAns));
  }

  $.when(getQuestion()).done(function () {
    let listQues, listAns;

    // Ưu tiên lấy từ localStorage trước (nếu đã làm dở)
    if (localStorage.getItem(dethi) && localStorage.getItem(cautraloi)) {
      listQues = JSON.parse(localStorage.getItem(dethi));
      listAns = JSON.parse(localStorage.getItem(cautraloi));

      console.log("Dùng dữ liệu từ localStorage (đang làm dở)");
    } else {
      // Nếu chưa có thì mới dùng dữ liệu từ server
      listQues = questions;
      listAns = initListAnswer(questions);

      localStorage.setItem(dethi, JSON.stringify(listQues));
      localStorage.setItem(cautraloi, JSON.stringify(listAns));

      console.log("Tạo mới đề thi trong localStorage");
    }

    // Render với dữ liệu đã đồng bộ
    showListQuestion(listQues, listAns);
    showBtnSideBar(listQues, listAns);
  });

  function showBtnSideBar(questions, answers) {
    let html = ``;
    questions.forEach((q, i) => {
      // Nếu câu hỏi đã có đáp án, active, ngược lại không
      let isActive =
        answers[i] && answers[i].cautraloi && answers[i].cautraloi != 0
          ? " active"
          : "";
      html += `<li class="answer-item p-1">
      <a href="javascript:void(0)" 
         class="answer-item-link btn btn-outline-primary w-100 btn-sm${isActive}" 
         data-index="${i + 1}">${i + 1}</a>
    </li>`;
    });
    $(".answer").html(html);
  }

  $(document).on("click", ".btn-check", function () {
    const quesIndex = $(this).data("index");
    const macautl = $(this).data("macautl");
    const label = $(this).next("label").text().trim();

    changeAnswer(quesIndex - 1, macautl);

    // Lấy lại dữ liệu mới nhất
    const listQues = JSON.parse(localStorage.getItem(dethi));
    const listAns = JSON.parse(localStorage.getItem(cautraloi));

    showBtnSideBar(listQues, listAns);
    $(this)
      .next("label")
      .addClass("btn-warning")
      .siblings("label")
      .removeClass("btn-warning");

    showAnswerToast(quesIndex, label);
  });

  $(document).on("click", ".answer-item-link", function () {
    let ques = $(this).data("index");
    document.getElementById(`c${ques}`).scrollIntoView();
  });

  $("#btn-nop-bai").click(function (e) {
    e.preventDefault();

    let listAns = JSON.parse(localStorage.getItem(cautraloi));
    let unanswered = listAns.filter((ans) => ans.cautraloi === 0);

    if (unanswered.length > 0) {
      Swal.fire({
        icon: "warning",
        title: "Chưa hoàn thành!",
        html: `<p class="fs-6 text-center mb-0">Bạn chưa chọn đáp án cho <strong>${unanswered.length}</strong> câu hỏi.<br>Vui lòng hoàn thành tất cả trước khi nộp bài.</p>`,
        confirmButtonText: "OK",
      });
      return;
    }

    Swal.fire({
      title: "<center><p class='fs-3 mb-0'>Bạn có chắc chắn muốn nộp bài?</p>",
      html: "<p class='text-muted fs-6 text-center mb-0'>Khi xác nhận nộp bài, bạn sẽ không thể sửa lại bài thi của mình!</p>",
      icon: "info",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Vâng, chắc chắn!",
      cancelButtonText: "Huỷ",
    }).then((result) => {
      if (result.isConfirmed) {
        nopbai();
      }
    });
  });

  function nopbai() {
    let dethiCheck = $("#dethicontent").data("id");
    let thoigian = new Date();
    $.ajax({
      type: "post",
      url: "./test/submit",
      data: {
        listCauTraLoi: JSON.parse(localStorage.getItem(cautraloi)),
        thoigianlambai: thoigian,
        made: dethiCheck,
      },
      success: function (response) {
        localStorage.removeItem(cautraloi);
        localStorage.removeItem(dethi);
        location.href = `./test/start/${made}`;
      },
      error: function (response) {
        localStorage.removeItem(cautraloi);
        localStorage.removeItem(dethi);
        location.href = `./test/start/${made}`;
      },
    });
  }

  $("#btn-thoat").click(function (e) {
    e.preventDefault();
    Swal.fire({
      title: "Bạn có chắc muốn thoát?",
      html: "<p class='text-muted fs-6 text-center mb-0'>Khi xác nhận thoát, bạn sẽ không được tiếp tục làm bài ở lần thi này. Kết quả bài làm vẫn sẽ được nộp</p>",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Vâng, chắc chắn!",
      cancelButtonText: "Huỷ",
    }).then((result) => {
      if (result.isConfirmed) {
        nopbai();
        location.href = "./dashboard";
      }
    });
  });

  var endTime = -1;
  getTimeTest();

  function getTimeTest() {
    let dethi = $("#dethicontent").data("id");
    $.ajax({
      type: "post",
      url: "./test/getTimeTest",
      data: {
        dethi: dethi,
      },
      success: function (response) {
        endTime = new Date(response).getTime();
        let curTime = new Date().getTime();
        if (curTime > endTime) {
          localStorage.removeItem(cautraloi);
          localStorage.removeItem(dethi);
          location.href = `./test/start/${made}`;
        } else {
          $.ajax({
            type: "post",
            url: "./test/getTimeEndTest",
            data: {
              dethi: dethi,
            },
            success: function (responseEnd) {
              let endTimeTest = new Date(responseEnd).getTime();
              if (endTimeTest < endTime) {
                endTime = endTimeTest;
              }
            },
          });
          countDown();
        }
      },
    });
  }

  function countDown() {
    var x = setInterval(function () {
      var now = new Date().getTime();
      var distance = endTime - now;
      var hours = Math.floor(
        (distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)
      );
      if (hours < 10) hours = "0" + hours;
      var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
      if (minutes < 10) minutes = "0" + minutes;
      var seconds = Math.floor((distance % (1000 * 60)) / 1000);
      if (seconds < 10) seconds = "0" + seconds;
      $("#timer").html(hours + ":" + minutes + ":" + seconds);

      if (distance <= 30000) {
        $("#timer").css("color", "red").css("font-weight", "bold");
      }

      if (distance <= 1000 && distance >= 0) {
        nopbai();
        clearInterval(x);
      }
    }, 1000);
  }

  // Logic xử lý chuyển tab
  //nộp bài ngay khi chuyển tab nếu bài thi đó có rán giá trị nộp bài chuyển tab(nopbaichuyentab) là 1
  $(window).on("blur", function () {
    //lắng nghe sự kiện khi người dùng chuyển tab
    $.ajax({
      type: "post",
      url: "./test/chuyentab", //gọi đến hàm này trong controller test.php .← Gửi yêu cầu đến server kiểm tra số lần chuyển tab
      data: {
        made: $("#dethicontent").data("id"),
      },
      success: function (response) {
        if (response == 1) {
          nopbai();
        } else {
          localStorage.setItem("isTabSwitched_" + made, "1");
        }
      },
      error: function () {
        localStorage.setItem("isTabSwitched_" + made, "1");
      },
    });
  });
  // hàm phát hiện chuyển tab trong khi thi
  $(window).on("focus", function () {
    if (localStorage.getItem("isTabSwitched_" + made) === "1") {
      // lắng nghe sự kiện khi người dùng chuyển tab quay lại trang
      let curTime = new Date().getTime();
      if (curTime < endTime) {
        Swal.fire({
          icon: "warning",
          title: "Bạn đã rời khỏi trang thi",
          html: "<p class='fs-6 text-center mb-0'>Hệ thống phát hiện bạn đã chuyển tab trước đó. Bạn vẫn được thi tiếp vì còn thời gian làm bài.</p>",
          confirmButtonText: "Tiếp tục",
        });
        localStorage.removeItem("isTabSwitched_" + made);
      } else {
        nopbai();
      }
    }
  });
});
