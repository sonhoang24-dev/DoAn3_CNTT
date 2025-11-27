Dashmix.helpersOnLoad(["js-flatpickr", "jq-datepicker", "jq-select2"]);

let groups = [];

function getSelectedQuestionTypes() {
  let types = [];
  $(".dang-hoi:checked").each(function () {
    let val = $(this).val();
    // Chuẩn hóa về key backend: mcq, essay, reading (hoặc tracnghiem, tuluan, dochieu)
    if (val === "mcq" || val === "tracnghiem") types.push("mcq");
    else if (val === "essay" || val === "tuluan") types.push("essay");
    else if (val === "reading" || val === "dochieu") types.push("reading");
  });
  return types;
}
function getTotalQuestionOfChapter(chapters, monhoc, dokho, loaicauhoi) {
  var result = 0;
  if (!loaicauhoi || loaicauhoi.length === 0) return 0;

  $.ajax({
    url: "./question/getsoluongcauhoi",
    type: "post",
    data: {
      chuong: Array.isArray(chapters) ? chapters : [chapters].filter(Boolean),
      monhoc: monhoc,
      dokho: dokho,
      loaicauhoi: loaicauhoi, // gửi lên server
    },
    async: false, // đồng bộ để trả về luôn
    success: function (response) {
      if (response && response.success && response.data) {
        // server trả về dạng: {mcq: {de: 11, tb: 49, kho: 29}, essay: {...}, reading: {...}}
        loaicauhoi.forEach(function (lc) {
          if (response.data[lc]) {
            switch (dokho) {
              case 1:
                result = response.data[lc].de;
                break;
              case 2:
                result = response.data[lc].tb;
                break;
              case 3:
                result = response.data[lc].kho;
                break;
            }
          }
        });
      } else {
        console.warn("Server không trả về data hợp lệ:", response);
      }
    },
    error: function (xhr, status, error) {
      console.error("Lỗi AJAX:", error, xhr.responseText);
    },
  });

  return result || 0;
}

function updateQuestionCounts() {
  let chapters = getSelectedChapters();
  let m = $("#nhom-hp").val() ? groups[$("#nhom-hp").val()].mamonhoc : 0;
  let isAuto = $("#tudongsoande").prop("checked");

  // Kiểm tra cho cả đề thủ công và tự động
  if (chapters.length === 0 || !m) {
    $("#coban").val(0);
    $("#trungbinh").val(0);
    $("#kho").val(0);
    $("#coban-error").text("Vui lòng chọn chương và môn học");
    $("#trungbinh-error").text("Vui lòng chọn chương và môn học");
    $("#kho-error").text("Vui lòng chọn chương và môn học");
    return;
  }

  let availableEasy = getTotalQuestionOfChapter(chapters, m, 1);
  let availableMedium = getTotalQuestionOfChapter(chapters, m, 2);
  let availableHard = getTotalQuestionOfChapter(chapters, m, 3);

  $("#coban-error").text(`Có ${availableEasy} câu dễ`);
  $("#trungbinh-error").text(`Có ${availableMedium} câu trung bình`);
  $("#kho-error").text(`Có ${availableHard} câu khó`);

  jQuery(".form-taodethi").valid();
}
$.validator.addMethod(
  "validSoLuong",
  function (value, element, param) {
    let chapters = getSelectedChapters();
    let m = $("#nhom-hp").val() ? groups[$("#nhom-hp").val()].mamonhoc : 0;
    let parsedValue = parseFloat(value);

    if (parsedValue < 0) {
      return false;
    }
    if (parsedValue % 1 !== 0) {
      return false;
    }

    // Kiểm tra số lượng câu hỏi cho cả đề thủ công và tự động
    let result = getToTalQuestionOfChapter(chapters, m, param);
    console.log(`Validating: Value=${value}, Result=${result}, Dokho=${param}`);
    return result >= parseInt(value);
  },
  function (params, element) {
    let value = parseFloat($(element).val());
    if (value < 0) {
      return "Số câu hỏi không được âm";
    }
    if (value % 1 !== 0) {
      return "Số câu hỏi phải là số nguyên";
    }
    let chapters = getSelectedChapters();
    let m = $("#nhom-hp").val() ? groups[$("#nhom-hp").val()].mamonhoc : 0;
    let result = getToTalQuestionOfChapter(chapters, m, params);
    return `Chỉ có ${result} câu hỏi mức độ ${
      params == 1 ? "dễ" : params == 2 ? "trung bình" : "khó"
    }, bạn yêu cầu ${$(element).val()} câu`;
  }
);
function getMinutesBetweenDates(start, end) {
  const startDate = new Date(start);
  const endDate = new Date(end);
  const diffMs = endDate.getTime() - startDate.getTime();
  return Math.round(diffMs / 60000);
}

