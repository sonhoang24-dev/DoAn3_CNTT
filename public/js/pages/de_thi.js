$(document).ready(function () {
  let questions = [];
  let answers = [];
  const made = $("#dethicontent").data("id");
  const userId = $("#dethicontent").data("user") || "guest"; // Lấy MSSV từ data-user

  // Key giờ độc nhất cho từng người + từng đề
  const dethiKey = `dethi_${made}_user_${userId}`;
  const answerKey = `cautraloi_${made}_user_${userId}`;
  // ==================== INDEXEDDB - LƯU ẢNH TẠM ====================
  let db;
  const dbName = "exam-images-db-v2";
  // ==================== INDEXEDDB SIÊU TỐI ƯU ====================
  const dbPromise = idb.openDB("exam-images-db-v3", 3, {
    upgrade(db, oldVersion) {
      if (oldVersion < 1) {
        // Tạo store lần đầu
        const store = db.createObjectStore("images", {
          keyPath: "id",
          autoIncrement: true,
        });
        store.createIndex("by_exam_user_question", [
          "answerKey",
          "questionIndex",
        ]);
      } else if (oldVersion < 3) {
        // Nếu store đã tồn tại nhưng thiếu index (phòng trường hợp nâng version)
        const store = db.transaction.objectStore("images");
        if (!store.indexNames.contains("by_exam_user_question")) {
          store.createIndex("by_exam_user_question", [
            "answerKey",
            "questionIndex",
          ]);
        }
      }
    },
  });

  // Lưu một ảnh vào IndexedDB
  async function saveImageToDB(questionIndex, base64Data) {
    if (
      !base64Data ||
      typeof base64Data !== "string" ||
      base64Data.length < 100
    ) {
      console.warn("Base64 không hợp lệ");
      return;
    }

    const db = await dbPromise;
    await db.add("images", {
      answerKey,
      questionIndex,
      base64: base64Data.trim(),
      timestamp: Date.now(),
    });
  }

  // Lấy tất cả ảnh của 1 câu hỏi
  async function getImagesForQuestion(questionIndex) {
    const db = await dbPromise;
    const range = IDBKeyRange.bound(
      [answerKey, questionIndex],
      [answerKey, questionIndex, []]
    );
    return await db.getAllFromIndex("images", "by_exam_user_question", range);
  }

  // Xóa một ảnh theo base64 (khi người dùng bấm nút xóa)
  async function deleteImageFromDB(questionIndex, base64ToDelete) {
    if (!db) return;
    const all = await db.getAll("images");
    const toDelete = all.find(
      (img) =>
        img.answerKey === answerKey &&
        img.questionIndex === questionIndex &&
        img.base64 === base64ToDelete
    );
    if (toDelete) await db.delete("images", toDelete.id);
  }

  // Xóa toàn bộ ảnh của đề thi này (khi nộp bài thành công)
  async function clearImagesForExam() {
    if (!db) return;
    const all = await db.getAll("images");
    const tx = db.transaction("images", "readwrite");
    all
      .filter((img) => img.answerKey === answerKey)
      .forEach((img) => tx.store.delete(img.id));
    await tx.done;
  }

  function compressImage(base64) {
    return new Promise((resolve) => {
      const img = new Image();
      img.onload = () => {
        const canvas = document.createElement("canvas");
        const MAX_WIDTH = 1400;
        let { width, height } = img;
        if (width > MAX_WIDTH) {
          height = (MAX_WIDTH / width) * height;
          width = MAX_WIDTH;
        }
        canvas.width = width;
        canvas.height = height;
        const ctx = canvas.getContext("2d");
        ctx.drawImage(img, 0, 0, width, height);
        resolve(canvas.toDataURL("image/jpeg", 0.8));
      };
      img.src = base64;
    });
  }

  // Khởi động DB
  (async () => {
    try {
      db = await dbPromise;
      console.log("IndexedDB sẵn sàng – có thể lưu ảnh lớn!");
    } catch (err) {
      console.error("Lỗi IndexedDB:", err);
    }
  })();

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
      cautraloi: q.loai !== "essay" ? 0 : null, // Null cho essay
      noidungtl: q.loai === "essay" ? "" : null,
    }));
  }
  // ================== Render sidebar ==================
  // THAY TOÀN BỘ hàm showBtnSideBar bằng đoạn này:
  async function showBtnSideBar(questions, answers) {
    let html = "";

    for (const [i, q] of questions.entries()) {
      const ans = answers[i] || {};
      let isActive = "";

      if (q.loai === "essay") {
        const hasText = !!(
          ans.noidungtl &&
          ans.noidungtl.toString().trim().length > 0 &&
          ans.noidungtl !== "<p></p>" &&
          ans.noidungtl !== "<p><br></p>"
        );

        let hasImage = false;
        try {
          const images = await getImagesForQuestion(i);
          hasImage = images.length > 0;
        } catch (err) {
          console.warn("Lỗi kiểm tra ảnh câu " + (i + 1), err);
        }

        if (hasText || hasImage) {
          isActive = " active";
        }
      } else {
        // Trắc nghiệm
        if (ans.cautraloi && ans.cautraloi !== 0) {
          isActive = " active";
        }
      }

      const questionId =
        q.loai === "reading" ? `reading-q${i + 1}` : `c${i + 1}`;

      html += `<li class="answer-item p-1">
               <a href="javascript:void(0)" 
                  class="answer-item-link btn btn-outline-primary w-100 btn-sm${isActive}" 
                  data-target="${questionId}">${i + 1}</a>
             </li>`;
    }

    $(".answer").html(html);
  }
  // Click scroll
  $(document).on("click", ".answer-item-link", function () {
    const targetId = $(this).data("target");
    const el = document.getElementById(targetId);
    if (el) el.scrollIntoView({ behavior: "smooth" });
  });

  // ================== Render câu hỏi + CKEditor + MCQ ==================
  async function showListQuestion(questions, answers) {
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
          groupHtml += `</div>`;
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
        groupHtml += `</div></div>`;
        groupHtml += `</div>`;
      } else {
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
          <p class="mb-3 fw-bold fs-5">Trả lời tại đây:</p>
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
                //localStorage.setItem(answerKey, JSON.stringify(answers));
              };

              async function renderImages(qIndex) {
                const previewContainer = document.getElementById(thumbId);
                if (!previewContainer) return;

                try {
                  const images = await getImagesForQuestion(qIndex);
                  previewContainer.innerHTML = "";

                  if (images.length === 0) {
                    previewContainer.innerHTML = `<small class="text-muted no-image-text">Chưa có ảnh nào được chọn</small>`;
                    return;
                  }

                  images.forEach((imgObj, i) => {
                    const base64String = imgObj.base64;
                    if (!base64String || typeof base64String !== "string")
                      return;

                    const wrapper = document.createElement("div");
                    wrapper.className =
                      "position-relative d-inline-block me-3 mb-3";

                    const img = document.createElement("img");
                    img.src = base64String;
                    img.className = "img-thumbnail rounded";
                    img.style.cssText =
                      "max-height:150px; max-width:200px; object-fit:cover; cursor:pointer;";
                    img.onclick = () => {
                      // Click để xem ảnh lớn hơn (tùy chọn)
                      window.open(base64String, "_blank");
                    };

                    const btnDelete = document.createElement("button");
                    btnDelete.className =
                      "btn btn-danger btn-sm rounded-circle position-absolute";
                    btnDelete.style.cssText =
                      "top:5px; right:5px; width:28px; height:28px; font-size:16px; line-height:1;";
                    btnDelete.innerHTML = "×";
                    btnDelete.onclick = async (e) => {
                      e.stopPropagation();

                      await deleteImageFromDB(qIndex, base64String);

                      const currentData = editor.getData();
                      const tempDiv = document.createElement("div");
                      tempDiv.innerHTML = currentData;

                      const imgs = tempDiv.querySelectorAll("img");
                      imgs.forEach((img) => {
                        if (img.src === base64String) {
                          img.parentElement?.remove();
                        }
                      });

                      editor.setData(tempDiv.innerHTML);

                      await renderImages(qIndex);
                      saveAnswersToStorage();
                      await showBtnSideBar(questions, answers);
                    };

                    wrapper.appendChild(img);
                    wrapper.appendChild(btnDelete);
                    previewContainer.appendChild(wrapper);
                  });
                } catch (err) {
                  console.error("Lỗi render ảnh:", err);
                }
              }

              let debounceTimer;
              editor.model.document.on("change:data", async () => {
                const html = editor.getData();
                const div = document.createElement("div");
                div.innerHTML = html;
                const cleanText = div.innerText.trim();

                answers[index].noidungtl = cleanText || null;
                saveAnswersToStorage();

                // Cập nhật sidebar nếu có text hoặc ảnh
                const images = await getImagesForQuestion(index);
                if (cleanText || images.length > 0) {
                  $(`.answer-item-link[data-target="c${index + 1}"]`).addClass(
                    "active"
                  );
                }

                await showBtnSideBar(questions, answers);
              });

              // ==================== XỬ LÝ ẢNH TRONG CKEDITOR ====================
              fileInput.addEventListener("change", async function (e) {
                const files = Array.from(e.target.files);
                if (files.length === 0) return;

                const currentCount = (await getImagesForQuestion(index)).length;
                if (currentCount + files.length > 12) {
                  alert("Tối đa 12 ảnh mỗi câu!");
                  return;
                }

                for (const file of files) {
                  if (file.size > 20 * 1024 * 1024) {
                    alert(`Ảnh ${file.name} quá lớn (>20MB)`);
                    continue;
                  }

                  const reader = new FileReader();
                  reader.onload = async (ev) => {
                    const originalBase64 = ev.target.result;
                    const compressedBase64 = await compressImage(
                      originalBase64
                    );

                    // Chèn ảnh vào CKEditor
                    const imgTag = `<p><img src="${compressedBase64}" style="max-width:100%;height:auto;"></p>`;
                    editor.setData(editor.getData() + imgTag);

                    // Lưu vào IndexedDB
                    await saveImageToDB(index, compressedBase64);

                    // Cập nhật preview
                    await renderImages(index); // thêm await cho chắc
                    saveAnswersToStorage();

                    // THÊM DÒNG NÀY – BẮT BUỘC PHẢI CÓ!
                    await showBtnSideBar(questions, answers);
                  };
                  reader.readAsDataURL(file);
                }
                this.value = "";
              });

              syncImages();
              renderImages();
            });

            // === TỰ ĐỘNG CHÈN LẠI ẢNH VÀO CKEDITOR KHI LOAD LẠI TRANG ===
            (async () => {
              const savedImages = await getImagesForQuestion(index);
              if (savedImages.length > 0) {
                let imgTags = "";
                savedImages.forEach((imgObj) => {
                  if (imgObj.base64) {
                    imgTags += `<p><img src="${imgObj.base64}" style="max-width:100%;height:auto;"></p>`;
                  }
                });
                // Chèn vào cuối nội dung hiện tại
                editor.getData();
                editor.model.change((writer) => {
                  const insertPosition =
                    editor.model.document.selection.getFirstPosition();
                  editor.model.insertContent(
                    writer.createText(imgTags),
                    insertPosition
                  );
                });
                // Hoặc đơn giản hơn:
                setTimeout(() => {
                  const current = editor.getData();
                  editor.setData(current + imgTags);
                }, 300);
              }
              await renderImages(index); // hiển thị preview
            })();
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
          html += `</div></div></div>`;
        }
      }
    });

    // Đóng nhóm reading cuối
    if (groupHtml)
      html += `<div class="reading-group rounded border mb-3 bg-white p-3">${groupHtml}</div>`;

    $("#list-question").html(html);

    $(".btn-check").off("change");
    $(document)
      .off("change", ".btn-check")
      .on("change", ".btn-check", async function () {
        const idx = $(this).data("index");
        const macautl = $(this).data("macautl");
        const quesIndex = idx + 1; // For toast
        const label = $("label[for='" + $(this).attr("id") + "']")
          .text()
          .trim();

        if (!answers[idx]) answers[idx] = {};
        answers[idx].cautraloi = macautl;
        localStorage.setItem(answerKey, JSON.stringify(answers));
        await showBtnSideBar(questions, answers);

        $("label[for='" + $(this).attr("id") + "']")
          .addClass("btn-warning")
          .siblings("label")
          .removeClass("btn-warning")
          .css("background-color", "");

        showAnswerToast(quesIndex, label);
      });

    await showBtnSideBar(questions, answers);
  }
  function saveAnswersToStorage() {
    const safeData = listQues.map((q, i) => {
      const ans = answers[i] || {};
      return {
        macauhoi: q.macauhoi,
        cautraloi: ans.cautraloi ?? (q.loai === "essay" ? null : 0),
        noidungtl: ans.noidungtl ?? (q.loai === "essay" ? "" : null),
      };
    });

    try {
      localStorage.setItem(answerKey, JSON.stringify(safeData));
    } catch (e) {
      console.warn("Không lưu được vào localStorage", e);
    }
  }

  // ================= Khởi tạo và render lần đầu =================
  $.when(getQuestion()).done(async () => {
    let savedQ = localStorage.getItem(dethiKey);
    let savedA = localStorage.getItem(answerKey);

    if (savedQ) {
      listQues = JSON.parse(savedQ);
    } else {
      // THÊM DÒNG NÀY: đánh số thứ tự hiển thị
      questions.forEach((q, index) => {
        q.displayOrder = index + 1; // thứ tự hiển thị trên giao diện
      });

      listQues = questions;
      localStorage.setItem(dethiKey, JSON.stringify(listQues));
    }

    if (savedA) {
      const savedAnswersRaw = JSON.parse(savedA);
      // Tái tạo mảng answers đúng thứ tự theo listQues
      answers = listQues.map((q) => {
        const saved = savedAnswersRaw.find((s) => s.macauhoi === q.macauhoi);
        if (saved) {
          return {
            cautraloi: saved.cautraloi || (q.loai === "essay" ? null : 0),
            noidungtl: saved.noidungtl || (q.loai === "essay" ? "" : null),
          };
        }
        return {
          cautraloi: q.loai === "essay" ? null : 0,
          noidungtl: q.loai === "essay" ? "" : null,
        };
      });
    } else {
      answers = initListAnswer(listQues);
    }

    localStorage.setItem(
      answerKey,
      JSON.stringify(
        listQues.map((q, i) => ({
          macauhoi: q.macauhoi,
          cautraloi: answers[i]?.cautraloi ?? (q.loai === "essay" ? null : 0),
          noidungtl: answers[i]?.noidungtl ?? (q.loai === "essay" ? "" : null),
        }))
      )
    );

    await showListQuestion(listQues, answers);
    await showBtnSideBar(listQues, answers);
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

  $("#btn-nop-bai").click(async function (e) {
    e.preventDefault();

    const savedAnswers = JSON.parse(localStorage.getItem(answerKey) || "[]");
    const unanswered = [];

    for (let i = 0; i < listQues.length; i++) {
      const q = listQues[i];
      const ans = savedAnswers[i] || {};

      if (q.loai === "essay") {
        // Kiểm tra có text (loại bỏ thẻ rỗng)
        const hasText =
          ans.noidungtl &&
          typeof ans.noidungtl === "string" &&
          ans.noidungtl.trim().length > 0 &&
          !["<p></p>", "<p><br></p>", "<br>", ""].includes(
            ans.noidungtl.trim()
          );

        let hasImage = false;
        try {
          const images = await getImagesForQuestion(i);
          hasImage = images.length > 0;
        } catch (e) {}

        if (!hasText && !hasImage) {
          unanswered.push(i + 1); // ← câu essay chưa làm gì cả
        }
        // ← KẾT THÚC if essay, không có else ở đây nữa
      } else {
        // Đây là trắc nghiệm hoặc reading
        if (!ans.cautraloi || ans.cautraloi === 0) {
          unanswered.push(i + 1);
        }
      }
    }

    if (unanswered.length > 0) {
      Swal.fire({
        icon: "warning",
        title: "Chưa hoàn thành!",
        html: `<p class='fs-6 text-center mb-0'>Bạn chưa trả lời <strong>${
          unanswered.length
        }</strong> câu:<br>
              <span class="text-danger fw-bold fs-5">${unanswered.join(
                ", "
              )}</span></p>`,
        confirmButtonText: "OK",
      });
      return;
    }

    // Xác nhận nộp bài
    Swal.fire({
      title:
        "<center><p class='fs-3 mb-0'>Bạn có chắc chắn muốn nộp bài?</p></center>",
      html: "<p class='text-muted fs-6 text-center mb-0'>Khi xác nhận nộp bài, bạn sẽ không thể sửa lại!</p>",
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

  async function nopbai() {
    const formData = new FormData();
    const savedAnswers = JSON.parse(localStorage.getItem(answerKey) || "[]");

    formData.append("made", made);
    formData.append("thoigian", new Date().toISOString());

    // ==== TRẮC NGHIỆM + READING (LOẠI TRỪ ESSAY) ====
    const tracnghiem = [];
    for (let i = 0; i < listQues.length; i++) {
      const q = listQues[i];
      if (q.loai === "essay") continue;

      const savedAns = savedAnswers[i] || {};
      tracnghiem.push({
        macauhoi: q.macauhoi,
        thutu: q.displayOrder || i + 1, // Dùng displayOrder thay vì thutu DB
        cautraloi: savedAns.cautraloi || 0,
      });
    }

    formData.append("listCauTraLoi", JSON.stringify(tracnghiem));

    // ==== TỰ LUẬN + ẢNH ====
    for (let i = 0; i < listQues.length; i++) {
      const q = listQues[i];
      if (q.loai !== "essay") continue;

      const ans = savedAnswers[i] || {};
      const text = (ans.noidungtl || "").trim();
      let images = [];

      try {
        images = await getImagesForQuestion(i);
      } catch (err) {}

      if (images.length > 0 || text) {
        formData.append(`essay_${i}_exists`, "1");
        formData.append(`essay_${i}_macauhoi`, q.macauhoi);
        formData.append(`essay_${i}_thutu`, q.displayOrder || i + 1);
        formData.append(`essay_${i}_noidung`, text || "");

        images.forEach((imgObj, j) => {
          if (imgObj?.base64) {
            const pure = imgObj.base64.includes(",")
              ? imgObj.base64.split(",")[1]
              : imgObj.base64;
            if (pure && pure.length > 100) {
              formData.append(`essay_${i}_image_${j}`, pure);
            }
          }
        });
      }
    }

    // ==== LOG + XÁC NHẬN ====
    // console.clear();
    // console.log(
    //   "%c DỮ LIỆU SẮP GỬI ĐI KHI NỘP BÀI",
    //   "font-size:18px;color:#e91e63;font-weight:bold"
    // );
    // console.table(
    //   tracnghiem.map((x, idx) => ({
    //     STT: idx + 1,
    //     macauhoi: x.macauhoi,
    //     thutu: x.thutu,
    //     cautraloi: x.cautraloi,
    //     "Đúng/Sai": x.cautraloi === 0 ? "Chưa chọn" : "Đã chọn",
    //   }))
    // );

    // // ==== XÁC NHẬN GỬI ====
    // const confirmSend = await Swal.fire({
    //   title: "XEM DỮ LIỆU GỬI ĐI",
    //   html: `<p>Đã in dữ liệu ra Console (F12)</p>
    //        <p class="text-success">listCauTraLoi có <strong>${tracnghiem.length}</strong> câu</p>
    //        <p>Bạn có muốn <strong>gửi thật</strong> không?</p>`,
    //   icon: "question",
    //   showCancelButton: true,
    //   confirmButtonText: "Gửi thật",
    //   cancelButtonText: "Chỉ xem thôi",
    //   allowOutsideClick: false,
    //   allowEscapeKey: false,
    // });

    // if (!confirmSend.isConfirmed) {
    //   Swal.fire("Đã hủy", "Không gửi bài. Bạn có thể kiểm tra lại.", "info");
    //   return;
    // }

    // ==== GỬI LÊN SERVER ====
    try {
      const res = await fetch("test/submit", {
        method: "POST",
        body: formData,
      }).then((r) => r.json());
      if (res.success) {
        await clearImagesForExam();
        clearExamData();
        Swal.fire("Thành công!", "Bài thi đã được nộp.", "success").then(() => {
          location.href = `./test/start/${made}`;
        });
      } else {
        throw new Error(res.message || "Lỗi server");
      }
    } catch (err) {
      console.error(err);
      Swal.fire("Lỗi!", "Không thể nộp bài: " + err.message, "error");
    }
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
  // ==================== CHỐNG CHUYỂN TAB - KHÔNG BỊ LỖI KHI CHỌN ẢNH ====================
  let isSelectingFile = false;
  let tabSwitchCount = parseInt(
    localStorage.getItem("isTabSwitched_" + made) || "0",
    10
  );
  let hasWarned = false;

  $(document).on("click", "input[type=file]", function () {
    isSelectingFile = true;
    setTimeout(() => {
      isSelectingFile = false;
    }, 4000);
  });

  // Blur thật sự (chuyển tab, Alt+Tab, vuốt đa nhiệm…)
  $(window).on("blur", function () {
    if (isSelectingFile) {
      isSelectingFile = false;
      return;
    }

    // Đây là chuyển tab thật
    tabSwitchCount++;

    $.ajax({
      type: "post",
      url: "./test/chuyentab",
      data: { made: $("#dethicontent").data("id") },
      timeout: 6000,
    })
      .done(function (res) {
        if (res == 1) {
          if (!hasWarned) {
            hasWarned = true;
            Swal.fire({
              icon: "error",
              title: "Phát hiện rời khỏi bài thi!",
              text: "Hệ thống tự động nộp bài vì bạn đã rời khỏi cửa sổ thi.",
              allowOutsideClick: false,
              allowEscapeKey: false,
              confirmButtonText: "OK",
            }).then(() => nopbai());
          }
        } else {
          localStorage.setItem("isTabSwitched_" + made, tabSwitchCount);

          // if (tabSwitchCount === 1 && !hasWarned) {
          //   hasWarned = true;
          //   Swal.fire({
          //     icon: "warning",
          //     title: "Cảnh báo lần 1",
          //     html: "Bạn đã rời khỏi cửa sổ bài thi.<br><b>Lần sau sẽ bị nộp bài tự động!</b>",
          //     confirmButtonText: "Tôi hiểu",
          //   });
          // }
          // else if (tabSwitchCount >= 2) {
          //   Swal.fire({
          //     icon: "error",
          //     title: "Nhiều lần rời khỏi bài thi",
          //     text: "Hệ thống tự động nộp bài để đảm bảo công bằng.",
          //     allowOutsideClick: false,
          //     allowEscapeKey: false,
          //   }).then(() => nopbai());
          // }
        }
      })
      .fail(function () {
        localStorage.setItem("isTabSwitched_" + made, tabSwitchCount);
      });
  });

  //
  // Xóa dữ liệu khi rời khỏi trang
  // window.addEventListener("beforeunload", function () {
  //   localStorage.removeItem(answerKey);
  //   localStorage.removeItem(dethiKey);
  //   localStorage.removeItem("isTabSwitched_" + made);
  // });

  //chặn load trang
  // $(document).on("keydown", function (e) {
  //   if (
  //     e.which === 116 || // F5
  //     ((e.ctrlKey || e.metaKey) && e.which === 82) // Ctrl+R / Cmd+R
  //   ) {
  //     e.preventDefault();

  //     Swal.fire({
  //       icon: "warning",
  //       title: "Không thể tải lại!",
  //       html: `<p class='fs-6 text-center mb-0'>Bạn không thể tải lại trang khi đang làm bài.</p>`,
  //       confirmButtonText: "OK",
  //     });
  //   }
  // });

  // history.pushState(null, null, location.href);
  // window.onpopstate = function () {
  //   history.go(1);

  //   Swal.fire({
  //     icon: "warning",
  //     title: "Không thể quay lại!",
  //     html: `<p class='fs-6 text-center mb-0'>Bạn không thể quay lại trang trước khi hoàn thành bài thi.</p>`,
  //     confirmButtonText: "OK",
  //   });
  // };
  // window.addEventListener("beforeunload", function (e) {
  //   // Kiểm tra xem bài chưa hoàn thành
  //   const answers = JSON.parse(localStorage.getItem(answerKey) || "[]");
  //   const unanswered = answers.filter((ans, i) => {
  //     const q = questions[i];
  //     if (q.loai === "essay") return !ans.noidungtl?.trim();
  //     return ans.cautraloi === 0;
  //   });

  //   if (unanswered.length > 0) {
  //     Swal.fire({
  //       icon: "warning",
  //       title: "Bài chưa hoàn thành!",
  //       html: `<p class='fs-6 text-center mb-0'>Bạn chưa hoàn thành <strong>${unanswered.length}</strong> câu hỏi.<br>Vui lòng hoàn thành trước khi rời trang.</p>`,
  //       confirmButtonText: "OK",
  //     });

  //     e.preventDefault();
  //     e.returnValue = ""; // Chrome hiện cảnh báo mặc định
  //     return "";
  //   }
  // });
});
