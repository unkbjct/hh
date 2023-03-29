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
    $("#city-input").on("input", function () {
        if (!this.value) return;
        // alert()
        $.ajax({
            url: `${window.location.protocol}//${window.location.hostname}/api/city/find`,
            method: 'GET',
            data: {
                city: this.value,
            },
            success: function (cities) {
                // console.log(cities)
                $("#cities-list")[0].innerHTML = null;
                cities.forEach(city => {
                    $("#cities-list").append(`<a href="${window.location.protocol}//${window.location.hostname}/api/city/${city.id}/set" class="list-group-item list-group-item-action">${city.address}</a>`);
                });
            },
            error: function (e) {
                console.log(e)
            }
        })
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