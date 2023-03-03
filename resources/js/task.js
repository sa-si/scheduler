import { indexOf, keysIn } from "lodash";
import MicroModal from "micromodal";
const current_path = location.pathname;

window.onload = function () {
    var target = document.getElementsByClassName("js_initial-disable-wrapper");
    Array.prototype.forEach.call(target, function (element) {
        var inputTypeRadio = element.querySelector("input[type='radio']");
        var itemsParent =
            element.getElementsByClassName("js_initial-disable")[0];
        var initialInputItems = itemsParent.getElementsByTagName("input");
        var initialSelectItems = itemsParent.getElementsByTagName("select");

        if (inputTypeRadio.checked) {
            for (var i = 0; i < initialInputItems.length; i++) {
                initialInputItems[i].removeAttribute("disabled");
            }

            for (var i = 0; i < initialSelectItems.length; i++) {
                initialSelectItems[i].removeAttribute("disabled");
            }
        } else {
            for (var i = 0; i < initialInputItems.length; i++) {
                initialInputItems[i].setAttribute("disabled", true);
            }

            for (var i = 0; i < initialSelectItems.length; i++) {
                initialSelectItems[i].setAttribute("disabled", true);
            }
        }
    });
};
window.globalFunctions = {};
window.globalFunctions.toggleEnableAndDisable = toggleEnableAndDisable;

function toggleEnableAndDisable(enable = [], disable = []) {
    // enable
    if (enable) {
        enable.forEach((element) => {
            var enableInputItem = document.getElementById(element) ?? "";
            if (
                enableInputItem.tagName === "INPUT" &&
                enableInputItem.disabled === true
            ) {
                enableInputItem.removeAttribute("disabled");
                if (enableInputItem.checked) {
                    disabledItem = document
                        .getElementById(enableInputItem.id + "_input")
                        .getElementsByTagName("input");
                    for (var i = 0; i < disabledItem.length; i++) {
                        if (disabledItem[i].disabled === true) {
                            disabledItem[i].removeAttribute("disabled");
                        }
                    }
                }
            } else {
                var enableInputItem = enableInputItem
                    ? enableInputItem.getElementsByTagName("input")
                    : "";
            }

            if (enableInputItem && enableInputItem.tagName !== "INPUT") {
                for (var i = 0; i < enableInputItem.length; i++) {
                    if (enableInputItem[i].disabled === true) {
                        enableInputItem[i].removeAttribute("disabled");
                    }
                }
            }

            var enableSelectItem = document.getElementById(element) ?? "";
            if (
                enableSelectItem.tagName === "SELECT" &&
                enableSelectItem.disabled === true
            ) {
                enableSelectItem.removeAttribute("disabled");
            } else {
                var enableSelectItem = enableSelectItem
                    ? enableSelectItem.getElementsByTagName("select")
                    : "";
            }
            if (enableSelectItem && enableSelectItem.tagName !== "SELECT") {
                for (var i = 0; i < enableSelectItem.length; i++) {
                    if (enableSelectItem[i].disabled === true) {
                        enableSelectItem[i].removeAttribute("disabled");
                    }
                }
            }
        });
    }
    // disable
    // id=disableの子要素inputを全て抽出して配列disableInputItemオブジェクトに代入する
    if (disable) {
        disable.forEach((element) => {
            var disableInputItem = document.getElementById(element) ?? "";
            var disableInputItem = disableInputItem
                ? disableInputItem.getElementsByTagName("input")
                : "";
            ("");
            // 取得したinput要素にdisabled属性をつける
            if (disableInputItem) {
                for (var i = 0; i < disableInputItem.length; i++) {
                    if (disableInputItem[i].disabled === false) {
                        disableInputItem[i].setAttribute("disabled", true);
                    }
                }
            }

            var disableSelectItem = document.getElementById(element) ?? "";
            var disableSelectItem = disableSelectItem
                ? disableSelectItem.getElementsByTagName("select")
                : "";
            // 取得したselect要素にdisabled属性をつける
            if (disableSelectItem) {
                for (var i = 0; i < disableSelectItem.length; i++) {
                    if (disableSelectItem[i].disabled === false) {
                        disableSelectItem[i].setAttribute("disabled", true);
                    }
                }
            }
        });
    }
}

