Dashmix.helpersOnLoad(["js-flatpickr", "jq-datepicker", "jq-select2"]);
Dashmix.onLoad(() =>
  class {
    static init() {
      // Không cần validation cho môn học
    }
  }.init()
);

// Hiển thị danh sách môn học (chỉ giữ cột cần thiết để mở modal chương)
function showData(subjects) {
  let html = "";
  subjects.forEach((subject) => {
    html += `
      <tr tid="${subject.mamonhoc}">
        <td class="text-center fs-sm"><strong>${subject.mamonhoc}</strong></td>
        <td>${subject.tenmonhoc}</td>
        <td class="d-none d-sm-table-cell text-center fs-sm">${subject.sotinchi}</td>
        <td class="d-none d-sm-table-cell text-center fs-sm">${subject.sotietlythuyet}</td>
        <td class="d-none d-sm-table-cell text-center fs-sm">${subject.sotietthuchanh}</td>
        <td class="text-center col-action">
          <a href="javascript:void(0)"
             class="btn btn-sm btn-alt-primary btn-view-chapter me-1"
             data-bs-toggle="tooltip" data-bs-placement="top"
             title="Quản lý chương"
             data-bs-target="#modal-chapter"
             data-mamonhoc="${subject.mamonhoc}">
            <i class="fa fa-folder-open text-primary fs-5"></i>
          </a>
        </td>
      </tr>`;
  });
  $("#list-subject").html(html);
  $('[data-bs-toggle="tooltip"]').tooltip();
}

