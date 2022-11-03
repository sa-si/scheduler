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
function replacedTaskDisplayInMonthlyCalendar(parent_element = "") {
    // console.log("1つ目div", parent_element);
    if (parent_element) {
        let xhr = new XMLHttpRequest();
        // 日時の値を取得 (2022-10-22, など)
        const date = parent_element.getAttribute("data-date");
        // タスク名(p要素)を差し込む親div要素の隣の親div要素
        const first_element = document.querySelectorAll(
            'div[class="js_form"][data-date="' + date + '"'
        )[0];

        const second_element = document.querySelectorAll(
            'div[class="js_form"][data-date="' + date + '"'
        )[1];
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
                if (response[0]) {
                    first_element.setAttribute(
                        "data-time",
                        response[0]["start_time"]
                    );
                    if (first_element.hasAttribute("data-new-form")) {
                        first_element.removeAttribute("data-new-form");
                    }
                    first_element.innerHTML =
                        "<p>" + response[0]["name"] + "</p>";
                    if (response.length > 1) {
                        second_element.setAttribute(
                            "data-time",
                            response[1]["start_time"]
                        );
                        if (second_element.hasAttribute("data-new-form")) {
                            second_element.removeAttribute("data-new-form");
                        }
                        second_element.innerHTML =
                            "<p>" + response[1]["name"] + "</p>";
                        if (response.length > 2) {
                            const other_tasks_length = response.length - 2;
                            const other_tasks_element =
                                second_element.nextElementSibling;
                            const new_other_tasks_element =
                                document.createElement("a");
                            new_other_tasks_element.href =
                                "/replaced-task-display/" + date;
                            new_other_tasks_element.className = "js_task-list";
                            new_other_tasks_element.dataset.date = date;
                            new_other_tasks_element.textContent =
                                "他" + other_tasks_length + "件";
                            if (other_tasks_element) {
                                other_tasks_element.replaceWith(
                                    new_other_tasks_element
                                );
                                eventRegistrationInMonthlyAndYear();
                            } else {
                                second_element.after(new_other_tasks_element);
                                eventRegistrationInMonthlyAndYear();
                            }
                        } else {
                            if (second_element.nextElementSibling) {
                                second_element.nextElementSibling.parentNode.removeChild(
                                    second_element.nextElementSibling
                                );
                            }
                        }
                    } else {
                        second_element.setAttribute("data-time", "00:00");
                        if (!second_element.hasAttribute("data-new-form")) {
                            second_element.setAttribute("data-new-form", "");
                        }
                        if (second_element.firstChild) {
                            second_element.removeChild(
                                second_element.firstChild
                            );
                        }
                    }
                } else {
                    first_element.setAttribute("data-time", "00:00");
                    if (!first_element.hasAttribute("data-new-form")) {
                        first_element.setAttribute("data-new-form", "");
                    }
                    if (first_element.hasChildNodes()) {
                        first_element.removeChild(first_element.firstChild);
                    }
                }
            }
        };
        xhr.send(null);
    }
}

function submitForm() {
    let xhr = new XMLHttpRequest();
    let formData = new FormData(document.forms.task);
    xhr.open("POST", "/task", true);
    xhr.send(formData);
    xhr.onload = function () {
        if (this.status != 200) {
            console.log(this.status + ":エラーが発生しています。");
        } else {
            console.log(this.status + "正常に動作しました。");
        }
    };

    xhr.addEventListener("readystatechange", () => {
        if (xhr.readyState === 4 && xhr.status === 200) {
            MicroModal.close("modal-1");
        }
    });
}

function deleteTask(deleteUrl, target, target_global) {
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
                replacedTaskDisplayInMonthlyCalendar(target);
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
    console.log(target, target_global);
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

                    modal.addEventListener("click", function (e) {
                        if (e.target.hasAttribute("data-close-confirm")) {
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
                    formSubmit.addEventListener("click", function () {
                        submitForm();
                        const input_date =
                            modal.querySelector("input[name='date']").value;
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

                        if (current_path.indexOf("month") !== -1) {
                            // 以下の書き方の場合、必ず、１つ目のdiv要素になる
                            const input_month_element = document.querySelector(
                                'div[class="js_form"][data-date="' +
                                    input_date +
                                    '"'
                            );
                            // console.log("月カレです", input_month_element);
                            // 画面内に書き換え対象の欄がある場合
                            if (input_month_element) {
                                replacedTaskDisplayInMonthlyCalendar(
                                    input_month_element
                                );
                                if (
                                    input_date !==
                                    target.getAttribute("data-date")
                                ) {
                                    target.setAttribute("data-time", "00:00");
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

                    if (modal.querySelector("input[name='task_id']")) {
                        task_destroy.addEventListener("click", function (e) {
                            e.preventDefault();
                            const deleteUrl = e.target.getAttribute("href");
                            deleteTask(deleteUrl, target, target_global);
                        });

                        task_toggle_completion_checks.addEventListener(
                            "click",
                            function (e) {
                                e.preventDefault();
                                const toggleUrl = e.target.getAttribute("href");
                                const completion_check = e.target.getAttribute(
                                    "data-completion-check"
                                );
                                // e.target.getAttribute("data-");
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
        // console.log(e.currentTarget);
        const date = this.getAttribute("data-date");
        const time = this.getAttribute("data-time");
        getForm("js_form-display", date, time, e.currentTarget);
    });
}

function eventRegistrationInMonthlyAndYear() {
    const task_list = document.getElementsByClassName("js_task-list");
    for (let i = 0; i < task_list.length; i++) {
        task_list[i].addEventListener("click", function (e) {
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

// function addClassNameToTodaysDate() {
//     const today_obj = new Date();
//     const year = today_obj.getFullYear();
//     const month = today_obj.getMonth() + 1;
//     const date = today_obj.getDate();

//     const today = year + "-" + month + "-" + date;
//     const todayElement = document.querySelector(
//         "[class='date'][data-date='" + today + "']"
//     );
//     console.log(todayElement);
//     if (todayElement) {
//         todayElement.classList.add("todays-date");
//     }
// }
// addClassNameToTodaysDate();
// console.log(calendar.tasks);
