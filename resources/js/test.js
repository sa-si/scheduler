import MicroModal from "micromodal";

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

function deleteTask(deleteUrl, target) {
    var xhr = new XMLHttpRequest();
    xhr.open("GET", deleteUrl, true);
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            MicroModal.close("modal-1", {});
            target.parentNode.removeChild(target);
        }
    };
    xhr.send(null);
}

function getForm(replace, date, time, target) {
    var path = document.getElementById("form-path").value;
    let url = new URL(path);
    url.searchParams.set("date", date);
    url.searchParams.append("time", time);
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
                                console.log("フォーム変更あり!");
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
                        target.innerHTML =
                            "<p>" +
                            modal.querySelector("input[name='name']").value +
                            "</p>";
                    });

                    if (modal.querySelector("input[name='task_id']")) {
                        task_destroy.addEventListener("click", function (e) {
                            e.preventDefault();
                            const deleteUrl = e.target.getAttribute("href");
                            // console.log(1 * 1);
                            deleteTask(deleteUrl, target);
                        });
                    }
                },
            });

            return data;
        }
    };
    xhr.send(null);
}

// var taskForm1 = document.getElementById("js_form-display");
var target = document.getElementsByClassName("js_form");

for (var i = 0; i < target.length; i++) {
    target[i].addEventListener("click", function (e) {
        // console.log(e.target);
        var date = this.getAttribute("data-date");
        var time = this.getAttribute("data-time");
        getForm("js_form-display", date, time, e.target);
    });
}

// function createCounter() {
//     let obj = { closeModal: "" };
//     // `increment`関数は`count`変数を参照
//     function increment() {
//         obj.closeModal = taskForm.querySelector("[data-micromodal-close]");
//         return obj.closeModal;
//     }
//     return increment;
// }
