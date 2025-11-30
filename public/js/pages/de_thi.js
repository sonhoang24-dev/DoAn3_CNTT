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

      // Xác định ID thực tế của câu hỏi
      let questionId = q.loai === "reading" ? `reading-q${i + 1}` : `c${i + 1}`;

      html += `<li class="answer-item p-1">
                   <a href="javascript:void(0)" class="answer-item-link btn btn-outline-primary w-100 btn-sm${isActive}" data-target="${questionId}">${
        i + 1
      }</a>
                 </li>`;
    });
    $(".answer").html(html);
  }

  // Click scroll
  $(document).on("click", ".answer-item-link", function () {
    const targetId = $(this).data("target");
    const el = document.getElementById(targetId);
    if (el) el.scrollIntoView({ behavior: "smooth" });
  });

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
    let currentContext = null;
    let groupHtml = "";

    questions.forEach((question, index) => {
      const userAnswer = answers[index] || {};
      const userChosenId = userAnswer.cautraloi || null;
      const userEssayText = userAnswer.noidungtl || "";
      const editorId = `editor_${index + 1}`;
      const thumbId = `thumb_${index + 1}`;
      const fileInputId = `fileinput_${index + 1}`;

      // === READING GROUP ===
      if (question.loai === "reading") {
        // Nếu context mới, đóng nhóm cũ
        if ((question.context || "").trim() !== (currentContext || "").trim()) {
          if (groupHtml) {
            html += `<div class="reading-group rounded border mb-3 bg-white p-3">${groupHtml}</div>`;
            groupHtml = "";
          }
          currentContext = (question.context || "").trim();

          groupHtml += `${
            question.tieude_context
              ? `<h5 class="fw-bold mb-3 text-center p-2 rounded" style="background-color: #ffc107; color: #212529;">
  ${question.tieude_context}
</h5>
`
              : ""
          }`;
          groupHtml += `<p class="mb-4 text-muted" style="line-height: 1.8; font-size: 0.95rem;">${question.context}</p>`;
        }

        const quesNum = index + 1;
        groupHtml += `<div class="question-item mb-4 p-3 bg-light rounded" id="reading-q${quesNum}">
          <p class="question-content fw-bold mb-3" style="color: #333; font-size: 1rem;">${quesNum}. ${question.noidung}</p>`;

        if (
          question.cautraloi &&
          Array.isArray(question.cautraloi) &&
          question.cautraloi.length > 0
        ) {
          groupHtml += `<div class="row g-2">`;
          question.cautraloi.forEach((ctl, i) => {
            const content = ctl.noidungtl || ctl.content || "";
            groupHtml += `<div class="col-12 col-md-6 mb-2">
              <div class="d-flex align-items-start">
                <span class="badge bg-primary me-2" style="min-width: 30px; text-align: center;">${String.fromCharCode(
                  65 + i
                )}</span>
              <span style="word-break: break-word; white-space: normal;">${content}</span>
              </div>
            </div>`;
          });
          groupHtml += `</div>`; // end row
        } else {
          groupHtml += `<p class="text-warning small">Không có phương án</p>`;
        }

        groupHtml += `<div class="test-ans bg-primary rounded py-3 px-3 d-flex align-items-center mt-3 flex-wrap">
          <p class="mb-0 text-white me-3 fw-bold">Đáp án:</p>
          <div class="d-flex gap-2 flex-wrap">`;
        if (
          question.cautraloi &&
          Array.isArray(question.cautraloi) &&
          question.cautraloi.length > 0
        ) {
          question.cautraloi.forEach((ctl, i) => {
            const isChecked = String(userChosenId) === String(ctl.macautl);
            groupHtml += `
              <input type="radio" class="btn-check"
                     name="options-reading-${quesNum}"
                     id="ctl-${ctl.macautl}" 
                     autocomplete="off"
                     data-index="${index}"
                     data-macautl="${ctl.macautl}" 
                     value="${ctl.macautl}" ${isChecked ? "checked" : ""}>
              <label class="btn btn-light rounded-pill btn-answer fw-bold ${
                isChecked ? "btn-warning" : ""
              }"
                     style="min-width: 45px; text-align: center; cursor: pointer;"
                     for="ctl-${ctl.macautl}">
                ${String.fromCharCode(65 + i)}
              </label>`;
          });
        } else {
          groupHtml += `<span class="text-white small">Không có lựa chọn</span>`;
        }
        groupHtml += `</div></div>`; // end test-ans
        groupHtml += `</div>`; // end question-item
      }
      // === MCQ or ESSAY ===
      else {
        if (groupHtml) {
          html += `<div class="reading-group rounded border mb-3 bg-white p-3">${groupHtml}</div>`;
          groupHtml = "";
          currentContext = null;
        }

        html += `<div class="question rounded border mb-3 bg-white" id="c${
          index + 1
        }">
        <div class="question-top p-3">
          <p class="question-content fw-bold mb-2">${index + 1}. ${
          question.noidung
        }</p>
        </div>`;

        if (question.loai === "essay") {
          html += `<div class="test-ans bg-light text-dark p-4 rounded-bottom">
          <p class="mb-3 fw-bold fs-5">Đáp án của bạn:</p>
          <div id="${editorId}" class="mb-4">${userEssayText || ""}</div>
          <div class="mb-3">
            <label class="form-label">Hình ảnh (tùy chọn)</label>
            <input type="file" class="form-control" id="${fileInputId}" accept="image/*" multiple>
          </div>
          <div id="${thumbId}" class="image-preview-container border rounded p-3 bg-white">
            <small class="text-muted no-image-text">Chưa có ảnh nào được chọn</small>
          </div>
        </div>`;

          setTimeout(() => {
            const previewContainer = document.getElementById(thumbId);
            const fileInput = document.getElementById(fileInputId);
            if (window["ckeditor_" + editorId])
              window["ckeditor_" + editorId].destroy().catch(() => {});

            ClassicEditor.create(document.getElementById(editorId), {
              toolbar: [
                "heading",
                "|",
                "bold",
                "italic",
                "underline",
                "|",
                "bulletedList",
                "numberedList",
                "|",
                "insertTable",
                "blockQuote",
                "|",
                "undo",
                "redo",
              ],
              placeholder: "Nhập câu trả lời của bạn tại đây...",
            }).then((editor) => {
              window["ckeditor_" + editorId] = editor;
              editor.ui.view.editable.element.style.minHeight = "200px";
              if (userEssayText) editor.setData(userEssayText);

              const syncImages = () => {
                const temp = document.createElement("div");
                temp.innerHTML = editor.getData();
                const imgs = Array.from(temp.querySelectorAll("img")).map((i) =>
                  i.src ? i.src : ""
                );
                answers[index] = answers[index] || {};
                answers[index].images = imgs.filter((s) => s && s.trim());
                localStorage.setItem(answerKey, JSON.stringify(answers));
              };

              const renderImages = () => {
                const images = answers[index]?.images || [];
                previewContainer.innerHTML = "";
                if (images.length === 0) {
                  previewContainer.innerHTML = `<small class="text-muted no-image-text">Chưa có ảnh nào được chọn</small>`;
                  return;
                }
                images.forEach((src, i) => {
                  const wrapper = document.createElement("div");
                  wrapper.className =
                    "position-relative d-inline-block me-3 mb-3";
                  const img = document.createElement("img");
                  img.src = src;
                  img.className = "img-thumbnail rounded";
                  img.style.cssText =
                    "max-height:150px; max-width:200px; object-fit:cover; cursor:pointer;";
                  const btnDelete = document.createElement("button");
                  btnDelete.className =
                    "btn btn-danger btn-sm rounded-circle position-absolute";
                  btnDelete.style.cssText =
                    "top:5px; right:5px; width:28px; height:28px; font-size:16px; line-height:1;";
                  btnDelete.innerHTML = "×";
                  btnDelete.onclick = (e) => {
                    e.stopPropagation();
                    answers[index].images.splice(i, 1);
                    try {
                      editor.setData(editor.getData().split(src).join(""));
                    } catch {}
                    localStorage.setItem(answerKey, JSON.stringify(answers));
                    renderImages();
                  };
                  wrapper.appendChild(img);
                  wrapper.appendChild(btnDelete);
                  previewContainer.appendChild(wrapper);
                });
              };

              let debounceTimer;
              editor.model.document.on("change:data", () => {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                  answers[index] = answers[index] || {};
                  answers[index].noidungtl = editor.getData();
                  syncImages();
                  localStorage.setItem(answerKey, JSON.stringify(answers));
                  showBtnSideBar(questions, answers);
                  saveEssayAnswer(index, editor.getData());
                  renderImages();
                }, 350);
              });

              fileInput.addEventListener("change", function (e) {
                const files = Array.from(e.target.files);
                if (!files.length) return;
                answers[index] = answers[index] || {};
                answers[index].images = answers[index].images || [];
                let loadedCount = 0;
                const total = files.length;

                files.forEach((file) => {
                  if (file.size > 10 * 1024 * 1024) {
                    loadedCount++;
                    if (loadedCount === total) renderImages();
                    return;
                  }
                  const reader = new FileReader();
                  reader.onload = function (ev) {
                    answers[index].images.push(ev.target.result);
                    try {
                      editor.setData(
                        editor.getData() +
                          `<p><img src="${ev.target.result}" alt="image"></p>`
                      );
                    } catch {}
                    loadedCount++;
                    if (loadedCount === total) {
                      localStorage.setItem(answerKey, JSON.stringify(answers));
                      renderImages();
                    }
                  };
                  reader.readAsDataURL(file);
                });
                this.value = "";
              });

              syncImages();
              renderImages();
            });
          }, 50);
        } else {
          // MCQ non-reading
          html += `<div class="row">`;
          question.cautraloi.forEach((ctl, i) => {
            const content = ctl.noidungtl || "";
            html += `<div class="col-6 mb-2"><div><b>${String.fromCharCode(
              65 + i
            )}.</b> ${content}</div></div>`;
          });
          html += `</div>`;

          html += `<div class="test-ans bg-primary rounded-bottom py-2 px-3 d-flex align-items-center">
          <p class="mb-0 text-white me-4">Đáp án của bạn:</p>
          <div>`;
          question.cautraloi.forEach((ctl, i) => {
            const isChecked = String(userChosenId) === String(ctl.macautl);
            html += `<input type="radio" class="btn-check" name="options-c${
              index + 1
            }" id="ctl-${
              ctl.macautl
            }" autocomplete="off" data-index="${index}" data-macautl="${
              ctl.macautl
            }" value="${ctl.macautl}" ${isChecked ? "checked" : ""}>
          <label class="btn btn-light rounded-pill me-2 btn-answer ${
            isChecked ? "btn-warning" : ""
          }" for="ctl-${ctl.macautl}">${String.fromCharCode(65 + i)}</label>`;
          });
          html += `</div></div></div>`; // end question
        }
      }
    });

    // Đóng nhóm reading cuối
    if (groupHtml)
      html += `<div class="reading-group rounded border mb-3 bg-white p-3">${groupHtml}</div>`;

    $("#list-question").html(html);

    // === MCQ event (consolidated handler) ===
    $(".btn-check").off("change"); // Unbind any existing to avoid duplicates
    $(document)
      .off("change", ".btn-check")
      .on("change", ".btn-check", function () {
        const idx = $(this).data("index");
        const macautl = $(this).data("macautl");
        const quesIndex = idx + 1; // For toast
        const label = $("label[for='" + $(this).attr("id") + "']")
          .text()
          .trim();

        if (!answers[idx]) answers[idx] = {};
        answers[idx].cautraloi = macautl;
        localStorage.setItem(answerKey, JSON.stringify(answers));
        showBtnSideBar(questions, answers);
        // saveMCQAnswer(idx, macautl);

        // Update UI: Add warning class to selected, remove from siblings (works for both types now)
        $("label[for='" + $(this).attr("id") + "']")
          .addClass("btn-warning")
          .siblings("label")
          .removeClass("btn-warning")
          .css("background-color", ""); // Clear any lingering inline styles

        showAnswerToast(quesIndex, label);
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

  $(document).on("click", ".btn-answer", function () {
    const forId = $(this).attr("for");
    const $input = $("#" + forId);
    if ($input.length) {
      $input.prop("checked", true).trigger("change");
    }
  });

  function showAnswerToast(cau, dapan) {
    // Xoá toast cũ nếu tồn tại
    $("#answer-toast").remove();

    // HTML toast mới
    const toastHtml = `
    <div id="answer-toast" class="position-fixed top-0 start-50 translate-middle-x p-3" style="z-index: 9999; margin-top: 20px;">
      <div class="toast show align-items-center text-white bg-success border-0 shadow-lg" role="alert">
        <div class="d-flex">
          <div class="toast-body fw-bold fs-5 text-center">
           Bạn đã chọn: <span class="text-warning">Câu ${cau}</span> → Đáp án <span class="text-warning fs-4">${dapan}</span>
          </div>
          <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" onclick="$('#answer-toast').fadeOut(200, function(){$(this).remove()})"></button>
        </div>
      </div>
    </div>
  `;

    $("body").append(toastHtml);

    // Ẩn tự động sau 1.5 giây nếu chưa đóng
    setTimeout(() => {
      $("#answer-toast").fadeOut(200, function () {
        $(this).remove();
      });
    }, 1500);
  }
  // XÓA DỮ LIỆU
  function clearExamData() {
    localStorage.removeItem(answerKey);
    localStorage.removeItem(dethiKey);
    localStorage.removeItem("isTabSwitched_" + made);
  }

  $("#btn-nop-bai").click(function (e) {
    e.preventDefault();

    let listAns = JSON.parse(localStorage.getItem(answerKey));
    let unanswered = listAns.filter((ans, i) => {
      const q = questions[i];
      if (q.loai === "essay") {
        return !ans.noidungtl?.trim();
      }
      return ans.cautraloi === 0;
    });

    if (unanswered.length > 0) {
      Swal.fire({
        icon: "warning",
        title: "Chưa hoàn thành!",
        html: `<p class='fs-6 text-center mb-0'>Bạn chưa chọn đáp án cho <strong>${unanswered.length}</strong> câu hỏi.<br>Vui lòng hoàn thành tất cả trước khi nộp bài.</p>`,
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
    const dethiCheck = $("#dethicontent").data("id");
    const thoigian = new Date();

    const answers = JSON.parse(localStorage.getItem(answerKey) || "[]");

    $.ajax({
      type: "post",
      url: "./test/submit",
      data: {
        listCauTraLoi: answers,
        thoigianlambai: thoigian,
        made: dethiCheck,
      },
      success: function (response) {
        clearExamData();
        location.href = `./test/start/${dethiCheck}`;
      },
      error: function (xhr) {
        clearExamData();
        location.href = `./test/start/${dethiCheck}`;
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
        nopbai(() => {
          window.location.href = "./dashboard";
        });
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
    $.ajax({
      type: "post",
      url: "./test/chuyentab",
      data: { made: $("#dethicontent").data("id") },
      success: function (response) {
        if (response == 1) {
          nopbai(clearExamData);
        } else {
          localStorage.setItem("isTabSwitched_" + made, "1");
        }
      },
      error: function () {
        localStorage.setItem("isTabSwitched_" + made, "1");
      },
    });
  });

  //
  // Xóa dữ liệu khi rời khỏi trang
  window.addEventListener("beforeunload", function () {
    localStorage.removeItem(answerKey);
    localStorage.removeItem(dethiKey);
    localStorage.removeItem("isTabSwitched_" + made);
  });

  //chặn load trang
  $(document).on("keydown", function (e) {
    if (
      e.which === 116 || // F5
      ((e.ctrlKey || e.metaKey) && e.which === 82) // Ctrl+R / Cmd+R
    ) {
      e.preventDefault();

      Swal.fire({
        icon: "warning",
        title: "Không thể tải lại!",
        html: `<p class='fs-6 text-center mb-0'>Bạn không thể tải lại trang khi đang làm bài.</p>`,
        confirmButtonText: "OK",
      });
    }
  });

  history.pushState(null, null, location.href);
  window.onpopstate = function () {
    history.go(1);

    Swal.fire({
      icon: "warning",
      title: "Không thể quay lại!",
      html: `<p class='fs-6 text-center mb-0'>Bạn không thể quay lại trang trước khi hoàn thành bài thi.</p>`,
      confirmButtonText: "OK",
    });
  };
  window.addEventListener("beforeunload", function (e) {
    // Kiểm tra xem bài chưa hoàn thành
    const answers = JSON.parse(localStorage.getItem(answerKey) || "[]");
    const unanswered = answers.filter((ans, i) => {
      const q = questions[i];
      if (q.loai === "essay") return !ans.noidungtl?.trim();
      return ans.cautraloi === 0;
    });

    if (unanswered.length > 0) {
      Swal.fire({
        icon: "warning",
        title: "Bài chưa hoàn thành!",
        html: `<p class='fs-6 text-center mb-0'>Bạn chưa hoàn thành <strong>${unanswered.length}</strong> câu hỏi.<br>Vui lòng hoàn thành trước khi rời trang.</p>`,
        confirmButtonText: "OK",
      });

      e.preventDefault();
      e.returnValue = ""; // Chrome hiện cảnh báo mặc định
      return "";
    }
  });
});
