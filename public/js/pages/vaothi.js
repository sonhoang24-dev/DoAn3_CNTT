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
    if (!Array.isArray(questions)) {
      console.error("Invalid questions:", questions);
      $("#content-file").html(
        '<div class="alert alert-warning">Dữ liệu không hợp lệ.</div>'
      );
      return;
    }

    let html = "";
    let currentContext = null;
    let groupHtml = "";

    questions.forEach((item, index) => {
      item.cautraloi = Array.isArray(item.cautraloi) ? item.cautraloi : [];
      const dadung = item.cautraloi.find((op) => op.ladapan == 1) || null;
      const dapanchon = item.dapanchon || null;

      // ===========================================
      // =========   CÂU HỎI DẠNG READING   =========
      // ===========================================
      if (item.loai === "reading") {
        const contextRaw = (item.context || "").trim();
        const title = (item.tieude_context || "Không có tiêu đề").trim();
        const context = contextRaw;

        // Nếu đổi context → đóng nhóm cũ
        if (contextRaw !== (currentContext || "")) {
          if (groupHtml !== "") {
            html += `<div class="reading-group rounded border mb-3 bg-white p-3">${groupHtml}</div>`;
            groupHtml = "";
          }

          currentContext = contextRaw;

          if (title !== "") {
            groupHtml += `
                        <h5 class="fw-bold mb-3 text-center p-2 rounded"
                            style="background-color: #ffc107; color:#212529;">
                            ${title}
                        </h5>`;
          }

          groupHtml += `<p class="mb-4 text-muted" style="line-height:1.7;">${context}</p>`;
        }

        // =======================
        // CÂU HỎI READING
        // =======================
        const qNum = index + 1;

        groupHtml += `
                <div class="question-item mb-3 p-3 bg-light rounded">
                    <p class="fw-bold mb-2">${qNum}. ${item.noidung}</p>
                    <div class="row">`;

        item.cautraloi.forEach((op, i) => {
          groupHtml += `
                    <div class="col-6 mb-1">
                        <p><b>${String.fromCharCode(i + 65)}.</b> ${
            op.noidungtl
          }</p>
                    </div>`;
        });

        groupHtml += `
                </div>
                <div class="test-ans bg-primary rounded-bottom py-2 px-3 d-flex align-items-center mt-2">
                    <p class="mb-0 text-white me-3">Đáp án của bạn:</p>`;

        // ----------- HIỂN THỊ NÚT A/B/C/D ----------
        item.cautraloi.forEach((op, i) => {
          let cls = "";

          if (dapanchon == op.macautl) {
            cls = op.ladapan == 1 ? "btn-answer-true" : "btn-answer-false";
          }

          groupHtml += `
                    <button class="btn btn-light rounded-pill me-2 btn-answer-question ${cls}">
                        ${String.fromCharCode(i + 65)}
                    </button>`;
        });

        // ----------- CHECK ĐÚNG / SAI ----------
        if (dadung && dadung.macautl == dapanchon) {
          groupHtml += `<span class="h2 mb-0 ms-1"><i class="fa fa-check" style="color:#76BB68;"></i></span>`;
        } else {
          const correctIndex = dadung
            ? item.cautraloi.findIndex((op) => op.macautl == dadung.macautl)
            : -1;

          groupHtml += `
                    <span class="h2 mb-0 ms-1"><i class="fa fa-xmark" style="color:#FF5A5F;"></i></span>
                `;

          if (correctIndex >= 0) {
            groupHtml += `
                        <span class="mx-2 text-white">Đáp án đúng: ${String.fromCharCode(
                          correctIndex + 65
                        )}</span>`;
          }
        }

        groupHtml += `</div></div>`;
        return;
      }

      // ===========================================
      // =========   NORMAL QUESTION (MCQ)  =========
      // ===========================================
      if (groupHtml !== "") {
        html += `<div class="reading-group rounded border mb-3 bg-white p-3">${groupHtml}</div>`;
        groupHtml = "";
        currentContext = null;
      }

      html += `
            <div class="question rounded border mb-3">
                <div class="question-top p-3">
                    <p class="fw-bold mb-3">${index + 1}. ${item.noidung}</p>
                    <div class="row">`;

      item.cautraloi.forEach((op, i) => {
        html += `
                <div class="col-6 mb-1">
                    <p><b>${String.fromCharCode(i + 65)}.</b> ${
          op.noidungtl
        }</p>
                </div>`;
      });

      html += `
                </div>
            </div>
            <div class="test-ans bg-primary rounded-bottom py-2 px-3 d-flex align-items-center">
                <p class="mb-0 text-white me-3">Đáp án của bạn:</p>`;

      // ======= NÚT A/B/C/D =========
      item.cautraloi.forEach((op, i) => {
        let cls = "";

        if (dapanchon == op.macautl) {
          cls = op.ladapan == 1 ? "btn-answer-true" : "btn-answer-false";
        }

        html += `
                <button class="btn btn-light rounded-pill me-2 btn-answer-question ${cls}">
                    ${String.fromCharCode(i + 65)}
                </button>`;
      });

      // ======= HIỂN THỊ CHECK / XMARK =========
      if (dadung && dadung.macautl == dapanchon) {
        html += `<span class="h2 mb-0 ms-1"><i class="fa fa-check" style="color:#76BB68;"></i></span>`;
      } else {
        const correctIndex = dadung
          ? item.cautraloi.findIndex((op) => op.macautl == dadung.macautl)
          : -1;

        html += `<span class="h2 mb-0 ms-1"><i class="fa fa-xmark" style="color:#FF5A5F;"></i></span>`;

        if (correctIndex >= 0) {
          html += `<span class="mx-2 text-white">Đáp án đúng: ${String.fromCharCode(
            correctIndex + 65
          )}</span>`;
        }
      }

      html += `</div></div>`;
    });

    // ĐÓNG GROUP CUỐI
    if (groupHtml !== "") {
      html += `<div class="reading-group rounded border mb-3 bg-white p-3">${groupHtml}</div>`;
    }

    $("#content-file").html(html);
  }
});