function showGroup() {
  let html = "<option></option>";
  $.ajax({
    type: "post",
    url: "./module/loadData",
    async: false,
    data: {
      hienthi: 1,
    },
    dataType: "json",
    success: function (response) {
      groups = response;
      response.forEach((item, index) => {
        html += `<option value="${index}">${
          item.mamonhoc +
          " - " +
          item.tenmonhoc +
          " - " +
          "(" +
          item.tennamhoc +
          ")" +
          " - " +
          item.tenhocky
        }</option>`;
      });
      $("#nhom-hp").html(html);
    },
  });
}
$("#nhom-hp").on("change", function () {
  let index = $(this).val();
  let mamonhoc = groups[index].mamonhoc;
  showListGroup(index);
  showChapter(mamonhoc);
});

function showChapter(mamonhoc) {
  let html = `
    <div class="form-check">
      <input class="form-check-input" type="checkbox" id="select-all-chapters">
      <label class="form-check-label fw-semibold" for="select-all-chapters">
        <i class="me-1 text-success"></i> Chọn tất cả chương
      </label>
    </div>`;

  $.ajax({
    type: "post",
    url: "./subject/getAllChapter",
    async: false,
    data: { mamonhoc: mamonhoc },
    dataType: "json",
    success: function (data) {
      data.forEach((item) => {
        html += `
          <div class="form-check">
            <input class="form-check-input select-chapter-item" type="checkbox"
              value="${item.machuong}" id="chuong-${item.machuong}">
            <label class="form-check-label" for="chuong-${item.machuong}">
              ${item.tenchuong}
            </label>
          </div>`;
      });
      $("#chuong").html(html);
    },
  });
}

$(document).on("click", "#select-all-chapters", function () {
  let check = $(this).prop("checked");
  $(".select-chapter-item").prop("checked", check);
  updateQuestionCounts();
});

function getSelectedChapters() {
  let result = [];
  $(".select-chapter-item").each(function () {
    if ($(this).prop("checked")) {
      result.push($(this).val());
    }
  });
  return result;
}
function showListGroup(index) {
  let html = "";
  if (groups[index] && groups[index].nhom.length > 0) {
    html += `<div class="col-12 mb-3">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="select-all-group">
                <label class="form-check-label" for="select-all-group">Chọn tất cả</label>
            </div></div>`;
    groups[index].nhom.forEach((item) => {
      html += `<div class="col-4">
                    <div class="form-check">
                        <input class="form-check-input select-group-item" type="checkbox" value="${item.manhom}"
                            id="nhom-${item.manhom}" name="nhom-${item.manhom}">
                        <label class="form-check-label" for="nhom-${item.manhom}">${item.tennhom}</label>
                    </div>
                </div>`;
    });
  } else {
    html += `<div class="text-center fs-sm"><img style="width:100px" src="./public/media/svg/empty_data.png" alt=""></div>`;
  }
  $("#list-group").html(html);
}

function getGroupSelected() {
  let result = [];
  $(".select-group-item").each(function () {
    if ($(this).prop("checked")) {
      result.push($(this).val());
    }
  });
  return result;
}

