$(document).ready(function () {
  let questions = [];
  const made = $("#dethicontent").data("id");
  const dethiKey = "dethi" + made;
  const answerKey = "cautraloi" + made;

  // ================== Lấy câu hỏi từ server ==================
  function getQuestion() {
    return $.ajax({
      type: "post",
      url: "./test/getQuestion",
      data: { made: made },
      dataType: "json",
      success: function (response) {
        if (response && Array.isArray(response) && response.length > 0) {
          questions = response;
        } else {
          $("#list-question").html(
            "<p class='text-center text-danger'>Không tải được câu hỏi!</p>"
          );
        }
      },
      error: function (xhr) {
        $("#list-question").html(
          "<p class='text-center text-danger'>Lỗi khi tải câu hỏi</p>"
        );
      },
    });
  }

  // ================== Khởi tạo listAnswer ==================
  function initListAnswer(questions) {
    return questions.map((q) => ({
      macauhoi: q.macauhoi,
      cautraloi: q.loai === "essay" ? null : 0,
      noidungtl: q.loai === "essay" ? "" : null,
    }));
  }

  // ================== Render sidebar ==================
  function showBtnSideBar(questions, answers) {
    let html = "";
    questions.forEach((q, i) => {
      let isActive = "";
      if (answers[i]) {
        if (q.loai === "essay" && answers[i].noidungtl?.trim())
          isActive = " active";
        if (q.loai !== "essay" && answers[i].cautraloi) isActive = " active";
      }
      html += `<li class="answer-item p-1">
                 <a href="javascript:void(0)" class="answer-item-link btn btn-outline-primary w-100 btn-sm${isActive}" data-index="${
        i + 1
      }">${i + 1}</a>
               </li>`;
    });
    $(".answer").html(html);
  }

  // ================== Render câu hỏi + CKEditor + MCQ ==================
  function showBtnSideBar(questions, answers) {
    let html = "";
    questions.forEach((q, i) => {
      let isActive = "";
      if (answers[i]) {
        if (q.loai === "essay" && answers[i].noidungtl?.trim())
          isActive = " active";
        if (q.loai !== "essay" && answers[i].cautraloi) isActive = " active";
      }
      html += `<li class="answer-item p-1">
                 <a href="javascript:void(0)" class="answer-item-link btn btn-outline-primary w-100 btn-sm${isActive}" data-index="${
        i + 1
      }">${i + 1}</a>
               </li>`;
    });
    $(".answer").html(html);
  }

  // ================== Render câu hỏi + CKEditor + MCQ ==================
  function showListQuestion(questions, answers) {
    if (!questions || !Array.isArray(questions) || questions.length === 0) {
      $("#list-question").html(
        "<p class='text-center text-danger'>Không có câu hỏi</p>"
      );
      return;
    }

    answers = Array.isArray(answers) ? answers : [];
    let html = "";

    questions.forEach((question, index) => {
      const userAnswer = answers[index] || {};
      const userChosenId = userAnswer.cautraloi || null;
      const userEssayText = userAnswer.noidungtl || "";
      const editorId = `editor_${index + 1}`;
      const thumbId = `thumb_${index + 1}`;

      html += `
      <style>
        .img-question { max-width: 350px; max-height: 250px; object-fit: contain; display: block; margin: 0 auto; }
        .img-answer { width: 120px; height: 120px; object-fit: cover; border-radius: 10px; margin-left: 10px; margin-top: 5px; }
        .img-thumb { width: 60px; height: 60px; object-fit: cover; border-radius: 5px; margin-right: 5px; }
      </style>
      <div class="question rounded border mb-3 bg-white" id="c${index + 1}">
        <div class="question-top p-3">
          <p class="question-content fw-bold mb-2">${index + 1}. ${
        question.noidung
      }</p>`;

      // Hình câu hỏi
      if (question.hinhanh?.trim()) {
        const srcQ = question.hinhanh.startsWith("data:image")
          ? question.hinhanh
          : "data:image/png;base64," + question.hinhanh;
        html += `<div class="text-center mb-3"><img src="${srcQ}" class="img-question" alt="Hình câu hỏi"></div>`;
      }

      html += `</div>`; // đóng question-top

      if (question.loai === "essay") {
        const editorId = `editor_${index + 1}`;
        const thumbId = `thumb_${index + 1}`;
        const fileInputId = `fileinput_${index + 1}`;

        html += `
  <div class="test-ans bg-light text-dark p-4 rounded-bottom">
    <p class="mb-3 fw-bold fs-5">Đáp án của bạn:</p>

    <div id="${editorId}" class="mb-4">${userEssayText || ""}</div>

    <div class="mb-3">
      <label class="form-label">Hình ảnh (tùy chọn)</label>
      <input type="file" class="form-control" 
             id="${fileInputId}" accept="image/*" multiple>
    </div>

    <div id="${thumbId}" class="image-preview-container border rounded p-3 bg-white">
      <small class="text-muted no-image-text">Chưa có ảnh nào được chọn</small>
    </div>
  </div>`;

        setTimeout(() => {
          const editorDiv = document.getElementById(editorId);
          const fileInput = document.getElementById(fileInputId);
          const previewContainer = document.getElementById(thumbId);

          // CKEditor
          ClassicEditor.create(editorDiv).then((editor) => {
            window["ckeditor_" + editorId] = editor;

            if (userEssayText) editor.setData(userEssayText);

            const renderImages = () => {
              const imgs = answers[index]?.images || [];
              previewContainer.innerHTML = "";

              if (imgs.length === 0) {
                previewContainer.innerHTML = `<small class="text-muted">Chưa có ảnh nào được chọn</small>`;
                return;
              }

              imgs.forEach((src, i) => {
                const wrap = document.createElement("div");
                wrap.className = "position-relative d-inline-block me-2";

                const img = document.createElement("img");
                img.src = src;
                img.className = "img-thumbnail rounded";
                img.style.cssText = "max-width:150px; max-height:150px;";

                const del = document.createElement("button");
                del.className = "btn btn-danger btn-sm position-absolute";
                del.style = "top:5px; right:5px;";
                del.innerHTML = "×";

                del.onclick = () => {
                  answers[index].images.splice(i, 1);
                  localStorage.setItem(answerKey, JSON.stringify(answers));
                  renderImages();
                };

                wrap.appendChild(img);
                wrap.appendChild(del);
                previewContainer.appendChild(wrap);
              });
            };

            // Upload ảnh
            fileInput.addEventListener("change", (e) => {
              const files = Array.from(e.target.files);

              answers[index] = answers[index] || {};
              answers[index].images = answers[index].images || [];

              files.forEach((file) => {
                const reader = new FileReader();
                reader.onload = (ev) => {
                  answers[index].images.push(ev.target.result);
                  localStorage.setItem(answerKey, JSON.stringify(answers));
                  renderImages();
                };
                reader.readAsDataURL(file);
              });

              fileInput.value = "";
            });

            renderImages();
          });
        }, 100);
      } else {
        // ================= MCQ =================
        html += `<div class="row">`;
        question.cautraloi.forEach((ctl, i) => {
          let srcAns = "";
          if (ctl.hinhanhtl || ctl.hinhanh) {
            const imgData = ctl.hinhanhtl || ctl.hinhanh;
            srcAns = imgData.startsWith("data:image")
              ? imgData
              : "data:image/png;base64," + imgData;
          }
          html += `
          <div class="col-6 mb-2">
            <div>
              <b>${String.fromCharCode(65 + i)}.</b> ${ctl.noidungtl || ""}
              ${srcAns ? `<br><img src="${srcAns}" class="img-answer">` : ""}
            </div>
          </div>`;
        });
        html += `</div>`; // end row

        // Vùng chọn đáp án
        html += `
        <div class="test-ans bg-primary rounded-bottom py-2 px-3 d-flex align-items-center">
          <p class="mb-0 text-white me-4">Đáp án của bạn:</p>
          <div>`;
        question.cautraloi.forEach((ctl, i) => {
          const isChecked = String(userChosenId) === String(ctl.macautl);
          html += `
          <input type="radio" class="btn-check"
                 name="options-c${index + 1}"
                 id="ctl-${ctl.macautl}" 
                 autocomplete="off"
                 data-index="${index}"
                 data-macautl="${ctl.macautl}" 
                 value="${ctl.macautl}" ${isChecked ? "checked" : ""}>
          <label class="btn btn-light rounded-pill me-2 btn-answer ${
            isChecked ? "btn-warning" : ""
          }" for="ctl-${ctl.macautl}">
            ${String.fromCharCode(65 + i)}
          </label>`;
        });
        html += `</div></div></div>`; // end question
      }
    });

    $("#list-question").html(html);

    // ================= gán sự kiện MCQ =================
    $(".btn-check")
      .off("change")
      .on("change", function () {
        const idx = $(this).data("index");
        const macautl = $(this).data("macautl");

        if (!answers[idx]) answers[idx] = {};
        answers[idx].cautraloi = macautl;
        localStorage.setItem(answerKey, JSON.stringify(answers));
        showBtnSideBar(questions, answers);
        saveMCQAnswer(idx, macautl); // nếu cần server
      });

    showBtnSideBar(questions, answers);
  }

  // ================= Khởi tạo và render lần đầu =================
  $.when(getQuestion()).done(() => {
    let savedQ = localStorage.getItem(dethiKey);
    let savedA = localStorage.getItem(answerKey);

    if (savedQ && savedA) {
      listQues = JSON.parse(savedQ);
      listAns = JSON.parse(savedA);
    } else {
      // Chỉ tạo mới nếu CHƯA TỪNG LƯU
      listQues = questions;
      listAns = initListAnswer(questions);

      if (!savedQ) localStorage.setItem(dethiKey, JSON.stringify(listQues));
      if (!savedA) localStorage.setItem(answerKey, JSON.stringify(listAns));
    }

    showListQuestion(listQues, listAns);
    showBtnSideBar(listQues, listAns);
  });

  // ================= Sidebar click =================
  $(document).on("click", ".answer-item-link", function () {
    const ques = $(this).data("index");
    document.getElementById(`c${ques}`).scrollIntoView({ behavior: "smooth" });
  });

  $(document).on("change", ".btn-check", function () {
    const quesIndex = $(this).data("index");
    const macautl = $(this).data("macautl");
    const label = $("label[for='" + $(this).attr("id") + "']")
      .text()
      .trim();

    changeAnswer(quesIndex - 1, macautl);

    // Lấy lại dữ liệu mới nhất
    const listQues = JSON.parse(localStorage.getItem(dethiKey));
    const listAns = JSON.parse(localStorage.getItem(answerKey));

    showBtnSideBar(listQues, listAns);
    $("label[for='" + $(this).attr("id") + "']")
      .addClass("btn-warning")
      .siblings("label")
      .removeClass("btn-warning");

    showAnswerToast(quesIndex, label);
  });

  $(document).on("click", ".btn-answer", function () {
    const forId = $(this).attr("for");
    const $input = $("#" + forId);
    if ($input.length) {
      $input.prop("checked", true).trigger("change");
    }
  });

  $("#btn-nop-bai").click(function (e) {
    e.preventDefault();

    let listAns = JSON.parse(localStorage.getItem(answerKey));
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
        listCauTraLoi: JSON.parse(localStorage.getItem(answerKey) || "[]"),
        thoigianlambai: thoigian,
        made: dethiCheck,
      },
      success: function (response) {
        localStorage.removeItem(answerKey);
        localStorage.removeItem(dethiKey);

        location.href = `./test/start/${made}`;
      },
      error: function (response) {
        localStorage.removeItem(answerKey);
        localStorage.removeItem(dethiKey);
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
          localStorage.removeItem(answerKey);
          localStorage.removeItem(dethiKey);
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
  // $(window).on("focus", function () {
  //   if (localStorage.getItem("isTabSwitched_" + made) === "1") {
  //     // lắng nghe sự kiện khi người dùng chuyển tab quay lại trang
  //     let curTime = new Date().getTime();
  //     if (curTime < endTime) {
  //       Swal.fire({
  //         icon: "warning",
  //         title: "Bạn đã rời khỏi trang thi",
  //         html: "<p class='fs-6 text-center mb-0'>Hệ thống phát hiện bạn đã chuyển tab trước đó. Bạn vẫn được thi tiếp vì còn thời gian làm bài.</p>",
  //         confirmButtonText: "Tiếp tục",
  //       });
  //       localStorage.removeItem("isTabSwitched_" + made);
  //     } else {
  //       nopbai();
  //     }
  //   }
  // });
});
