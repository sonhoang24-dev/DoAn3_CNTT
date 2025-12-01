function showData(data) {
  console.log("Toàn bộ data:", data);

  data.forEach((Element, index) => {
    console.log(`---- [Item ${index}] ----`);
    console.log("manguoidung:", Element["manguoidung"]);
    console.log("hoten:", Element["hoten"]);
    console.log("thoigianvaothi:", Element["thoigianvaothi"]);
    console.log("thoigianbatdau:", Element["thoigianbatdau"]);
    console.log("thoigianketthuc:", Element["thoigianketthuc"]);
    console.log("thoigianlambai:", Element["thoigianlambai"]);
    console.log("------------------------");
  });

  let html = "";
  let now = new Date();
  let start = new Date(Element["thoigianbatdau"]);
  let end = new Date(Element["thoigianketthuc"]);
  let statusText = "";

  if (!Element["thoigianvaothi"]) {
    if (now > end) {
      statusText = "(Vắng thi)";
    } else if (now >= start && now <= end) {
      statusText = "(Chưa thi)";
    } else {
      statusText = "(Chưa tới giờ thi)";
    }
  } else {
    statusText = Element["thoigianvaothi"];
  }
  data.forEach((Element) => {
    var totalSeconds = Element["thoigianlambai"] || 0;
    var hours = Math.floor(totalSeconds / 3600);
    var minutes = Math.floor((totalSeconds % 3600) / 60);
    var seconds = Math.floor(totalSeconds % 60);
    var formattedTime =
      hours.toString().padStart(2, "0") +
      ":" +
      minutes.toString().padStart(2, "0") +
      ":" +
      seconds.toString().padStart(2, "0");
    html += `<tr>
        <td class="text-center">${Element["manguoidung"]}</td>
        <td class="fs-sm d-flex align-items-center">
            <img class="img-avatar img-avatar48 me-3"
               src="./public/media/avatars/${
                 Element["avatar"] && Element["avatar"].trim() !== ""
                   ? Element["avatar"]
                   : "ANHSV.png"
               }"

                }" alt="${Element["hoten"]}">
            <div class="d-flex flex-column">
                <strong class="text-primary">${Element["hoten"]}</strong>
                <span class="fw-normal fs-sm text-muted">${
                  Element["email"]
                }</span>
            </div>
        </td>
        <td class="text-center">
  ${((Element["diemthi"] ?? 0) + (Element["diem_tuluan"] ?? 0)).toFixed(2)}
</td>



        <td class="text-center">${
          Element["thoigianvaothi"] || "(Vắng thi)"
        }</td>
        <td class="text-center">${formattedTime}</td>
        <td class="text-center">${Element["solanchuyentab"] || 0}</td>
        <td class="text-center">
            <a class="btn btn-sm btn-alt-secondary show-exam-detail" href="javascript:void(0)" data-bs-toggle="tooltip" aria-label="Xem chi tiết" data-bs-original-title="Xem chi tiết" data-id="${
              Element["makq"] || ""
            }">
                <i class="fa fa-fw fa-eye"></i>
            </a>
            <a class="btn btn-sm btn-alt-secondary print-pdf" href="javascript:void(0)" data-bs-toggle="tooltip" aria-label="In bài làm" data-bs-original-title="In bài làm" data-id="${
              Element["makq"] || ""
            }">
                <i class="fa fa-fw fa-print"></i>
            </a>
        </td>
    </tr>`;
  });
  $("#took_the_exam").html(html);
  $('[data-bs-toggle="tooltip"]').tooltip();
}

const made = document.getElementById("chitietdethi").dataset.id;

// Lấy danh sách mã nhóm
const listGroupID = [];
document.querySelectorAll(".filtered-by-group").forEach(function (element) {
  const id = element.dataset.value;
  listGroupID.push(+id);
});
let currentGroupID = listGroupID[0];

