Dashmix.helpersOnLoad(["jq-select2"]);

Dashmix.onLoad(() =>
  class {
    static initValidation() {
      Dashmix.helpers("jq-validation"),
        jQuery(".form-taodethi").validate({
          rules: {
            "giang-vien": {
              required: !0,
            },
          },
          messages: {
            "giang-vien": {
              required: "Vui lòng chọn giảng viên",
            },
          },
        });
    }
    static init() {
      this.initValidation();
    }
  }.init()
);

// Store assigned (checked) subjects while modal is opening
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

    html += `
      <tr>
        <td class="text-center"><strong>${stt}</strong></td>
        <td class="fw-semibold">${Dashmix.helpers.escapeHtml(
          element["hoten"] || ""
        )}</td>
        <td class="text-center">${Dashmix.helpers.escapeHtml(monhoc_code)}</td>
        <td>${Dashmix.helpers.escapeHtml(element["tenmonhoc"] || "")}</td>
        <td class="text-center">
          <!-- NÚT SỬA -->
          <button type="button" class="btn btn-sm btn-outline-teal me-1 btn-edit-assignment"
                  data-id="${assignment_id}"
                  data-giangvien="${giangvien_id}"
                  data-monhoc="${monhoc_code}"
                  title="Chỉnh sửa">
            <i class="fa fa-edit"></i>
          </button>
          <!-- NÚT XÓA -->
          <button type="button" class="btn btn-sm btn-outline-danger btn-delete-assignment"
                  data-id="${giangvien_id}"
                  data-mamon="${monhoc_code}"
                  title="Xóa">
            <i class="fa fa-trash"></i>
          </button>
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

  if ($("#giang-vien").val() !== "") {
    updateCheckmarkSubject(subject);
  }
}

function updateCheckmarkSubject(checkedSubjects) {
  $("input:checkbox[name=selectSubject]:checked").removeAttr("checked");
  checkedSubjects.forEach(function (subject) {
    $(`input:checkbox[value=${subject}]`).attr("checked", "checked");
  });
}

$(document).ready(function () {
  // Select2 cho modal thêm
  $(".js-select2").select2({
    dropdownParent: $("#modal-add-assignment"),
  });

  // Select2 cho modal sửa (kích hoạt khi modal mở)
  $("#modal-default-vcenter").on("shown.bs.modal", function () {
    $("#edit-giang-vien, #edit-mon-hoc").select2({
      dropdownParent: $("#modal-default-vcenter"),
    });
  });

  $.get(
    "./assignment/getGiangVien",
    function (data) {
      let html = "<option></option>";
      data.forEach((element) => {
        html += `<option value="${element["id"]}">${element["hoten"]}</option>`;
      });
      $("#giang-vien").html(html);
      $("#edit-giang-vien").html(html);
    },
    "json"
  );

  $.get(
    "./assignment/getMonHoc",
    function (data) {
      let html = "<option></option>";
      data.forEach((element) => {
        html += `<option value="${element["mamonhoc"]}">${element["tenmonhoc"]}</option>`;
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
      modalAddAssignmentPagination.valuePage.curPage
    );
  });

  $("#modal-add-assignment").on("hidden.bs.modal", function () {
    subject.clear();
  });

  $("#btn_assignment").click(function () {
    if ($(".form-phancong").valid()) {
      let giangvien = $("#giang-vien").val();
      if (subject.size === 0) {
        deleteAssignmentUser(giangvien);
        mainPagePagination.getPagination(
          mainPagePagination.option,
          mainPagePagination.valuePage.curPage
        );
        $("#modal-add-assignment").modal("hide");
        Dashmix.helpers("jq-notify", {
          type: "success",
          icon: "fa fa-check me-1",
          message: "Phân công thành công! :)",
        });
      } else {
        clearAllAndAddAssignmentUser(giangvien, [...subject]);
      }
    }
  });

  $(document).on("change", "#giang-vien", function (e) {
    let giangvien = $("#giang-vien").val();
    $.ajax({
      type: "post",
      url: "./assignment/getAssignmentByUser",
      data: { id: giangvien },
      dataType: "json",
      success: function (response) {
        subject = new Set(response.map((el) => el.mamonhoc));
        modalAddAssignmentPagination.valuePage.curPage = 1;
        modalAddAssignmentPagination.getPagination(
          modalAddAssignmentPagination.option,
          modalAddAssignmentPagination.valuePage.curPage
        );
      },
      error: function (err) {
        console.error(err.responseText);
      },
    });
  });

  $("#list-subject").on("click", function (e) {
    if (!e.target.closest('input[type=checkbox][name="selectSubject"]')) return;
    const el = e.target;
    const mamonhoc = el.value;
    if (el.checked) {
      subject.add(mamonhoc);
    } else {
      subject.delete(mamonhoc);
    }
  });

  function addAssignment(giangvien, listSubject) {
    $.ajax({
      type: "post",
      url: "./assignment/addAssignment",
      data: { magiangvien: giangvien, listSubject: listSubject },
      dataType: "json",
      success: function (response) {
        $("#modal-add-assignment").modal("hide");
        Dashmix.helpers("jq-notify", {
          type: response ? "success" : "danger",
          message: response
            ? "Phân công thành công! :)"
            : "Phân công thất bại!",
        });
        mainPagePagination.getPagination(
          mainPagePagination.option,
          mainPagePagination.valuePage.curPage
        );
      },
    });
  }

  function deleteAssignmentUser(giangvien) {
    $.ajax({
      type: "post",
      url: "./assignment/deleteAll",
      data: { id: giangvien },
      success: function () {},
    });
  }

  function clearAllAndAddAssignmentUser(giangvien, listSubject) {
    deleteAssignmentUser(giangvien);
    addAssignment(giangvien, listSubject);
  }

  // =================== SỬA PHÂN CÔNG ===================
  $(document).on("click", ".btn-edit-assignment", function () {
    const assignment_id = $(this).data("id");
    const giangvien_id = $(this).data("giangvien");
    const monhoc_code = $(this).data("monhoc");

    $("#assignment_id").val(assignment_id);
    $("#edit-giang-vien").val(giangvien_id).trigger("change");
    $("#edit-mon-hoc").val(monhoc_code).trigger("change");
    $("#modal-default-vcenter").modal("show");
  });

  // =================== LƯU SỬA ===================
  $("#form-edit-assignment").submit(function (e) {
    e.preventDefault();
    $.post(
      "./assignment/update",
      {
        assignment_id: $("#assignment_id").val(),
        magiangvien: $("#edit-giang-vien").val(),
        mamonhoc: $("#edit-mon-hoc").val(),
      },
      function (res) {
        const success = res.success || res == 1;
        Dashmix.helpers("jq-notify", {
          type: success ? "success" : "danger",
          message: success
            ? "Cập nhật phân công thành công!"
            : "Cập nhật thất bại!",
        });
        if (success) {
          $("#modal-default-vcenter").modal("hide");
          mainPagePagination.getPagination(
            mainPagePagination.option,
            mainPagePagination.valuePage.curPage
          );
        }
      },
      "json"
    );
  });

  // =================== XÓA PHÂN CÔNG ===================
  $(document).on("click", ".btn-delete-assignment", function () {
    const id = $(this).data("id");
    const mamon = $(this).data("mamon");

    Swal.fire({
      title: "Xóa phân công?",
      text: "Bạn có chắc chắn muốn xóa phân công này?",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: "Xóa",
      cancelButtonText: "Hủy",
    }).then((result) => {
      if (result.isConfirmed) {
        $.post("./assignment/delete", { id: id, mamon: mamon }, function (res) {
          const success = res.success || res == 1;
          Swal.fire(
            success ? "Thành công!" : "Lỗi!",
            success ? "Xóa phân công thành công!" : "Xóa thất bại!",
            success ? "success" : "error"
          );
          if (success) {
            mainPagePagination.getPagination(
              mainPagePagination.option,
              mainPagePagination.valuePage.curPage
            );
          }
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
