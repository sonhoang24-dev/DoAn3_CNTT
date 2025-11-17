Dashmix.helpersOnLoad(["jq-select2"]);

// Destroy CKEditor instances if they exist
if (CKEDITOR.instances["js-ckeditor"]) {
  CKEDITOR.instances["js-ckeditor"].destroy(true);
}
if (CKEDITOR.instances["option-content"]) {
  CKEDITOR.instances["option-content"].destroy(true);
}
if (CKEDITOR.instances["passage-content"]) {
  CKEDITOR.instances["passage-content"].destroy(true);
}
if (CKEDITOR.instances["reading-question-content"]) {
  CKEDITOR.instances["reading-question-content"].destroy(true);
}

// Initialize CKEditor
CKEDITOR.replace("js-ckeditor", {
  entities: false,
  basicEntities: false,
  enterMode: CKEDITOR.ENTER_DIV,
});
CKEDITOR.replace("option-content", {
  entities: false,
  basicEntities: false,
});
CKEDITOR.replace("passage-content", {
  entities: false,
  basicEntities: false,
});
CKEDITOR.replace("reading-question-content", {
  entities: false,
  basicEntities: false,
});

Dashmix.onLoad(() =>
  class {
    static initValidation() {
      Dashmix.helpers("jq-validation"),
        jQuery("#form_add_question").validate({
          rules: {
            "mon-hoc": {
              required: true,
            },
            chuong: {
              required: true,
            },
            dokho: {
              required: true,
            },
            "js-ckeditor": {
              required: function () {
                return $("#loai-cau-hoi").val() !== "reading";
              },
            },
            "passage-content": {
              required: function () {
                return $("#loai-cau-hoi").val() === "reading";
              },
            },
          },
          messages: {
            "mon-hoc": {
              required: "Vui lòng chọn môn học",
            },
            chuong: {
              required: "Vui lòng chọn chương.",
            },
            dokho: {
              required: "Vui lòng chọn mức độ.",
            },
            "js-ckeditor": {
              required: "Vui lòng không để trống câu hỏi.",
            },
            "passage-content": {
              required: "Vui lòng nhập đoạn ngữ liệu.",
            },
          },
          errorClass: "is-invalid",
          validClass: "is-valid",
        });
    }
    static init() {
      this.initValidation();
    }
  }.init()
);

function showData(data) {
  let html = "";
  data.forEach((question) => {
    let dokho = "";
    switch (String(question["dokho"])) {
      case "1":
        dokho = "Cơ bản";
        break;
      case "2":
        dokho = "Trung bình";
        break;
      case "3":
        dokho = "Nâng cao";
        break;
      default:
        dokho = "Không xác định";
    }

    // Strip HTML và rút gọn nội dung hiển thị
    let rawText = $("<div>").html(question["noidung"]).text();
    rawText = rawText.replace(/\s+/g, " ").trim();
    let shortText =
      rawText.length > 180 ? rawText.substring(0, 180) + "......" : rawText;

    // Badge loại câu hỏi + số câu con (nếu là reading)
    let badge = "";
    if (question["loai"] === "reading") {
      let numSub = question["num_subquestions"] || 0;
      badge = `<span class="badge bg-primary badge ms-2">Đoạn văn · ${numSub} câu</span>`;
    } else if (question["loai"] === "essay") {
      badge = `<span class="badge bg-warning ms-2">Tự luận</span>`;
    } else {
      badge = `<span class="badge bg-success ms-2">Trắc nghiệm</span>`;
    }

    html += `
      <tr>
        <td class="text-center fs-sm">
          <a class="fw-semibold" href="#">
            <strong>${question["macauhoi"] ?? ""}</strong>
          </a>
        </td>
        <td class="fs-sm">
          <div class="d-flex align-items-start gap-2">
            <div>${shortText}</div>
            ${badge}
          </div>
        </td>
        <td class="d-none d-xl-table-cell fs-sm">
          <a class="fw-semibold">${question["tenmonhoc"] ?? ""}</a>
        </td>
        <td class="d-none d-sm-table-cell fs-sm">
          <strong>${dokho}</strong>
        </td>
        <td class="text-center col-action">
          <a data-role="cauhoi" data-action="update"
             class="btn btn-sm btn-alt-secondary btn-edit-question"
             data-id="${question["macauhoi"]}">
            <i class="fa fa-fw fa-pencil"></i>
          </a>
          <a data-role="cauhoi" data-action="delete"
             class="btn btn-sm btn-alt-secondary btn-delete-question"
             data-id="${question["macauhoi"]}">
            <i class="fa fa-fw fa-times"></i>
          </a>
        </td>
      </tr>
    `;
  });

  $("#listQuestion").html(html);
  $('[data-bs-toggle="tooltip"]').tooltip();
}

