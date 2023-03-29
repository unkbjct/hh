$(document).ready(() => {
    $(".validation").focus(function () {
        $(this).removeClass("is-invalid")
    })
    $(".validation").blur(function () {
        if (this.value == '' && !this.classList.contains("category")) {
            $(this).addClass("is-invalid")
        }
    })
    $("select").change(function () {
        if (this.value == '' && !this.classList.contains("filter")) {
            $(this).addClass("is-invalid")
        } else {
            $(this).removeClass("is-invalid");
        }
    })
})

const showMessage = (options) => {
    // $("#modal .modal-body").empty()

    // $("#modal .modal-title").text(options.title)

    // let content = $("#modal .modal-content");
    // content.removeAttr("class")
    // content.addClass("modal-content")
    // content.addClass(`bg-${options.type}-subtle`);

    for (let key in options.messageList) {
        // $("#modal .modal-body")[0].insertAdjacentHTML("beforeend", `<p>${options.messageList[key]}</p>`)
        if (options.type == 'danger') $("#" + key).addClass("is-invalid");
    }
}