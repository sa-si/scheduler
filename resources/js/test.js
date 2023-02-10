import { indexOf } from "lodash";
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
    // console.log("1つ目div", parent_element);
    let xhr = new XMLHttpRequest();
    // 日時の値を取得 (2022-10-22, など)
    // const date = parent_element.getAttribute("data-date");
    // タスク名(p要素)を差し込む親div要素の隣の親div要素
    // const first_element = document.querySelectorAll(
    //     'div[class="js_form"][data-date="' + date + '"'
    // )[0];

    // const second_element = document.querySelectorAll(
    //     'div[class="js_form"][data-date="' + date + '"'
    // )[1];
    const tasks_container = document.querySelector(
        'div[class="tasks_container"][data-date="' + date + '"]'
    );
    // let second_parent_element;
    // form_elements.forEach((form) => {
    //     console.log("all", form);
    //     if (form !== parent_element) {
    //         second_parent_element = form;
    //     }
    // });
    // console.log("2つ目div", second_parent_element);
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
                    "<p class='task badge bg-primary w-100 mb-0 text-start text-truncate align-middle'>" +
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
                        "<p class='task badge bg-primary w-100 mb-0 text-start text-truncate align-middle'>" +
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
                        // new_other_tasks_element.className = "";
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
    // "00:00"形式になっているかチェックするための正規表現
    const date_format = /^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[12][0-9]|3[01])$/;
    const time_format = /^([01][0-9]|2[0-3]):(00|15|30|45)$/;

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
            }
        })
        .catch((error) => {
            console.log(error);
        });
}