$(document).ready(function () {
  let options = []; // For MCQ and Reading question options
  let readingQuestions = []; // For Reading type questions

  // Initialize Select2
  $(".js-select2").select2();
  $("#main-page-loai").select2();
  $("#loai-cau-hoi").select2({
    dropdownParent: $("#modal-add-question"),
  });
  $("#mon-hoc").select2({
    dropdownParent: $("#modal-add-question"),
  });
  $("#chuong").select2({
    dropdownParent: $("#modal-add-question"),
  });
  $("#dokho").select2({
    dropdownParent: $("#modal-add-question"),
  });
  $("#monhocfile").select2({
    dropdownParent: $("#modal-add-question"),
  });
  $("#chuongfile").select2({
    dropdownParent: $("#modal-add-question"),
  });

  // Toggle option form buttons
  $("[data-bs-target='#add_option']").on("click", function () {
    $("#update-option").hide();
    $("#save-option").show();
  });

  // Toggle form sections based on question type
  function toggleQuestionType(type) {
    // Ẩn tất cả trước
    $("#mcq-options-area").hide();
    $("#add_option").collapse("hide");

    $("#essay-area").hide();
    $("#passage-area").hide();
    $("#reading-questions-area").hide();
    $("#question-content-area").show();

    if (type === "mcq") {
      $("#mcq-options-area").show();
    } else if (type === "essay") {
      $("#mcq-options-area").hide();
      $("#essay-area").show();
    } else if (type === "reading") {
      $("#mcq-options-area").hide();
      $("#list-options").html("");
      options = [];

      $("#passage-area").show();
      $("#reading-questions-area").show();
      $("#question-content-area").hide();
    }
  }

  // Initial toggle
  toggleQuestionType($("#loai-cau-hoi").val());

  // Handle question type change
  $("#loai-cau-hoi").on("change", function () {
    toggleQuestionType($(this).val());
  });

  // Save MCQ option
  $("#save-option").click(function (e) {
    e.preventDefault();
    let content_option = CKEDITOR.instances["option-content"].getData();
    let true_option = $("#true-option").prop("checked");
    let option = {
      content: content_option,
      check: true_option,
    };
    options.push(option);
    $("#add_option").collapse("hide");
    resetOptionForm();
    showOptions(options);
  });

  // Update MCQ option
  $("#update-option").click(function (e) {
    e.preventDefault();
    let index = $(this).data("id");
    options[index].content = CKEDITOR.instances["option-content"].getData();
    options[index].check = $("#true-option").prop("checked");
    showOptions(options);
    resetOptionForm();
    $("#add_option").collapse("hide");
  });

  // Show MCQ options
  function showOptions(options) {
    let data = "";
    options.forEach((item, index) => {
      data += `<tr>
        <th class="text-center" scope="row">${index + 1}</th>
        <td>${item.content}</td>
        <td class="text-center">
            <div class="form-check">
                <input class="form-check-input" type="radio" name="da-dung" data-id="${index}" id="da-${index}" ${
        item.check ? "checked" : ""
      }>
                <label class="form-check-label" for="da-${index}">Đáp án đúng</label>
            </div>
        </td>
        <td class="text-center">
            <button type="button" class="btn btn-sm btn-alt-secondary btn-edit-option" data-bs-toggle="tooltip" title="Edit" data-id="${index}">
                <i class="fa fa-pencil-alt"></i>
            </button>
            <button type="button" class="btn btn-sm btn-alt-secondary btn-delete-option" data-bs-toggle="tooltip" title="Delete" data-id="${index}">
                <i class="fa fa-times"></i>
            </button>
        </td>
    </tr>`;
    });
    $("#list-options").html(data);
  }

  // Reset MCQ option form
  function resetOptionForm() {
    CKEDITOR.instances["option-content"].setData("");
    $("#true-option").prop("checked", false);
  }

  // Edit MCQ option
  $(document).on("click", ".btn-edit-option", function () {
    let index = $(this).data("id");
    $("#update-option").show();
    $("#save-option").hide();
    $("#update-option").data("id", index);
    $("#add_option").collapse("show");
    CKEDITOR.instances["option-content"].setData(options[index].content);
    $("#true-option").prop("checked", options[index].check);
  });

  // Delete MCQ option
  $(document).on("click", ".btn-delete-option", function () {
    let index = $(this).data("id");
    let e = Swal.mixin({
      buttonsStyling: false,
      target: "#page-container",
      customClass: {
        confirmButton: "btn btn-success m-1",
        cancelButton: "btn btn-danger m-1",
        input: "form-control",
      },
    });

    e.fire({
      title: "Are you sure?",
      text: "Bạn có chắc chắn muốn xoá câu trả lời?",
      icon: "warning",
      showCancelButton: true,
      customClass: {
        confirmButton: "btn btn-danger m-1",
        cancelButton: "btn btn-secondary m-1",
      },
      confirmButtonText: "Vâng, tôi chắc chắn!",
      html: false,
      preConfirm: () =>
        new Promise((resolve) => {
          setTimeout(() => {
            resolve();
          }, 50);
        }),
    }).then((result) => {
      if (result.value) {
        e.fire("Deleted!", "Xóa câu trả lời thành công!", "success");
        options.splice(index, 1);
        showOptions(options);
      }
    });
  });

  // Update correct answer for MCQ
  $(document).on("change", "[name='da-dung']", function () {
    let index = $(this).data("id");
    options.forEach((item) => {
      item.check = false;
    });
    options[index].check = true;
  });

  // Add Reading Option
  $("#add-reading-option").on("click", function () {
    let optionHtml = `
<div class="mb-2 reading-option-item d-flex align-items-start gap-2">
  <div class="form-check mt-2">
      <input type="radio" name="reading-correct" class="form-check-input">
      <label class="form-check-label">Đáp án đúng</label>
  </div>
  <textarea class="form-control reading-option-content" rows="2" placeholder="Nhập nội dung đáp án"></textarea>
  <button type="button" class="btn btn-sm btn-danger btn-remove-reading-option">
      <i class="fa fa-times"></i>
  </button>
</div>`;
    $("#reading-question-options").append(optionHtml);
  });

  // Remove Reading Option
  $(document).on("click", ".btn-remove-reading-option", function () {
    $(this).closest(".reading-option-item").remove();
  });

  // Save Reading Question
  $("#save-reading-question").on("click", function () {
    let questionContent =
      CKEDITOR.instances["reading-question-content"].getData();
    let options = [];

    $(".reading-option-item").each(function () {
      let content = $(this).find(".reading-option-content").val();
      let check = $(this).find("input[type=radio]").prop("checked");
      if (content) options.push({ content: content, check: check });
    });

    if (!questionContent || options.length === 0) {
      Dashmix.helpers("jq-notify", {
        type: "danger",
        icon: "fa fa-times me-1",
        message: "Vui lòng nhập đoạn ngữ liệu và ít nhất một câu hỏi.",
      });
      return;
    }

    readingQuestions.push({
      content: questionContent,
      options: options,
    });

    showReadingQuestions();
    resetReadingQuestionForm();
    $("#add_reading_question").collapse("hide");
  });

  // Show Reading Questions List
  function showReadingQuestions() {
    let html = "";

    readingQuestions.forEach((question, index) => {
      // --- Dòng câu hỏi ---
      html += `
      <tr class="question-row">
        <td class="text-center align-top">${index + 1}</td>
        <td class="align-top"><strong>${question.content}</strong></td>
        <td></td>
        <td></td>
        <td class="text-center align-top">
          <div class="btn-group">
            <button class="btn btn-sm btn-alt-secondary btn-edit-reading-question"
                    data-id="${index}" title="Chỉnh sửa">
                <i class="fa fa-pencil-alt"></i>
            </button>
            <button class="btn btn-sm btn-alt-secondary btn-delete-reading-question"
                    data-id="${index}" title="Xóa">
                <i class="fa fa-times"></i>
            </button>
          </div>
        </td>
      </tr>
    `;

      // --- Các phương án ---
      let correctRendered = false; // Đảm bảo chỉ 1 đáp án đúng được tô
      question.options.forEach((opt, i) => {
        let isCorrect = "";
        let checkedAttr = "";

        if (opt.check && !correctRendered) {
          isCorrect = "correct-answer";
          checkedAttr = "checked";
          correctRendered = true;
        }

        // Tạo chữ A, B, C,... cho từng option
        const optionLetter = String.fromCharCode(65 + i);

        html += `
        <tr class="option-row">
          <td></td>
          <td></td>
          <td class="ps-4">${optionLetter}. ${opt.content}</td>
          <td class="text-center">
            <input type="radio" name="reading-da-${index}"
                   class="correct-radio ${isCorrect}"
                   ${checkedAttr} disabled>
          </td>
          <td></td>
        </tr>
      `;
      });
    });

    $("#reading-questions-list").html(html);
  }

  // Reset Reading Question Form
  function resetReadingQuestionForm() {
    CKEDITOR.instances["reading-question-content"].setData("");
    $("#reading-question-options").empty();
  }

  // Edit Reading Question
  $(document).on("click", ".btn-edit-reading-question", function () {
    let index = $(this).data("id");
    let q = readingQuestions[index];

    CKEDITOR.instances["reading-question-content"].setData(q.content);
    $("#reading-question-options").empty();

    q.options.forEach((opt) => {
      let optionHtml = `
<div class="mb-2 reading-option-item d-flex align-items-start gap-2">
  <div class="form-check mt-2">
    <input type="radio" name="reading-correct" class="form-check-input" ${
      opt.check ? "checked" : ""
    }>
    <label class="form-check-label">Đáp án đúng</label>
  </div>
  <textarea class="form-control reading-option-content" rows="2">${
    opt.content
  }</textarea>
  <button type="button" class="btn btn-sm btn-danger btn-remove-reading-option mt-1">Xóa</button>
</div>`;
      $("#reading-question-options").append(optionHtml);
    });

    $("#add_reading_question").collapse("show");
    readingQuestions.splice(index, 1); // Xóa cũ để update
    showReadingQuestions();
  });

  // Delete Reading Question
  $(document).on("click", ".btn-delete-reading-question", function () {
    let index = $(this).data("id");
    Swal.fire({
      title: "Are you sure?",
      text: "Bạn có chắc chắn muốn xoá câu hỏi này?",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: "Vâng, tôi chắc chắn!",
      cancelButtonText: "Huỷ",
    }).then((result) => {
      if (result.isConfirmed) {
        readingQuestions.splice(index, 1);
        showReadingQuestions();
        Swal.fire("Deleted!", "Xóa câu hỏi thành công!", "success");
      }
    });
  });

  // Load subjects
  $.get(
    "./subject/getSubjectAssignment",
    function (data) {
      let html = "<option></option>";
      data.forEach((item) => {
        html += `<option value="${item.mamonhoc}">${item.tenmonhoc}</option>`;
      });
      $(".data-monhoc").html(html);
      $("#main-page-monhoc").html(html);
    },
    "json"
  );

  // Load chapters
  $(".data-monhoc").on("change", function () {
    let selectedValue = $(this).val();
    let id = $(this).data("tab");
    let html = "<option></option>";
    $.ajax({
      type: "post",
      url: "./subject/getAllChapter",
      data: {
        mamonhoc: selectedValue,
      },
      dataType: "json",
      success: function (data) {
        data.forEach((item) => {
          html += `<option value="${item.machuong}">${item.tenchuong}</option>`;
        });
        $(`.data-chuong[data-tab="${id}"]`).html(html);
      },
    });
  });

  // Filter by subject
  $("#main-page-monhoc").on("change", function () {
    let mamonhoc = $(this).val();
    let id = $(this).data("tab");
    let html = "<option></option>";
    $.ajax({
      type: "post",
      url: "./subject/getAllChapter",
      data: {
        mamonhoc: mamonhoc,
      },
      dataType: "json",
      success: function (data) {
        data.forEach((item) => {
          html += `<option value="${item.machuong}">${item.tenchuong}</option>`;
        });
        $(`#main-page-chuong[data-tab="${id}"]`).html(html);
      },
    });

    $("#main-page-dokho").val(0).trigger("change");
    mainPagePagination.option.filter = {};
    mainPagePagination.option.filter.mamonhoc = mamonhoc;
    mainPagePagination.getPagination(
      mainPagePagination.option,
      mainPagePagination.valuePage.curPage
    );
  });

  // Filter by chapter
  $("#main-page-chuong").on("change", function () {
    const machuong = $(this).val();
    mainPagePagination.option.filter.machuong = machuong;
    mainPagePagination.getPagination(
      mainPagePagination.option,
      mainPagePagination.valuePage.curPage
    );
  });

  // Filter by difficulty
  $("#main-page-dokho").on("change", function () {
    const dokho = +$(this).val();
    mainPagePagination.option.filter.dokho = dokho;
    mainPagePagination.getPagination(
      mainPagePagination.option,
      mainPagePagination.valuePage.curPage
    );
  });

  // Filter by question type
  $("#main-page-loai").on("change", function () {
    const loai = $(this).val();
    // store as 0/'0' or empty means all
    mainPagePagination.option.filter.loai = loai && loai !== "0" ? loai : "";
    mainPagePagination.getPagination(
      mainPagePagination.option,
      mainPagePagination.valuePage.curPage
    );
  });

  // Handle file upload
  $("#file-cau-hoi").change(function (e) {
    e.preventDefault();
    var file = $("#file-cau-hoi")[0].files[0];
    var formData = new FormData();
    formData.append("fileToUpload", file);
    $.ajax({
      type: "post",
      url: "./question/xulyDocx",
      data: formData,
      contentType: false,
      processData: false,
      dataType: "json",
      beforeSend: function () {
        Dashmix.layout("header_loader_on");
      },
      success: function (response) {
        console.log(response);
        questions = response;
        loadDataQuestion(response);
      },
      complete: function () {
        Dashmix.layout("header_loader_off");
      },
    });
  });

  // Add questions from file
  $("#nhap-file").click(function () {
    $.ajax({
      type: "post",
      url: "./question/addQuesFile",
      data: {
        monhoc: $("#monhocfile").val(),
        chuong: $("#chuongfile").val(),
        questions: questions,
      },
      success: function (response) {
        $("#modal-add-question").modal("hide");
        loadQuestion();
        mainPagePagination.getPagination(
          mainPagePagination.option,
          mainPagePagination.valuePage.curPage
        );
        setTimeout(function () {
          Dashmix.helpers("jq-notify", {
            type: "success",
            icon: "fa fa-check me-1",
            message: "Thêm câu hỏi từ file thành công!",
          });
        }, 10);
      },
    });
  });

  // Load questions
  function loadQuestion() {
    $.get(
      "./question/getQuestion",
      {
        page: page,
        selected: $(".btn-filter").text(),
        content: $("#search-input").val().trim(),
      },
      function (data) {
        showData(data);
      },
      "json"
    );
  }
  // Add question
  $("#add_question").click(function (e) {
    e.preventDefault();

    let qtype = $("#loai-cau-hoi").val();

    // Lấy dữ liệu từ CKEditor, kiểm tra instance tồn tại
    let noidung = (CKEDITOR.instances["js-ckeditor"]?.getData() ?? "").trim();
    let passage = (
      CKEDITOR.instances["passage-content"]?.getData() ?? ""
    ).trim();
    let passageTitle = $("#passage-title")?.val() || "";
    let cautraloi = [];

    if (qtype === "reading") {
      if (!passage || readingQuestions.length === 0) {
        Dashmix.helpers("jq-notify", {
          type: "danger",
          message: "Vui lòng nhập đoạn văn và ít nhất một câu hỏi!",
        });
        return;
      }

      // Kiểm tra mỗi câu hỏi con có ít nhất 1 đáp án đúng
      let hasValidQuestions = readingQuestions.every(
        (q) =>
          q.content.trim() &&
          q.options.length > 0 &&
          q.options.some((opt) => opt.check)
      );
      if (!hasValidQuestions) {
        Dashmix.helpers("jq-notify", {
          type: "danger",
          message:
            "Mỗi câu hỏi con phải có nội dung và ít nhất một đáp án đúng!",
        });
        return;
      }

      // Reading question không dùng trường noidung chính
      noidung = "";
      cautraloi = readingQuestions;
    } else if (qtype === "mcq") {
      if (
        !noidung ||
        options.length === 0 ||
        !options.some((opt) => opt.check)
      ) {
        Dashmix.helpers("jq-notify", {
          type: "danger",
          message: "Vui lòng nhập câu hỏi và chọn ít nhất một đáp án đúng!",
        });
        return;
      }
      cautraloi = options;
    } else if (qtype === "essay") {
      if (!noidung) {
        Dashmix.helpers("jq-notify", {
          type: "danger",
          message: "Vui lòng nhập nội dung câu hỏi!",
        });
        return;
      }
      let essayContent = $("#essay-answer").val() || "";
      cautraloi = [{ content: essayContent, check: false }];
    }

    // Dữ liệu gửi lên server
    let dataPost = {
      mamon: $("#mon-hoc").val(),
      machuong: $("#chuong").val(),
      dokho: $("#dokho").val(),
      noidung: noidung,
      loai: qtype,
      cautraloi: JSON.stringify(cautraloi),
    };

    if (qtype === "reading") {
      dataPost.doanvan_noidung = passage;
      dataPost.doanvan_tieude = passageTitle;
    }

    $.post("./question/addQues", dataPost, function (res) {
      let response = typeof res === "string" ? JSON.parse(res) : res;
      if (response.status === "success") {
        Dashmix.helpers("jq-notify", {
          type: "success",
          message: "Tạo câu hỏi thành công!",
        });
        $("#modal-add-question").modal("hide");
        loadQuestion();
        reloadQuestionList();
      } else {
        Dashmix.helpers("jq-notify", {
          type: "danger",
          message: response.message,
        });
      }
    });
  });

  // Check if at least one correct answer exists
  function checkSOption(data) {
    if (!Array.isArray(data)) return false;
    return data.some((item) =>
      Array.isArray(item.options)
        ? item.options.some((opt) => opt.check)
        : item.check
    );
  }
  $(document).on("click", ".btn-edit-question", function () {
    $("#add_question").hide();
    $("#edit_question").show();
    let id = $(this).data("id");
    $("#question_id").val(id);
    getQuestionById(id);
    $("#modal-add-question").modal("show");
  });

  // Reset form for new question
  $("#addquestionnew").click(function () {
    $("#add_question").show();
    $("#edit_question").hide();
    $("#mon-hoc").val("").trigger("change");
    $("#chuong").val("").trigger("change");
    $("#dokho").val("").trigger("change");
    $("#monhocfile").val("").trigger("change");
    $("#chuongfile").val("").trigger("change");
    CKEDITOR.instances["js-ckeditor"].setData("");
    CKEDITOR.instances["passage-content"].setData("");
    options = [];
    readingQuestions = [];
    $("#add_option").collapse("hide");
    $("#add_reading_question").collapse("hide");
    $("#list-options").html("");
    $("#reading-questions-list").html("");
    $("#loai-cau-hoi").val("mcq").trigger("change");
    $("#essay-answer").val("");
    $("#file-cau-hoi").val(null);
    $("#btabs-alt-static-home-tab").tab("show");
    $("#content-file").html("");
  });

  // Edit question
  $("#edit_question").click(function () {
    let mamonhoc = $("#mon-hoc").val();
    let machuong = $("#chuong").val();
    let dokho = $("#dokho").val();
    let noidung = CKEDITOR.instances["js-ckeditor"].getData();
    let passage = CKEDITOR.instances["passage-content"].getData();
    let qtype = $("#loai-cau-hoi").val();
    let cautraloi = [];
    let id = $("#question_id").val();
    // Nếu có tiêu đề đoạn văn
    // let tieudeDV = $("#doanvan-tieude").val() || "";

    if (qtype === "essay") {
      let essayContent = $("#essay-answer").val() || "";
      cautraloi = [{ content: essayContent, check: false }];
    } else if (qtype === "reading") {
      if (!passage || readingQuestions.length === 0) {
        Dashmix.helpers("jq-notify", {
          type: "danger",
          icon: "fa fa-times me-1",
          message: "Vui lòng nhập đoạn ngữ liệu và ít nhất một câu hỏi!",
        });
        return;
      }
      noidung = passage;
      cautraloi = readingQuestions;
    } else {
      cautraloi = options;
    }

    if (
      mamonhoc &&
      machuong &&
      dokho &&
      (qtype === "reading" ? passage : noidung) &&
      (qtype === "essay" || (cautraloi.length > 0 && checkSOption(cautraloi)))
    ) {
      $.ajax({
        type: "post",
        url: "./question/editQuesion",
        data: {
          id: id,
          mamon: mamonhoc,
          machuong: machuong,
          dokho: dokho,
          noidung: noidung,
          loai: qtype,
          cautraloi: JSON.stringify(cautraloi),
          // doanvan_tieude: tieudeDV // Thêm nếu dùng tieude
        },
        dataType: "json",
        success: function (response) {
          if (response.status === "success") {
            Dashmix.helpers("jq-notify", {
              type: "success",
              icon: "fa fa-check me-1",
              message: response.message || "Sửa câu hỏi thành công!",
            });
            $("#modal-add-question").modal("hide");
            loadQuestion();
            reloadQuestionList();
            mainPagePagination.getPagination(
              mainPagePagination.option,
              mainPagePagination.valuePage.curPage
            );
          } else {
            Dashmix.helpers("jq-notify", {
              type: "danger",
              icon: "fa fa-times me-1",
              message: response.message || "Lỗi khi sửa câu hỏi",
            });
          }
        },
        error: function (err) {
          console.error("Error in editQuesion:", err.responseText);
          Dashmix.helpers("jq-notify", {
            type: "danger",
            icon: "fa fa-times me-1",
            message: "Lỗi kết nối đến server",
          });
        },
      });
    } else {
      // Giữ logic validate như cũ
      if (!mamonhoc) {
        Dashmix.helpers("jq-notify", {
          type: "error",
          icon: "fa fa-times me-1",
          message: "Vui lòng chọn mã môn học",
        });
        $("#mon-hoc").focus();
      } else if (!machuong) {
        Dashmix.helpers("jq-notify", {
          type: "error",
          icon: "fa fa-times me-1",
          message: "Vui lòng chọn mã chương",
        });
        $("#chuong").focus();
      } else if (!dokho) {
        Dashmix.helpers("jq-notify", {
          type: "error",
          icon: "fa fa-times me-1",
          message: "Vui lòng chọn độ khó",
        });
        $("#dokho").focus();
      } else if (!noidung && qtype !== "reading") {
        Dashmix.helpers("jq-notify", {
          type: "error",
          icon: "fa fa-times me-1",
          message: "Vui lòng nhập nội dung",
        });
        CKEDITOR.instances["js-ckeditor"].focus();
      } else if (!passage && qtype === "reading") {
        Dashmix.helpers("jq-notify", {
          type: "error",
          icon: "fa fa-times me-1",
          message: "Vui lòng nhập đoạn ngữ liệu",
        });
        CKEDITOR.instances["passage-content"].focus();
      } else if (cautraloi.length < 1 && qtype !== "essay") {
        Dashmix.helpers("jq-notify", {
          type: "error",
          icon: "fa fa-times me-1",
          message: "Vui lòng thêm câu trả lời",
        });
      } else if (!checkSOption(cautraloi) && qtype !== "essay") {
        Dashmix.helpers("jq-notify", {
          type: "error",
          icon: "fa fa-times me-1",
          message: "Vui lòng chọn đáp án đúng",
        });
      }
    }
  });

  // Get question by ID
  function getQuestionById(id) {
    $.ajax({
      type: "post",
      url: "./question/getQuestionById",
      data: {
        id: id,
      },
      dataType: "json",
      success: function (response) {
        let data = response;
        let monhoc = data["mamonhoc"];
        let machuong = data["machuong"];
        let dokho = data["dokho"];
        let noidung = data["noidung"];
        let loai = data["loai"];
        $("#mon-hoc").val(monhoc).trigger("change");
        $("#dokho").val(dokho).trigger("change");
        $("#loai-cau-hoi").val(loai).trigger("change");
        if (loai === "reading") {
          CKEDITOR.instances["passage-content"].setData(noidung);
          CKEDITOR.instances["js-ckeditor"].setData("");
          // Nếu có tiêu đề đoạn văn
          // $("#doanvan-tieude").val(data["tieude"] || "");
        } else {
          CKEDITOR.instances["js-ckeditor"].setData(noidung);
          CKEDITOR.instances["passage-content"].setData("");
        }
        setTimeout(function () {
          $("#chuong").val(machuong).trigger("change");
        }, 100);
      },
      error: function (err) {
        console.error("Error in getQuestionById:", err.responseText);
        Dashmix.helpers("jq-notify", {
          type: "danger",
          message: "Lỗi khi lấy dữ liệu câu hỏi",
        });
      },
    });

    $.ajax({
      type: "post",
      url: "./question/getAnswerById",
      data: {
        id: id,
      },
      dataType: "json",
      success: function (response) {
        options = [];
        readingQuestions = [];
        let data = response;
        if ($("#loai-cau-hoi").val() === "essay") {
          $("#essay-answer").val(data[0]?.noidungtl || "");
          $("#list-options").html("");
        } else if ($("#loai-cau-hoi").val() === "reading") {
          readingQuestions = data.map((q) => ({
            content: q.noidung,
            options: q.options.map((opt) => ({
              content: opt.noidungtl,
              check: opt.ladapan == 1,
            })),
          }));
          showReadingQuestions();
          $("#list-options").html("");
        } else {
          data.forEach((option_get) => {
            let option = {
              content: option_get["noidungtl"],
              check: option_get["ladapan"] == 1,
            };
            options.push(option);
          });
          showOptions(options);
        }
      },
      error: function (err) {
        console.error("Error in getAnswerById:", err.responseText);
        Dashmix.helpers("jq-notify", {
          type: "danger",
          message: "Lỗi khi lấy đáp án",
        });
      },
    });
  }

  // Delete question
  $(document).on("click", ".btn-delete-question", function () {
    let trid = $(this).data("id");
    let e = Swal.mixin({
      buttonsStyling: false,
      target: "#page-container",
      customClass: {
        confirmButton: "btn btn-success m-1",
        cancelButton: "btn btn-danger m-1",
        input: "form-control",
      },
    });

    e.fire({
      title: "Are you sure?",
      text: "Bạn có chắc chắn muốn xoá câu hỏi này?",
      icon: "warning",
      showCancelButton: true,
      customClass: {
        confirmButton: "btn btn-danger m-1",
        cancelButton: "btn btn-secondary m-1",
      },
      confirmButtonText: "Vâng, tôi chắc chắn!",
      html: false,
      preConfirm: () =>
        new Promise((resolve) => {
          setTimeout(() => {
            resolve();
          }, 50);
        }),
    }).then((result) => {
      if (result.value) {
        $.ajax({
          type: "post",
          url: "./question/delete",
          data: {
            macauhoi: trid,
          },
          success: function (response) {
            e.fire("Deleted!", "Xóa câu hỏi thành công!", "success");
            mainPagePagination.getPagination(
              mainPagePagination.option,
              mainPagePagination.valuePage.curPage
            );
          },
        });
      }
    });
  });

  // Load questions
  var page = 1;
  var select = "Tất cả";
  loadQuestion();
});

function reloadQuestionList() {
  mainPagePagination.getPagination(
    mainPagePagination.option,
    mainPagePagination.valuePage.curPage
  );
}

// Get current user ID
const container = document.querySelector(".content");
const currentUser = container.dataset.id;
delete container.dataset.id;

// Pagination
const mainPagePagination = new Pagination();
mainPagePagination.option.controller = "question";
mainPagePagination.option.model = "CauHoiModel";
mainPagePagination.option.limit = 10;
mainPagePagination.option.id = currentUser;
mainPagePagination.option.filter = {};
mainPagePagination.getPagination(
  mainPagePagination.option,
  mainPagePagination.valuePage.curPage
);
