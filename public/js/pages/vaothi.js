$(document).ready(function () {
  $("#start-test").click(function (e) {
    let made = $(this).data("id");
    e.preventDefault();
    $.ajax({
      type: "post",
      url: "./test/startTest",
      data: {
        made: made,
      },
      dataType: "json",
      success: function (response) {
        if (response) {
          location.href = `./test/taketest/${made}`;
        } else {
          Dashmix.helpers("jq-notify", {
            type: "danger",
            icon: "fa fa-times me-1",
            message: "Có lỗi gì đó xảy ra!",
          });
        }
      },
    });
  });

  $(document).on("click", "#show-exam-detail", function () {
    $("#modal-show-test").modal("show");
    let makq = $(this).data("id");
    $.ajax({
      type: "post",
      url: "./test/getResultDetail",
      data: {
        makq: makq,
      },
      dataType: "json",
      success: function (response) {
        // The server returns { success: true, data: [...] }.
        // Accept either the wrapped form or a raw array for backward-compatibility.
        let questions = response;
        console.debug("getResultDetail raw response:", response);
        try {
          if (
            response &&
            typeof response === "object" &&
            response.hasOwnProperty("data")
          ) {
            questions = response.data;
          }
        } catch (err) {
          console.error(
            "Error while parsing getResultDetail response",
            err,
            response
          );
        }

        // If server returned an object (associative) instead of an array, coerce to array
        if (
          !Array.isArray(questions) &&
          questions &&
          typeof questions === "object"
        ) {
          questions = Object.values(questions);
        }

        if (!Array.isArray(questions)) {
          console.error(
            "getResultDetail returned unexpected payload, expected array of questions",
            response
          );
          $("#content-file").html(
            '<div class="alert alert-warning">Không có chi tiết bài làm để hiển thị.</div>'
          );
          return;
        }

        showTestDetail(questions);
      },
    });
  });
  function showTestDetail(questions) {
    let html = "";
    let lastContext = null;

    questions.forEach((item, index) => {
      let dadung = item.cautraloi
        ? item.cautraloi.find((op) => op.ladapan == 1)
        : null;
      let dapanchon = item.dapanchon || null;

      // Nếu là câu thuộc dạng 'reading' thì hiển thị đoạn văn/tiêu đề một lần
      if (
        item.loai === "reading" &&
        item.context &&
        item.context !== lastContext
      ) {
        html += `<div class="card mb-3">
            <div class="card-body bg-light">
              <h6 class="mb-2">${
                item.tieude_context ? item.tieude_context : "Đoạn văn"
              }</h6>
              <div class="small text-muted">${item.context}</div>
            </div>
          </div>`;
        lastContext = item.context;
      }

      // Câu hỏi
      html += `<div class="question rounded border mb-3">
        <div class="question-top p-3">
          <p class="fw-bold mb-3">${index + 1}. ${item.noidung}</p>`;

      // Nếu là câu tự luận → hiển thị câu trả lời và điểm giáo viên (nếu có)
      if (item.loai === "essay") {
        html += `<div class="mb-4">
            <strong class="text-success"><i class="fas fa-pen me-2"></i>Câu trả lời của thí sinh:</strong>
            <div class="bg-white p-3 rounded border mt-2 min-vh-20">
              ${
                item.noidung_tra_loi
                  ? item.noidung_tra_loi
                  : '<em class="text-muted">Chưa làm</em>'
              }
            </div>
          </div>`;

        // Hiển thị điểm giáo viên cho câu này (nếu đã chấm)
        const diemCau =
          item.diem_cham_tuluan !== null && item.diem_cham_tuluan !== undefined
            ? parseFloat(item.diem_cham_tuluan).toFixed(2)
            : null;

        html += `<div class="mt-2 mb-3">
            <span class="fw-bold">Điểm giáo viên:</span>
            <span class="ms-2">${
              diemCau !== null
                ? diemCau + " điểm"
                : '<em class="text-muted">Chưa chấm</em>'
            }</span>
          </div>`;

        html += `</div>`; // đóng question-top
        html += `</div>`; // đóng question
      } else {
        // Nếu có student essay data for a reading subquestion, prefer showing it
        if (
          (item.noidung_tra_loi !== undefined &&
            item.noidung_tra_loi !== null) ||
          (item.diem_cham_tuluan !== undefined &&
            item.diem_cham_tuluan !== null)
        ) {
          html += `<div class="mb-4">
              <strong class="text-success"><i class="fas fa-pen me-2"></i>Câu trả lời của thí sinh:</strong>
              <div class="bg-white p-3 rounded border mt-2 min-vh-20">
                ${
                  item.noidung_tra_loi
                    ? item.noidung_tra_loi
                    : '<em class="text-muted">Chưa làm</em>'
                }
              </div>
            </div>`;

          const diemCau =
            item.diem_cham_tuluan !== null &&
            item.diem_cham_tuluan !== undefined
              ? parseFloat(item.diem_cham_tuluan).toFixed(2)
              : null;
          html += `<div class="mt-2 mb-3">
              <span class="fw-bold">Điểm giáo viên:</span>
              <span class="ms-2">${
                diemCau !== null
                  ? diemCau + " điểm"
                  : '<em class="text-muted">Chưa chấm</em>'
              }</span>
            </div>`;

          html += `</div>`; // đóng question-top
          html += `</div>`; // đóng question
        } else {
          html += `<div class="row">`;

          // Hiển thị đáp án (MCQ)
          if (item.cautraloi && Array.isArray(item.cautraloi)) {
            item.cautraloi.forEach((op, i) => {
              let label = String.fromCharCode(65 + i);
              let cls = "";

              // Highlight đáp án đúng/sai
              if (op.ladapan == 1) cls = "text-success fw-bold";
              if (dapanchon == op.macautl) {
                cls =
                  op.ladapan == 1
                    ? "bg-success text-white fw-bold"
                    : "bg-danger text-white fw-bold";
              }

              // Nếu có hình ảnh BLOB (đã chuyển Base64), hiển thị ảnh
              let content = "";
              if (op.hinhanh && op.hinhanh.trim() !== "") {
                content = `<img src="${op.hinhanh}" alt="Hình ảnh đáp án" class="img-fluid">`;
              } else {
                content = op.noidungtl;
              }

              html += `<div class="col-6 mb-1">
                <p class="${cls}"><b>${label}.</b> ${content}</p>
              </div>`;
            });
          }

          html += `</div></div>`; // đóng question-top

          // Phần kết quả cho MCQ
          html += `<div class="test-ans bg-primary rounded-bottom py-2 px-3 d-flex align-items-center">
            <p class="mb-0 text-white me-4">Đáp án của bạn:</p>`;

          if (dapanchon === null) {
            html += `<span class="text-white">Chưa làm</span>`;
          } else if (dadung && dadung.macautl == dapanchon) {
            html += `<span class="h2 mb-0 ms-1">
                     <i class="fa fa-check" style="color:#76BB68;"></i>
                   </span>`;
          } else if (dadung) {
            html += `<span class="h2 mb-0 ms-1">
                     <i class="fa fa-xmark" style="color:#FF5A5F;"></i>
                   </span>
                   <span class="mx-2 text-white">
                     Đáp án đúng: ${String.fromCharCode(
                       item.cautraloi.indexOf(dadung) + 65
                     )}
                   </span>`;
          }

          html += `</div></div>`; // đóng test-ans và question
        }
      }
    });

    // ĐÓNG GROUP CUỐI
    if (lastContext !== null && lastContext !== "") {
      // no-op: group output already appended when context changed in loop above
    }

    $("#content-file").html(html);
  }
});