$(document).ready(function () {
  $("[data-bs-target='#modal-cau-hoi']").click(function (e) {
    e.preventDefault();
    let made = $(this).data("id");
    $.ajax({
      type: "post",
      url: "./test/getQuestionOfTestManual",
      data: {
        made: made,
      },
      dataType: "json",
      success: function (response) {
        showListQuestion(response);
      },
    });
  });

  function showListQuestion(questions) {
    let html = ``;

    questions.forEach((question, index) => {
      html += `<div class="question rounded border mb-3 bg-white" id="c${
        index + 1
      }">
      <div class="question-top p-3">
        <p class="question-content fw-bold mb-3">${index + 1}. ${
        question.noidung
      }</p>
        <div class="row">`;

      question.cautraloi.forEach((ctl, i) => {
        let content = "";

        // Nếu có hình ảnh
        if (ctl.hinhanh && ctl.hinhanh.trim() !== "") {
          content = `<img src="${ctl.hinhanh}" alt="Hình ảnh đáp án" class="img-fluid">`;
        } else {
          content = ctl.noidungtl; // hiển thị text
        }

        html += `<div class="col-6 mb-1">
          <p class="mb-1"><b>${String.fromCharCode(i + 65)}.</b> ${content}</p>
        </div>`;
      });

      html += `</div></div></div>`;
    });

    $("#list-question").html(html);
  }

  var made = $("#chitietdethi").data("id");

  // Dropdown
  $(".filtered-by-group").click(function (e) {
    e.preventDefault();
    $(".btn-filtered-by-group").text($(this).text());
    currentGroupID = $(this).data("value");
    mainPagePagination.option.manhom =
      currentGroupID == 0 ? listGroupID.slice(1) : currentGroupID;
    resetFilterState();
    renderTableTitleColumns();
    resetSortIcons();
    mainPagePagination.getPagination(
      mainPagePagination.option,
      mainPagePagination.valuePage.curPage
    );
  });

  $(".filtered-by-state").click(function (e) {
    e.preventDefault();
    $(".btn-filtered-by-state").text($(this).text());
    const state = $(this).data("state");
    mainPagePagination.option.filter = state;
    renderTableTitleColumns(state);
    resetSortIcons();

    mainPagePagination.getPagination(
      mainPagePagination.option,
      mainPagePagination.valuePage.curPage
    );
  });

  // Hiển thị đề kiểm tra đáp án + câu trả lời của thí sinh đó
  function showTestDetail(questions) {
    let html = "";

    questions.forEach((item, index) => {
      let dadung = item.cautraloi.find((op) => op.ladapan == 1);
      let dapanchon = item.dapanchon || null;

      // Câu hỏi
      html += `<div class="question rounded border mb-3">
      <div class="question-top p-3">
        <p class="fw-bold mb-3">${index + 1}. ${item.noidung}</p>
        <div class="row">`;

      // Hiển thị đáp án
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

      html += `</div></div>`; // đóng question-top

      // Phần kết quả
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
    });

    $("#content-file").html(html);
  }

  // Khai báo SweetAlert2 instance dùng chung
  const e = Swal.mixin({
    buttonsStyling: false,
    target: "#page-container",
    customClass: {
      confirmButton: "btn btn-success m-1",
      cancelButton: "btn btn-danger m-1",
      input: "form-control",
    },
  });

  // Xử lý sự kiện khi click nút xem chi tiết bài thi
  $(document).on("click", ".show-exam-detail", function () {
    const makq = $(this).data("id");
    if (!makq || mainPagePagination.option.filter === "interrupted") {
      Swal.fire({
        icon: "warning",
        title: "Không thể xem. Thí sinh chưa làm bài thi !",
      });
      return;
    }

    const modal = new bootstrap.Modal(
      document.getElementById("modal-show-test")
    );
    modal.show();

    $.post(
      "./test/getResultDetail",
      { makq: makq, made: made },
      function (res) {
        console.log("Full response:", res);

        let questions = res;
        if (res.data) questions = res.data;
        if (res.questions) questions = res.questions;
        if (res.result) questions = res.result;

        if (!Array.isArray(questions)) {
          console.error("Không tìm thấy mảng câu hỏi!", res);
          Swal.fire({ icon: "error", title: "Lỗi định dạng dữ liệu!" });
          return;
        }

        // Chắc chắn hiện
        setTimeout(() => {
          showTestDetail(questions);
        }, 250);
      },
      "json"
    ).fail(function () {
      modal.hide();
      Swal.fire({ icon: "error", title: "Lỗi server!" });
    });
  });
  function resetSortIcons() {
    document.querySelectorAll(".col-sort").forEach((column) => {
      column.dataset.sortOrder = "default";
    });
  }

  function resetFilterState() {
    mainPagePagination.option.filter = "present";
    $(".btn-filtered-by-state").text("Đã nộp bài");
  }

  function renderTableTitleColumns(state = "present") {
    let html = `
    <th class="text-center col-sort" data-sort-column="manguoidung" data-sort-order="default">MSSV</th>
    <th class="col-sort" data-sort-column="hoten" data-sort-order="default">Họ tên</th>
    `;

    switch (state) {
      case "all":
      case "present":
        html += `
        <th class="text-center col-sort" data-sort-column="diemthi" data-sort-order="default">Điểm</th>
        <th class="text-center col-sort" data-sort-column="thoigianvaothi" data-sort-order="default">Thời gian vào thi</th>
        <th class="text-center col-sort" data-sort-column="thoigianlambai" data-sort-order="default">Thời gian thi</th>
        <th class="text-center col-sort" data-sort-column="solanchuyentab" data-sort-order="default">Số lần thoát</th>
        `;
        break;
      case "absent":
        html += `
        <th class="text-center">Điểm</th>
        <th class="text-center">Thời gian vào thi</th>
        <th class="text-center">Thời gian thi</th>
        <th class="text-center">Số lần thoát</th>
        `;
        break;
      case "interrupted":
        html += `
        <th class="text-center">Điểm</th>
        <th class="text-center col-sort" data-sort-column="thoigianvaothi" data-sort-order="default">Thời gian vào thi</th>
        <th class="text-center">Thời gian thi</th>
        <th class="text-center">Số lần thoát</th>
        `;
        break;
      default:
    }
    html += `
    <th class="text-center">Hành động</th>
    `;
    $(".table-col-title").html(html);
  }

  $(".table-col-title").click(function (e) {
    if (!e.target.classList.contains("col-sort")) {
      return;
    }
    const column = e.target.dataset.sortColumn;

    switch (mainPagePagination.option.filter) {
      case "absent":
        switch (column) {
          case "diemthi":
          case "thoigianvaothi":
          case "thoigianlambai":
          case "solanchuyentab":
            return;
          default:
        }
        break;
      case "interrupted":
        switch (column) {
          case "diemthi":
          case "thoigianlambai":
          case "solanchuyentab":
            return;
          default:
        }
        break;
      default:
    }

    const prevSortOrder = e.target.dataset.sortOrder;
    let currentSortOrder = "";
    switch (prevSortOrder) {
      case "default":
        currentSortOrder = "asc";
        break;
      case "asc":
        currentSortOrder = "desc";
        break;
      case "desc":
        currentSortOrder = "default";
        break;
    }

    if (currentSortOrder === "default") {
      mainPagePagination.option.custom = {};
    } else {
      mainPagePagination.option.custom.function = "sort";
      mainPagePagination.option.custom.column = column;
      mainPagePagination.option.custom.order = currentSortOrder;
    }

    mainPagePagination.valuePage.curPage = 1;
    mainPagePagination.getPagination(
      mainPagePagination.option,
      mainPagePagination.valuePage.curPage
    );

    resetSortIcons();
    e.target.dataset.sortOrder = currentSortOrder;
  });

  $(document).on("click", ".print-pdf", function () {
    let makq = $(this).data("id");
    if (makq != "") {
      $.ajax({
        url: `./test/exportPdf/${makq}`,
        method: "POST",
        success: function (response) {
          var binaryString = atob(response);
          var binaryLen = binaryString.length;
          var bytes = new Uint8Array(binaryLen);

          for (var i = 0; i < binaryLen; i++) {
            bytes[i] = binaryString.charCodeAt(i);
          }
          var blob = new Blob([bytes], { type: "application/pdf" });
          var url = URL.createObjectURL(blob);
          var a = document.createElement("a");
          a.href = url;
          a.download = "ket_qua_thi.pdf";
          a.style.display = "none";
          document.body.appendChild(a);
          a.click();
          setTimeout(function () {
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
          }, 100);
        },
      });
    } else {
      e.fire({
        icon: "warning",
        title: "Không thể in kết quả. Thí sinh chưa làm bài thi!",
        confirmButtonText: "Đóng",
      });
    }
  });

  $("#export_excel").click(function () {
    let manhom = $(".filtered-by-group.active").data("value");
    let ds = mainPagePagination.option.manhom;
    $.ajax({
      method: "post",
      url: "./test/exportExcel",
      dataType: "json",
      data: {
        made: made,
        manhom: manhom,
        ds: ds,
      },
      success: function (response) {
        var $a = $("<a>");
        $a.attr("href", response.file);
        $("body").append($a);
        $a.attr("download", "Kết quả bài thi.xls");
        $a[0].click();
        $a.remove();
      },
    });
  });
});