document.addEventListener(
    "DOMContentLoaded",
    function () {
        var batchBtn = document.getElementById("js_batch-check-button");
        var targets = document.getElementsByClassName("js_batch-check-target");

        if (batchBtn) {
            batchBtn.addEventListener("change", function () {
                //チェックされているか
                if (batchBtn.checked) {
                    //全て選択
                    for (let i in targets) {
                        if (targets.hasOwnProperty(i)) {
                            targets[i].checked = true;
                        }
                    }
                } else {
                    //全て解除
                    for (let i in targets) {
                        if (targets.hasOwnProperty(i)) {
                            targets[i].checked = false;
                        }
                    }
                }
            });
        }
    },
    false
);

// parent_element -> DB登録したタスク名(p要素)を差し込む親div要素
function replacedTaskDisplayInMonthlyCalendar(date) {
    let xhr = new XMLHttpRequest();
    const tasks_container = document.querySelector(
        'div[class="tasks_container"][data-date="' + date + '"]'
    );
    xhr.open("GET", "/replaced-task-display/" + date, true);
    xhr.responseType = "json";
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            // 以下、全体的に、新表示箇所の日付欄に対しての操作
            //レスポンスjsonデータ(該当日のタスクをdbから取得)
            const response = xhr.response;
            if (response.length > 0) {
                const first_element = tasks_container.firstElementChild;
                first_element.setAttribute(
                    "data-time",
                    response[0]["start_time"]
                );
                first_element.innerHTML =
                    "<p class='task badge w-100 mb-0 text-start text-truncate align-middle'>" +
                    response[0]["name"] +
                    "</p>";
                // 登録タスクが2つ以上あれば
                if (response.length > 1) {
                    const second_element = tasks_container.lastElementChild;
                    second_element.setAttribute(
                        "data-time",
                        response[1]["start_time"]
                    );
                    second_element.innerHTML =
                        "<p class='task badge w-100 mb-0 text-start text-truncate align-middle'>" +
                        response[1]["name"] +
                        "</p>";
                    // 登録タスクが3つ以上あれば
                    if (response.length > 2) {
                        const other_tasks_length = response.length - 2;
                        const other_tasks_element =
                            tasks_container.nextElementSibling;
                        const new_other_tasks_element =
                            document.createElement("a");
                        new_other_tasks_element.href =
                            "/replaced-task-display/" + date;
                        new_other_tasks_element.setAttribute(
                            "class",
                            "js_task-list fw-bold text-decoration-none text-reset d-block px-1 mt-1"
                        );
                        new_other_tasks_element.dataset.date = date;
                        new_other_tasks_element.textContent =
                            "他" + other_tasks_length + "件";
                        if (other_tasks_element) {
                            other_tasks_element.replaceWith(
                                new_other_tasks_element
                            );
                            eventRegistrationInMonthlyAndYear();
                        } else {
                            tasks_container.after(new_other_tasks_element);
                            eventRegistrationInMonthlyAndYear();
                        }
                    } else {
                        if (tasks_container.nextElementSibling) {
                            tasks_container.nextElementSibling.parentNode.removeChild(
                                tasks_container.nextElementSibling
                            );
                        }
                    }
                } else {
                    const remove_element_parent = tasks_container.lastChild;

                    if (remove_element_parent.firstChild) {
                        remove_element_parent.removeChild(
                            remove_element_parent.firstChild
                        );
                        remove_element_parent.setAttribute(
                            "data-time",
                            "00:00"
                        );
                    }
                }
            } else {
                // 登録タスクが0だったら
                const remove_element_parent = tasks_container.firstChild;

                remove_element_parent.removeChild(
                    remove_element_parent.firstChild
                );
                remove_element_parent.setAttribute("data-time", "00:00");
            }
        }
    };
    xhr.send(null);
}

