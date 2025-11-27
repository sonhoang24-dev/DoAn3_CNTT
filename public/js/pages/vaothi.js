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
      console.error("showTestDetail expected array, got:", questions);
      $("#content-file").html(
        '<div class="alert alert-warning">Dữ liệu không hợp lệ.</div>'
      );
      return;
    }

    let data = ``;
    questions.forEach((item, index) => {
      // Defensive: ensure answers array exists
      item.cautraloi = Array.isArray(item.cautraloi) ? item.cautraloi : [];
      let dadung = item.cautraloi.find((op) => op.ladapan == 1) || null;
      data += `<div class="question rounded border mb-3">
            <div class="question-top p-3">
                <p class="question-content fw-bold mb-3">${index + 1}. ${
        item.noidung
      } </p>
                <div class="row">`;
      item.cautraloi.forEach((op, i) => {
        data += `<div class="col-6 mb-1">
                <p class="mb-1"><b>${String.fromCharCode(i + 65)}.</b> ${
          op.noidungtl
        }</p></div>`;
      });
      data += `</div></div>`;
      data += `<div class="test-ans bg-primary rounded-bottom py-2 px-3 d-flex align-items-center"><p class="mb-0 text-white me-4">Đáp án của bạn:</p>`;
      item.cautraloi.forEach((op, i) => {
        let check =
          item.dapanchon == op.macautl
            ? op.ladapan == 1
              ? "btn-answer-true"
              : "btn-answer-false"
            : "";
        data += `<button class="btn btn-light rounded-pill me-2 btn-answer-question ${check}" for="option-c${index}_${i}">${String.fromCharCode(
          i + 65
        )}</button>`;
      });
      if (dadung != null && dadung.macautl == item.dapanchon) {
        data += `<span class="h2 mb-0 ms-1"><i class="fa fa-check" style="color:#76BB68;"></i></span>`;
      } else {
        // compute index safely
        let correctIndex = -1;
        if (dadung != null) {
          correctIndex = item.cautraloi.findIndex(
            (o) => o.macautl == dadung.macautl
          );
        }
        data += `<span class="h2 mb-0 ms-1"><i class="fa fa-xmark" style="color:#FF5A5F;"></i></span>`;
        if (correctIndex >= 0) {
          data += `<span class="mx-2 text-white">Đáp án đúng: ${String.fromCharCode(
            correctIndex + 65
          )}</span>`;
        }
      }
      data += `</div></div>`;
    });
    $("#content-file").html(data);
  }
});
