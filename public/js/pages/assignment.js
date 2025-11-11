Dashmix.helpersOnLoad(["jq-select2"]);

Dashmix.onLoad(() =>
  class {
    static initValidation() {
      Dashmix.helpers("jq-validation");
      jQuery(".form-phancong").validate({
        rules: {
          "giang-vien": { required: true },
        },
        messages: {
          "giang-vien": { required: "Vui lòng chọn giảng viên" },
        },
      });
    }
    static init() {
      this.initValidation();
    }
  }.init()
);

let subject = new Set();

function showAssignment(data) {
  if (data.length === 0) {
    $("#listAssignment").html(
      '<tr><td colspan="5" class="text-center text-muted py-4">Chưa có phân công nào</td></tr>'
    );
    $('[data-bs-toggle="tooltip"]').tooltip("dispose");
    return;
  }

  let html = "";
  let limit = this.option?.limit || 10;
  let curPage = this.valuePage?.curPage || 1;
  let offset = (curPage - 1) * limit;

  data.forEach((element, idx) => {
    const stt = offset + idx + 1;
    const assignment_id = element["assignment_id"] || element["id"] || "";
    const giangvien_id =
      element["manguoidung"] || element["giangvien_id"] || "";
    const monhoc_code = element["mamonhoc"] || "";
    const hoten = element["hoten"]
      ? $("<div>").text(element["hoten"]).html()
      : "";
    const tenmonhoc = element["tenmonhoc"]
      ? $("<div>").text(element["tenmonhoc"]).html()
      : "";

    html += `
      <tr>
        <td class="text-center"><strong>${stt}</strong></td>
        <td class="fw-semibold">${hoten}</td>
        <td class="text-center">${$("<div>").text(monhoc_code).html()}</td>
        <td>${tenmonhoc}</td>
        <td class="text-center col-action">
          <a href="javascript:void(0)"
             class="btn btn-sm btn-alt-warning btn-edit btn-edit-assignment me-1"
             data-bs-toggle="tooltip" data-bs-placement="top" title="Chỉnh sửa"
             data-id="${assignment_id}"
             data-giangvien="${giangvien_id}"
             data-monhoc="${monhoc_code}">
            <i class="fa fa-edit"></i>
          </a>
          <a href="javascript:void(0)"
             class="btn btn-sm btn-alt-danger btn-delete btn-delete-assignment"
             data-bs-toggle="tooltip" data-bs-placement="top" title="Xóa"
             data-id="${giangvien_id}"
             data-mamon="${monhoc_code}">
            <i class="fa fa-trash"></i>
          </a>
        </td>
      </tr>`;
  });

  $("#listAssignment").html(html);
  $('[data-bs-toggle="tooltip"]').tooltip();
}

function showSubject(data) {
  if (data.length == 0) {
    $("#list-subject").html("");
    return;
  }
  let html = "";
  data.forEach((element) => {
    html += `<tr>
        <td class="text-center">
            <input class="form-check-input" type="checkbox" name="selectSubject" value="${element["mamonhoc"]}">
        </td>
        <td class="text-center">${element["mamonhoc"]}</td>
        <td>${element["tenmonhoc"]}</td>
        <td class="text-center">${element["sotinchi"]}</td>
        <td class="text-center">${element["sotietlythuyet"]}</td>
        <td class="text-center">${element["sotietthuchanh"]}</td>
    </tr>`;
  });
  $("#list-subject").html(html);
}

function updateCheckmarkSubject(checkedSubjects) {
  $("input:checkbox[name=selectSubject]:checked").removeAttr("checked");
  checkedSubjects.forEach(function (subject) {
    $(`input:checkbox[value=${subject}]`).attr("checked", "checked");
  });
}