function displayErrorMessagesInForm(error_messages) {
    if (current_path.indexOf("trash-can") !== -1) {
        const trash = document.getElementById("error-form-trash-can-field");
        if (error_messages["trash-can"]) {
            trash.innerHTML = error_messages["trash-can"];
            if (trash.classList.contains("display-none")) {
                trash.classList.remove("display-none");
            }
        }
        return;
    }

    const name = document.getElementById("error-form-task-field-name");
    const description = document.getElementById(
        "error-form-task-field-description"
    );
    const date = document.getElementById("error-form-task-field-date");
    const one_day_start_time = document.getElementById(
        "error-form-task-field-one_day_start_time"
    );
    const one_day_end_time = document.getElementById(
        "error-form-task-field-one_day_end_time"
    );

    if (error_messages["name"]) {
        name.innerHTML = error_messages["name"];
        if (name.classList.contains("display-none")) {
            name.classList.remove("display-none");
        }
    } else {
        if (name.hasChildNodes()) {
            name.innerHTML = "";
        }
        if (!name.classList.contains("display-none")) {
            name.classList.add("display-none");
        }
    }

    if (error_messages["description"]) {
        description.innerHTML = error_messages["description"];
        if (description.classList.contains("display-none")) {
            description.classList.remove("display-none");
        }
    } else {
        if (description.hasChildNodes()) {
            description.innerHTML = "";
        }
        if (!description.classList.contains("display-none")) {
            description.classList.add("display-none");
        }
    }

    if (error_messages["date"]) {
        date.innerHTML = error_messages["date"];
        if (date.classList.contains("display-none")) {
            date.classList.remove("display-none");
        }
    } else {
        if (date.hasChildNodes()) {
            date.innerHTML = "";
        }
        if (!date.classList.contains("display-none")) {
            date.classList.add("display-none");
        }
    }

    if (error_messages["one_day_start_time"]) {
        one_day_start_time.innerHTML = error_messages["one_day_start_time"];
        if (one_day_start_time.classList.contains("display-none")) {
            one_day_start_time.classList.remove("display-none");
        }
    } else {
        if (one_day_start_time.hasChildNodes()) {
            one_day_start_time.innerHTML = "";
        }
        if (!one_day_start_time.classList.contains("display-none")) {
            one_day_start_time.classList.add("display-none");
        }
    }

    if (error_messages["one_day_end_time"]) {
        one_day_end_time.innerHTML = error_messages["one_day_end_time"];
        if (one_day_end_time.classList.contains("display-none")) {
            one_day_end_time.classList.remove("display-none");
        }
    } else {
        if (one_day_end_time.hasChildNodes()) {
            one_day_end_time.innerHTML = "";
        }
        if (!one_day_end_time.classList.contains("display-none")) {
            one_day_end_time.classList.add("display-none");
        }
    }
}

function returnErrorMessagesIfFormHasInputErrors(form_data) {
    const error_messages = [];
    const field_name = form_data.get("name");
    const field_description = form_data.get("description");
    const field_date = form_data.get("date");
    const field_one_day_start_time = form_data.get("one_day_start_time");
    const field_one_day_end_time = form_data.get("one_day_end_time");
    const field_trash_can_tasks = form_data.getAll("checked_tasks[]");

    // "00:00"形式になっているかチェックするための正規表現
    const date_format = /^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[12][0-9]|3[01])$/;
    const time_format = /^([01][0-9]|2[0-3]):(00|15|30|45)$/;

    if (current_path.indexOf("trash-can") !== -1) {
        if (field_trash_can_tasks.length === 0) {
            error_messages["trash-can"] =
                "削除または復元するタスクを選択してください";
            return error_messages;
        }
    }

    if (field_name === "") {
        error_messages["name"] = "タスク名を入力してください";
    } else if (field_name.length > 255) {
        error_messages["name"] = "タスク名は255文字以内で入力してください";
    }

    if (field_description.length > 5000) {
        error_messages["description"] = "説明は5000文字以内で入力してください";
    }

    if (field_date === "") {
        error_messages["date"] = "日付を入力してください";
    } else if (!date_format.test(field_date)) {
        error_messages["date"] = "日付を正しく入力してください";
    }

    if (field_one_day_start_time === "") {
        error_messages["one_day_start_time"] = "開始時間を入力してください";
    } else if (!time_format.test(field_one_day_start_time)) {
        error_messages["one_day_start_time"] =
            "開始時間を正しく入力してください";
    }

    if (field_one_day_end_time === "") {
        error_messages["one_day_end_time"] = "終了時間を入力してください";
    } else if (!time_format.test(field_one_day_end_time)) {
        error_messages["one_day_end_time"] = "終了時間を正しく入力してください";
    }

    if (
        field_one_day_start_time === field_one_day_end_time ||
        field_one_day_start_time > field_one_day_end_time
    ) {
        error_messages["one_day_start_time"] = "時間を正しく入力してください";
    }

    return error_messages;
}