$(".filtered-by-group").click(function (e) {
  e.preventDefault();
  $(".filtered-by-group.active").removeClass("active");
  $(this).addClass("active");
  $(".chart-container").html('<canvas id="myChart"></canvas>');
  getStatictical();
});

$(".filtered-by-static").click(function (e) {
  e.preventDefault();
  $(".filtered-by-static.active").removeClass("active");
  $(this).addClass("active");
  $(".chart-container").html('<canvas id="myChart"></canvas>');
  getStatictical();
});

function getStatictical() {
  $.ajax({
    type: "post",
    url: "./test/getStatictical",
    data: {
      made: made,
      manhom: $(".filtered-by-static.active").data("id"),
    },
    dataType: "json",
    success: function (response) {
      if (response.error) {
        Swal.fire({
          icon: "error",
          title: "Lỗi",
          text: response.error,
        });
        $("#da_nop").text("0");
        $("#chua_nop").text("0");
        $("#khong_thi").text("0");
        $("#diem_trung_binh").text("0");
        $("#diem_duoi_1").text("0");
        $("#diem_duoi_5").text("0");
        $("#diem_lon_5").text("0");
        $("#diem_cao_nhat").text("0");
        showChart([0, 0, 0, 0, 0, 0, 0, 0, 0, 0]);
        return;
      }
      $("#da_nop").text(response.da_nop_bai || 0);
      $("#chua_nop").text(response.chua_nop_bai || 0);
      $("#khong_thi").text(response.khong_thi || 0);
      $("#diem_trung_binh").text(response.diem_trung_binh || 0);
      $("#diem_duoi_1").text(
        (response.thong_ke_diem && response.thong_ke_diem[0]) || 0
      );
      $("#diem_duoi_5").text(
        (response.thong_ke_diem || [0, 0, 0, 0, 0, 0, 0, 0, 0, 0])
          .slice(0, 5)
          .reduce((a, b) => a + b, 0) || 0
      );
      $("#diem_lon_5").text(
        (response.thong_ke_diem || [0, 0, 0, 0, 0, 0, 0, 0, 0, 0])
          .slice(5)
          .reduce((a, b) => a + b, 0) || 0
      );
      $("#diem_cao_nhat").text(Math.min(response.diem_cao_nhat || 0, 10)); // Cap at 10
      const chartData =
        Array.isArray(response.thong_ke_diem) &&
        response.thong_ke_diem.length >= 10
          ? response.thong_ke_diem.slice(0, 10)
          : [0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
      showChart(chartData);
    },
    error: function () {
      Swal.fire({
        icon: "error",
        title: "Lỗi",
        text: "Không thể kết nối đến server!",
      });
      showChart([0, 0, 0, 0, 0, 0, 0, 0, 0, 0]);
    },
  });
}

getStatictical();

function showChart(data) {
  if (!Array.isArray(data) || data.length === 0) {
    console.error("Dữ liệu không hợp lệ cho biểu đồ:", data);
    return;
  }

  // Tạo labels động với định dạng khoảng điểm (0-1, 1-2, ..., 9-10)
  const labels = data.map((_, i) => (i === 9 ? `9-10` : `${i}-${i + 1}`));

  const ctx = document.getElementById("myChart").getContext("2d");

  if (window.myChart && typeof window.myChart.destroy === "function") {
    window.myChart.destroy();
  }

  window.myChart = new Chart(ctx, {
    type: "bar",
    data: {
      labels: labels,
      datasets: [
        {
          label: "Số lượng sinh viên",
          data: data,
          backgroundColor: "rgba(6, 101, 208, 0.8)",
          borderColor: "rgba(6, 101, 208, 1)",
          borderWidth: 1,
        },
      ],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          position: "bottom",
        },
        title: {
          display: true,
          text: "Thống kê điểm thi",
          font: {
            size: 20,
            weight: "normal",
            family: "Inter",
          },
        },
      },
      scales: {
        y: {
          beginAtZero: true,
          title: {
            display: true,
            text: "Số lượng sinh viên",
          },
        },
        x: {
          title: {
            display: true,
            text: "Khoảng điểm",
          },
        },
      },
    },
  });
}
//chấm tự luận
$(document).ready(function () {
  // Ẩn badge số lượng chưa chấm lúc đầu
  $("#count-chua-cham").hide();
  // Load số lượng bài cần chấm ngay khi tải trang để badge hiển thị ngay
  try {
    var madeInitial = $("#chitietdethi").data("id");
    if (madeInitial && madeInitial > 0) {
      loadStudentsEssayToGrade(madeInitial);
    }
  } catch (e) {
    console.error('Lỗi khi load số bài tự luận ban đầu:', e);
  }
});