function deleteTask(deleteUrl, target, target_global, date) {
    console.log(date);

    var xhr = new XMLHttpRequest();
    xhr.open("GET", deleteUrl, true);
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            MicroModal.close("modal-1", {});
            if (current_path.indexOf("year") !== -1) {
                const target_parent = target.parentNode;
                target.parentNode.removeChild(target);
                if (!target_parent.hasChildNodes()) {
                    console.log(target_parent, "子はいません！");
                    // 以下に、a要素の水色丸を消す処理を書く
                    if (target_global.classList.contains("tasks-include")) {
                        target_global.classList.remove("tasks-include");
                    }
                }
            } else if (current_path.indexOf("month") !== -1) {
                replacedTaskDisplayInMonthlyCalendar(date);
            } else {
                target.removeChild(target.firstChild);
            }
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
            console.log(completion_check_value, db_completion_check_value);
            if (completion_check_value !== db_completion_check_value) {
                if (completion_check_value === "0") {
                    completion_check_element.setAttribute(
                        "data-completion-check",
                        1
                    );
                    completion_check_element.innerText = "未完了にする";
                } else if (completion_check_value === "1") {
                    completion_check_element.setAttribute(
                        "data-completion-check",
                        0
                    );
                    completion_check_element.innerText = "完了にする";
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
            console.log("ここまで処理進んでる？");
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
                        // 日付データを取得(例:2023-02-04)
                        const input_date =
                            modal.querySelector("input[name='date']").value;
                        // 時間を取得(例:00:00)
                        const input_time = modal.querySelector(
                            "input[name='one_day_start_time']"
                        ).value;
                        const input_element = document.querySelector(
                            'div[class="js_form"][data-date="' +
                                input_date +
                                '"][data-time="' +
                                input_time +
                                '"]'
                        );
                        // 月カレンダー
                        if (current_path.indexOf("month") !== -1) {
                            // 以下の書き方の場合(querySelector→対象要素が複数あった場合)、必ず、１つ目のdiv要素になる
                            const input_month_element = document.querySelector(
                                'div[class="js_form"][data-date="' +
                                    input_date +
                                    '"][data-new-form]'
                            );
                            // 画面内に書き換え対象の欄がある場合
                            if (input_month_element) {
                                replacedTaskDisplayInMonthlyCalendar(
                                    input_date
                                );
                                // 同月内かつクリックしたフォームの日付と登録日が別であれば
                                if (
                                    input_date !==
                                    target.getAttribute("data-date")
                                ) {
                                    // 開始時間を初期値の"00:00"にする
                                    target.setAttribute("data-time", "00:00");
                                    // 更新で登録日付が変われば
                                    if (target.hasChildNodes()) {
                                        target.removeChild(target.firstChild);
                                    }
                                }
                            } else {
                                // 新規/編集タスクが当月以外だったら
                                if (
                                    input_date !==
                                    target.getAttribute("data-date")
                                ) {
                                    if (target.hasChildNodes()) {
                                        target.setAttribute(
                                            "data-time",
                                            "00:00"
                                        );
                                        target.removeChild(target.firstChild);
                                    }
                                }
                            }

                            return;
                        }
                        // 年カレンダー
                        if (current_path.indexOf("year") !== -1) {
                            if (
                                target.getAttribute("data-date") === input_date
                            ) {
                                console.log("同日更新");
                                const url = target_global.getAttribute("href");
                                let xhr = new XMLHttpRequest();
                                xhr.open("GET", url, true);
                                xhr.responseType = "json";
                                xhr.onreadystatechange = function () {
                                    if (
                                        xhr.readyState == 4 &&
                                        xhr.status == 200
                                    ) {
                                        console.log("同日更新,通信成功");
                                        let tasks = xhr.response;
                                        // console.log(tasks.length);

                                        const parent_content =
                                            document.getElementById(
                                                "modal-3-content"
                                            );

                                        let html = "";
                                        if (tasks.length === 0) {
                                            html =
                                                "<p>登録したタスクがありません。</p>";
                                        } else {
                                            tasks.forEach((task) => {
                                                html +=
                                                    "<div class='js_form' data-date='" +
                                                    task["date"] +
                                                    "' data-time='" +
                                                    task["start_time"] +
                                                    "'><p><span>" +
                                                    task["start_time"] +
                                                    "</span>" +
                                                    task["name"] +
                                                    "</p></div>";
                                            });
                                        }
                                        parent_content.innerHTML = html;
                                    }
                                };
                            } else {
                                const target_parent = target.parentNode;
                                target.parentNode.removeChild(target);
                                if (!target_parent.hasChildNodes()) {
                                    console.log(
                                        "更新して子がいなくなってもうた！"
                                    );
                                    // 以下に、a要素の水色丸を消す処理を書く
                                    if (
                                        target_global.classList.contains(
                                            "tasks-include"
                                        )
                                    ) {
                                        target_global.classList.remove(
                                            "tasks-include"
                                        );
                                    }
                                }

                                const input_element_year =
                                    document.querySelector(
                                        'a[class="js_task-list"][data-date="' +
                                            input_date +
                                            '"]'
                                    );
                                if (input_element_year) {
                                    if (
                                        !input_element_year.classList.contains(
                                            "tasks-include"
                                        )
                                    ) {
                                        input_element_year.classList.add(
                                            "tasks-include"
                                        );
                                    }
                                }
                            }

                            return;
                        }
                        // 以下、日・週カレンダー対象
                        if (target === input_element) {
                            target.innerHTML =
                                "<p>" +
                                modal.querySelector("input[name='name']")
                                    .value +
                                "</p>";
                        } else if (input_element && target !== input_element) {
                            input_element.innerHTML =
                                "<p>" +
                                modal.querySelector("input[name='name']")
                                    .value +
                                "</p>";
                            if (target.hasChildNodes()) {
                                const child = target.children[0];
                                target.removeChild(child);
                            }
                        } else if (!input_element && target !== input_element) {
                            if (target.hasChildNodes()) {
                                const child = target.children[0];
                                target.removeChild(child);
                            }
                        }
                    });
                    // 編集フォーム
                    if (modal.querySelector("input[name='task_id']")) {
                        task_destroy.addEventListener("click", function (e) {
                            e.preventDefault();
                            const deleteUrl = e.target.getAttribute("href");
                            console.log(deleteUrl, target, target_global);
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
            console.log(tasks.length);
            if (current_path.indexOf("month") !== -1) {
                tasks = tasks.slice(2);
            }
            const date_obj = new Date(date);
            const day_arr = ["月", "火", "水", "木", "金", "土", "日"];
            const month_for_display = date_obj.getMonth() + 1;
            const date_for_display = date_obj.getDate();
            const day_for_display = day_arr[date_obj.getDay()];
            const tasks_header_title =
                month_for_display +
                "/" +
                date_for_display +
                "(" +
                day_for_display +
                ")";
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
                        "'><p><span>" +
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
                        console.log(e.target);
                        if (e.target.hasAttribute("data-close-confirm")) {
                            MicroModal.close("modal-3", {});
                        }
                    });

                    const modal_target =
                        modal.getElementsByClassName("js_form");

                    for (let i = 0; i < modal_target.length; i++) {
                        modal_target[i].addEventListener("click", function (e) {
                            console.log("年タスクリストのdivをclick!");
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
            console.log(target_global);
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

// js_sidebar_toggle.addEventListener("click", function () {
//     const sidebar = document.getElementById("js_sidebar");
//     sidebar.classList.toggle("display-none");
// });

const type = document.getElementById("js_calendar_type").value;
const previous = document.getElementById("js_header_calendar_previous");

previous.addEventListener("click", function (e) {
    e.preventDefault();
    const previous_url = document
        .getElementById("js_header_calendar_previous")
        .getAttribute("href");
    const year = document.getElementById("js_header_calendar_year").value;
    const month = document.getElementById("js_header_calendar_month").value;
    const url = new URL(previous_url);
    url.searchParams.set("year", year);
    url.searchParams.append("month", month);
    url.searchParams.append("type", type);
    const xhr = new XMLHttpRequest();
    xhr.open("GET", decodeURIComponent(String(url)), true);
    xhr.responseType = "json";
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            const response = xhr.response;
            const responseString = response.shift();
            const parent =
                document.getElementById("js_header_calendar").parentNode;
            parent.innerHTML = responseString;
            const new_previous = document.getElementById(
                "js_header_calendar_previous"
            );
            new_previous.replaceWith(previous);
            const new_next = document.getElementById("js_header_calendar_next");
            new_next.replaceWith(next);

            return responseString;
        }
    };
    xhr.send(null);
});

const next = document.getElementById("js_header_calendar_next");

next.addEventListener("click", function (e) {
    e.preventDefault();
    const next_url = document
        .getElementById("js_header_calendar_next")
        .getAttribute("href");
    const year = document.getElementById("js_header_calendar_year").value;
    const month = document.getElementById("js_header_calendar_month").value;
    const url = new URL(next_url);
    url.searchParams.set("year", year);
    url.searchParams.append("month", month);
    url.searchParams.append("type", type);
    const xhr = new XMLHttpRequest();
    xhr.open("GET", decodeURIComponent(String(url)), true);
    xhr.responseType = "json";
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            const response = xhr.response;
            const responseString = response.shift();
            const parent =
                document.getElementById("js_header_calendar").parentNode;
            parent.innerHTML = responseString;
            const new_previous = document.getElementById(
                "js_header_calendar_previous"
            );
            new_previous.replaceWith(previous);
            const new_next = document.getElementById("js_header_calendar_next");
            new_next.replaceWith(next);

            return responseString;
        }
    };
    xhr.send(null);
});

const header_dropdown = document.getElementById("header_dropdown");
header_dropdown.addEventListener("hidden.bs.dropdown", function () {
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
    const xhr = new XMLHttpRequest();
    xhr.open("GET", decodeURIComponent(String(url)), true);
    xhr.responseType = "json";
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            const response = xhr.response;
            const responseString = response.shift();
            const parent =
                document.getElementById("js_header_calendar").parentNode;
            parent.innerHTML = responseString;
            const new_previous = document.getElementById(
                "js_header_calendar_previous"
            );
            new_previous.replaceWith(previous);
            const new_next = document.getElementById("js_header_calendar_next");
            new_next.replaceWith(next);

            return responseString;
        }
    };
    xhr.send(null);
});