function submitForm() {
    const formData = new FormData(document.forms.task);
    const url = document.getElementById("task_id")
        ? "/planning-task-update"
        : "/planning-task-input";

    fetch(url, {
        method: "POST",
        body: formData,
    })
        .then((response) => {
            if (!response.ok) {
                console.log("error!");
            }

            return response.json();
        })
        .then((data) => {
            const one_day_start_time = document.getElementById(
                "error-form-task-field-one_day_start_time"
            );
            const time_duplicated_notification_message =
                data.time_duplicated_notification_message;
            // 時間重複エラーメッセージをフォームに表示
            if (time_duplicated_notification_message) {
                one_day_start_time.innerHTML =
                    time_duplicated_notification_message;
                if (one_day_start_time.classList.contains("display-none")) {
                    one_day_start_time.classList.remove("display-none");
                }
            } else {
                // 時間重複エラーメッセージをフォームから消す
                if (one_day_start_time.hasChildNodes()) {
                    one_day_start_time.innerHTML = "";
                }
                if (!one_day_start_time.classList.contains("display-none")) {
                    one_day_start_time.classList.add("display-none");
                }
                MicroModal.close("modal-1");
                const path = document.getElementById("js_current_url").value;
                const url = new URL(path);
                const xhr2 = new XMLHttpRequest();
                xhr2.open("GET", decodeURIComponent(String(url)), true);
                xhr2.onreadystatechange = function () {
                    if (xhr2.readyState == 4 && xhr2.status == 200) {
                        const data = xhr2.responseText;
                        const elem = document.body;
                        elem.innerHTML = data;
                        const head = document.getElementsByTagName("head")[0];
                        const script = document.createElement("script");
                        script.src = src;
                        head.appendChild(script);
                    }
                };
                xhr2.send(null);
            }
        })
        .catch((error) => {
            console.log(error);
        });
}

function deleteTask(deleteUrl, target, target_global, date) {
    var xhr = new XMLHttpRequest();
    xhr.open("GET", deleteUrl, true);
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            MicroModal.close("modal-1", {});

            const path = document.getElementById("js_current_url").value;
            const url = new URL(path);
            const xhr2 = new XMLHttpRequest();
            xhr2.open("GET", decodeURIComponent(String(url)), true);
            xhr2.onreadystatechange = function () {
                if (xhr2.readyState == 4 && xhr2.status == 200) {
                    const data = xhr2.responseText;
                    const elem = document.body;
                    elem.innerHTML = data;
                    const head = document.getElementsByTagName("head")[0];
                    const script = document.createElement("script");
                    script.src = src;
                    head.appendChild(script);
                }
            };
            xhr2.send(null);
        }
    };
    xhr.send(null);
}

function toggleTaskCompletionChecks(toggleUrl, completion_check_element) {
    var xhr = new XMLHttpRequest();
    xhr.open("GET", toggleUrl, true);
    xhr.responseType = "json";
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            const completion_check_value =
                completion_check_element.getAttribute("data-completion-check");
            const db_completion_check_value = xhr.response["completion_check"];
            if (completion_check_value !== db_completion_check_value) {
                if (completion_check_value === "0") {
                    completion_check_element.setAttribute(
                        "data-completion-check",
                        1
                    );
                    completion_check_element.innerText = "未完了にする";
                    task_toggle_completion_checks.classList.toggle(
                        "text-secondary"
                    );
                    task_toggle_completion_checks.classList.toggle(
                        "text-success"
                    );
                } else if (completion_check_value === "1") {
                    completion_check_element.setAttribute(
                        "data-completion-check",
                        0
                    );
                    completion_check_element.innerText = "完了にする";
                    task_toggle_completion_checks.classList.toggle(
                        "text-success"
                    );
                    task_toggle_completion_checks.classList.toggle(
                        "text-secondary"
                    );
                }
            }
        }
    };
    xhr.send(null);
}