$(document).ready(function () {
  $(".js-select2").select2({ dropdownParent: $("#modal-add-assignment") });

  $("#modal-default-vcenter").on("shown.bs.modal", function () {
    $("#edit-giang-vien, #edit-mon-hoc").select2({
      dropdownParent: $("#modal-default-vcenter"),
    });
  });

  $.get(
    "./assignment/getGiangVien",
    function (data) {
      let html = "<option></option>";
      data.forEach((el) => {
        html += `<option value="${el["id"]}">${el["hoten"]}</option>`;
      });
      $("#giang-vien, #edit-giang-vien").html(html);
    },
    "json"
  );

  $.get(
    "./assignment/getMonHoc",
    function (data) {
      let html = "<option></option>";
      data.forEach((el) => {
        html += `<option value="${el["mamonhoc"]}">${el["tenmonhoc"]}</option>`;
      });
      $("#edit-mon-hoc").html(html);
    },
    "json"
  );

  $("#add_assignment").click(function () {
    $("#giang-vien").val("").trigger("change");
    subject.clear();
    modalAddAssignmentPagination.getPagination(
      modalAddAssignmentPagination.option,
      1
    );
  });

  $("#modal-add-assignment").on("hidden.bs.modal", function () {
    subject.clear();
  });

  // Thêm môn mới, kiểm tra trùng
  $("#btn_assignment").click(function () {
    if ($(".form-phancong").valid()) {
      let giangvien = $("#giang-vien").val();
      let listSubject = [...subject];

      if (listSubject.length === 0) {
        Dashmix.helpers("jq-notify", {
          type: "info",
          message: "Chưa chọn môn nào để phân công.",
        });
        return;
      }

      $.post(
        "./assignment/checkDuplicate",
        { magiangvien: giangvien, listSubject: listSubject },
        function (res) {
          const duplicates = res.duplicates || [];
          const newSubjects = listSubject.filter(
            (mh) => !duplicates.includes(mh)
          );

          // Thông báo các môn trùng
          if (duplicates.length > 0) {
            Dashmix.helpers("jq-notify", {
              type: "danger",
              message:
                "Các môn sau đã phân công cho giảng viên này: " +
                duplicates.join(", "),
            });
          }

          // Thêm các môn mới
          if (newSubjects.length > 0) {
            $.post(
              "./assignment/addAssignment",
              { magiangvien: giangvien, listSubject: newSubjects },
              function (res) {
                Dashmix.helpers("jq-notify", {
                  type: "info",
                  message:
                    "Phân công thành công môn: " + newSubjects.join(", "),
                });

                // Nếu có môn mới, đóng form
                $("#modal-add-assignment").modal("hide");

                // Cập nhật lại danh sách
                mainPagePagination.getPagination(
                  mainPagePagination.option,
                  mainPagePagination.valuePage.curPage
                );
              },
              "json"
            );
          }
        },
        "json"
      );
    }
  });

  $(document).on("change", "#giang-vien", function () {
    let giangvien = $(this).val();
    $.post(
      "./assignment/getAssignmentByUser",
      { id: giangvien },
      function (res) {
        subject = new Set(res.map((el) => el.mamonhoc));
        modalAddAssignmentPagination.getPagination(
          modalAddAssignmentPagination.option,
          1
        );
      },
      "json"
    );
  });

  $("#list-subject").on("click", "input[type=checkbox]", function () {
    const val = $(this).val();
    if ($(this).is(":checked")) subject.add(val);
    else subject.delete(val);
  });

  function addAssignment(giangvien, listSubject) {
    $.post(
      "./assignment/addAssignment",
      { magiangvien: giangvien, listSubject: listSubject },
      function (res) {
        $("#modal-add-assignment").modal("hide");
        Dashmix.helpers("jq-notify", {
          type: res.success ? "success" : "danger",
          message:
            res.message ||
            (res.success ? "Phân công thành công!" : "Lỗi thêm môn mới!"),
        });
        mainPagePagination.getPagination(
          mainPagePagination.option,
          mainPagePagination.valuePage.curPage
        );
      },
      "json"
    );
  }

  function deleteAssignmentUser(giangvien) {
    $.post("./assignment/deleteAll", { id: giangvien });
  }

  // Chỉnh sửa phân công
  $(document).on("click", ".btn-edit-assignment", function () {
    const row = $(this).closest("tr");
    const giangvien_id = $(this).data("giangvien");
    const monhoc_code = $(this).data("monhoc");

    row.data("old_gv", giangvien_id);
    row.data("old_mh", monhoc_code);

    $("#edit-giang-vien").val(giangvien_id).trigger("change");
    $("#edit-mon-hoc").val(monhoc_code).trigger("change");
    $("#modal-default-vcenter").modal("show");
  });

  $("#form-edit-assignment").submit(function (e) {
    e.preventDefault();
    const row = $("#listAssignment tr")
      .filter(function () {
        return $(this).data("old_gv") != undefined;
      })
      .first();
    const old_mh = row.data("old_mh");
    const old_gv = row.data("old_gv");

    const newGv = $("#edit-giang-vien").val();
    const newMh = $("#edit-mon-hoc").val();

    // Kiểm tra trùng môn trước khi update
    $.post(
      "./assignment/checkDuplicateForUpdate",
      {
        magiangvien: newGv,
        listSubject: [newMh],
        old_mamonhoc: old_mh,
      },
      function (res) {
        const duplicates = res.duplicates || [];
        if (duplicates.length > 0) {
          Dashmix.helpers("jq-notify", {
            type: "danger",
            message: "Giảng viên đã có môn này: " + duplicates.join(", "),
          });
          return;
        }

        // Nếu không trùng mới update
        $.post(
          "./assignment/update",
          {
            old_mamonhoc: old_mh,
            old_manguoidung: old_gv,
            mamonhoc: newMh,
            magiangvien: newGv,
          },
          function (res) {
            if (res.success) {
              Dashmix.helpers("jq-notify", {
                type: "success",
                message: res.message || "Cập nhật phân công thành công!",
              });
              $("#modal-default-vcenter").modal("hide");
              mainPagePagination.getPagination(
                mainPagePagination.option,
                mainPagePagination.valuePage.curPage
              );
            } else {
              Dashmix.helpers("jq-notify", {
                type: "danger",
                message: res.message || "Cập nhật thất bại!",
              });
            }
          },
          "json"
        );
      },
      "json"
    );
  });

  // Xóa phân công
  $(document).on("click", ".btn-delete-assignment", function () {
    const id = $(this).data("id");
    const mamon = $(this).data("mamon");
    Swal.fire({
      title: "Xóa phân công?",
      text: "Không thể hoàn tác!",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: "Xóa",
    }).then((r) => {
      if (r.isConfirmed) {
        $.post("./assignment/delete", { id: id, mamon: mamon }, function (res) {
          const success = res.success || res == 1;
          Swal.fire(
            success ? "Thành công!" : "Lỗi!",
            success ? "Đã xóa!" : "Xóa thất bại!",
            success ? "success" : "error"
          );
          if (success)
            mainPagePagination.getPagination(
              mainPagePagination.option,
              mainPagePagination.valuePage.curPage
            );
        });
      }
    });
  });
});