Dashmix.onLoad(() =>
  class {
    static initValidation() {
      Dashmix.helpers("jq-validation");
      $.validator.addMethod(
        "validTimeEnd",
        function (value) {
          var startTime = new Date($("#time-start").val());
          var currentTime = new Date();
          var endTime = new Date(value);
          return endTime > startTime && endTime > currentTime;
        },
        "Thời gian kết thúc phải lớn hơn thời gian bắt đầu và không bé hơn thời gian hiện tại"
      );
      $.validator.addMethod(
        "atLeastOneQuestion",
        function () {
          const easy = parseInt($("#coban").val()) || 0;
          const medium = parseInt($("#trungbinh").val()) || 0;
          const hard = parseInt($("#kho").val()) || 0;
          return easy + medium + hard > 0;
        },
        "Phải có ít nhất 1 câu hỏi."
      );

      $.validator.addMethod(
        "validTimeStart",
        function (value) {
          var startTime = new Date(value);
          var currentTime = new Date();
          return startTime > currentTime;
        },
        "Thời gian bắt đầu không được bé hơn thời gian hiện tại"
      );

      $.validator.addMethod(
        "validThoigianthi",
        function (value) {
          let startTime = new Date($("#time-start").val());
          let endTime = new Date($("#time-end").val());
          return (
            startTime < endTime &&
            parseInt(getMinutesBetweenDates(startTime, endTime)) >=
              parseInt(value)
          );
        },
        "Thời gian làm bài không hợp lệ"
      );

      jQuery(".form-taodethi").validate({
        rules: {
          "name-exam": { required: true },
          "time-start": { required: true, validTimeStart: true },
          "time-end": { required: true, validTimeEnd: true },
          "exam-time": { required: true, digits: true, validThoigianthi: true },
          "nhom-hp": { required: true },
          user_nhomquyen: { required: true },
          chuong: {
            required: function () {
              return getSelectedChapters().length > 0;
            },
          },
          coban: {
            required: true,
            digits: true,
            validSoLuong: 1,
            atLeastOneQuestion: true,
          },
          trungbinh: {
            required: true,
            digits: true,
            validSoLuong: 2,
            atLeastOneQuestion: true,
          },
          kho: {
            required: true,
            digits: true,
            validSoLuong: 3,
            atLeastOneQuestion: true,
          },
        },
        messages: {
          "name-exam": { required: "Vui lòng nhập tên đề kiểm tra" },
          "time-start": {
            required: "Vui lòng chọn thời điểm bắt đầu của bài kiểm tra",
            validTimeStart:
              "Thời gian bắt đầu không được bé hơn thời gian hiện tại",
          },
          "time-end": {
            required: "Vui lòng chọn thời điểm kết thúc của bài kiểm tra",
            validTimeEnd: "Thời gian kết thúc không hợp lệ",
          },
          "exam-time": {
            required: "Vui lòng chọn thời gian làm bài kiểm tra",
          },
          "nhom-hp": { required: "Vui lòng chọn nhóm học phần giảng dạy" },
          chuong: {
            required: "Vui lòng chọn ít nhất một chương cho đề kiểm tra",
          },
          coban: {
            required: "Vui lòng cho biết số câu dễ",
            digits: "Vui lòng nhập số",
          },
          trungbinh: {
            required: "Vui lòng cho biết số câu trung bình",
            digits: "Vui lòng nhập số",
          },
          kho: {
            required: "Vui lòng cho biết số câu khó",
            digits: "Vui lòng nhập số",
          },
        },
      });
    }
    static init() {
      this.initValidation();
    }
  }.init()
);