function getForm(replace, date, time, target, target_global) {
    var path = document.getElementById("form-path").value;
    let url = new URL(path);
    url.searchParams.set("date", date);
    url.searchParams.append("time", time);
    if (target.hasAttribute("data-new-form")) {
        url.searchParams.append("form", "new");
    }
    var xhr = new XMLHttpRequest();
    xhr.open("GET", decodeURIComponent(String(url)), true);
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            var data = xhr.responseText;
            var elem = document.getElementById(replace);
            elem.innerHTML = data;
            MicroModal.show("modal-1", {
                disableFocus: true,
                onShow: function (modal) {
                    first_focus.focus();
                    let inputs = modal.getElementsByTagName("input");
                    for (var num0 = 0; num0 < inputs.length; num0++) {
                        inputs[num0].addEventListener("change", () => {
                            if (!modal.classList.contains("value-change")) {
                                modal.classList.add("value-change");
                            }
                        });
                    }

                    let selects = modal.getElementsByTagName("select");

                    for (var num0 = 0; num0 < selects.length; num0++) {
                        selects[num0].addEventListener("change", () => {
                            if (!modal.classList.contains("value-change")) {
                                modal.classList.add("value-change");
                            }
                        });
                    }

                    let textarea = modal.getElementsByTagName("textarea");

                    for (var num0 = 0; num0 < textarea.length; num0++) {
                        textarea[num0].addEventListener("change", () => {
                            if (!modal.classList.contains("value-change")) {
                                modal.classList.add("value-change");
                            }
                        });
                    }
                    //フォームを閉じる
                    modal.addEventListener("click", function (e) {
                        if (e.target.hasAttribute("data-close-confirm")) {
                            // フォーム入力値の変更があれば、確認ダイアログを表示
                            if (modal.classList.contains("value-change")) {
                                MicroModal.show("modal-2", {});
                                form_destruction.addEventListener(
                                    "click",
                                    function () {
                                        MicroModal.close("modal-2");
                                        MicroModal.close("modal-1");
                                    }
                                );
                            } else {
                                MicroModal.close("modal-1", {});
                            }
                        }
                    });

                    const formSubmit =
                        document.getElementById("js_form-submit");

                    //フォーム送信処理
                    formSubmit.addEventListener("click", function (e) {
                        e.preventDefault();
                        // 入力漏れなどのエラーをチェックし、エラーなければフォーム送信
                        const form_data = new FormData(document.forms.task);
                        const error_messages =
                            returnErrorMessagesIfFormHasInputErrors(form_data);
                        if (Object.keys(error_messages).length > 0) {
                            displayErrorMessagesInForm(error_messages);
                            return;
                        } else {
                            // フォーム送信し、日時重複チェックでエラーがあればフォーム内にエラーメッセージを表示
                            submitForm();
                        }
                    });

                    // 編集フォーム
                    if (modal.querySelector("input[name='task_id']")) {
                        task_destroy.addEventListener("click", function (e) {
                            e.preventDefault();
                            const deleteUrl = e.target.getAttribute("href");
                            deleteTask(deleteUrl, target, target_global, date);
                        });
                        // 完了・未完了チェックの切り替え
                        task_toggle_completion_checks.addEventListener(
                            "click",
                            function (e) {
                                e.preventDefault();
                                const toggleUrl = e.target.getAttribute("href");
                                const completion_check = e.target.getAttribute(
                                    "data-completion-check"
                                );
                                toggleTaskCompletionChecks(toggleUrl, e.target);
                            }
                        );
                    }
                },
            });

            return data;
        }
    };
    xhr.send(null);
}