// ==================== 1. KHI MỞ TAB CHẤM TỰ LUẬN ====================
$("#cham-tuluan-tab").on("shown.bs.tab", function () {
  const made = $("#chitietdethi").data("id");
  if (made && made > 0) {
    loadStudentsEssayToGrade(made);
  }
});

// ==================== 2. LOAD DANH SÁCH SINH VIÊN CÓ BÀI TỰ LUẬN ====================
function loadStudentsEssayToGrade(made) {
  $.ajax({
    url: "./test/getListEssaySubmissionsAction",
    type: "POST",
    data: { made: made },
    dataType: "json",
    success: function (res) {
      if (!res || !res.success || !res.data || res.data.length === 0) {
        $("#danh-sach-sinhvien-tuluan").html(`
          <div class="text-center py-5 text-muted">
            <i class="fas fa-inbox fa-3x mb-3 opacity-50"></i>
            <p class="mb-0">Không có bài nộp tự luận</p>
          </div>
        `);
        $("#count-chua-cham").hide();
        return;
      }

      let html = "";
      let chuaCham = 0;

      res.data.forEach((item) => {
        const tn =
          item.diemthi !== null ? parseFloat(item.diemthi).toFixed(2) : "0.00";
        const tl = parseFloat(item.diem_tuluan_hien_tai || 0).toFixed(2);
        const tong = (parseFloat(tn) + parseFloat(tl)).toFixed(2);
        const daCham = parseFloat(tl) > 0;
        if (!daCham) chuaCham++;

        const hoten = item.hoten?.trim() || item.manguoidung;
        const avatarLetter = hoten.charAt(0).toUpperCase();

        const badge = daCham
          ? `<div class="badge bg-success rounded-pill px-3 py-2"><i class="fas fa-check me-1"></i>${tl}</div>`
          : `<div class="badge bg-warning text-dark rounded-pill px-3 py-2"><i class="fas fa-clock me-1"></i>Chưa chấm</div>`;

        html += `
        <div class="student-item border rounded-3 mb-3 shadow-sm hover-shadow transition-all pointer bg-white"
             data-makq="${item.makq}" 
             data-manguoidung="${item.manguoidung}" 
             data-hoten="${hoten}">

          <div class="p-3">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
              
              <!-- Avatar + Tên + MSSV -->
              <div class="d-flex align-items-center flex-grow-1 min-width-0">
                <div class="avatar-bg bg-primary text-white rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 me-3"
                     style="width:48px; height:48px; font-size:20px; font-weight:bold;">
                  ${avatarLetter}
                </div>
                <div class="min-width-0">
                  <h6 class="mb-1 fw-bold text-dark text-truncate">${hoten}</h6>
                  <small class="text-muted"><i class="fas fa-id-card me-1"></i>${
                    item.manguoidung
                  }</small>
                </div>
              </div>

              <!-- ĐIỂM - RESPONSIVE HOÀN HẢO -->
              <div class="d-flex align-items-center gap-3 flex-wrap justify-content-end">
                <div class="text-center">
                  <small class="text-muted d-block fw-medium">Trắc nghiệm</small>
                  <strong class="text-info fs-5">${tn}</strong>
                </div>
                <div class="text-center">
                  <small class="text-muted d-block fw-medium">Tự luận</small>
                  <strong class="${
                    daCham ? "text-success" : "text-danger"
                  } fs-5">${tl}</strong>
                </div>
                <div class="text-center border-start ps-3">
                  <small class="text-muted d-block fw-medium">Tổng</small>
                  <strong class="text-primary fs-4 fw-bold">${tong}đ</strong>
                </div>
              </div>

              <!-- Badge trạng thái -->
              <div class="d-flex align-items-center ms-3">
                ${badge}
              </div>
            </div>
          </div>
        </div>`;
      });

      $("#danh-sach-sinhvien-tuluan").html(html);
      $("#total-chua-cham").text(chuaCham);
      $("#count-chua-cham")
        .text(chuaCham > 0 ? chuaCham : "")
        .toggle(chuaCham > 0);
    },
    error: function () {
      $("#danh-sach-sinhvien-tuluan").html(`
        <div class="text-center py-5 text-danger">
          <i class="fas fa-wifi fa-3x mb-3"></i>
          <p>Lỗi kết nối. Vui lòng thử lại!</p>
        </div>
      `);
    },
  });
}
// ==================== 3. KHI CLICK VÀO 1 SINH VIÊN ====================
$(document).on(
  "click",
  "#danh-sach-sinhvien-tuluan .student-item",
  function () {
    const $this = $(this);
    const makq = $this.data("makq");
    const hoten = $this.data("hoten");
    const mssv = $this.data("manguoidung");

    // Active
    $(".student-item").removeClass("active bg-primary text-white");
    $this.addClass("active bg-primary text-white");

    // Hiển thị thông tin sinh viên
    $("#ten-sinhvien-cham").text(hoten);
    $("#mssv-cham").text(mssv);
    $("#khu-vuc-cham-bai").show();

    // Load bài làm
    $.post(
      "./test/getEssayDetailAction",
      { makq: makq },
      function (res) {
        if (!res.success || !res.cautraloi || res.cautraloi.length === 0) {
          $("#noi-dung-tuluan").html(
            '<div class="alert alert-warning text-center">Chưa có bài làm tự luận.</div>'
          );
          $("#tong-diem-tuluan").text("0.00");
          $("#diem-tuluan-input").val("0");
          return;
        }

        let html = "";
        let tong = 0;

        res.cautraloi.forEach((c, i) => {
          const diem = parseFloat(c.diem_cham || 0);
          tong += diem;

          html += `
      <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-gradient text-white" style="background: linear-gradient(135deg, #0d6efd, #0b5ed7);">
          <h5 class="mb-0"><i class="fas fa-question-circle me-2"></i>Câu ${
            i + 1
          } (Mã: ${c.macauhoi})</h5>
        </div>
        <div class="card-body">
          <div class="mb-4">
            <strong class="text-danger"><i class="fas fa-book-open me-2"></i>Câu hỏi:</strong>
            <div class="bg-light p-3 rounded border mt-2">${
              c.noidung_cauhoi || "—"
            }</div>
          </div>

          <div class="mb-4">
            <strong class="text-success"><i class="fas fa-pen me-2"></i>Câu trả lời:</strong>
            <div class="bg-white p-3 rounded border mt-2 min-vh-20">
              ${
                c.noidung_tra_loi
                  ? c.noidung_tra_loi
                  : '<em class="text-muted">Không có nội dung</em>'
              }
            </div>
          </div>

          ${
            c.hinhanh && c.hinhanh.length > 0
              ? c.hinhanh
                  .map(
                    (img) => `
            <div class="text-center mb-4">
              <img src="data:image/png;base64,${img}" class="img-fluid rounded shadow" style="max-height: 500px;">
            </div>`
                  )
                  .join("")
              : ""
          }

          <div class="mt-4 d-flex align-items-center">
            <label class="fw-bold text-primary me-3">Điểm câu này:</label>
            <input type="number" step="0.25" min="0" max="50"
                   class="form-control diem-cau w-25" style="font-size:1.2rem;"
                   value="${diem.toFixed(2)}" data-macauhoi="${c.macauhoi}">
            <small class="text-muted ms-3"><i class="fas fa-clock"></i> ${
              c.thoigianlam || "—"
            }</small>
          </div>
        </div>
      </div>`;
        });

        $("#noi-dung-tuluan").html(html);
        $("#tong-diem-tuluan").text(tong.toFixed(2));
        $("#diem-tuluan-input").val(tong.toFixed(2));
        $(".diem-cau").first().focus();
      },
      "json"
    );
  }
);