$(document).ready(function () {
  // ==================== QUẢN LÝ CHƯƠNG ====================

  // Mở modal quản lý chương
  $(document).on("click", ".btn-view-chapter", function () {
    var mamonhoc = $(this).data("mamonhoc");
    $("#mamon_chuong").val(mamonhoc);
    showChapter(mamonhoc);
    $("#modal-chapter").modal("show");
  });

  // Reset form khi đóng modal
  function resetFormChapter() {
    $("#collapseChapter").collapse("hide");
    $("#name_chapter").val("");
    $("#machuong").val("");
    $("#add-chapter").show();
    $("#edit-chapter").hide();
  }

  $("#modal-chapter").on("hidden.bs.modal", function () {
    resetFormChapter();
  });

  // Hiển thị danh sách chương
  function showChapter(mamonhoc) {
    $.ajax({
      type: "post",
      url: "./view_subject/getAllChapter",
      data: { mamonhoc: mamonhoc },
      dataType: "json",
      success: function (response) {
        let html = "";
        if (response.length > 0) {
          response.forEach((chapter, index) => {
            html += `<tr>
                        <td class="text-center fs-sm"><strong>${
                          index + 1
                        }</strong></td>
                        <td>${chapter["tenchuong"]}</td>
                        <td class="text-center col-action">
                            <a data-role="chuong" data-action="update" class="btn btn-sm btn-alt-secondary chapter-edit"
                                data-bs-toggle="tooltip" data-bs-original-title="Sửa" data-id="${
                                  chapter["machuong"]
                                }">
                                <i class="fa fa-fw fa-pencil"></i>
                            </a>
                            <a data-role="chuong" data-action="delete" class="btn btn-sm btn-alt-secondary chapter-delete"
                                data-bs-toggle="tooltip" data-bs-original-title="Xóa" data-id="${
                                  chapter["machuong"]
                                }">
                                <i class="fa fa-fw fa-times"></i>
                            </a>
                        </td>
                    </tr>`;
          });
        } else {
          html = `<tr><td colspan="3" class="text-center">
                    <img style="width:180px" src="./public/media/svg/empty_data.png" alt=""/>
                    <p class="text-center mt-3">Không có dữ liệu</p>
                  </td></tr>`;
        }
        $("#showChapper").html(html);
        $('[data-bs-toggle="tooltip"]').tooltip();
      },
    });
  }

  // Mở form thêm chương
  $("#btn-add-chapter").click(function () {
    $("#add-chapter").show();
    $("#edit-chapter").hide();
    $("#name_chapter").val("");
    $("#collapseChapter").collapse("show");
  });

  // Thêm chương mới
  $("#add-chapter").on("click", function (e) {
    e.preventDefault();
    let mamonhoc = $("#mamon_chuong").val();
    let tenchuong = $("#name_chapter").val().trim();

    if (tenchuong === "") {
      Dashmix.helpers("jq-notify", {
        type: "danger",
        icon: "fa fa-times me-1",
        message: "Tên chương không được để trống!",
      });
      return;
    }

    $.ajax({
      type: "post",
      url: "./view_subject/addChapter",
      data: { mamonhoc: mamonhoc, tenchuong: tenchuong },
      success: function (response) {
        if (response) {
          Dashmix.helpers("jq-notify", {
            type: "success",
            icon: "fa fa-check me-1",
            message: "Thêm chương thành công!",
          });
          resetFormChapter();
          showChapter(mamonhoc);
        } else {
          Dashmix.helpers("jq-notify", {
            type: "danger",
            icon: "fa fa-times me-1",
            message: "Thêm chương thất bại!",
          });
        }
      },
    });
  });

  // Đóng form nhập
  $(document).on("click", ".close-chapter", function (e) {
    e.preventDefault();
    $("#collapseChapter").collapse("hide");
  });

  // Xóa chương
  $(document).on("click", ".chapter-delete", function () {
    let machuong = $(this).data("id");
    let mamonhoc = $("#mamon_chuong").val();

    Swal.fire({
      title: "Xác nhận xóa?",
      text: "Bạn có chắc muốn xóa chương này?",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: "Xóa",
      cancelButtonText: "Hủy",
      customClass: {
        confirmButton: "btn btn-danger m-1",
        cancelButton: "btn btn-secondary m-1",
      },
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          type: "post",
          url: "./view_subject/chapterDelete",
          data: { machuong: machuong },
          success: function (response) {
            if (response) {
              Dashmix.helpers("jq-notify", {
                type: "success",
                icon: "fa fa-check me-1",
                message: "Xóa chương thành công!",
              });
              showChapter(mamonhoc);
            } else {
              Dashmix.helpers("jq-notify", {
                type: "danger",
                icon: "fa fa-times me-1",
                message: "Xóa chương thất bại!",
              });
            }
          },
        });
      }
    });
  });

  // Sửa chương: điền dữ liệu vào form
  $(document).on("click", ".chapter-edit", function () {
    let id = $(this).data("id");
    let name = $(this).closest("tr").find("td:eq(1)").text();

    $("#machuong").val(id);
    $("#name_chapter").val(name);
    $("#add-chapter").hide();
    $("#edit-chapter").show();
    $("#collapseChapter").collapse("show");

    setTimeout(() => {
      document.getElementById("collapseChapter").scrollIntoView({
        behavior: "smooth",
        block: "start",
      });
    }, 300);
  });

  // Cập nhật chương
  $("#edit-chapter").on("click", function (e) {
    e.preventDefault();
    let machuong = $("#machuong").val();
    let tenchuong = $("#name_chapter").val().trim();
    let mamonhoc = $("#mamon_chuong").val();

    if (tenchuong === "") {
      Dashmix.helpers("jq-notify", {
        type: "danger",
        icon: "fa fa-times me-1",
        message: "Tên chương không được để trống!",
      });
      return;
    }

    $.ajax({
      type: "post",
      url: "./view_subject/updateChapter",
      data: { machuong: machuong, tenchuong: tenchuong },
      success: function (response) {
        if (response) {
          Dashmix.helpers("jq-notify", {
            type: "success",
            icon: "fa fa-check me-1",
            message: "Cập nhật chương thành công!",
          });
          resetFormChapter();
          showChapter(mamonhoc);
        } else {
          Dashmix.helpers("jq-notify", {
            type: "danger",
            icon: "fa fa-times me-1",
            message: "Cập nhật chương thất bại!",
          });
        }
      },
    });
  });

  // ==================== PHÂN TRANG (GIỮ NGUYÊN) ====================
  const mainPagePagination = new Pagination();
  mainPagePagination.option.controller = "view_subject";
  mainPagePagination.option.model = "MonHocModel";
  mainPagePagination.option.limit = 10;
  mainPagePagination.getPagination(
    mainPagePagination.option,
    mainPagePagination.valuePage.curPage
  );
});