function displayTaskList(date, url, target_global) {
    let xhr = new XMLHttpRequest();
    xhr.open("GET", url, true);
    xhr.responseType = "json";
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            let tasks = xhr.response;
            if (current_path.indexOf("month") !== -1) {
                tasks = tasks.slice(2);
            }
            const date_obj = new Date(date);
            const day_arr = ["月", "火", "水", "木", "金", "土", "日"];
            const year_for_display = date_obj.getFullYear();
            const month_for_display = date_obj.getMonth() + 1;
            const date_for_display = date_obj.getDate();
            const day_for_display = day_arr[date_obj.getDay()];
            const day_calendar_path = document.getElementById(
                "js_day_calendar_path"
            ).value;
            const tasks_header_title =
                "<a class='text-reset text-decoration-none' href='" +
                day_calendar_path +
                "/" +
                year_for_display +
                "/" +
                month_for_display +
                "/" +
                date_for_display +
                "'>" +
                month_for_display +
                "/" +
                date_for_display +
                "(" +
                day_for_display +
                ")" +
                "</a>";
            const parent_title = document.getElementById("modal-3-title");
            const parent_content = document.getElementById("modal-3-content");
            parent_title.innerHTML = tasks_header_title;
            let html = "";
            if (tasks.length === 0) {
                html = "<p>登録したタスクがありません。</p>";
            } else {
                tasks.forEach((task) => {
                    html +=
                        "<div class='js_form' data-date='" +
                        task["date"] +
                        "' data-time='" +
                        task["start_time"] +
                        "'><p class='other-task-list-item mb-2'><span class='me-2'>" +
                        task["start_time"] +
                        "</span>" +
                        task["name"] +
                        "</p></div>";
                });
            }
            parent_content.innerHTML = html;
            MicroModal.show("modal-3", {
                onShow: function (modal) {
                    modal.addEventListener("click", function (e) {
                        if (e.target.hasAttribute("data-close-confirm")) {
                            MicroModal.close("modal-3", {});
                        }
                    });

                    const modal_target =
                        modal.getElementsByClassName("js_form");

                    for (let i = 0; i < modal_target.length; i++) {
                        modal_target[i].addEventListener("click", function (e) {
                            const date = this.getAttribute("data-date");
                            const time = this.getAttribute("data-time");
                            getForm(
                                "js_form-display",
                                date,
                                time,
                                e.currentTarget,
                                target_global
                            );
                            MicroModal.close("modal-3", {});
                        });
                    }
                },
            });
        }
    };
    xhr.send(null);
}

const target = document.getElementsByClassName("js_form");

for (let i = 0; i < target.length; i++) {
    target[i].addEventListener("click", function (e) {
        e.stopPropagation();
        const date = this.getAttribute("data-date");
        const time = this.getAttribute("data-time");
        getForm("js_form-display", date, time, e.currentTarget);
    });
}

function eventRegistrationInMonthlyAndYear() {
    const task_list = document.getElementsByClassName("js_task-list");
    for (let i = 0; i < task_list.length; i++) {
        task_list[i].addEventListener("click", function (e) {
            e.stopPropagation();
            e.preventDefault();
            const date = this.getAttribute("data-date");
            const task_list_url = e.target.getAttribute("href");
            const target_global = e.currentTarget;
            displayTaskList(date, task_list_url, target_global);
        });
    }
}

if (
    current_path.indexOf("month") !== -1 ||
    current_path.indexOf("year") !== -1
) {
    eventRegistrationInMonthlyAndYear();
}

function initializeHeaderCalendarDates(width, previous, next) {
    const header_calendar_date = document.getElementById(
        "js_header_calendar_date"
    ).value;
    const calendar_type = document.getElementById("js_calendar_type").value;
    const initialize_url = document.getElementById(
        "js_header_calendar_initialize"
    ).value;

    const url = new URL(initialize_url);
    url.searchParams.append("date", header_calendar_date);
    url.searchParams.append("type", calendar_type);
    url.searchParams.append("width", width);
    const xhr = new XMLHttpRequest();
    xhr.open("GET", decodeURIComponent(String(url)), true);
    xhr.responseType = "json";
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            const response = xhr.response;
            const responseString = response.shift();
            const parent = document.getElementById(
                "js_header_calendar_" + width
            ).parentNode;

            parent.innerHTML = responseString;
            const new_previous = document.getElementById(
                "js_header_calendar_previous_" + width
            );

            new_previous.replaceWith(previous);
            const new_next = document.getElementById(
                "js_header_calendar_next_" + width
            );
            new_next.replaceWith(next);

            return responseString;
        }
    };
    xhr.send(null);
}