$(document).ready(function () {
  // Xử lý cắt URL để lấy mã đề thi
  let url = location.href.split("/");
  let param = url[url.length - 2] == "update" ? url[url.length - 1] : 0;
  if (param) {
    getDetail(param);
  }

  // Sự kiện thay đổi nhóm học phần
  $("#nhom-hp").on("select2:select", function () {
    let index = $(this).val();
    if (index && groups[index]) {
      let mamonhoc = groups[index].mamonhoc;
      showListGroup(index);
      showChapter(mamonhoc);
      updateQuestionCounts();
    }
  });

  // Sự kiện thay đổi chương
  $("#chuong").on("select2:select", function () {
    updateQuestionCounts();
  });

  $("#tudongsoande").on("change", function () {
    $(".show-chap").toggle();
    if (!$(this).prop("checked")) {
      $("#chuong").val("").trigger("change");
      $("#coban").val(0);
      $("#trungbinh").val(0);
      $("#kho").val(0);
    }
    updateQuestionCounts();
  });

  // Sự kiện thay đổi các checkbox khác
  $("#xemdiem, #xemda, #xembailam, #daocauhoi, #daodapan, #tudongnop").on(
    "change",
    function () {
      updateQuestionCounts();
    }
  );

  // Chọn hoặc bỏ chọn tất cả nhóm
  $(document).on("click", "#select-all-group", function () {
    let check = $(this).prop("checked");
    $(".select-group-item").prop("checked", check);
  });

  // Xử lý nút tạo đề
  $("#btn-add-test").click(function (e) {
    e.preventDefault();

    if (!$(".form-taodethi").valid()) {
      return;
    }
    let chapters = getSelectedChapters();
    let selectedGroups = getGroupSelected();
    let m = $("#nhom-hp").val() ? groups[$("#nhom-hp").val()].mamonhoc : 0;

    // Lấy danh sách loại câu hỏi đã chọn (value: mcq, essay, reading)
    let questionTypes = getSelectedQuestionTypes();

    if (questionTypes.length === 0) {
      Dashmix.helpers("jq-notify", {
        type: "danger",
        message: "Phải chọn ít nhất 1 loại câu hỏi!",
      });
      return;
    }

    if (selectedGroups.length === 0) {
      Dashmix.helpers("jq-notify", {
        type: "danger",
        message: "Chọn ít nhất một nhóm học phần!",
      });
      return;
    }

    // Object lưu số lượng câu theo từng loại
    let socau = {};
    let valid = true;

    // Map từ value checkbox → tên input thực tế
    const typeMap = {
      mcq: "tracnghiem",
      essay: "tuluan",
      reading: "dochieu",
    };

    questionTypes.forEach((type) => {
      let prefix = typeMap[type]; // tracnghiem / tuluan / dochieu

      let de = parseInt($(`#coban_${prefix}`).val()) || 0;
      let tb = parseInt($(`#trungbinh_${prefix}`).val()) || 0;
      let kho = parseInt($(`#kho_${prefix}`).val()) || 0;

      let total = de + tb + kho;

      if (total === 0) {
        valid = false;
        let tenLoai = {
          mcq: "Trắc nghiệm",
          essay: "Tự luận",
          reading: "Đọc hiểu",
        }[type];

        Dashmix.helpers("jq-notify", {
          type: "danger",
          message: `Loại "${tenLoai}" phải có ít nhất 1 câu!`,
        });
        return;
      }

      socau[type] = { de, tb, kho };
    });

    if (!valid) return;

    // Kiểm tra số lượng có đủ trong ngân hàng câu hỏi
    questionTypes.forEach((type) => {
      let prefix = typeMap[type];

      let available = {
        de: getTotalQuestionOfChapter(chapters, m, 1, [type]),
        tb: getTotalQuestionOfChapter(chapters, m, 2, [type]),
        kho: getTotalQuestionOfChapter(chapters, m, 3, [type]),
      };

      let required = socau[type];

      if (available.de < required.de) {
        valid = false;
        Dashmix.helpers("jq-notify", {
          type: "danger",
          message: `Trắc nghiệm: chỉ có ${available.de} câu dễ, bạn cần ${required.de}`,
        });
      }
      if (available.tb < required.tb) {
        valid = false;
        Dashmix.helpers("jq-notify", {
          type: "danger",
          message: `Trắc nghiệm: chỉ có ${available.tb} câu trung bình`,
        });
      }
      if (available.kho < required.kho) {
        valid = false;
        Dashmix.helpers("jq-notify", {
          type: "danger",
          message: `Trắc nghiệm: chỉ có ${available.kho} câu khó`,
        });
      }
    });

    if (!valid) return;

    // Gửi dữ liệu lên server
    // Thêm debug in action_test.js để xem response thực tế
    $.ajax({
      url: "./test/addTest",
      type: "post",
      data: {
        mamonhoc: m,
        tende: $("#name-exam").val(),
        thoigianthi: parseInt($("#exam-time").val()) || 0,
        thoigianbatdau: $("#time-start").val(),
        thoigianketthuc: $("#time-end").val(),
        socau: JSON.stringify(socau),
        chuong: chapters,
        loaide: $("#tudongsoande").prop("checked") ? 1 : 0,
        xemdiem: $("#xemdiem").prop("checked") ? 1 : 0,
        xemdapan: $("#xemda").prop("checked") ? 1 : 0,
        xembailam: $("#xembailam").prop("checked") ? 1 : 0,
        daocauhoi: $("#daocauhoi").prop("checked") ? 1 : 0,
        daodapan: $("#daodapan").prop("checked") ? 1 : 0,
        tudongnop: $("#tudongnop").prop("checked") ? 1 : 0,
        manhom: selectedGroups,
        loaicauhoi: questionTypes,
      },
      dataType: "json",
      success: function (res) {
        console.log("Response nhận được:", res);

        if (res.success && res.made) {
          Dashmix.helpers("jq-notify", {
            type: "success",
            message: `Tạo đề thành công! ID đề: ${res.made}`,
          });

          setTimeout(() => {
            window.location.href = "./test";
          }, 1500);
        } else {
          Dashmix.helpers("jq-notify", {
            type: "danger",
            message: res.error || "Tạo đề thất bại!",
          });
        }
      },
      error: function (xhr, status, error) {
        console.error("AJAX Error:", {
          status: xhr.status,
          statusText: xhr.statusText,
          responseText: xhr.responseText,
          responseJSON: xhr.responseJSON,
          error: error,
        });
        Dashmix.helpers("jq-notify", {
          type: "danger",
          message: `Lỗi: ${xhr.responseText || error}`,
        });
      },
    });
  });
  // Trong $("#btn-update-test").click
  $("#btn-update-test").click(function (e) {
    e.preventDefault();

    // Kiểm tra form cơ bản và thời gian
    if (
      (!checkDate(infodethi.thoigianbatdau) && $(".form-taodethi").valid()) ||
      validUpdate()
    ) {
      const made = $(this).data("id");
      const loaide = $("#tudongsoande").prop("checked") ? 1 : 0;

      // --- Nhóm học phần ---
      const manhom = getGroupSelected().map((x) => parseInt(x));
      if (manhom.length === 0) {
        Dashmix.helpers("jq-notify", {
          type: "danger",
          message: "Chọn ít nhất một nhóm học phần!",
        });
        return;
      }

      // --- Lấy loại câu hỏi được chọn (dùng chung hàm đã có) ---
      const loaicauhoi = getSelectedQuestionTypes(); // ← quan trọng!

      if (loaicauhoi.length === 0) {
        Dashmix.helpers("jq-notify", {
          type: "danger",
          message: "Phải chọn ít nhất 1 loại câu hỏi!",
        });
        return;
      }

      // --- Lấy số câu theo loại + mức độ (đúng với ID thực tế của bạn) ---
      const socau = {};
      let valid = true;

      loaicauhoi.forEach((type) => {
        let de = 0,
          tb = 0,
          kho = 0;

        if (type === "mcq") {
          // Trắc nghiệm
          de = parseInt($("#coban_tracnghiem").val()) || 0;
          tb = parseInt($("#trungbinh_tracnghiem").val()) || 0;
          kho = parseInt($("#kho_tracnghiem").val()) || 0;
        } else if (type === "essay") {
          // Tự luận
          de = parseInt($("#coban_tuluan").val()) || 0;
          tb = parseInt($("#trungbinh_tuluan").val()) || 0;
          kho = parseInt($("#kho_tuluan").val()) || 0;
        } else if (type === "reading") {
          // Đọc hiểu
          de = parseInt($("#coban_dochieu").val()) || 0;
          tb = parseInt($("#trungbinh_dochieu").val()) || 0;
          kho = parseInt($("#kho_dochieu").val()) || 0;
        }

        const total = de + tb + kho;

        if (total === 0) {
          valid = false;
          const tenLoai = {
            mcq: "Trắc nghiệm",
            essay: "Tự luận",
            reading: "Đọc hiểu",
          }[type];

          Dashmix.helpers("jq-notify", {
            type: "danger",
            message: `Loại "${tenLoai}" phải có ít nhất 1 câu!`,
          });
        }

        socau[type] = { de, tb, kho };
      });

      if (!valid) return;

      // --- Gửi Ajax ---
      const data = {
        made: infodethi.made,
        mamonhoc: groups[$("#nhom-hp").val()].mamonhoc,
        tende: $("#name-exam").val(),
        thoigianthi: parseInt($("#exam-time").val()) || 0,
        thoigianbatdau: $("#time-start").val(),
        thoigianketthuc: $("#time-end").val(),
        chuong: getSelectedChapters(), // dùng hàm có sẵn cho chắc
        loaide: loaide,
        xemdiem: $("#xemdiem").prop("checked") ? 1 : 0,
        xemdapan: $("#xemda").prop("checked") ? 1 : 0,
        xembailam: $("#xembailam").prop("checked") ? 1 : 0,
        daocauhoi: $("#daocauhoi").prop("checked") ? 1 : 0,
        daodapan: $("#daodapan").prop("checked") ? 1 : 0,
        tudongnop: $("#tudongnop").prop("checked") ? 1 : 0,
        manhom: manhom,
        socau: JSON.stringify(socau),
        loaicauhoi: loaicauhoi,
      };

      $.ajax({
        type: "post",
        url: "./test/updateTest",
        data: data,
        dataType: "json",
        success: function (response) {
          if (response && response.success) {
            Dashmix.helpers("jq-notify", {
              type: "success",
              icon: "fa fa-check me-1",
              message: "Cập nhật đề thi thành công!",
            });
            setTimeout(() => {
              location.href = "./test";
            }, 1500);
          } else {
            Dashmix.helpers("jq-notify", {
              type: "danger",
              icon: "fa fa-times me-1",
              message: response.error || "Cập nhật đề thi không thành công!",
              delay: 10000,
            });
          }
        },
        error: function (xhr, status, error) {
          console.error("AJAX Error:", xhr.status, xhr.responseText);
          Dashmix.helpers("jq-notify", {
            type: "danger",
            icon: "fa fa-times me-1",
            message: `Lỗi: ${xhr.responseText || error}`,
          });
        },
      });
    }
  });

  function checkDate(time) {
    let dateToCompare = new Date(time);
    let currentTime = new Date();
    return dateToCompare.getTime() < currentTime.getTime();
  }
  // Hiển thị/ẩn div socau-type dựa theo checkbox
  function toggleSocauType() {
    $(".dang-hoi").each(function () {
      const val = $(this).val(); // mcq / essay / reading
      let targetId = "";
      if (val === "mcq") targetId = "#box-tn";
      else if (val === "essay") targetId = "#box-tl";
      else if (val === "reading") targetId = "#box-dh";

      if ($(this).prop("checked")) {
        $(targetId).removeClass("d-none");
      } else {
        $(targetId).addClass("d-none");
        $(targetId).find("input").val(0); // reset số lượng khi bỏ chọn
      }
    });
  }

  // Gắn sự kiện khi checkbox thay đổi
  $(document).on("change", ".dang-hoi", toggleSocauType);

  // Gọi 1 lần khi load trang
  toggleSocauType();

  function findIndexGroup(manhom) {
    let i = 0;
    let index = -1;
    while (i < groups.length && index == -1) {
      index = groups[i].nhom.findIndex((item) => item.manhom == manhom);
      if (index == -1) i++;
    }
    return i;
  }
  function showInfo(dethi) {
    const checkD = checkDate(dethi.thoigianbatdau);

    // --- Checkbox loại câu hỏi ---
    $("#loai-tracnghiem").prop(
      "checked",
      dethi.mcq_de + dethi.mcq_tb + dethi.mcq_kho > 0
    );
    $("#loai-tuluan").prop(
      "checked",
      dethi.essay_de + dethi.essay_tb + dethi.essay_kho > 0
    );
    $("#loai-doc-hieu").prop(
      "checked",
      dethi.reading_de + dethi.reading_tb + dethi.reading_kho > 0
    );

    // --- Số lượng câu hỏi từng loại ---
    $("#coban_tracnghiem").val(dethi.mcq_de);
    $("#trungbinh_tracnghiem").val(dethi.mcq_tb);
    $("#kho_tracnghiem").val(dethi.mcq_kho);

    $("#coban_tuluan").val(dethi.essay_de);
    $("#trungbinh_tuluan").val(dethi.essay_tb);
    $("#kho_tuluan").val(dethi.essay_kho);

    $("#coban_dochieu").val(dethi.reading_de);
    $("#trungbinh_dochieu").val(dethi.reading_tb);
    $("#kho_dochieu").val(dethi.reading_kho);

    // --- Hiển thị div socau-type đúng ---
    toggleSocauType();

    // --- Các input khác ---
    $("#name-exam").val(dethi.tende);
    $("#exam-time").val(dethi.thoigianthi).prop("disabled", checkD);

    $("#time-start").flatpickr({
      enableTime: true,
      altInput: true,
      allowInput: !checkD,
      defaultDate: dethi.thoigianbatdau,
    });

    $("#time-end").flatpickr({
      enableTime: true,
      altInput: true,
      allowInput: true,
      defaultDate: dethi.thoigianketthuc,
    });

    $("#tudongsoande")
      .prop("checked", dethi.loaide == "1")
      .prop("disabled", checkD);
    $("#xemdiem").prop("checked", dethi.xemdiemthi == "1");
    $("#xemda").prop("checked", dethi.xemdapan == "1");
    $("#xembailam").prop("checked", dethi.hienthibailam == "1");
    $("#daocauhoi").prop("checked", dethi.troncauhoi == "1");
    $("#daodapan").prop("checked", dethi.trondapan == "1");
    $("#tudongnop").prop("checked", dethi.nopbaichuyentab == "1");

    // --- Load nhóm học phần và chương ---
    $.when(showGroup(), showChapter(dethi.monthi)).done(function () {
      $("#nhom-hp").val(findIndexGroup(dethi.nhom[0])).trigger("change");
      setGroup(dethi.nhom, dethi.thoigianbatdau);

      if (dethi.loaide == "1") {
        $(".show-chap").show();

        if (Array.isArray(dethi.chuong)) {
          dethi.chuong.forEach(function (machuong) {
            $("#chuong input[type=checkbox][value='" + machuong + "']").prop(
              "checked",
              true
            );
          });
        }

        if (checkD) {
          $("#chuong-container")
            .find("input, select, textarea, button")
            .prop("disabled", true)
            .css({ "pointer-events": "none", opacity: "0.6" });
        }
      } else {
        $(".show-chap").hide();
      }
    });
  }

  function setGroup(list, date) {
    let v = checkDate(date);
    $("#select-all-group").prop("disabled", v);
    list.forEach((item) => {
      $(`.select-group-item[value='${item}']`)
        .prop("checked", true)
        .prop("disabled", v);
    });
  }

  function validUpdate() {
    let check = true;
    if ($("#name-exam").val() == "") {
      Dashmix.helpers("jq-notify", {
        type: "danger",
        icon: "fa fa-times me-1",
        message: "Tên đề không được để trống",
      });
      check = false;
    }
    let startTime = new Date($("#time-start").val());
    let endTime = new Date($("#time-end").val());

    if (endTime <= startTime) {
      Dashmix.helpers("jq-notify", {
        type: "danger",
        icon: "fa fa-times me-1",
        message: "Thời gian kết thúc không được bé hơn thời gian bắt đầu",
      });
      check = false;
    }

    if (endTime < new Date(infodethi.thoigianketthuc)) {
      Dashmix.helpers("jq-notify", {
        type: "danger",
        icon: "fa fa-times me-1",
        message: "Thời gian kết thúc không được bé hơn thời gian kết thúc cũ",
      });
      check = false;
    }

    if (
      endTime > startTime &&
      getMinutesBetweenDates(startTime, endTime) <
        parseInt(infodethi.thoigianthi)
    ) {
      Dashmix.helpers("jq-notify", {
        type: "danger",
        icon: "fa fa-times me-1",
        message: "Thời gian làm bài không hợp lệ",
      });
      check = false;
    }

    return check;
  }

  showGroup();

  $("#btn-update-quesoftest").hide();
  // Khởi tạo biến đề thi để chứa thông tin đề
  let infodethi;
  function getDetail(made) {
    return $.ajax({
      type: "post",
      url: "./test/getDetail",
      data: {
        made: made,
      },
      dataType: "json",
      success: function (response) {
        if (response.loaide == 0) {
          $("#btn-update-quesoftest").show();
          $("#btn-update-quesoftest").attr(
            "href",
            `./test/select/${response.made}`
          );
        }
        console.log("Dữ liệu từ server:", response);
        console.log("Giá trị xembailam:", response.xembailam);
        infodethi = response;
        showInfo(response);
      },
    });
  }
});