// Pagination
const paginationContainer = document.querySelectorAll(".pagination-container");
paginationContainer[0].classList.add(paginationClassName[0]);
paginationContainer[1].classList.add(paginationClassName[1]);

const mainPageNav = document.querySelector(`.${paginationClassName[0]}`);
const mainPageSearchForm = document.getElementById("main-page-search-form");
const modalAssignmentNav = document.querySelector(`.${paginationClassName[1]}`);
const modalAssignmentSearchForm = document.getElementById(
  "modal-add-assignment-search-form"
);

const mainPagePagination = new Pagination(
  mainPageNav,
  mainPageSearchForm,
  showAssignment
);
mainPagePagination.option.controller = "assignment";
mainPagePagination.option.model = "PhanCongModel";
mainPagePagination.option.limit = 10;
mainPagePagination.getPagination(
  mainPagePagination.option,
  mainPagePagination.valuePage.curPage
);

const modalAddAssignmentPagination = new Pagination(
  modalAssignmentNav,
  modalAssignmentSearchForm,
  showSubject
);
modalAddAssignmentPagination.option.controller = "assignment";
modalAddAssignmentPagination.option.model = "PhanCongModel";
modalAddAssignmentPagination.option.custom.function = "monhoc";
modalAddAssignmentPagination.getPagination(
  modalAddAssignmentPagination.option,
  1
);