// ==================== 4. TỰ ĐỘNG TÍNH TỔNG ĐIỂM KHI NHẬP ====================
$(document).on("input change", ".diem-cau", function () {
  let tong = 0;
  $(".diem-cau").each(function () {
    const val = parseFloat($(this).val()) || 0;
    tong += val;
  });
  $("#tong-diem-tuluan").text(tong.toFixed(2));
  $("#diem-tuluan-input").val(tong.toFixed(2));
});

// ==================== 5. LƯU ĐIỂM TỰ LUẬN ====================
let isSavingEssayScore = false; // Chống double submit

$("#form-cham-diem-tuluan").on("submit", function (e) {
  e.preventDefault();

  if (isSavingEssayScore) return;
  isSavingEssayScore = true;

  const $btn = $(this).find("button[type=submit]");
  const $activeItem = $("#danh-sach-sinhvien-tuluan .student-item.active");
  const makq = $activeItem.data("makq");

  if (!makq || makq <= 0) {
    Swal.fire("Lỗi", "Vui lòng chọn sinh viên để chấm!", "error");
    isSavingEssayScore = false;
    return;
  }

  // LẤY TỔNG ĐIỂM TỪ Ô INPUT (người dùng nhập)
  const diemTong = parseFloat($("#diem-tuluan-input").val()) || 0;

  // QUAN TRỌNG: Thu thập điểm từng câu để gửi lên lưu chi tiết
  const diemTungCau = {};
  $(".diem-cau").each(function () {
    const macauhoi = $(this).data("macauhoi");
    const diem = parseFloat($(this).val()) || 0;
    if (macauhoi > 0) {
      diemTungCau[macauhoi] = diem;
    }
  });

  // Vô hiệu hóa nút
  $btn
    .prop("disabled", true)
    .html('<span class="spinner-border spinner-border-sm"></span> Đang lưu...');

  $.post("./test/saveEssayScoreAction", {
    makq: makq,
    diem: diemTong, // tổng điểm → lưu vào ketqua.diem_tuluan
    cau: diemTungCau, // điểm từng câu → lưu vào bảng cham_tuluan
  })
    .done(function (res) {
      if (res.success && res.diem_tuluan !== undefined) {
        const diemTuLuan = parseFloat(res.diem_tuluan).toFixed(2);

        Swal.fire({
          icon: "success",
          title: "Thành công!",
          text: `Đã lưu điểm tự luận: ${diemTuLuan} điểm`,
          timer: 1500,
          showConfirmButton: false,
        });

        // Cập nhật badge
        const $badge = $activeItem.find(".badge");
        $badge
          .removeClass("bg-warning text-dark")
          .addClass("bg-success")
          .html(`<i class="fas fa-check me-1"></i>${diemTuLuan}đ`);

        // Cập nhật điểm tự luận trong danh sách sinh viên
        const $cols = $activeItem.find(".text-center strong.fs-5");
        const diemTracNghiem = parseFloat($cols.eq(0).text()) || 0;
        $cols
          .eq(1)
          .text(diemTuLuan)
          .removeClass("text-danger")
          .addClass("text-success");

        // Cập nhật tổng điểm
        const tongDiem = (diemTracNghiem + parseFloat(diemTuLuan)).toFixed(2);
        $activeItem.find(".text-center strong.fs-4").text(tongDiem);

        // Cập nhật lại khu vực chấm bài
        $("#tong-diem-tuluan").text(diemTuLuan);
        $("#diem-tuluan-input").val(diemTuLuan);

        // Cập nhật số bài chưa chấm
        const currentCount = parseInt($("#count-chua-cham").text()) || 0;
        if (currentCount > 0) {
          const newCount = currentCount - 1;
          $("#count-chua-cham").text(newCount);
          if (newCount === 0) $("#count-chua-cham").hide();
        }
      } else {
        Swal.fire("Lỗi", res.message || "Không thể lưu điểm tự luận", "error");
      }
    })
    .fail(function (xhr) {
      console.error("Lỗi AJAX:", xhr.responseText);
      Swal.fire(
        "Lỗi hệ thống",
        "Không kết nối được server. Vui lòng thử lại!",
        "error"
      );
    })
    .always(function () {
      isSavingEssayScore = false;
      $btn.prop("disabled", false).html("Lưu điểm");
    });
});
// Pagination
const mainPagePagination = new Pagination();
mainPagePagination.option.controller = "test";
mainPagePagination.option.model = "KetQuaModel";
mainPagePagination.option.made = made;
mainPagePagination.option.manhom = listGroupID.slice(1);
mainPagePagination.option.limit = 10;
mainPagePagination.option.filter = "present";
mainPagePagination.getPagination(
  mainPagePagination.option,
  mainPagePagination.valuePage.curPage
);