if (
    current_path.indexOf("trash-can") !== -1 ||
    current_path.indexOf("profile") !== -1
) {
    const trashCanForm = document.getElementById("js_trash_can_form");

    //フォーム送信処理
    trashCanForm.addEventListener("submit", function (e) {
        // 入力漏れなどのエラーをチェックし、エラーなければフォーム送信
        const trash_can_form_data = new FormData(document.forms.trashCan);
        const error_messages =
            returnErrorMessagesIfFormHasInputErrors(trash_can_form_data);
        if (Object.keys(error_messages).length > 0) {
            displayErrorMessagesInForm(error_messages);
            e.preventDefault();
        }
    });
} else {
    function moveBackAndForthInHeaderCalendar(received_url, width) {
        const year = document.getElementById(
            "js_header_calendar_year_" + width
        ).value;
        const month = document.getElementById(
            "js_header_calendar_month_" + width
        ).value;
        const url = new URL(received_url);
        url.searchParams.set("year", year);
        url.searchParams.append("month", month);
        url.searchParams.append("type", type);
        url.searchParams.append("width", width);
        const xhr = new XMLHttpRequest();
        xhr.open("GET", decodeURIComponent(String(url)), true);
        xhr.responseType = "json";
        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4 && xhr.status == 200) {
                const response = xhr.response;
                const responseString = response.shift();
                const parent = document.getElementById(
                    "js_header_calendar_" + width
                ).parentNode;
                parent.innerHTML = responseString;
                const new_previous = document.getElementById(
                    "js_header_calendar_previous_" + width
                );
                const new_next = document.getElementById(
                    "js_header_calendar_next_" + width
                );
                if (width === "lg") {
                    new_previous.replaceWith(previous_lg);
                    new_next.replaceWith(next_lg);
                } else if (width === "md") {
                    new_previous.replaceWith(previous_md);
                    new_next.replaceWith(next_md);
                }

                return responseString;
            }
        };
        xhr.send(null);
    }

    const type = document.getElementById("js_calendar_type").value;
    const width_lg = document.getElementById(
        "js_header_calendar_width_lg"
    ).value;
    const width_md = document.getElementById(
        "js_header_calendar_width_md"
    ).value;
    const previous_lg = document.getElementById(
        "js_header_calendar_previous_lg"
    );
    const previous_md = document.getElementById(
        "js_header_calendar_previous_md"
    );
    const next_lg = document.getElementById("js_header_calendar_next_lg");
    const next_md = document.getElementById("js_header_calendar_next_md");

    previous_lg.addEventListener("click", function (e) {
        e.preventDefault();
        const previous_lg_url = document
            .getElementById("js_header_calendar_previous_lg")
            .getAttribute("href");
        moveBackAndForthInHeaderCalendar(previous_lg_url, width_lg);
    });

    previous_md.addEventListener("click", function (e) {
        e.preventDefault();
        const previous_md_url = document
            .getElementById("js_header_calendar_previous_md")
            .getAttribute("href");
        moveBackAndForthInHeaderCalendar(previous_md_url, width_md);
    });

    next_lg.addEventListener("click", function (e) {
        e.preventDefault();
        const next_lg_url = document
            .getElementById("js_header_calendar_next_lg")
            .getAttribute("href");
        moveBackAndForthInHeaderCalendar(next_lg_url, width_lg);
    });

    next_md.addEventListener("click", function (e) {
        e.preventDefault();
        const next_md_url = document
            .getElementById("js_header_calendar_next_md")
            .getAttribute("href");
        moveBackAndForthInHeaderCalendar(next_md_url, width_md);
    });

    const header_dropdown_lg = document.getElementById("header_dropdown_lg");
    const header_dropdown_md = document.getElementById("header_dropdown_md");

    // ヘッダーカレンダー外をクリックで、初期値(今現在のurl(2022/2/1、とか))をカレンダー外から取得し、カレンダーを今現在の年月日に戻す
    header_dropdown_lg.addEventListener("hidden.bs.dropdown", function () {
        const width = document.getElementById(
            "js_header_calendar_width_lg"
        ).value;
        initializeHeaderCalendarDates(width, previous_lg, next_lg);
    });

    header_dropdown_md.addEventListener("hidden.bs.dropdown", function () {
        const width = document.getElementById(
            "js_header_calendar_width_md"
        ).value;
        initializeHeaderCalendarDates(width, previous_md, next_md);
    });
}

let src = document.currentScript.src;
